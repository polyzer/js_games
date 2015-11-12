<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>MANY WAYS GAME</title>
<script src='../../libs/konva.js'></script>
<style>
#GameContainer
{
	background-color: yellow;
}

html
{
	overflow: hidden;
}
</style>
</head>

<body>
	
<script>
function Game() {

//	GLOBAL OBJECTS
	var Dots; // точки, между которыми мы будем перемещаться
	var Arrows; // это стрелки!


	Dots = new Array();
	Arrows = new Array();


// FUNCTIONS

// Пересечение точек кругов!!!!!
	function intersectCircles(json_params)
	{
		if (json_params.Dots)
		{
			// Если задан радиус дальности!!!
			if (json_params.RangeRadius)
			{
				for(var i = (json_params.Dots.length - 1); i >= 1; i--)
				{
					for (var j = (i - 1); j >= 0; j--)
					{
						if(
							((Math.abs(json_params.Dots[i].x() - json_params.Dots[j].x())) < (json_params.Dots[i].radius() + json_params.Dots[j].radius() + json_params.RangeRadius)
							&&
							(Math.abs(json_params.Dots[i].y() - json_params.Dots[j].y())) < (json_params.Dots[i].radius() + json_params.Dots[j].radius() + json_params.RangeRadius))
							)
						{
							json_params.Dots[i].remove();
							json_params.Dots.splice(i,1);
							break;
						};
					}
					
				}
			} else
			{// Если радиус дальности не задан!!!
				for(var i = (json_params.Dots.length - 1); i >= 1; i--)
				{
					for (var j = (i - 1); j >= 0; j--)
					{
						if(
							((Math.abs(json_params.Dots[i].x() - json_params.Dots[j].x())) < (json_params.Dots[i].radius() + json_params.Dots[j].radius())
							&&
							(Math.abs(json_params.Dots[i].y() - json_params.Dots[j].y())) < (json_params.Dots[i].radius() + json_params.Dots[j].radius()))
							)
						{
							json_params.Dots[i].remove();
							json_params.Dots.splice(i,1);
							break;
						};
					}
				}				
			}//
		}
	}
////////// конец функции распознавания пересечения кругов!
	function genCircles(json_params)
	{
		var retObj = new Object();
		retObj.circles = new Array();
		var WidthRange, HeightRange;
		if(json_params)
		{
			if(json_params.count)
			{
				if (json_params.WidthRange && json_params.HeightRange)
				{
						WidthRange = json_params.WidthRange;
						HeightRange = json_params.HeightRange;
				} else
				{
					WidthRange = window.innerWidth;
					HeightRange = window.innerHeight;
				}
				
				for (var i = 0; i < json_params.count; i++)
				{
					var circle = new Konva.Circle({
						x: Math.random() * WidthRange,
						y: Math.random() * HeightRange,
						radius: 50,
						fill: 'blue',
						stroke: 'black',
						strokeWidth: 6,
						draggable: true
					});
					retObj.circles.push(circle);
					if (json_params.layer)
					{
						layer.add(circle);
					}
				}
			}
		}
		return retObj;
	}
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
	
	var layer = new Konva.Layer();	
	
	var circlesObj = genCircles({
			count: 100,
			layer: layer,
			"WidthRange": W,
			"HeightRange": H
	});
	
	intersectCircles({"Dots": circlesObj.circles, "RangeRadius": 90});
	
	stage.add(layer);
	for (var i = 0; i < circlesObj.circles.length; i++)
	{
		circlesObj.circles[i].on('click', 
			function() 
			{	
				this.remove();
				layer.draw();
			}
		);
	}

	layer.draw();
	
	
};	

Game();
</script>
	
	
</body>
</html>
