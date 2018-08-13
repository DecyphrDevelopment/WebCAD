var isnewbolo = false;
var newbolo = "";

var isnewboloaction = false;
var newboloaction = 0;
var newactionubid = "";
var newbolo = "";

window.setInterval(function () {
    $.ajax({
        type: "POST",
        async: true,
        url: "../scripts/php/bolos_db.php",
        success: function (data) {
            if (isnewboloaction) {
                isnewboloaction = false;
                if (newboloaction == 101) {
                    //alert("isnewboloaction 101");

                }
                if (newboloaction == 99) {

                }
                newboloaction = 0;
            }
            var obj = $.parseJSON(data);

            var table = "<table border='1' cellpadding='10' class='' id='tab'><tr> <th style='width:80%'>Bolo</th> <th style='width:20%'>Update</th> </tr>"
            $.each(obj, function () {
                var show = 1;
                var color = "";
                if (show == 1) {
                    table = table + "<tr><td style='width:80%'>" + this['bolo'] + "</td> <td style='width:20%'>" +
                        "<button class='btn btn-flat btn-delete status' id='" + this['ubid'] + "' onClick='bolo_delete(this.id)'>Remove</button>" + "</td ></tr > ";
                }
            });
            table = table + "</table>"
            $("#bolo_table").html(table);
        }
    });
}, 1000);
function bolo_delete(clicked_id) {
    $("#bolo_result").html("<font color='blue'>Processing request... Please wait.</font>");
    $.ajax({
        url: '../scripts/php/bolos_db.php',
        type: 'POST',
        data: { action: 99, ubid: clicked_id },
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.response == "success") {
                $("#bolo_result").html("<font color='green'>Removed bolo!</font>");
            }
        }
    });
}
function bolo_add(bolo) {
    $("#bolo_result").html("<font color='blue'>Processing request... Please wait.</font>");
    $.ajax({
        url: '../scripts/php/bolos_db.php',
        type: 'POST',
        data: { action: 101, bolo: bolo },
        dataType: 'json',
        async: true,
        success: function (data) {
            isnewboloaction = false;
            if (data.response == "success") {
                $("#bolo_result").html("<font color='green'>Added bolo!</font>");
            }
        },
        error: function (data) {
            alert("err");
        }
    });
}