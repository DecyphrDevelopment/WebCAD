var isLookup = false;
$(document).ready(function() { 
    console.log(getUrlParameter('user'));
    if (getUrlParameter('user') != undefined) {
        $("#unInput").val(getUrlParameter('user'));
        lookup();
    }
});


function lookup() {
    $.ajax({
        url: 'getUserLookup.php',
        type: 'POST',
        data: { username: $("#unInput").val() },
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.status == "valid") {
                $("#returnUsername").val(data.username);
                $("#returnIPs").html(data.ips);
                $("#returnAliases").html(data.aliases);
                var color = "#ffffff";
                if (data.group == "Super Admin") {
                    color = "#ff4f4f";
                }
                if (data.group == "Admin") {
                    color = "#ffe100";
                }
                if (data.group == "User") {
                    color = "#00d1a7";
                }
                if (data.group == "Dispatch Blacklisted") {
                    color = "#930093";
                }
                $("#userUUID").val(data.uuid);
                $("#userUN").val(data.unitnumber);
                $("#userGroup").val(data.group);
                $("#userGroup").css("color", color);
                isLookup = true;
            }
        },
        error: function () {
            console.log("error");
        }
    });
}
function goToLogs() {
    if (isLookup) {
        window.location.replace("../logs/?username=" + $("#unInput").val());
    }
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