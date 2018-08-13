var isLookup = false;
$(document).ready(function() { 
    console.log(getUrlParameter('ip'));
    if (getUrlParameter('ip') != undefined) {
        $("#ipInput").val(getUrlParameter('ip'));
        lookup();
    }
});

function lookup() {
    $.ajax({
        url: 'getIpLookup.php',
        type: 'POST',
        data: { ip: $("#ipInput").val() },
        dataType: 'json',
        async: true,
        success: function (data) {
            if (data.status == "valid") {
                $("#returnIP").val(data.ip);
                $("#returnUsers").html(data.aliases);
                $("#isIpBanned").html("Is Banned: <font color='green'>No</font>");
                if (data.isBanned) {
                    $("#isIpBanned").html("Is Banned: <font color='red'>Yes</font>");
                    $("#bannedBy").val(data.bannedBy);
                    $("#banReason").val(data.banReason);
                }
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
        window.location.replace("../logs/?ip=" + $("#ipInput").val());
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