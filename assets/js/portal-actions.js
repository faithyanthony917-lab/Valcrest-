(function () {
  async function csrfToken() {
    const response = await fetch("/api/auth/csrf.php", { credentials: "include" });
    if (!response.ok) throw new Error("Could not start a secure request.");
    return (await response.json()).csrfToken;
  }

  async function submitJson(url, payload) {
    const token = await csrfToken();
    const response = await fetch(url, {
      method: "POST",
      credentials: "include",
      headers: { "Content-Type": "application/json", "X-CSRF-Token": token },
      body: JSON.stringify(payload),
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.error || "Request could not be submitted.");
    return data;
  }

  function showResult(form, message, failed) {
    let result = form.querySelector("[data-request-result]");
    if (!result) {
      result = document.createElement("p");
      result.dataset.requestResult = "true";
      form.appendChild(result);
    }
    result.textContent = message;
    result.className = failed ? "text-danger" : "text-success";
  }

  document.querySelectorAll('form[action*="/deposits/now"], form[action*="/withdrawals/now"]').forEach(function (form) {
    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      const amount = form.querySelector('[name="amount"]')?.value || "";
      const type = form.action.includes("/withdrawals/") ? "withdrawal" : "deposit";
      try {
        const data = await submitJson("/api/user/financial-request.php", { type: type, amount: amount });
        showResult(form, data.message || "Request submitted for review.", false);
      } catch (error) {
        showResult(form, error.message, true);
      }
    });
  });

  document.querySelectorAll('form[action*="/transfers/now"]').forEach(function (form) {
    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      try {
        const data = await submitJson("/api/user/transfers.php", {
          recipient: form.querySelector('[name="email"]')?.value || "",
          amount: form.querySelector('[name="amount"]')?.value || "",
          note: form.querySelector('[name="note"]')?.value || "",
        });
        showResult(form, data.message || "Transfer completed.", false);
      } catch (error) {
        showResult(form, error.message, true);
      }
    });
  });

  document.querySelectorAll("[data-support-ticket]").forEach(function (form) {
    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      try {
        const data = await submitJson("/api/user/support-tickets.php", {
          subject: form.querySelector('[name="subject"]')?.value || "",
          category: form.querySelector('[name="category"]')?.value || "general",
          message: form.querySelector('[name="message"]')?.value || "",
        });
        showResult(form, data.message || "Support ticket submitted.", false);
        form.reset();
      } catch (error) {
        showResult(form, error.message, true);
      }
    });
  });

  document.querySelectorAll("[data-ticket-list]").forEach(async function (list) {
    try {
      const response = await fetch("/api/user/support-tickets.php", { credentials: "include" });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || "Tickets could not be loaded.");
      if (!data.tickets.length) {
        list.textContent = "No support tickets yet.";
        return;
      }
      data.tickets.forEach(function (ticket) {
        const item = document.createElement("p");
        item.textContent = "#" + ticket.id + " - " + ticket.subject + " (" + ticket.status + ")";
        list.appendChild(item);
      });
    } catch (error) {
      list.textContent = error.message;
    }
  });

  document.querySelectorAll("[data-investment-form]").forEach(function (form) {
    const amountInput = form.querySelector('[name="amount"]');
    const expectedReturn = form.querySelector("[data-expected-return]");
    const returnRate = Number(form.dataset.returnRate || 0);
    const currency = form.dataset.currency || "";
    if (amountInput && expectedReturn && returnRate > 0) {
      amountInput.addEventListener("input", function () {
        const profit = (Number(amountInput.value || 0) * returnRate) / 100;
        expectedReturn.textContent = currency + " " + profit.toFixed(2);
      });
    }
    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      try {
        const data = await submitJson("/api/user/investment-create.php", {
          planId: form.querySelector('[name="planId"]').value,
          accountId: form.querySelector('[name="accountId"]').value,
          amount: form.querySelector('[name="amount"]').value,
        });
        showResult(form, data.message + " Matures " + new Date(data.maturesAt).toLocaleDateString() + ".", false);
        form.reset();
      } catch (error) {
        showResult(form, error.message, true);
      }
    });
  });
})();
