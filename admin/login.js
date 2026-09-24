const form = document.querySelector("#admin-login");
const message = document.querySelector(".auth-message");
form.addEventListener("submit", async (event) => {
  event.preventDefault();
  const button = form.querySelector("button");
  button.disabled = true;
  try {
    const csrf = await fetch("../api/auth/csrf.php", { credentials: "include" }).then((response) => response.json());
    const body = Object.fromEntries(new FormData(form).entries());
    const response = await fetch("../api/admin/login.php", { method: "POST", credentials: "include", headers: { "Content-Type": "application/json", "X-CSRF-Token": csrf.csrfToken }, body: JSON.stringify(body) });
    const result = await response.json();
    if (!response.ok) throw new Error(result.error || "Administrator login failed.");
    message.textContent = result.message;
    message.classList.add("visible");
    window.location.assign(result.redirect);
  } catch (error) {
    message.textContent = error.message;
    message.classList.add("visible", "error");
  } finally {
    button.disabled = false;
  }
});
