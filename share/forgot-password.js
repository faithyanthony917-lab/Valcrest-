const form = document.querySelector("#forgot-form");
const message = form.querySelector(".auth-message");
form.addEventListener("submit", async (event) => {
  event.preventDefault();
  const button = form.querySelector("button");
  button.disabled = true;
  try {
    const csrf = await fetch("../../api/auth/csrf.php", { credentials: "include" }).then((response) => response.json());
    const response = await fetch("../../api/auth/forgot-password.php", {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json", "X-CSRF-Token": csrf.csrfToken },
      body: JSON.stringify(Object.fromEntries(new FormData(form).entries())),
    });
    const result = await response.json();
    if (!response.ok) throw new Error(result.error || "The reset request could not be completed.");
    message.textContent = result.message;
    message.classList.add("visible");
    form.reset();
  } catch (error) {
    message.textContent = error.message;
    message.classList.add("visible", "error");
  } finally {
    button.disabled = false;
  }
});
