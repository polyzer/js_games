<?php 
	session_start();
	$_SESSION["vk_cheeser"]["true_connection"] = "true";
?>


<!DOCTYPE html>
<html>
<head></head>

<body>
<script>
// тексты запросов:
// get_result_by_vk_id
// save_result
// get_rating_by_num


var xmlhttp;	
var SendDatas;
	
function setRating(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	if (ServerAnswerDatas.server_answer == "HAVE_RATING")
	{
		console.log(ServerAnswerDatas.result_datas.user_results.vk_id);
	} else
	{
		console.log(ServerAnswerDatas.server_answer);
	}
}

function setResults(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	if (ServerAnswerDatas.server_answer == "HAVE_DATA")
	{
		console.log(ServerAnswerDatas.result_datas.user_results.vk_id);
	} else
	{
		console.log(ServerAnswerDatas.server_answer);
	}
}

function saveResults(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	
	if (ServerAnswerDatas.server_answer == "DATA_UPDATED" || 
			ServerAnswerDatas.server_answer == "DATA_SAVED")
	{
		console.log(ServerAnswerDatas.server_answer);
	} else
	{
		console.log(ServerAnswerDatas.server_answer);
	}
}

function getRatingRequest()
{
	SendDatas = {
		Operation: "get_rating_by_num",
		RatsKilled: 11,
		Time: 19,
		vk_id: "id4357883",
		Level: 4,
		RateNum: 10
	};
	// кодируем показания для передачи
	SendDatas = "Datas=" + JSON.stringify(SendDatas);
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
	SendDatas = {
		Operation: "get_result_by_vk_id",
		RatsKilled: 11,
		Time: 19,
		vk_id: "id43578832342",
		Level: 4,
		RateNum: 10
	};
	// кодируем показания для передачи
	SendDatas = "Datas=" + JSON.stringify(SendDatas);
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
	// снимаем показания!
	SendDatas = {
		Operation: "save_result",
		RatsKilled: 31,
		Time: 31,
		vk_id: "id43578834",
		Level: 4,
		RateNum: 10
	};
	// кодируем показания для передачи
	SendDatas = "Datas=" + JSON.stringify(SendDatas);
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
	
saveResultsRequest();


</script>
</body>
</html>
