using Microsoft.AspNetCore.Mvc;
using System.Text.Json;

namespace TestUI.Controllers
{
    public class UserController : Controller
    {
        private const string PY_BASE = "http://10.239.2.95:8000";

        private bool IsUser()
        {
            var role = HttpContext.Session.GetString("Role");
            return role == "User";
        }

        private IActionResult? CheckAuth()
        {
            if (!IsUser())
                return RedirectToAction("Login", "Account");

            return null;
        }

        [HttpGet]
        public IActionResult Index()
        {
            var auth = CheckAuth();
            if (auth != null) return auth;

            return View();
        }

       
        [HttpPost]
        public async Task<IActionResult> UploadFiles(IFormFile originalFile, IFormFile modifiedFile)
        {
            var auth = CheckAuth();
            if (auth != null) return auth;

            if (originalFile == null || modifiedFile == null ||
                originalFile.Length == 0 || modifiedFile.Length == 0)
            {
                ViewBag.PopupMessage = "Thiếu file hoặc file rỗng";
                return View("Index");
            }

            try
            {
                using var client = new HttpClient();
                client.Timeout = TimeSpan.FromMinutes(10);

                using var form = new MultipartFormDataContent();

                var username = User.Identity?.Name ?? "guest";
                form.Add(new StringContent(username), "username");

                // không dispose stream trước khi PostAsync xong
                var originalStream = originalFile.OpenReadStream();
                var modifiedStream = modifiedFile.OpenReadStream();

                form.Add(new StreamContent(originalStream), "originalFile", originalFile.FileName);
                form.Add(new StreamContent(modifiedStream), "modifiedFile", modifiedFile.FileName);

                var uploadResponse = await client.PostAsync($"{PY_BASE}/upload", form);

                // close stream
                originalStream.Dispose();
                modifiedStream.Dispose();

                if (!uploadResponse.IsSuccessStatusCode)
                {
                    var errorText = await uploadResponse.Content.ReadAsStringAsync();
                    ViewBag.PopupMessage = $"Upload API lỗi: {errorText}";
                    return View("Index");
                }

                var uploadJson = await uploadResponse.Content.ReadAsStringAsync();
                var uploadData = JsonSerializer.Deserialize<JsonElement>(uploadJson);

                if (!uploadData.TryGetProperty("job_id", out var jobIdElement))
                {
                    ViewBag.PopupMessage = "Không nhận được job_id từ API upload";
                    return View("Index");
                }

                var jobId = jobIdElement.GetString();
                if (string.IsNullOrWhiteSpace(jobId))
                {
                    ViewBag.PopupMessage = "job_id không hợp lệ";
                    return View("Index");
                }

                
                return RedirectToAction("Result", new { jobId });
            }
            catch (Exception ex)
            {
                ViewBag.PopupMessage = ex.ToString();
                return View("Index");
            }
        }

      
        [HttpGet]
        public IActionResult Result(string jobId)
        {
            var auth = CheckAuth();
            if (auth != null) return auth;

            if (string.IsNullOrWhiteSpace(jobId))
                return RedirectToAction("Index");

            ViewBag.JobId = jobId;
            return View();
        }

       
        [HttpGet]
        public async Task<IActionResult> JobStatus(string jobId)
        {
            var auth = CheckAuth();
            if (auth != null) return auth;

            if (string.IsNullOrWhiteSpace(jobId))
                return BadRequest(new { error = "jobId is required" });

            try
            {
                using var client = new HttpClient();
                client.Timeout = TimeSpan.FromSeconds(30);

                var res = await client.GetAsync($"{PY_BASE}/api/status/job/{jobId}");
                var json = await res.Content.ReadAsStringAsync();

               
                return Content(json, "application/json");
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { error = ex.Message });
            }
        }
        [HttpPost]
        public async Task<IActionResult> ExportChecksheet(string jobId)
        {
            var auth = CheckAuth();
            if (auth != null) return auth;

            if (string.IsNullOrWhiteSpace(jobId))
                return BadRequest(new { error = "jobId is required" });

          
            return StatusCode(501, new { error = "Export API chưa implement" });
        }
    }
}