from pathlib import Path
import asyncio
from core.jobs import update_job

from services.diff_service import DiffService


async def process_job(job: dict):
    o = Path(job["original_path"])
    m = Path(job["modified_path"])

    print("====================================")
    print(f"[PROCESS] job_id={job['job_id']} user={job.get('username')}")
    print(f"[PROCESS] original_path={o}")
    print(f"[PROCESS] modified_path={m}")

    try:
        # ✅ chỉ xử lý docx
        if o.suffix.lower() != ".docx" or m.suffix.lower() != ".docx":
            raise ValueError("Input must be .docx")

        # -------- COMPARE (GỌI ĐÚNG CONTRACT) --------
        service = DiffService()
        compare_result = service.compare(str(o), str(m))

        print("[PROCESS] Compare done")

        # -------- UPDATE JOB --------
        update_job(
            job["job_id"],
            {
                "status": "done",
                "result": {
                    "compare": compare_result
                }
            }
        )

    except Exception as e:
        print("[ERROR]", str(e))
        update_job(
            job["job_id"],
            {
                "status": "error",
                "error": str(e)
            }
        )

    print("====================================")
    await asyncio.sleep(0.2)