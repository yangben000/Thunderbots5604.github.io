function displayText(obj_array) {
	$("#title1").text(obj_array[obj_array.length - 1].title);
	$("#author1").text(obj_array[obj_array.length - 1].author);
	$("#content1").text(obj_array[obj_array.length - 1].content);
	$("#title2").text(obj_array[obj_array.length - 2].title);
	$("#author2").text(obj_array[obj_array.length - 2].author);
	$("#content2").text(obj_array[obj_array.length - 2].content);
	$("#title3").text(obj_array[obj_array.length - 3].title);
	$("#author3").text(obj_array[obj_array.length - 3].author);
	$("#content3").text(obj_array[obj_array.length - 3].content);
}

var xmlhttp = new XMLHttpRequest();
var url = "notifications/notify.php";
xmlhttp.onreadystatechange = function() {
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var obj_array = JSON.parse(xmlhttp.responseText);
		displayText(obj_array);
	}
}

xmlhttp.open("GET", url, true);
xmlhttp.send();