const form = document.querySelector("#reset-form");
const message = form.querySelector(".auth-message");
const token = new URLSearchParams(window.location.search).get("token") || "";
form.addEventListener("submit", async (event) => {
  event.preventDefault();
  try {
    const csrf = await fetch("../../api/auth/csrf.php", { credentials: "include" }).then((response) => response.json());
    const body = Object.fromEntries(new FormData(form).entries());
    body.token = token;
    const response = await fetch("../../api/auth/reset-password.php", { method: "POST", credentials: "include", headers: { "Content-Type": "application/json", "X-CSRF-Token": csrf.csrfToken }, body: JSON.stringify(body) });
    const result = await response.json();
    if (!response.ok) throw new Error(result.error || "Password reset failed.");
    message.textContent = result.message;
    message.classList.add("visible");
    window.setTimeout(() => window.location.assign("../login/"), 900);
  } catch (error) {
    message.textContent = error.message;
    message.classList.add("visible", "error");
  }
});
