<?php 
	session_start();
if ($_SESSION["vk_cheeser"]["true_connection"] && 
	$_POST["Datas"]
   ) 
{
	// присланные AJAX данные
	$datas = json_decode(stripslashes($_POST["Datas"]), true);
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
	//user    : host1302305
	//password: 54c8b5d4

	$mysqli = new mysqli("localhost", //адрес хоста БД
						 "root", // имя пользователя
						 "000000", //пароль
						 "cheeser_game"); //база данных
	
	if ($mysqli->connect_errno) {
		echo "Не удалось подключиться к MYSQL: (" . $mysqli->connect_errno . ") ". $mysqli->connect_error;
	} else {
		$mysqli->set_charset("utf8");
	}
	
	if ($datas["Operation"] === "get_result_by_vk_id") {
		$res = $mysqli->query("SELECT * FROM `results` WHERE `vk_id`='".$datas["vk_id"]."';");
		if ($res->num_rows != 0){
			$row = $res->fetch_assoc();			
			foreach($row as $key => $value) {
				$result_arr["result_datas"]["user_results"][$key] = $value;
			}
			$result_arr["server_answer"] = "HAVE_DATA";
			echo json_encode($result_arr);
		} else {
			$result_arr["server_answer"] = "HAVE_NO_DATA";
			echo json_encode($result_arr);
		}
	}
	
		
	if ($datas["Operation"] === "save_result") {
		$res = $mysqli->query("SELECT * FROM `results` WHERE `vk_id`='".$datas["vk_id"]."';");
		if ($res->num_rows != 0){
			$row = $res->fetch_assoc();			
			foreach($row as $key => $value) {
				$result_arr["result_datas"]["user_results"][$key] = $value;
			}
////////////////// проверка!!!!!!!!!!!!!!!!!!!
			/// устанавливаем значение по-умолчанию!
			$result_arr["server_answer"] = "NO_DATA_SAVE/UPDATES";
			
			if ($datas["RatsKilled"])
			{
				$query = "UPDATE `results` SET `rats_killed_max` = '". ((int)$datas["RatsKilled"] + (int)$result_arr["result_datas"]["user_results"]["rats_killed_max"])."' WHERE `vk_id`='".$datas["vk_id"]."';";
				if ($mysqli->query($query)) {
					$result_arr["server_answer"] = "DATA_UPDATED";
				}
			} 
			if ($datas["Time"] > $result_arr["result_datas"]["user_results"]["time_max"])
			{
				$query = "UPDATE `results` SET `time_max` = '".$datas["Time"]."' WHERE `vk_id`='".$datas["vk_id"]."';";
				if ($mysqli->query($query)) {
					$result_arr["server_answer"] = "DATA_UPDATED";
				}
			}
			if ($datas["Level"] > $result_arr["result_datas"]["user_results"]["level_max"])
			{
				$query = "UPDATE `results` SET `level_max` = '".$datas["Level"]."' WHERE `vk_id`='".$datas["vk_id"]."';";
				if ($mysqli->query($query)) {
					$result_arr["server_answer"] = "DATA_UPDATED";
				}
			} 			 			
			echo json_encode($result_arr);
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
		if (!($res = $mysqli->query($query_string)))
			echo $mysqli->error;
		else {
			$result_arr["server_answer"] = "DATA_SAVED";;
			echo json_encode($result_arr);
		}
		}
	}

	if ($datas["Operation"] === "get_rating_by_num") {
		$res = $mysqli->query("SELECT * FROM `results` ORDER BY `rats_killed_max` DESC LIMIT ".$datas["RateNum"]." ;");
		if ($res->num_rows != 0){
			$i = 0;
			while($row = $res->fetch_assoc())
			{
					$result_arr["result_datas"]["best_rating"][$i] = array();
					$result_arr["result_datas"]["best_rating"][$i]["id"] = $row["id"];
					$result_arr["result_datas"]["best_rating"][$i]["rats_killed_max"] = $row["rats_killed_max"];
					$result_arr["result_datas"]["best_rating"][$i]["time_max"] = $row["time_max"];
					$result_arr["result_datas"]["best_rating"][$i]["level_max"] = $row["level_max"];
					$result_arr["result_datas"]["best_rating"][$i]["vk_id"] = $row["vk_id"];					
/*				foreach($row as $key => $value) {
					// добавляем новую запись в массив
					$result_arr["result_datas"]["best_rating"][$i] = array();
					$result_arr["result_datas"]["best_rating"][$i][$key] = $value;
				}
	*/
				// увеличиваем счетчик!
				$i++;
	}
			$result_arr["server_answer"] = "HAVE_RATING";
			echo json_encode($result_arr);
		} else {
			$result_arr["server_answer"] = "HAVE_NO_RATING";	
			echo json_encode($result_arr);
		}
	}
	
	

} else {
	echo "You have no permission!";
}
?>

