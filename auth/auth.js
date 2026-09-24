const apiRoot = "../../api/auth";

async function requestJson(path, options = {}) {
  const response = await fetch(`${apiRoot}/${path}`, {
    credentials: "include",
    headers: { "Content-Type": "application/json", ...(options.headers || {}) },
    ...options,
  });
  const payload = await response.json().catch(() => ({}));
  if (!response.ok) {
    throw new Error(payload.error || "The account service returned an error.");
  }
  return payload;
}

function showMessage(form, text, isError = false) {
  const message = form.querySelector(".auth-message");
  if (!message) return;
  message.textContent = text;
  message.classList.toggle("error", isError);
  message.classList.add("visible");
}

document.querySelectorAll("form[data-static-form]").forEach((form) => {
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const submit = form.querySelector('button[type="submit"]');
    if (submit) submit.disabled = true;
    try {
      const { csrfToken } = await requestJson("csrf.php");
      const endpoint = location.pathname.includes("/register/") ? "register.php" : "login.php";
      const payload = Object.fromEntries(new FormData(form).entries());
      const result = await requestJson(endpoint, {
        method: "POST",
        headers: { "X-CSRF-Token": csrfToken },
        body: JSON.stringify(payload),
      });
      showMessage(form, result.message || "Request completed.");
      if (endpoint === "login.php" && result.redirect) {
        window.location.assign(result.redirect);
      } else if (endpoint === "register.php") {
        window.setTimeout(() => window.location.assign("../login/"), 700);
      }
    } catch (error) {
      showMessage(form, error.message, true);
    } finally {
      if (submit) submit.disabled = false;
    }
  });
});
