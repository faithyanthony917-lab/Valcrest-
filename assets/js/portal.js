const sidebar = document.querySelector("#sidebar");
const scrim = document.querySelector(".mobile-scrim");
const menuOpen = document.querySelector("[data-menu-open]");
const closeButtons = document.querySelectorAll("[data-menu-close]");
const adminLink = document.querySelector(".admin-only");
const dashboardStatus = document.querySelector("#dashboard-status");

function formatMoney(amount, currency) {
  const value = Number(amount);
  if (!Number.isFinite(value)) return "--";
  return new Intl.NumberFormat("en-GB", {
    style: "currency",
    currency: currency || "GBP",
  }).format(value);
}

function setMenu(open) {
  sidebar?.classList.toggle("open", open);
  scrim?.classList.toggle("visible", open);
  menuOpen?.setAttribute("aria-expanded", String(open));
}

menuOpen?.addEventListener("click", () => setMenu(true));
closeButtons.forEach((button) => button.addEventListener("click", () => setMenu(false)));
document.querySelectorAll(".primary-nav a").forEach((link) => {
  link.addEventListener("click", () => setMenu(false));
});

Promise.all([
  fetch("../../../api/auth/me.php", { credentials: "include" }),
  fetch("../../../api/user/dashboard.php", { credentials: "include" }),
])
  .then(async ([userResponse, dashboardResponse]) => {
    if (!userResponse.ok || !dashboardResponse.ok) {
      throw new Error("Unable to load account data.");
    }
    return Promise.all([userResponse.json(), dashboardResponse.json()]);
  })
  .then(([userData, dashboardData]) => {
    const user = userData.user;
    const account = dashboardData.account;
    const name = [user.firstName, user.lastName].filter(Boolean).join(" ") || user.username;
    const currency = account.currency || "GBP";
    document.querySelector("#client-name")?.replaceChildren(document.createTextNode(user.firstName || user.username));
    document.querySelector("#profile-name")?.replaceChildren(document.createTextNode(name));
    document.querySelector("#profile-email")?.replaceChildren(document.createTextNode(user.email));
    document.querySelector("#available-balance")?.replaceChildren(document.createTextNode(formatMoney(account.availableBalance, currency)));
    document.querySelector("#pending-deposits")?.replaceChildren(document.createTextNode(formatMoney(account.pendingDeposits, currency)));
    document.querySelector("#pending-withdrawals")?.replaceChildren(document.createTextNode(formatMoney(account.pendingWithdrawals, currency)));
    document.querySelector("#account-currency")?.replaceChildren(document.createTextNode(currency));
    dashboardStatus?.replaceChildren(document.createTextNode("Account data loaded."));
    if (user.role === "admin" && user.status !== "suspended") {
      adminLink?.removeAttribute("hidden");
    }
  })
  .catch(() => {
    if (dashboardStatus) {
      dashboardStatus.textContent = "We could not load your account data. Please sign in again.";
    }
  });
