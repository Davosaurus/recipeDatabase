
$(document).ready(function() {
  document.addEventListener("visibilitychange", handleVisibilityChange);
  enableWakeLock();
});

let wakeLock = null;

const enableWakeLock = async () => {
  console.log("Requesting wakeLock");
  console.log("wakeLock was " + wakeLock);
  wakeLock = await navigator.wakeLock.request('screen');
  console.log("wakeLock is " + wakeLock);
}

const disableWakeLock = async () => {
  console.log("Disabling wakeLock");
  console.log("wakeLock was " + wakeLock);
  wakeLock.release().then(() => { wakeLock = null; console.log("wakeLock is " + wakeLock);})
}

const handleVisibilityChange = () => {
  if (wakeLock !== null && document.visibilityState === "visible") {
    enableWakeLock();
  }
}

const toggleWakeLock = function() {
  if(this.getAttribute("wakeLockEnabled") === "true") {
    disableWakeLock();
    this.setAttribute("wakeLockEnabled", "false");
    this.innerHTML = this.getAttribute("disabledText");
  }
  else {
    enableWakeLock();
    this.setAttribute("wakeLockEnabled", "true");
    this.innerHTML = this.getAttribute("enabledText");
  }
}