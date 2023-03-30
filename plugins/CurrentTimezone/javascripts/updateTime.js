// This is because we want user's local timezone and not server timezone
document.cookie = "user_timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;

function currentTimeByTimezone(timezone) {
  const dateRaw = new Date();
  const date = new Date(dateRaw.toLocaleString('en-US', { timeZone: timezone }));
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  return `${year}/${month}/${day} ${hours}:${minutes}`;
}

// Update html for current widget, using js to limit number of requests
function updateCurrentTime() {
  // reason behind getting timezone from rendered HTML and not here directly because if browser restrict the js timezone does not become an issue
  const siteTimeWidget = document.getElementById('js-current-site-time-widget');
  const serverTimeWidget = document.getElementById('js-current-server-time-widget');

  if (siteTimeWidget) {
    siteTimeWidget.innerHTML = currentTimeByTimezone(siteTimeWidget.dataset.timezone);
  }
  if (serverTimeWidget) {
    serverTimeWidget.innerHTML = currentTimeByTimezone(serverTimeWidget.dataset.timezone);
  }
}

// Syncing it at 00 second would be a better user experience
const interval = 60000;

function scheduleUpdate() {
  const now = new Date();
  const delay = interval - now.getTime() % interval;
  setTimeout(() => {
    updateCurrentTime();
    setInterval(updateCurrentTime, interval);
  }, delay);
}

// Schedule the first update to occur at the start of the next minute
scheduleUpdate();
