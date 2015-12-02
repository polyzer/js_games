<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Konva Wheel of Fortune Demo</title>
  <script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>
</head>
<body>
  <div id="container"></div>
  <script>
function VK_VARS() {
	this.user_id = "0";
	answr = location.search;
	answr = answr.split("&");
	for (var i = 0; i < answr.length; i++) {
		answr[i] = answr[i].split('=');//Создание двумерного массива
		this[answr[i][0]] = answr[i][1];//Создание объекта, со свойствами двумерного массива.
	}
	if (this.user_id == 0) {
		this.user_id = this.viewer_id;
	}
};

var MyVK = new VK_VARS();

  </script>

</body>
</html>
