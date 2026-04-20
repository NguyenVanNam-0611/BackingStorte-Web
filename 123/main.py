from fastapi import FastAPI
from fastapi.staticfiles import StaticFiles

from api.upload import router as upload_router, start_scheduler, stop_scheduler
from api.status import router as status_router
from core.queue import start_worker
from worker.processor import process_job

app = FastAPI()

_started = False

# Serve uploaded files
app.mount("/uploads", StaticFiles(directory="uploads"), name="uploads")

# Routers
app.include_router(upload_router)
app.include_router(status_router, prefix="/api")


@app.on_event("startup")
async def on_startup():
    global _started
    if not _started:
        start_worker(process_job)
        start_scheduler()
        _started = True


@app.on_event("shutdown")
async def on_shutdown():
    stop_scheduler()
