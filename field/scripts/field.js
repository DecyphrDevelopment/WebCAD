var trys = 0;
var continuee = "true";
window.setInterval(function () {
    if (continuee) {
        var httpStatus = new XMLHttpRequest();
        var url = "scripts/fieldActions.php";
        var statusParams = "getStatus=yes";
        httpStatus.open("GET", url + "?" + statusParams, true);
        httpStatus.onreadystatechange = function () {
            if (httpStatus.readyState == 4 && httpStatus.status == 200) {
                var response = this.responseText;
                if (response == "inactive") {
                    $("#status").html("Current Status: <br><font color='purple'>Offline</font>");
                } else if (response == "noUser") {
                    continuee = false;
                    window.location.replace("../ums/login");
                } else if (getCookie("isnewstatus") == "true") {
                    if (getCookie("status") == response) {
                        setCookie("isnewstatus", false, -1);
                        setCookie("status", 0, -1);
                        trys = 0;
                    } else {
                        if (trys == 20) {
                            setCookie("isnewstatus", false, -1);
                            setCookie("status", false, -1);
                            if (response == 1) {
                                $("#status").html("Current Status: <br><font color='green'>10-8 On Duty</font>");
                            } else if (response == 2) {
                                $("#status").html("Current Status: <br><font color='#5942f4'>10-6 Busy</font>");
                            } else if (response == 3) {
                                $("#status").html("Current Status: <br><font color='red'>10-7 Out of Service</font>");
                            } else if (response == 4) {
                                $("#status").html("Current Status: <br><font color='#ffb31c'>10-11 On Traffic Stop</font>");
                            } else if (response == 5) {
                                $("#status").html("Current Status: <br><font color='#ffb31c'>10-80 In Pursuit</font>");
                            } else {
                                $("#status").html("Current Status: <br><font color='purple'>Error: Unknown status</font>");
                            }
                            console.log("out of trys");
                            trys = 0;
                        } else {
                            if (getCookie("status") == 1) {
                                $("#status").html("Current Status: <br><font color='green'>10-8 On Duty</font>");
                            } else if (getCookie("status") == 2) {
                                $("#status").html("Current Status: <br><font color='#5942f4'>10-6 Busy</font>");
                            } else if (getCookie("status") == 3) {
                                $("#status").html("Current Status: <br><font color='red'>10-7 Out of Service</font>");
                            } else if (getCookie("status") == 4) {
                                $("#status").html("Current Status: <br><font color='#ffb31c'>10-11 On Traffic Stop</font>");
                            } else if (getCookie("status") == 5) {
                                $("#status").html("Current Status: <br><font color='#ffb31c'>10-80 In Pursuit</font>");
                            } else {
                                $("#status").html("Current Status: <br><font color='purple'>Error: Unknown status</font>");
                            }
                            trys = trys + 1;
                        }
                    }
                } else {
                    if (response == 1) {
                        $("#status").html("Current Status: <br><font color='green'>10-8 On Duty</font>");
                    } else if (response == 2) {
                        $("#status").html("Current Status: <br><font color='#5942f4'>10-6 Busy</font>");
                    } else if (response == 3) {
                        $("#status").html("Current Status: <br><font color='red'>10-7 Out of Service</font>");
                    } else if (response == 4) {
                        $("#status").html("Current Status: <br><font color='#ffb31c'>10-11 On Traffic Stop</font>");
                    } else if (response == 5) {
                        $("#status").html("Current Status: <br><font color='#ffb31c'>10-80 In Pursuit</font>");
                    } else {
                        $("#status").html("Current Status: <br><font color='purple'>Error: Unknown status</font>");
                    }
                }
            }
        }
        httpStatus.send(null);
    }

    var httpBolos = new XMLHttpRequest();
    var url = "scripts/fieldActions.php";
    var boloParams = "getBolos=yes";
    httpBolos.open("GET", url + "?" + boloParams, true);
    httpBolos.onreadystatechange = function () {//Call a function when the state changes.
        if (httpBolos.readyState == 4 && httpBolos.status == 200) {
            $("#bolo_table").html(this.responseText);
        }
    }
    httpBolos.send(null);

    var httpCalls = new XMLHttpRequest();
    var url = "scripts/fieldActions.php";
    var callParams = "getCalls=yes";
    httpCalls.open("GET", url + "?" + callParams, true);
    httpCalls.onreadystatechange = function () {//Call a function when the state changes.
        if (httpCalls.readyState == 4 && httpBolos.status == 200) {
            $("#call_table").html(this.responseText);
        }
    }
    httpCalls.send(null);

    var httpPriority = new XMLHttpRequest();
    var url = "scripts/fieldActions.php";
    var priorityParams = "getPriority=yes";
    httpPriority.open("GET", url + "?" + priorityParams, true);
    httpPriority.onreadystatechange = function () {//Call a function when the state changes.
        if (httpPriority.readyState == 4 && httpPriority.status == 200) {
            if (this.responseText == "1") {
                $("#prior").html("Priority Status: <br><font color='red'>A priority is currently in effect.</font>");
            } else if (this.responseText == "0") {
                $("#prior").html("Priority Status: <br><font color='green'>No priority is currently in effect.</font>");
            } else {
                console.log(this.responseText);
            }
        }
    }
  httpPriority.send(null);
}, 1000);

