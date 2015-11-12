<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Cheeser! Kill rats!</title>
	<script src='../../libs/konva.js'></script>

<style>
#GameContainer
{
	background: black url("../games_resources/Cheeser/images/wood_floor_1.png") repeat;
	cursor: url("../games_resources/Cheeser/images/hammer.png"), pointer;

}
body img
{
	visible: hidden;
}

</style>
</head>

<body>
<img id="Rat_img" src="../games_resources/Cheeser/images/rat.png" />
<img id="RatDead_img" src="../games_resources/Cheeser/images/rat_dead.png" />
<img id="FloorHole_img" src="../games_resources/Cheeser/images/floor_hole.png" />
<img id="Hammer_img" src="../games_resources/Cheeser/images/hammer.png" />
<img id="Cheese_img" src="../games_resources/Cheeser/images/cheese.png" />
<img id="Crumbs_img" src="../games_resources/Cheeser/images/crumbs.png" />


<script>

////////////////////////////	____CLASSES_______ //////////

////////////////////////////		_Rat class /////////////////
// принимает параметры:
// EmergenceCoords - координаты появления;
// 
var MouseStates =  function () 
{
	this.Members = {};
	
	this.Members.LeftMouseButton = false;
	this.Members.RightMouseButton = false;
	
};

MouseStates.prototype.onLeftButtonClickDown = function (event) 
{
	this.Members.LeftMouse = True;
}

MouseStates.prototype.onRightButtonClickDown = function (event) 
{
	
}

function Rat()
{
	this.Members = {};
	this.Members.Speed = null;
	this.Speed(4);
};
Rat.prototype.Speed = function (Speed)
{
		if (Speed)
		{
			this.Members.Speed = Speed;
		}
		else
		{
			return this.Members.Speed;
		}
};




function screenMoving()
{
	
}

function comeAnimation()
{
	
}
	
function turnToTarget(target)
{
	
}
	
function Game() {

//	GLOBAL OBJECTS


// FUNCTIONS

// Выбирваем пол

	
//////////////////////////////////////////////////////////



	var W = 2000;
	var H = 2000;

	var GameContainer;
	GameContainer = document.createElement("div");
	GameContainer.setAttribute("id", "GameContainer");
	GameContainer.style.position = "absolute";
	GameContainer.style.left = "0px";
	GameContainer.style.top = "0px";
	GameContainer.style.width = W + "px";
	GameContainer.style.height = H + "px";
	document.body.appendChild(GameContainer);
	
	var stage = new Konva.Stage({
			container: 'GameContainer',
			width: W,
			height: H
	});
	var RatImageObj = document.getElementById("Rat_img");
	var RatDeadImageObj = document.getElementById("RatDead_img");
	var CheeseImageObj = document.getElementById("Cheese_img");
	var HammerImageObj = document.getElementById("Hammer_img");
	var FloorHoleImageObj = document.getElementById("FloorHole_img");
	
	var Rat1Image = new Konva.Image({
		x: 50,
		y: 50,
		image: RatImageObj,
		scale: {x: 0.5, y: 0.5}
	});

	var Rat2Image = new Konva.Image({
		x: 900,
		y: 800,
		image: RatImageObj,
		scale: {x: 0.75, y: 0.75}
	});

	var Rat3Image = new Konva.Image({
		x: 350,
		y: 50,
		image: RatImageObj,
	});
	
	var CheeseImage = new Konva.Image({
		x: 790,
		y: 500,
		image: CheeseImageObj
	});

		Rat2Image.rotate(3/2 * 180);
		Rat2Image.rotate(Math.atan2(CheeseImage.y() - Rat2Image.y(), CheeseImage.x() - Rat2Image.x()) / Math.PI * 180);
		var layer = new Konva.Layer();	
	
		layer.add(Rat1Image);
		layer.add(Rat2Image);
		layer.add(Rat3Image);
		layer.add(CheeseImage);

	Rat2Image.to({
		x: CheeseImage.x(),
		y: CheeseImage.y(),
		duration: 2
	});

		stage.add(layer);


	

	
};	

Game();
</script>


</body>

</html>

