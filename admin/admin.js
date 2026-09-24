const api = "/api";
let csrfToken = "";
const message = document.querySelector("#message");
async function request(path, options = {}) {
  const response = await fetch(`${api}/${path}`, { credentials: "include", headers: { "Content-Type": "application/json", ...(options.headers || {}) }, ...options });
  const data = await response.json().catch(() => ({}));
  if (!response.ok) throw new Error(data.error || "Request failed.");
  return data;
}
function escapeHtml(value) {
  return String(value ?? "").replace(/[&<>"']/g, (character) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;",
  }[character]));
}
async function load() {
  try {
    const [requests, users, support, investments] = await Promise.all([request("admin/requests.php"), request("admin/users.php"), request("admin/support-tickets.php"), request("admin/investments.php")]);
    document.querySelector("#pending-count").textContent = requests.requests.filter((item) => item.status === "pending").length;
    document.querySelector("#user-count").textContent = users.users.length;
    document.querySelector("#requests").innerHTML = requests.requests.map((item) => `<tr><td>${escapeHtml(item.reference)}</td><td>${escapeHtml(item.first_name)} ${escapeHtml(item.last_name)}<small>${escapeHtml(item.email)}</small></td><td>${escapeHtml(item.type)}</td><td>${escapeHtml(item.currency)} ${escapeHtml(item.amount)}</td><td>${escapeHtml(item.status)}</td><td>${item.status === "pending" ? `<span class="action"><button class="approve" data-id="${escapeHtml(item.id)}" data-action="approve">Approve</button><button class="reject" data-id="${escapeHtml(item.id)}" data-action="reject">Reject</button></span>` : "—"}</td></tr>`).join("") || `<tr><td colspan="6">No requests found.</td></tr>`;
    document.querySelector("#users").innerHTML = users.users.map((item) => `<tr><td>${escapeHtml(item.first_name)} ${escapeHtml(item.last_name)}</td><td>${escapeHtml(item.email)}</td><td>${escapeHtml(item.country)}</td><td>${escapeHtml(item.role)}</td><td>${escapeHtml(item.status)}</td></tr>`).join("");
    document.querySelector("#support-tickets").innerHTML = support.tickets.map((item) => `<tr><td>${escapeHtml(item.subject)}</td><td>${escapeHtml(item.first_name)} ${escapeHtml(item.last_name)}<small>${escapeHtml(item.email)}</small></td><td>${escapeHtml(item.category)}</td><td>${escapeHtml(item.status)}</td><td>${escapeHtml(item.updated_at)}</td><td><select data-ticket-status data-id="${escapeHtml(item.id)}"><option value="open" ${item.status === "open" ? "selected" : ""}>Open</option><option value="in_progress" ${item.status === "in_progress" ? "selected" : ""}>In progress</option><option value="resolved" ${item.status === "resolved" ? "selected" : ""}>Resolved</option><option value="closed" ${item.status === "closed" ? "selected" : ""}>Closed</option></select><button data-ticket-reply data-id="${escapeHtml(item.id)}">Reply</button></td></tr>`).join("") || `<tr><td colspan="6">No support tickets found.</td></tr>`;
    document.querySelector("#investments").innerHTML = investments.investments.map((item) => `<tr><td>${escapeHtml(item.reference)}</td><td>${escapeHtml(item.first_name)} ${escapeHtml(item.last_name)}<small>${escapeHtml(item.email)}</small></td><td>${escapeHtml(item.plan_name)}</td><td>${escapeHtml(item.currency)} ${escapeHtml(item.principal)}</td><td>${escapeHtml(item.matures_at)}</td><td>${escapeHtml(item.status)}</td></tr>`).join("") || `<tr><td colspan="6">No investments found.</td></tr>`;
  } catch (error) { message.textContent = error.message; }
}
document.querySelector("#refresh").addEventListener("click", load);
document.querySelector("#requests").addEventListener("click", async (event) => { const button = event.target.closest("button[data-id]"); if (!button) return; try { await request("auth/csrf.php").then((data) => { csrfToken = data.csrfToken; }); await request("admin/request-action.php", { method: "POST", headers: { "X-CSRF-Token": csrfToken }, body: JSON.stringify({ requestId: button.dataset.id, action: button.dataset.action }) }); message.textContent = "Request updated."; await load(); } catch (error) { message.textContent = error.message; } });
document.querySelector("#support-tickets").addEventListener("change", async (event) => { const select = event.target.closest("select[data-ticket-status]"); if (!select) return; try { const data = await request("auth/csrf.php"); await request("admin/support-tickets.php", { method: "POST", headers: { "X-CSRF-Token": data.csrfToken }, body: JSON.stringify({ ticketId: select.dataset.id, status: select.value }) }); message.textContent = "Ticket status updated."; await load(); } catch (error) { message.textContent = error.message; } });
document.querySelector("#support-tickets").addEventListener("click", async (event) => { const button = event.target.closest("button[data-ticket-reply]"); if (!button) return; const reply = window.prompt("Enter your reply:"); if (!reply || !reply.trim()) return; try { const data = await request("auth/csrf.php"); await request("admin/support-tickets.php", { method: "POST", headers: { "X-CSRF-Token": data.csrfToken }, body: JSON.stringify({ ticketId: button.dataset.id, message: reply.trim() }) }); message.textContent = "Reply sent."; await load(); } catch (error) { message.textContent = error.message; } });
document.querySelector("#logout").addEventListener("click", async () => { const data = await request("auth/csrf.php"); await request("auth/logout.php", { method: "POST", headers: { "X-CSRF-Token": data.csrfToken } }); location.href = "/auth/login/"; });
load();