function update_status(uuid, status) {
    var lastStatus = getNumericStatus(uuid);
    if (status == 1) {
        $("#status").html("Current Status: <br><font color='green'>10-8 On Duty</font>");
    }
    if (status == 2) {
        $("#status").html("Current Status: <br><font color='#5942f4'>10-6 Busy</font>");
    }
    if (status == 3) {
        $("#status").html("Current Status: <br><font color='red'>10-7 Out of Service</font>");
    }
    if (status == 4) {
        $("#status").html("Current Status: <br><font color='#ffb31c'>10-11 On Traffic Stop</font>");
    }
    if (status == 5) {
        $("#status").html("Current Status: <br><font color='#ffb31c'>10-80 In Pursuit</font>");
    }
    setCookie("status", status, 1);
    setCookie("isnewstatus", true, 1);
    var xhttp = new XMLHttpRequest();
    var url = "scripts/fieldActions.php";
    var statusParams = "updateUnit=yes&status="+status;
    xhttp.open("GET", url + "?" + statusParams, true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            if (response == "success") {
                setCookie(uuid + "_status", 0, -1);
            } else if (response == "notValidStatus") {
                $("#result").html("<font color='red'>Error: Invalid status!</font>");
                setCookie(uuid + "_status", lastStatus, 1);
            } else {
                $("#result").html("<font color='red'>An unknown error has occured!</font>");
            }
        }
    }
    xhttp.send(null);
}

function update_offduty(clicked_id) {
    var lastStatus = getNumericStatus(clicked_id);
    $("#status").html("Current Status: <br><font color='purple'>Offline</font>");
    var xhttp = new XMLHttpRequest();
    var url = "scripts/fieldActions.php";
    var statusParams = "updateUnit=yes&duty=0";
    xhttp.open("GET", url + "?" + statusParams, true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            if (response == "success") {
            } else if (response == "notValidStatus") {
                $("#result").html("<font color='red'>Error: Invalid status!</font>");
            } else {
                $("#result").html("<font color='red'>An unknown error has occured!</font>");
            }
        }
    }
    xhttp.send(null);
}
function getNumericStatus(uuid) {
    if (document.getElementById("status") != null) {
        var html = document.getElementById("status").innerHTML;
        if (html.includes("10-8")) {
            return 1;
        } else if (html.includes("10-6")) {
            return 2;
        } else if (html.includes("10-7")) {
            return 3;
        } else if (html.includes("10-11")) {
            return 4;
        } else if (html.includes("10-80")) {
            return 5;
        } else {
            return 0;
        }
    }
    else {
        return -1;
    }
}
