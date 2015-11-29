<?php 
	session_start();
if ($_SESSION["vk_cheeser"]["true_connection"] && 
	$_POST["Datas"]
   ) 
{
	// присланные AJAX данные
	$datas = addslashes($_POST["Datas"]);
	
	// ассоциативный массив, который будет преобразован в JSON объект и возвращен через echo
	$result_arr = array();
	// ответ сервера - есть данные, или нет!
	$result_arr["server_answer"] = NULL;
	// результирующие данные, в которых хранится результат текущий
	$result_arr["result_datas"] = array();
	// массив с результатами пользователя!
	$result_arr["result_datas"]["user_results"] = array();
	// здесь возвращается 10 лучших результатов!
	// пример:
	// $result_arr["result_datas"]["best_rating"]["1"]["rats_killed"] = 10;
	// $result_arr["result_datas"]["best_rating"]["1"]["time"] = 10;
	// $result_arr["result_datas"]["best_rating"]["1"]["vk_id"] = id23497976;
	$result_arr["result_datas"]["best_rating"] = array();
	
	echo json_encode($result_arr);
	
	
	$mysqli = new mysqli("localhost", //адрес хоста БД
						 "root", // имя пользователя
						 "000000", //пароль
						 "cheeser_game"); //база данных
	
	if ($mysqli->connect_errno) {
		echo "Не удалось подключиться к MYSQL: (" . $mysqli->connect_errno . ") ". $mysqli->connect_error;
	} else {
		$mysqli->set_charset("utf8");
	}
	
	
	if ($datas["Operation"] === "get_my_results") {
		$res = $mysqli->query("SELECT * FROM `results` WHERE `vk_id`=".$datas["VK_ID"].";");
		if ($res->num_rows != 0){
			$row = $res->fetch_assoc();			
			foreach($row as $key => $value) {
				$result_arr["result_datas"]["user_results"][$key] = $value;
			}
			$result_arr["server_answer"] = "YES_DATA";
			echo $return_string;
		} else {
			$result_arr["server_answer"] = "NO_DATA";
		}
	}
	
		
	if ($datas["Operation"] === "save_result") {
		if ($res->num_rows != 0){
			$row = $res->fetch_assoc();			
			foreach($row as $key => $value) {
				$result_arr["result_datas"]["user_results"][$key] = $value;
			}
			$query = "UPDATE `results` SET `rats_killed_max` = '".$datas["RatsKilled"]."';";
			if ($mysqli->query($query)) {
				echo "Данные обновлены!";
			}
			if ($datas["RatsKilled"] > $result_arr["result_datas"]["user_results"]["rats_killed_max"])
			{
				$query = "UPDATE `results` SET `rats_killed_max` = '".$datas["RatsKilled"]."';";
			} 
			if ($datas["Time"] > $result_arr["result_datas"]["user_results"]["time_max"])
			{
				$query = "UPDATE `results` SET `time_max` = '".$datas["Time"]."';";
			}
			if ($datas["Level"] > $result_arr["result_datas"]["user_results"]["level_max"])
			{
				$query = "UPDATE `results` SET `level_max` = '".$datas["Level"]."';";
			} 			 			
			$result_arr["server_answer"] = "YES_DATA";
			echo $return_string;
		} else {
			$query_string = "INSERT INTO `results` (`id`,
												   `rats_killed_max`,
												   `time_max`,
												   `level_max`,
												   `vk_id`)
											VALUES (NULL, 
							           '".$datas["RatsKilled"]."',
							           '".$datas["Time"]."',
							           '".$datas["Level"]."',
							           '".$datas["vk_id"]."');";
		}
		
		if (!($res = $mysqli->query($query_string)))
			echo $mysqli->error;
		else {
			echo 	$result_arr["server_answer"] = "DATAS_SAVED";;
		}
	}

	if ($datas["Operation"] === "get_rating_by_num") {
		$res = $mysqli->query("SELECT * FROM `results` WHERE `vk_id`='".$datas["vk_id"]."';");
		if ($res->num_rows != 0){
			$row = $res->fetch_assoc();
			$return_string = "";
			foreach($row as $key => $value) {
				$return_string .= $key."===".$value."&&&";
			}
			$return_string .= "server_answer===YES_DATA&&&";
			echo $return_string;
		} else {
			echo "server_answer===NO_DATA&&&";
		}
		
		
	}
	
	if ($datas["Operation"] === "update_game_results") {
		$query = "UPDATE `results` SET `first_column` = '$first_column',
										`second_column` = '$second_column',
										`third_column` = '$third_column',
										`fourth_column` = '$fourth_column',
										`time_date` = '$time_date'
									WHERE `vk_id` = $VKID;";
		if ($mysqli->query($query)) {
			echo "Данные обновлены!";
		}
	
	}
	
	
	

} else {
	echo "You have no permission!";
}
?>

