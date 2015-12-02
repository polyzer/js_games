<?php 
	session_start();
	$_SESSION["vk_cheeser"]["true_connection"] = "true";
?>


<!DOCTYPE html>
<html>
<head></head>

<body>
<script>

var xmlhttp;	
	
function setRating(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	if (ServerAnswerDatas.server_answer == "HAVE_RATING")
	{
		window.alert(ServerAnswerDatas.server_answer);
	}
}

function setResults(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	if (ServerAnswerDatas.server_answer == "HAVE_DATA")
	{
		window.alert(ServerAnswerDatas.server_answer);		
	}
}

function saveResults(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	
	if (ServerAnswerDatas.server_answer == "DATA_UPDATED" || 
			ServerAnswerDatas.server_answer == "DATA_SAVED")
	{
			window.alert(ServerAnswerDatas.server_answer);
	}
}

function getRatingRequest(SendDatas)
{
	doRequest(function() {
		if (xmlhttp.readyState == 4) {
			 if(xmlhttp.status == 200) {
				saveResults(xmlhttp.responseText);
			 }
		}
	},
	SendDatas);
}

function getResultsRequest(SendDatas)
{
	doRequest(function() {
		if (xmlhttp.readyState == 4) {
			 if(xmlhttp.status == 200) {
				setResults(xmlhttp.responseText);
			 }
		}
	},
	SendDatas);
}
function saveResultsRequest(SendDatas)
{
	doRequest(function() {
		if (xmlhttp.readyState == 4) {
			 if(xmlhttp.status == 200) {
				saveResults(xmlhttp.responseText);
			 }
		}
	},
	SendDatas);
}
	
function doRequest (onFunction, SendDatas) 
{
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open('POST', './cheeser_game_funcs.php', true);
	xmlhttp.onreadystatechange = onFunction;
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

getResultsRequest(SendDatas);

</script>
</body>
</html>
