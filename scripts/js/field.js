var isnewstat = false;
var newstat = 1;
var newstatuuid = "";

window.setInterval(function () {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var color = "";
            var show = 1;
            var status = this.responseText;
            if (status == "0") {
                this['status'] = "Offline";
                color = "<font color='purple'>";
                show = 0;
            }
            if (status == "1") {
                this['status'] = "10-8 On Duty";
                color = "<font color='green'>";
            }
            if (status == "2") {
                this['status'] = "10-6 Busy";
                color = "<font color='yellow'>";
            }
            if (status == "3") {
                this['status'] = "10-7 Out of Service";
                color = "<font color='red'>";
            }
            if (show == 1) {
                result = color + status + "</font>";
            }
            $("#status").html("Current Status: " + result);
        }
    };
    xhttp.open("GET", "../scripts/php/new/field_stats.php", true);
    xhttp.send();
}, 1000);
function update_10_8(clicked_id) {
    setCookie(clicked_id + "_status", 1, 1);
    $("#status").html("Current Status: <br><font color='green'>10-8 On Duty</font>");
    $.ajax({
        url: '../scripts/php/update.php',
        type: 'POST',
        data: { uuid: clicked_id, status: 1 },
        dataType: 'json',
        async: true,
        success: function (data) {

        },
        error: function () {
            console.log("main err");
        }
    });
}
function update_10_6(clicked_id) {
    setCookie(clicked_id + "_status", 2, 1);
    $("#status").html("Current Status: <br><font color='yellow'>10-6 Busy</font>");
    $.ajax({
        url: '../scripts/php/update.php',
        type: 'POST',
        data: { uuid: clicked_id, status: 2 },
        dataType: 'json',
        async: true,
        success: function (data) {

        },
        error: function () {
            console.log("main err");
        }
    });
}
function update_10_7(clicked_id) {
    setCookie(clicked_id + "_status", 3, 1);
    $("#status").html("Current Status: <br><font color='red'>10-7 Out of Service</font>");
    $.ajax({
        url: '../scripts/php/update.php',
        type: 'POST',
        data: { uuid: clicked_id, status: 3 },
        dataType: 'json',
        async: true,
        success: function (data) {

        },
        error: function () {
            console.log("main err");
        }
    });
}
function field_add(unit) {
    if (unit == "") {
        $("#result").html("<font color='red'>Enter a unit number.</font>");
        document.getElementById("input").classList.remove('form-control');
        document.getElementById("input").classList.add('form-control-error');
    }
    else {
        $.ajax({
            url: '../scripts/php/field_register.php',
            type: 'POST',
            data: { uuid: unit },
            dataType: 'json',
            async: true,
            success: function (data) {
                if (data.response == "success") {
                    $("#return").html("<font color='green'>Success!</font>");
                    document.getElementById("input").classList.remove('form-control-error');
                    window.location.href = 'main';
                }
                if (data.response == "exists") {
                    $("#return").html("<font color='red'>Unit already exists. Try a different one.</font>");
                    document.getElementById("input").classList.remove('form-control');
                    document.getElementById("input").classList.add('form-control-error');
                }
            },
            error: function () {
                $("#form").html("main err");
            }
        });
    }
}
function kill_session() {
    $.ajax({
        url: '../field/field_scripts/kill.php',
        type: 'POST',
        data: { },
        dataType: 'json',
        async: true,
        success: function (data) {
            alert(data.response);
        },
        error: function () {
            alert("ajax error");
        }
    });
}