var isLookup = false;
var currentLog = "Action";
var permLevel = "Admin";
function setLevel(level) {
    if (level == 1) {
        permLevel = "Admin";
    }
    if (level == 0) {
        permLevel = "Super Admin";
    }
    getActionLog();
}
function getActionLog() {
    var xhttp = new XMLHttpRequest();
    var url = "../../logging/log.php";
    var statusParams = "getActionLog=yes";
    if (getUrlParameter('ip') != undefined) {
        statusParams = "getActionLog=yes&ip="+getUrlParameter('ip');
    }
    if (getUrlParameter('username') != undefined) {
        statusParams = "getActionLog=yes&username="+getUrlParameter('username');
    }
    xhttp.open("GET", url + "?" + statusParams, true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            $("#log_area").html(response);
            var $textarea = $('#log_area');
            $textarea.scrollTop($textarea[0].scrollHeight);
            $("#result").html("<font color='green'>Loaded action log</font>");
        }
    }
    xhttp.send(null);
    $("#log").html("Action Log");
    if(getUrlParameter('ip') != undefined) {
        $("#log").html("Action Log for " + getUrlParameter('ip'));
    }
    currentLog = "Action";
}
function getAdminLog() {
    var xhttp = new XMLHttpRequest();
    var url = "../../logging/log.php";
    var statusParams = "getAdminLog=yes";
    if (getUrlParameter('ip') != undefined) {
        statusParams = "getAdminLog=yes&ip="+getUrlParameter('ip');
    }
    if (getUrlParameter('username') != undefined) {
        statusParams = "getAdminLog=yes&username="+getUrlParameter('username');
    }
    xhttp.open("GET", url + "?" + statusParams, true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            $("#log_area").html(response);
            var $textarea = $('#log_area');
            $textarea.scrollTop($textarea[0].scrollHeight);
            $("#result").html("<font color='green'>Loaded admin log</font>");
        }
    }
    xhttp.send(null);
    $("#log").html("Admin Log");
    if(getUrlParameter('ip') != undefined) {
        $("#log").html("Admin Log for " + getUrlParameter('ip'));
    }
    currentLog = "Admin";
}
function clearCurrentLog() {
    var xhttp = new XMLHttpRequest();
    var url = "../../logging/log.php";
    var statusParams = "clearLog=" + currentLog;
    xhttp.open("GET", url + "?" + statusParams, true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            if (response == "99noPermissions") {
                $("#result").html("<font color='red'>You do not have permissions to clear this log.</font>");
            } else {
                $("#result").html("<font color='green'>Cleared log: " + currentLog + "</font>");
                $("#log_area").html(response);
            }
        }
    }
    xhttp.send(null);
    var $textarea = $('#log_area');
    $textarea.scrollTop($textarea[0].scrollHeight);
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            var ret = sParameterName[1] === undefined ? true : sParameterName[1];
            if (ret == undefined) {
                ret = "";
            }
            return ret;
        }
    }
};