window.setInterval(function () {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("bolo_table").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "../scripts/php/new/bolos.php", true);
    xhttp.send();
}, 1000);