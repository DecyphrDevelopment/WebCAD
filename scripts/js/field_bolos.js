window.setInterval(function () {
    $.ajax({
        type: "POST",
        async: true,
        url: "../scripts/php/bolos_db.php",
        success: function (data) {
            var obj = $.parseJSON(data);

            var table = "<table border='1' cellpadding='10' class='' id='tab'><tr> <th style='width:100%'>Bolo</th> </tr>"
            $.each(obj, function () {
                var show = 1;
                var color = "";
                if (show == 1) {
                    table = table + "<tr><td style='width:100%'>" + this['bolo'] + "</td></tr > ";
                }
            });
            table = table + "</table>"
            $("#bolo_table").html(table);
        }
    });
}, 1000);