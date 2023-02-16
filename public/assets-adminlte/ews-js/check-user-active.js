var timeoutTime = 120000;
var timeoutTimer = setTimeout(timeOutRedirect, timeoutTime);
$(document).ready(function () {
    $("body").bind("mousedown keydown", function (event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(timeOutRedirect, timeoutTime);
    });
});

function timeOutRedirect() {
    // similar behavior as clicking on a link
    window.location.href = window.location.origin + "/timeout";
}
