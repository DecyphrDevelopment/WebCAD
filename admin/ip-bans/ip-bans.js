function checkDelete(){
    return confirm('Are you sure you want to delete this ban?');
}
function deleteBan(ubid) {
	if (confirm('Are you sure you want to save this thing into the database?')) {
		var httpStatus = new XMLHttpRequest();
		var url = "delete.php";
		var statusParams = "ubid=" + ubid;
		httpStatus.open("GET", url + "?" + statusParams, true);
		httpStatus.onreadystatechange = function () {
			if (httpStatus.readyState == 4 && httpStatus.status == 200) {
				if (this.responseText == "1") {
					$("#det_result").html("<font color='green'>Successfully unbanned.</font>");
				} else if (this.responseText == "0") {
					$("#det_result").html("<font color='red'>Error!</font>");
				}
			}
		};
		httpStatus.send(null);
	} else {
		// Do nothing!
	}
}