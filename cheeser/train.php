<?php 
	session_start();
	$_SESSION["vk_cheeser"]["true_connection"] = "true";
?>


<!DOCTYPE html>
<html>
<head></head>

<body>
<script>
function doRequest (SendDatas) 
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open('POST', './cheeser_game_funcs.php', true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			 if(xmlhttp.status == 200) {
				 alert(xmlhttp.responseText);
			 }
		}
	};
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send(SendDatas);
	
}	
	
var SendDatas = {
	Operation: "get_result_by_vk_id",
	RatsKilled: 11,
	Time: 19,
	vk_id: "id43578832",
	Level: 4,
	RateNum: 10
};
// кодируем данные!
SendDatas = "Datas=" + JSON.stringify(SendDatas);

doRequest(SendDatas);

</script>
</body>
</html>
