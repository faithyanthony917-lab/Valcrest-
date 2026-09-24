(async function () {
  const form = document.querySelector('form[action*="change-password.php"]');
  if (!form) return;
  form.addEventListener("submit", async function (event) {
    event.preventDefault();
    const result = document.createElement("p");
    form.appendChild(result);
    try {
      const csrfResponse = await fetch("/api/auth/csrf.php", { credentials: "include" });
      const csrf = await csrfResponse.json();
      const response = await fetch("/api/auth/change-password.php", {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json", "X-CSRF-Token": csrf.csrfToken },
        body: JSON.stringify({
          currentPassword: form.querySelector('[name="current_password"], [name="currentPassword"]')?.value || "",
          newPassword: form.querySelector('[name="new_password"], [name="password"], [name="newPassword"]')?.value || "",
          confirmPassword: form.querySelector('[name="new_confirm_password"], [name="confirmPassword"]')?.value || "",
        }),
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || "Password could not be changed.");
      result.textContent = data.message;
      result.className = "text-success";
      form.reset();
    } catch (error) {
      result.textContent = error.message;
      result.className = "text-danger";
    }
  });
})();
