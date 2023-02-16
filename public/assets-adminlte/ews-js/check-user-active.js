var timeoutTime = 120000;
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
$(document).ready(function () {
  $("body").bind("mousedown keydown", function (event) {
    clearTimeout(timeoutTimer);
    timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
  });
});
function ShowTimeOutWarning() {
  // similar behavior as clicking on a link
  window.location.href = "http://localhost:8080/timeout";
}
