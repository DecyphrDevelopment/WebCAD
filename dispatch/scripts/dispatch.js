var isnewstatus = false;
var newstatus = 0;
var newstatusuuid = "";

var isaddingunit = false;

$(window).on("load", function () {
    var httpStatus = new XMLHttpRequest();
    var url = "scripts/dispatchActions.php";
    var statusParams = "getPriority=yes";
    httpStatus.open("GET", url + "?" + statusParams, true);
    httpStatus.onreadystatechange = function () {//Call a function when the state changes.
        if (httpStatus.readyState == 4 && httpStatus.status == 200) {
            setCookie("priority", this.responseText, 1);
            if (this.responseText == "1") {
                $("#prior").html("<font color='red'>A priority is currently in effect.</font>");
                document.getElementById("priority").checked = true;
            } else if (this.responseText == "0") {
                $("#prior").html("<font color='green'>No priority is currently in effect.</font>");
                document.getElementById("priority").checked = false;
            } else {

            }
        }
    };
    httpStatus.send(null);
});
var act;
window.setInterval(function () {
    var httpStatus = new XMLHttpRequest();
      var url = "scripts/dispatchActions.php";
      var statusParams = "getUnits=yes";
      httpStatus.open("GET", url + "?" + statusParams, true);
      httpStatus.onreadystatechange = function () {
          if (httpStatus.readyState == 4 && httpStatus.status == 200) {
            document.getElementById("table").innerHTML = this.responseText;
            var cookies_name = cookies();
            fLen = cookies_name.length;
            for (i = 0; i < fLen; i++) {
                var uuid = cookies_name[i].replace("_status", "");
                if (getCookie(cookies_name[i]) != -1) {
                    if (getNumericStatus(uuid) != getCookie(cookies_name[i])) {
                        if (getCookie(cookies_name[i]) == 1) {
                            $("#" + uuid + "_status").html("<font color='green'>10-8 On Duty</font>");
                        } else if (getCookie(cookies_name[i]) == 2) {
                            $("#" + uuid + "_status").html("<font color='#f98500'>10-6 Busy</font>");
                        } else if (getCookie(cookies_name[i]) == 3) {
                            $("#" + uuid + "_status").html("<font color='red'>10-7 Out of Service</font>");
                        } else if (getCookie(cookies_name[i]) == 4) {
                            $("#" + uuid + "_status").html("<font color='#ffb31c'>10-11 On Traffic Stop</font>");
                        } else if (getCookie(cookies_name[i]) == 5) {
                            $("#" + uuid + "_status").html("<font color='#ffb31c'>10-80 In Pursuit</font>");
                        }
                    }
                    else {
                        setCookie(uuid + "_status", 1, -1);
                    }
                }
            }
          }
      }
      httpStatus.send(null);

      var bolosxhttp = new XMLHttpRequest();
      var url = "scripts/dispatchActions.php";
      var statusParams = "getBolos=yes";
      bolosxhttp.open("GET", url + "?" + statusParams, true);
      bolosxhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("bolo_table").innerHTML = this.responseText;
        }
      }
      bolosxhttp.send(null);
      if (!isaddingunit) {
        var callsxhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
        var statusParams = "getCalls=yes";
        callsxhttp.open("GET", url + "?" + statusParams, true);
        callsxhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            document.getElementById("calls_table").innerHTML = this.responseText;
            if (isaddingunit) {
                act.focus();
                console.log(act);
            }
        }
}
        callsxhttp.send(null);
    }
}, 2000);
window.setInterval(function () {
    if(!isaddingunit) {
        if (document.activeElement.parentElement.nodeName == "TD") {
            console.log(document.activeElement.parentElement.nodeName);
            isaddingunit=true;
            act=document.activeElement;
        }
    } else {
		if (document.activeElement.parentElement.nodeName != "TD") {
            console.log(document.activeElement.parentElement.nodeName);
            isaddingunit=false;
        } else {
            document.activeElement = act;
            isaddingunit = true;
            var id =act.id;
            console.log(id);
            $(function() {
                $("#" + id).autocomplete({
					source: 'scripts/unitAutoComplete.php',
					select: function (event, ui) {
                        isaddingunit = false;
                        addUnitToCall(ui.item.label, id);
                        $("#calls_table").focus();
						return false;
					}
				});
            });

        }
    }

}, 100);
var trys = 0;
window.setInterval(function () {
    var httpStatus = new XMLHttpRequest();
    var url = "scripts/dispatchActions.php";
    var statusParams = "getPriority=yes";
    httpStatus.open("GET", url + "?" + statusParams, true);
    httpStatus.onreadystatechange = function () {
        if (httpStatus.readyState == 4 && httpStatus.status == 200) {
            var response = this.responseText;
            if (getCookie("isnewpriority") == "true") {
                if (getCookie("priority") == response) {
                    setCookie("isnewpriority", false, -1);
                    setCookie("priority", 0, -1);
                    trys = 0;
                } else {
                    if (trys == 20) {
                        setCookie("isnewpriority", false, -1);
                        setCookie("priority", false, -1);
                        if (response == 1) {
                            $("#prior").html("<font color='red'>A priority is currently in effect.</font>");
                            document.getElementById("priority").checked = true;
                        }
                        if (response == 0) {
                            $("#prior").html("<font color='green'>No priority is currently in effect.</font>");
                            document.getElementById("priority").checked = false;
                        }
                        console.log("out of trys");
                        trys = 0;
                    } else {
                        if (getCookie("priority") == 1) {
                            $("#prior").html("<font color='red'>A priority is currently in effect.</font>");
                            document.getElementById("priority").checked = true;
                        }
                        if (getCookie("priority") == 0) {
                            $("#prior").html("<font color='green'>No priority is currently in effect.</font>");
                            document.getElementById("priority").checked = false;
                        }
                        trys = trys + 1;
                    }
                }
            } else {
                if (response == 1) {
                    $("#prior").html("<font color='red'>A priority is currently in effect.</font>");
                    document.getElementById("priority").checked = true;
                }
                if (response == 0) {
                    $("#prior").html("<font color='green'>No priority is currently in effect.</font>");
                    document.getElementById("priority").checked = false;
                }
            }
        }
    }
    httpStatus.send(null);
}, 1000);
function getNumericStatus(uuid) {
    if (document.getElementById(uuid + "_status") != null) {
        var html = document.getElementById(uuid + "_status").innerHTML;
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
function update_status(uuid, status) {
    var lastStatus = getNumericStatus(uuid);
    if (status == 1) {
        $("#" + uuid + "_status").html("<font color='green'>10-8 On Duty</font>");
    }
    if (status == 2) {
        $("#" + uuid + "_status").html("<font color='#f98500'>10-6 Busy</font>");
    }
    if (status == 3) {
        $("#" + uuid + "_status").html("<font color='red'>10-7 Out of Service</font>");
    }
    if (status == 4) {
        $("#" + uuid + "_status").html("<font color='#ffb31c'>10-11 On Traffic Stop</font>");
    }
    if (status == 5) {
        $("#" + uuid + "_status").html("<font color='#ffb31c'>10-80 In Pursuit</font>");
    }
    if (status == 6) {
        $("#" + uuid + "_status").html("<font color='#ffb31c'>10-97 Responding</font>");
    }
    if (status == 7) {
        $("#" + uuid + "_status").html("<font color='#ffb31c'>10-23 On Scene</font>");
    }
    setCookie(uuid + "_status", status, 1);
    var xhttp = new XMLHttpRequest();
    var url = "scripts/dispatchActions.php";
    var statusParams = "updateUnit=yes&uuid=" + uuid + "&status="+status;
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
function update_delete(clicked_id) {
    $("#result").html("<font color='blue'>Processing request... Please wait.</font>");
    var xhttp = new XMLHttpRequest();
    var url = "scripts/dispatchActions.php";
    var statusParams = "removeUnit=yes&uuid=" + clicked_id;
    xhttp.open("GET", url + "?" + statusParams, true);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            if (response == "success") {
                $("#result").html("<font color='green'>Successfully removed unit!</font>");
            } else {
                $("#result").html("<font color='red'>An unkown error has occured!</font>");
            }
        }
    }
    xhttp.send(null);
}
function update_add(unit) {
    $("#result").html("<font color='blue'>Processing request... Please wait.</font>");
    if (unit == "") {
        $("#result").html("<font color='red'>Enter a unit number.</font>");
        document.getElementById("input").classList.remove('form-control');
        document.getElementById("input").classList.add('form-control-error');
    }
    else {
        var xhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
        var statusParams = "addUnit=yes&name=" + unit;
        xhttp.open("GET", url + "?" + statusParams, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    $("#result").html("<font color='green'>Successfully added unit!</font>");
                    document.getElementById("input").classList.remove('form-control-error');
                    document.getElementById("input").classList.add('form-control');
                    document.getElementById("addunit").classList.remove('form-control-error');
                    document.getElementById("addunit").classList.add('form-control');
                } else if (response == "exists") {
                    $("#result").html("<font color='red'>Unit already exists with that number. Try a different one.</font>");
                    document.getElementById("input").classList.remove('form-control');
                    document.getElementById("input").classList.add('form-control-error');
                    document.getElementById("addunit").classList.remove('form-control');
                    document.getElementById("addunit").classList.add('form-control-error');
                } else {
                    $("#result").html("<font color='red'>" + response + "</font>");
                    document.getElementById("input").classList.remove('form-control');
                    document.getElementById("input").classList.add('form-control-error');
                    document.getElementById("addunit").classList.remove('form-control');
                    document.getElementById("addunit").classList.add('form-control-error');
                }
            }
        }
        xhttp.send(null);
    }
}
function bolo_delete(clicked_id) {
    $("#bolo_result").html("<font color='blue'>Processing request... Please wait.</font>");

    var xhttp = new XMLHttpRequest();
    var url = "scripts/dispatchActions.php";
    var params = "removeBolo=yes&ubid=" + clicked_id;
    xhttp.open("GET", url + "?" + params, true);
    xhttp.onreadystatechange = function () {//Call a function when the state changes.
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = this.responseText;
            if (response == "success") {
                $("#bolo_result").html("<font color='green'>Removed bolo.</font>");
            } else {
                $("#bolo_result").html("<font color='red'>An unknown error has occured.</font>");
            }
        }
    }
    xhttp.send(null);
}
function bolo_add(bolo) {
    if (bolo == "") {
        $("#bolo_result").html("<font color='red'>Cannot create an empty bolo.</font>");
        document.getElementById("bolos_input").classList.remove('form-control');
        document.getElementById("bolos_input").classList.add('form-control-error');
    } else {
        document.getElementById("bolos_input").classList.remove('form-control-error');
        document.getElementById("bolos_input").classList.add('form-control');
        $("#bolo_result").html("<font color='blue'>Processing request... Please wait.</font>");

        var xhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
        var params = "addBolo=yes&bolo=" + bolo;
        xhttp.open("GET", url + "?" + params, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    $("#bolo_result").html("<font color='green'>Added bolo.</font>");
                } else {
                    $("#bolo_result").html("<font color='red'>An unknown error has occured.</font>");
                }
            }
        }
        xhttp.send(null);
    }
}
function addUnitToCall(callsign, ucid) {
        var xhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
        var params = "addUnitToCall=yes&ucid=" + ucid + "&callsign=" + callsign;
        xhttp.open("GET", url + "?" + params, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    $("#call_result").html("<font color='green'>Added unit to call.</font>");
                } else {
                    $("#call_result").html("<font color='red'>An unknown error has occured.</font>");
                }
            }
        }
        xhttp.send(null);
}
function remUnitFromCalls(uuid) {
        var xhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
        var params = "remUnitFromCalls=yes&uuid=" + uuid;
        xhttp.open("GET", url + "?" + params, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
                if (response == "success") {
                    $("#call_result").html("<font color='green'>Removed unit from calls.</font>");
                } else {
                    $("#call_result").html("<font color='red'>An unknown error has occured.</font>");
                }
            }
        }
        xhttp.send(null);
}
function addCall(callDesc) {
        var xhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
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
        var url = "scripts/dispatchActions.php";
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
function priority() {
    var isprior = 3;
    if (!document.getElementById('priority').checked) {
        isprior = 0;
        $("#prior").html("<font color='green'>No priority is currently in effect!</font>");
    } else {
        isprior = 1;
        $("#prior").html("<font color='red'>A priority is currently in effect!</font>");
    }
    if (isprior != 3) {
        setCookie("priority", isprior, 1);
        setCookie("isnewpriority", true, 1);
        var xhttp = new XMLHttpRequest();
        var url = "scripts/dispatchActions.php";
        var params = "updatePriority=yes&status=" + isprior;
        xhttp.open("GET", url + "?" + params, true);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var response = this.responseText;
            }
        }
        xhttp.send(null);
    }
}
