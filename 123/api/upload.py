import uuid
import os
import shutil
import logging
from datetime import datetime, timedelta, timezone
from fastapi import APIRouter, UploadFile, File, Form, HTTPException
from apscheduler.schedulers.asyncio import AsyncIOScheduler

from core.jobs import create_job, mark_job_files_deleted
from core.queue import enqueue_job
from services.word_converter import convert_doc_to_docx_if_needed

logger = logging.getLogger(__name__)
router = APIRouter(tags=["Upload"])

# Khởi tạo scheduler (singleton, dùng chung toàn app)
scheduler = AsyncIOScheduler()


def start_scheduler():
    """Gọi hàm này trong startup event của FastAPI."""
    if not scheduler.running:
        scheduler.start()


def stop_scheduler():
    """Gọi hàm này trong shutdown event của FastAPI."""
    if scheduler.running:
        scheduler.shutdown(wait=False)


# ── Hàm xóa file ────────────────────────────────────────────────────────────

def delete_upload_files(job_id: str, folders: list[str]):
    """
    Xóa các folder upload sau 24h.
    Chạy trong thread pool của APScheduler (không phải async).
    """
    for folder in folders:
        try:
            if os.path.exists(folder):
                shutil.rmtree(folder)
                logger.info(f"[cleanup] Đã xóa folder: {folder}")
            else:
                logger.warning(f"[cleanup] Folder không tồn tại (đã xóa?): {folder}")
        except Exception as e:
            logger.error(f"[cleanup] Lỗi khi xóa {folder}: {e}")

    # Cập nhật trạng thái trong DB (tùy implementation của bạn)
    try:
        mark_job_files_deleted(job_id)
    except Exception as e:
        logger.error(f"[cleanup] Không thể update DB cho job {job_id}: {e}")


def schedule_cleanup(job_id: str, folders: list[str]):
    """Lên lịch xóa file sau 24h."""
    run_at = datetime.now() + timedelta(hours=24)

    scheduler.add_job(
        delete_upload_files,
        trigger="date",
        run_date=run_at,
        args=[job_id, folders],
        id=f"cleanup_{job_id}",     # id duy nhất, dễ cancel nếu cần
        replace_existing=True,
        misfire_grace_time=3600,    # nếu server restart, vẫn chạy nếu trễ ≤ 1h
    )
    logger.info(f"[cleanup] Đã lên lịch xóa job {job_id} lúc {run_at.isoformat()}")


# ── Helpers ──────────────────────────────────────────────────────────────────

async def save_file(file: UploadFile, username: str, mode: str, date: str):
    base_dir = os.getcwd()
    filename_without_ext = os.path.splitext(file.filename)[0]
    current_time = datetime.now().strftime("%H%M%S")
    folder_name = f"{filename_without_ext}_{current_time}"

    folder = os.path.join(base_dir, "uploads", date, username, mode, folder_name)
    os.makedirs(folder, exist_ok=True)

    # ✅ giữ nguyên tên file upload
    file_path = os.path.join(folder, file.filename)

    with open(file_path, "wb") as f:
        f.write(await file.read())

    return file_path, folder_name, filename_without_ext, folder


# ── Route ─────────────────────────────────────────────────────────────────────

@router.post("/upload")
async def upload_pair(
    username: str = Form(...),
    originalFile: UploadFile = File(...),
    modifiedFile: UploadFile = File(...)
):
    if not originalFile.filename or not modifiedFile.filename:
        raise HTTPException(status_code=400, detail="Need both files")

    date = datetime.now().strftime("%Y-%m-%d")

    o_path, o_folder_name, o_file_name, o_folder = await save_file(
        originalFile, username, "original", date
    )
    m_path, m_folder_name, m_file_name, m_folder = await save_file(
        modifiedFile, username, "modified", date
    )

    # ✅ Convert nếu là .doc -> .docx, và xóa .doc
    o_path = convert_doc_to_docx_if_needed(o_path)
    m_path = convert_doc_to_docx_if_needed(m_path)

    job_id = uuid.uuid4().hex

    create_job(
        job_id=job_id,
        username=username,
        original_path=str(o_path),
        modified_path=str(m_path),
        date=date,
        original_file_name=o_file_name,
        modified_file_name=m_file_name,
        original_folder_name=o_folder_name,
        modified_folder_name=m_folder_name,
        created_at=datetime.now().isoformat()
    )

    await enqueue_job(job_id)

    # Lên lịch xóa cả 2 folder sau 24h
    schedule_cleanup(job_id, folders=[o_folder, m_folder])

    return {
        "job_id": job_id,
        "status": "queued",
        "status_url": f"/api/status/job/{job_id}",
        # ✅ thời điểm hết hạn đúng UTC
        "files_expire_at": (datetime.now(timezone.utc) + timedelta(hours=24)).replace(microsecond=0).isoformat(),
    }
