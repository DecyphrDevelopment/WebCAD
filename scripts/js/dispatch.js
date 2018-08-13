var isnewstat = false;
var newstat = 1;
var newstatuuid = "";

var isnewaction = false;
var newaction = 0;
var newactionuuid = "";

window.setInterval(function () {
    if (document.getElementById("tab").style.height <= document.getElementById("input").style.height) {
        document.getElementById("main").style.height = document.getElementById("input").style.height;
    }
    else {
        document.getElementById("main").style.height = document.getElementById("tab").style.height;
    }
    $.ajax({
        type: "Post",
        async: true,
        url: "../scripts/php/update_prior.php",
        success: function (data) {

        }
    });
    $.ajax({
        type: "Post",
        async: true,
        url: "../scripts/php/db.php",
        success: function (data) {
            var obj = $.parseJSON(data);
            
            var table = '<table border="1" cellpadding="10" class="" id="tab"><tr> <th style="width:30%">Unit Number</th> <th style="width:30%">Status</th> <th style="width:30%">Update</th> </tr>'
            $.each(obj, function () {
                var show = 1;
                if (this['uuid'] == newstatuuid) {
                    if (isnewstat) {
                        if (this['status'] != newstat) {
                            $.ajax({
                                url: '../scripts/php/update.php',
                                type: 'POST',
                                data: { uuid: newstatuuid, status: newstat },
                                success: function (data) {
                                    isnewstat = false;
                                    newstatuuid = "";
                                    //alert("done; isnewstat = false; uuid = ''"); // Inspect this in your console
                                },
                                error: function () {
                                    console.log("status ajax err");
                                }
                            });
                            this['status'] = newstat;
                        }
                        else {
                            isnewstat = false;
                            newstatuuid = "";
                        }
                    }
                }
                if (this['uuid'] == newactionuuid) {
                    if (isnewaction) {
                        //alert(isnewaction + newactionuuid);
                        $.ajax({
                            url: '../scripts/php/update.php',
                            type: 'POST',
                            async: true,
                            data: { uuid: newactionuuid, status: newaction },
                            success: function (data) {
                                isnewaction = false;
                                newactionuuid = "";
                                //alert("done");
                                if (newaction == 99) {
                                    show = 0;
                                }
                                $("#result").html("<font color='green'>Removed unit.</font>");
                                console.log("done action"); // Inspect this in your console
                            },
                            error: function () {
                                console.log("main err");
                            }
                        });
                        this['status'] = newstat;
                    }
                }
                var color = "";
                if (this['status'] == 0) {
                    this['status'] = "Offline";
                    color = "<font color='purple'>";
                    show = 0;
                }
                if (this['status'] == 1) {
                    this['status'] = "10-8 On Duty";
                    color = "<font color='green'>";
                }
                if (this['status'] == 2) {
                    this['status'] = "10-6 Busy";
                    color = "<font color='yellow'>";
                }
                if (this['status'] == 3) {
                    this['status'] = "10-7 Out of Service";
                    color = "<font color='red'>";
                }
                if (this['level'] <= 2) {
                    show = 0;
                }
                if (show == 1) {
                    table = table + "<tr><td style='width: 30 %'>" + this['username'] + "</td><td style='width: 30 %'>" + color + this['status'] + "</font></td><td style='width: 40 %'>" +
                        "<button class='btn btn-flat btn-success status' id='" + this['uuid'] + "' onClick='update_10_8(this.id)'>10-8</button> <button class='btn btn-flat btn-warning status' id='" + this['uuid'] + "' onClick='update_10_6(this.id)'>10-6</button> <button class='btn btn-flat btn-danger status' id='" + this['uuid'] + "' onClick='update_10_7(this.id)'>10-7</button> <button class='btn btn-flat btn-delete status' id='" + this['uuid'] + "' onClick='update_delete(this.id)'>Remove</button>" + "</td ></tr > ";
                }
            });
            table = table + "</table>"
            $("#table").html(table);
        }
    });
}, 1000);

function update_10_8(clicked_id) {
    isnewstat = true;
    newstat = 1;
    newstatuuid = clicked_id;
}
function update_10_6(clicked_id) {
    isnewstat = true;
    newstat = 2;
    newstatuuid = clicked_id;
}
function update_10_7(clicked_id) {
    isnewstat = true;
    newstat = 3;
    newstatuuid = clicked_id;
}
function update_delete(clicked_id) {
    $("#result").html("<font color='blue'>Processing request... Please wait.</font>");
    isnewaction = true;
    newaction = 99;
    newactionuuid = clicked_id;
}
function update_add(unit) {
    $("#result").html("<font color='blue'>Processing request... Please wait.</font>");
    if (unit == "") {
        $("#result").html("<font color='red'>Enter a unit number.</font>");
        document.getElementById("input").classList.remove('form-control');
        document.getElementById("input").classList.add('form-control-error');
    }
    else {
        $.ajax({
            url: '../scripts/php/update.php',
            type: 'POST',
            data: { uuid: unit, status: 101 },
            dataType: 'json',
            async: true,
            success: function (data) {
                if (data.response == "success") {
                    $("#result").html("<font color='green'>Successfully added unit!</font>");
                    document.getElementById("input").classList.remove('form-control-error');
                    document.getElementById("input").classList.add('form-control');
                }
                if (data.response == "exists") {
                    $("#result").html("<font color='red'>Unit already exists with that number. Try a different one.</font>");
                    document.getElementById("input").classList.remove('form-control');
                    document.getElementById("input").classList.add('form-control-error');
                }
            },
            error: function () {
                console.log("main err");
            }
        });
    }
}
function priority() {
    var isprior = 3;
    if (!document.getElementById('priority').checked) {
        isprior = 0;
        $("#prior").html("<font color='green'>No priority is currently in effect!</font>");
    } else {
        isprior = 1;
        $("#prior").html("<font color='red'>Priority is currently in effect!</font>");
    }
    if (isprior != 3) {
        $.ajax({
            url: '../scripts/php/update_prior.php',
            type: 'POST',
            async: true,
            data: { status: isprior },
            success: function (data) {
                isnewaction = false;
                newactionuuid = "";
                if (newaction == 99) {
                    show = 0;
                }
                console.log("done action"); // Inspect this in your console
            },
            error: function () {
                console.log("main err");
            }
        });
    }
}