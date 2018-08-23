var trys = 0;
var continuee = "true";
var isnewprior = false;
var isnewcall = false;
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
                    $("#status").html("Current Status: <br><font>Offline</font>");
                    hideGroup1();
                } else if (response == "noUser") {
                    continuee = false;
                    window.location.replace("../ums/login");
                } else if (getCookie("isnewstatus") == "true") {
                    showGroup1();
                    if (getCookie("status") == response) {
                        setCookie("isnewstatus", false, -1);
                        setCookie("status", 0, -1);
                        trys = 0;
                    } else {
                      showGroup1();
                        if (trys == 20) {
                            setCookie("isnewstatus", false, -1);
                            setCookie("status", false, -1);
                            if (response == 1) {
                                $("#status").html("Current Status: <br><font>10-8 On Duty</font>");
                            } else if (response == 2) {
                                $("#status").html("Current Status: <br><font>10-6 Busy</font>");
                            } else if (response == 3) {
                                $("#status").html("Current Status: <br><font>10-7 Out of Service</font>");
                            } else if (response == 4) {
                                $("#status").html("Current Status: <br><font>10-11 On Traffic Stop</font>");
                            } else if (response == 5) {
                                $("#status").html("Current Status: <br><font>10-80 In Pursuit</font>");
                            } else if (response == 6) {
                                $("#status").html("Current Status: <br><font>10-97 Responding</font>");
                            } else if (response == 7) {
                                $("#status").html("Current Status: <br><font>10-23 On Scene</font>");
                            } else {
                                $("#status").html("Current Status: <br><font>Error: Unknown status</font>");
                            }
                            console.log("out of trys");
                            trys = 0;
                        } else {
                          showGroup1();
                            if (getCookie("status") == 1) {
                                $("#status").html("Current Status: <br><font>10-8 On Duty</font>");
                            } else if (getCookie("status") == 2) {
                                $("#status").html("Current Status: <br><font>10-6 Busy</font>");
                            } else if (getCookie("status") == 3) {
                                $("#status").html("Current Status: <br><font>10-7 Out of Service</font>");
                            } else if (getCookie("status") == 4) {
                                $("#status").html("Current Status: <br><font>10-11 On Traffic Stop</font>");
                            } else if (getCookie("status") == 5) {
                                $("#status").html("Current Status: <br><font>10-80 In Pursuit</font>");
                            } else if (getCookie("status") == 6) {
                                $("#status").html("Current Status: <br><font>10-97 Responding</font>");
                            } else if (getCookie("status") == 7) {
                                $("#status").html("Current Status: <br><font>10-80 On Scene</font>");
                            } else {
                                $("#status").html("Current Status: <br><font>Error: Unknown status</font>");
                            }
                            trys = trys + 1;
                        }
                    }
                } else {
                  showGroup1();
                    if (response == 1) {
                        $("#status").html("Current Status: <br><font>10-8 On Duty</font>");
                    } else if (response == 2) {
                        $("#status").html("Current Status: <br><font>10-6 Busy</font>");
                    } else if (response == 3) {
                        $("#status").html("Current Status: <br><font>10-7 Out of Service</font>");
                    } else if (response == 4) {
                        $("#status").html("Current Status: <br><font>10-11 On Traffic Stop</font>");
                    } else if (response == 5) {
                        $("#status").html("Current Status: <br><font>10-80 In Pursuit</font>");
                    } else if (response == 6) {
                        $("#status").html("Current Status: <br><font>10-97 Responding</font>");
                    } else if (response == 7) {
                        $("#status").html("Current Status: <br><font>10-23 On Scene</font>");
                    } else {
                        $("#status").html("Current Status: <br><font>Error: Unknown status</font>");
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
            if (!isnewcall && this.responseText != "<td>No active call.</td>") {
              showGroup2();
              var audio = new Audio('../includes/signal100.mp3');
              audio.volume = 0.5;
              audio.play();
            }
            isnewcall = true;
            if (this.responseText == "<td>No active call.</td>") {
              isnewcall = false;
              hideGroup2();
            }
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
                if (!isnewprior) {
                  var audio = new Audio('../includes/panic-button.mp3');
                  audio.volume = 0.5;
                  audio.play();
                }
                isnewprior = true;
            } else if (this.responseText == "0") {
                $("#prior").html("Priority Status: <br><font color='green'>No priority is currently in effect.</font>");
                isnewprior = false;
            } else {
                console.log(this.responseText);
            }
        }
    }
  httpPriority.send(null);
}, 1000);

function hideGroup1() {
  var x = document.getElementsByClassName("hideable1");
  x.forEach(function(element) {
    console.log(element.id);
    $("#" + element.id).hide("slow");
  });
}

function showGroup1() {
  var x = document.getElementsByClassName("hideable1");
  x.forEach(function(element) {
    console.log(element.id);
    $("#" + element.id).show("slow");
  });
}

function hideGroup2() {
  var x = document.getElementsByClassName("hideable2");
  x.forEach(function(element) {
    console.log(element.id);
    $("#" + element.id).hide("slow");
  });
}

function showGroup2() {
  var x = document.getElementsByClassName("hideable2");
  x.forEach(function(element) {
    console.log(element.id);
    $("#" + element.id).show("slow");
  });
}

function addCall(callDesc) {
        var xhttp = new XMLHttpRequest();
        var url = "scripts/fieldActions.php";
        var params = "addCall=yes&desc=" + callDesc;
        xhttp.open("GET", url + "?" + params, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    $("#call_result").html("<font color='green'>Added call.</font>");
                } else {
                    $("#call_result").html("<font color='red'>An unknown error has occured.</font>");
                }
            }
        }
        xhttp.send(null);
}

function remCall(ucid) {
        var xhttp = new XMLHttpRequest();
        var url = "scripts/fieldActions.php";
        var params = "remCall=yes&ucid=" + ucid;
        xhttp.open("GET", url + "?" + params, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    $("#call_result").html("<font color='green'>Removed call.</font>");
                } else {
                    $("#call_result").html("<font color='red'>An unknown error has occured.</font>");
                }
            }
        }
        xhttp.send(null);
}

function update_status(uuid, status) {
    var lastStatus = getNumericStatus(uuid);
    if (status == 1) {
        $("#status").html("Current Status: <br><font>10-8 On Duty</font>");
    }
    if (status == 2) {
        $("#status").html("Current Status: <br><font>10-6 Busy</font>");
    }
    if (status == 3) {
        $("#status").html("Current Status: <br><font>10-7 Out of Service</font>");
    }
    if (status == 4) {
        $("#status").html("Current Status: <br><font>10-11 On Traffic Stop</font>");
    }
    if (status == 5) {
        $("#status").html("Current Status: <br><font>10-80 In Pursuit</font>");
    }
    if (status == 6) {
        $("#status").html("Current Status: <br><font>10-97 Responding</font>");
    }
    if (status == 7) {
        $("#status").html("Current Status: <br><font>10-23 On Scene</font>");
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

function myFunction() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
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
        } else if (html.includes("10-97")) {
            return 6;
        } else if (html.includes("10-23")) {
            return 7;
        } else {
            return 0;
        }
    }
    else {
        return -1;
    }
}
