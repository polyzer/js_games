<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Cheeser! Kill rats!</title>
	<script src='../../libs/konva.js'></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./cheeser_game_style.css" />
</head>

<body>	
<img width = "20" height = "35" id="Rat_img" src="../games_resources/Cheeser/images/rat.png" />
<img width = "20" height = "35" id="RatDead_img" src="../games_resources/Cheeser/images/rat_dead.png" />
<img width = "40" height = "35" id="FloorHole_img" src="../games_resources/Cheeser/images/floor_hole.png" />
<img width = "40" height = "35" id="FloorHoleRepaired_img" src="../games_resources/Cheeser/images/floor_hole_repaired.png" />
<img width = "20" height = "30" id="Hammer_img" src="../games_resources/Cheeser/images/hammer.png" />
<img width = "20" height = "15" id="Cheese_img" src="../games_resources/Cheeser/images/cheese.png" />	
<img width = "15" height = "10" id="Crumbs_img" src="../games_resources/Cheeser/images/crumbs.png" />	

<div id="GameMenu">
	<div id="SurvivalGame">
		Выживание!
	</div>
</div>

<div id="GameResult">
	<div id="ResultStatus">
	</div>
	<div id="ResultBlock">
		<div id="RatsKilledResult">
		</div>
		<div id="TimeResult">
		</div>
	</div>
	<div id="RestartButton">
	 Еще?
	</div>
</div>

<script>
	var W = 1000; // ширина
	var H = 970; // высота

	var gameProcessTimer = null;
	// режим игры!
	var GAMEMODE = "survival";
	
function clone(obj) {
    var copy;

    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj) return obj;

    // Handle Date
    if (obj instanceof Date) {
        copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        copy = [];
        for (var i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}	
	

////////////////// My GameTimer CLASS////////////////////////////
////////////////////////////////////////////////////////////////
// json_params:
// {
//	StartTime: 0,
//	EndTime: 5,
//	FuncContext: this,
//	Parameters: {}
//	CalledFunction : function() {}
// }
function _GameTimer (json_params)
{
	this.Members = {};
	this.Members.CurrentTime = null;
	this.Members.EndTime = null;
	this.Members.CalledFunction = null;
	this.Members.FuncResultAnswer = null;
	this.Members.FuncContext = null;
	this.Members.Parameters = null;
	// значения:
	// free, working
	this.Members.Status = "free";
	if (json_params !== undefined)
	{
		this.set();
	}
}

_GameTimer.prototype.increaseTime = function (Value)
{
	if (this.Members.Status ==  "working")
	{
		if(Value !== undefined)
			this.Members.CurrentTime += Value;
	}
}

_GameTimer.prototype.checkTimer = function (Value)
{
	if (this.Status == "working")
	{
		if(this.CurrentTime >= this.Members.EndTime)
		{
			this.Members.CalledFunction();
		}
		this.Members.CurrentTime = null;
		this.Members.EndTime = null;
		this.Members.Status = "free";		
	}
}

_GameTimer.prototype.set = function (json_params) 
{
	if(this.Status == "free")
	{
		if (json_params !== undefined)
		{
			if (json_params.EndTime !== undefined)
			{
				this.Members.EndTime = json_params.EndTime;
			} else
			{
				console.log(this.constructor.name + " have no EndTime parameter");
				return;
			}
			if (json_params.CalledFunction !== undefined)
			{
				this.Members.CalledFunction = json_params.CalledFunction;
			} else
			{
				console.log(this.constructor.name + " have no CalledFunction parameter");
				return;
			}
			if (json_params.StartTime !== undefined)
			{
				this.Members.CurrentTime = json_params.StartTime;
			} else
			{
				console.log(this.constructor.name + " have no StartTime parameter");
				return;
			}			this.Members.Status = "working";			
		}
	}
}
////////////////////// END OF _GameTimer CLASS /////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

function _GameStats () { // статистика!
		this.Div = document.createElement("div");
		this.Div.setAttribute("id", "GameStats");
		this.Div.style.position = "absolute";
		this.Div.style.left = "0px";
		this.Div.style.top = "0px";
		this.Div.style.width = W + "px";
		this.Div.style.height = "30px";
		this.Div.style.backgroundColor = "blue";
		this.Div.style.borderBottom = "1px solid blue";
		document.body.appendChild(this.Div);
		
		this.RatsKilledDiv = document.createElement("div");		
		this.RatsKilledDiv.style.height = "100%";
		this.RatsKilledDiv.style.width = "25%";
		this.RatsKilledDiv.style.float = "left";
		this.RatsKilledDiv.style.backgroundColor = "#f5f1cb";
		this.Div.appendChild(this.RatsKilledDiv);

		this.FoodsDiv = document.createElement("div");		
		this.FoodsDiv.style.height = "100%";
		this.FoodsDiv.style.width = "25%";
		this.FoodsDiv.style.float = "left";
		this.FoodsDiv.style.backgroundColor = "#f5f1cb";
		this.Div.appendChild(this.FoodsDiv);

		this.FloorHolesDiv = document.createElement("div");		
		this.FloorHolesDiv.style.height = "100%";
		this.FloorHolesDiv.style.width = "25%";
		this.FloorHolesDiv.style.float = "left";
		this.FloorHolesDiv.style.backgroundColor = "#f5f1cb";
		this.Div.appendChild(this.FloorHolesDiv);
		
		this.TimerDiv = document.createElement("div");		
		this.TimerDiv.style.height = "100%";
		this.TimerDiv.style.width = "25%";
		this.TimerDiv.style.float = "left";
		this.TimerDiv.style.backgroundColor = "#f5f1cb";
		this.Div.appendChild(this.TimerDiv);		
		
		this.RatsKilledCounter = 0; // счетчик убитых крыс
		this.FoodsCounter = 0; // счетчик оставшейся пищи
		this.FloorHolesCounter = 0; // количество дыр!
		
		this.Timer = 0; // таймер, засекающий время продолжительности игры.
		
		this.updateDivs();
};

_GameStats.prototype.updateDivs = function () 
{
	this.RatsKilledDiv.innerHTML = "Крыс убито: " + this.RatsKilledCounter;
	this.FoodsDiv.innerHTML = "Пищи осталось: " + this.FoodsCounter;
	this.FloorHolesDiv.innerHTML = "Дыр в полу: " + this.FloorHolesCounter;
}

_GameStats.prototype.increaseRatsKilledCounter = function () 
{
	this.RatsKilledCounter++;
	this.updateDivs();
}

_GameStats.prototype.reduceRatsKilledCounter = function () 
{
	this.RatsKilledCounter--;
	this.updateDivs();
}

_GameStats.prototype.increaseFoodsCounter = function () 
{
	this.FoodsCounter++;
	this.updateDivs();
}

_GameStats.prototype.reduceFoodsCounter = function () 
{
	this.FoodsCounter--;
	this.updateDivs();
}

_GameStats.prototype.increaseFloorHolesCounter = function () 
{
	this.FloorHolesCounter++;
	this.updateDivs();
}

_GameStats.prototype.reduceFloorHolesCounter = function () 
{
	this.FloorHolesCounter--;
	this.updateDivs();
}

_GameStats.prototype.clearStats = function ()
{
		this.RatsKilledCounter = 0; // счетчик убитых крыс
		this.FoodsCounter = 0; // счетчик оставшейся пищи
		this.FloorHolesCounter = 0; // количество дыр!
		
		this.Timer = 0; // таймер, засекающий время продолжительности игры.
		this.updateDivs();
}

var gamestats = new _GameStats();


////////////////////////////	____CLASSES_______ //////////

////////////////////////////		_Rat class /////////////////
// принимает параметры:
// 
function _Rat (json_params) 
{
		this.Members = {};
		
		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = document.getElementById("Rat_img");
		this.Members.ImgObjs.Attack = document.getElementById("Rat_img");
		this.Members.ImgObjs.Dead = document.getElementById("RatDead_img");
		this.Members.ImgObjs.Damage = document.getElementById("Rat_img");
		
		this.Members.Layer = null;
		
		this.Members.Image = new Konva.Image();
		this.Members.Health = null; // здоровье, которое будет убывать, когда мы будем их бить.
		this.Members.Speed = null;  // скорость движения изображения
		this.Members.SpeedLimit = null; // лимит, который не может быть превышен
		this.Members.SpeedFactor = null;  // Фактор, на кот умнож текущая скорость
		this.Members.Step = null; // шаг скорости который будет прибавляться к текущей
		this.Members.Color = null; // цвет... не знаю зачем, пока.
//		this.Members.AttackDistance = null; // дистанция атаки
		this.Members.Damage = null; //
		this.Members.DamageFactor = null; // фактор, на кот умножается 
		this.Members.Target = null;
		// положение X и Y берутся в Image
				
		this.Members.Status = null;
		this.Members.AttackPoint = {};
				/// Проработать установку значений по-умолчанию
		if (json_params !== undefined)
		{
			this.init(json_params);
		}
		
		this.Image().image(this.ImgObjs().Default);
		this.Image().rotation(3/2 * 180);
		this.Layer().add(this.Image());
		this.Image().RatObj = this;
		this.Image().on('click', function (event) {
			event.target.RatObj.onClick({"Weapon" : Weapon});
		});
		if (json_params.Scale !== undefined)
		{
			this.Image().width(this.Image().width() * json_params.Scale.x);
			this.Image().height(this.Image().height() * json_params.Scale.y);
			this.Image().draw();
		}
		this.Layer().draw();
		console.log("_Rat: Я родился");

}	
////////////////////////////////////////////////////////////
// get/set members functions!!!!!!!!!!!!!!////////////////

_Rat.prototype.isDead = function ()
{
	if (this.Status() == "Dead")
	{
		return 1;
	} else
	{
		return 0;
	}
}

_Rat.prototype.isGoing = function ()
{
	if (this.Status() == "Going")
	{
		return 1;
	} else
	{
		return 0;
	}
}


_Rat.prototype.Layer = function(Value)
{
	if (Value !== undefined)
	{
		this.Members.Layer = Value;
	} else
	{
		return this.Members.Layer;
	}
}


_Rat.prototype.Speed = function(Speed)
{
	if (Speed !== undefined)
	{
		this.Members.Speed = Speed;
	} else
	{
		return this.Members.Speed;
	}
}

_Rat.prototype.SpeedLimit = function(SpeedLimit)
{
	if (SpeedLimit !== undefined)
	{
		this.Members.SpeedLimit = SpeedLimit;
	} else
	{
		return this.Members.SpeedLimit;
	}
}

_Rat.prototype.SpeedFactor = function(Value)
{
	if (Value !== undefined)
	{
		this.Members.SpeedFactor = Value;
	} else
	{
		return this.Members.SpeedFactor;
	}
}


_Rat.prototype.Step = function(Value)
{
	if (Value !== undefined)
	{
		this.Members.Step = Value;
	} else
	{
		return this.Members.Step;
	}
}

_Rat.prototype.X = function (X)
{
	if (X !== undefined)
	{
		this.Members.Image.x(X);
	} else
	{
		return this.Members.Image.x();
	}
}
_Rat.prototype.Y = function (Y)
{
	if (Y !== undefined)
	{
		this.Members.Image.y(Y);
	} else
	{
		return this.Members.Image.y();
	}
}

_Rat.prototype.Health = function (Health)
{
	if (Health !== undefined)
	{
		this.Members.Health = Health;
		console.log(this.constructor.name + " health: " + this.Members.Health);
	} else
	{
		return this.Members.Health;
	}
}

_Rat.prototype.Height = function (Height)
{
	if (Height !== undefined)
	{
		this.Members.Image.height(Height);
	} else
	{
		return this.Members.Image.height();
	}
}

_Rat.prototype.Width = function (Width)
{
	if (Width !== undefined)
	{
		this.Members.Image.width(Width);
	} else
	{
		return this.Members.Image.width();
	}
}

_Rat.prototype.Damage = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Damage = Value;
	} else
	{
		return this.Members.Damage;
	}
}

_Rat.prototype.DamageFactor = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.DamageFactor = Value;
	} else
	{
		return this.Members.DamageFactor;
	}
}

_Rat.prototype.AttackDistance = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.AttackDistance = Value;
	}else
	{
		return this.Members.AttackDistance;
	}
}


_Rat.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Rat.prototype.Image = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}

_Rat.prototype.Status = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Status = Value;
		console.log(this.constructor.name + " " + this.Members.Status);
	} else
	{
		return this.Members.Status;
	}
}

_Rat.prototype.Target = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Target = Value;
	} else
	{
		return this.Members.Target;
	}
}


////////////////////////////////////////////////////////////
// увеличение скорости

// данная функция просто будет вызываться в главном процессе,
// крыса будет сама вести свое существование!
//json_params должны быть следующие:
// Targets - массив с целями, из которого они будут выбираться!
// 

_Rat.prototype.init = function (json_params)
{

	if (json_params)
	{
		
		if (json_params.ImgObjs)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Image)
		{
			this.Image(json_params.Image); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Layer)
		{
			this.Layer(json_params.Layer); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Health)
		{
			this.Health(json_params.Health); // здоровье, которое будет убывать, когда мы будем их бить.
		}					
		if (json_params.Speed)
		{
			this.Speed(json_params.Speed);  // скорость движения изображения
		}					
		if (json_params.SpeedLimit)
		{
			this.SpeedLimit(json_params.SpeedLimit); // лимит, который не может быть превышен
		}					
		if (json_params.SpeedFactor)
		{
			this.SpeedFactor(json_params.SpeedFactor);  // Фактор, на кот умнож текущая скорость
		}					
		if (json_params.Step)
		{
			this.Step(json_params.Step); // шаг скорости который будет прибавляться к текущей
		}					
		if (json_params.Color)
		{
			this.Color(json_params.Color); // цвет... не знаю зачем, пока.
		}					
		if (json_params.Width)
		{
			this.Width(json_params.Width); // ширина крысы (Image)
		}					
		if (json_params.Height)
		{
			this.Height(json_params.Height); // размер изображения 
		}					
		if (json_params.X)
		{
			this.X(json_params.X); // положение X копируется в Image
		}					
		if (json_params.Y)
		{
			this.Y(json_params.Y); // положение Y копируется в Image
		}					
		if (json_params.AttackDistance)
		{
			this.AttackDistance(json_params.AttackDistance); // дистанция атаки
		}					
		if (json_params.Damage)
		{
			this.Damage(json_params.Damage); //
		}					
		if (json_params.DamageFactor)
		{
			this.DamageFactor(json_params.DamageFactor); // фактор, на кот умножается 
		}					
		if (json_params.Target)
		{
			this.Target(json_params.Target);
		}					
		if (json_params.Status)
		{		
			this.Status(json_params.Status);
		}					

	}
	
}

// функция, вызываемая в основном цикле!
_Rat.prototype.Life = function (json_params)
{
	// если я мертва - то ничего не делать!
	if (this.isDead())
		return;
	// если у нас нет цели, то выбирваем!	
	if (this.Target() == null)
	{
		if (json_params.Targets !== undefined)
		{
			if (json_params.Targets.length == 0)
			{
				console.log(this.constructor.name + ": have no food....");
				return;
			}
		}
		// выбираем цель из полученного списка!
		this.select1of5Target(json_params);
		// поворачиваемся к цели!
		this.turnToTarget({"Target" : this.Target()});
		// идем к цели
		this.comeTo({"Target" : this.Target()});
	} else 
	// если цель есть!
	{
		if(this.Status() == "Live")
		{
		// поворачиваемся к цели!
		this.turnToTarget({"Target" : this.Target()});
		// идем к цели
		this.comeTo({"Target" : this.Target()});
		}
		// если мы доели пищу - обнуляем ее, и ищем новую!		
		if (this.Target().isEaten())
		{
			this.Target(null);
			this.Status("Live");
		} else
		// если мы сейчас идем к цели - ничего не делаем...
		// если мы можем атаковать - атакуем....
		if (this.isCanAttack()){
			this.attackTarget({"Target" : this.Target()});
		}
	}
	
}


/*
// на дистанции атаки!
_Rat.prototype.isAtAttackDistance = function (json_params)
{
	if (this.Target() != null)
	{
		this.calculateAttackDistance();
		if (this.AttackDistance() <= 0)
		{
			return 1; // на дистанции атаки
		} else
		{
			return 0; // не на дистанции атаки!
		}
	}
	else 
	{
			console.log("from _Rat.isAtAttackDistance: Нет цели!");
			return 0;
	}		
}
*/

// передается массив с целями, которые еще присутствуют в игре:
// данные вида {"Targets" : targets}

_Rat.prototype.selectNearestTarget = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.selectNearestTarget: Targets array is empty!!!!");
				return;
			}
			
			this.Members.Target = json_params.Targets[0]; // сначала выбираем 0 элемент
			
			if (json_params.Targets.length == 1)
			{
				return;
			}
			
			for(var i = 1; i < json_params.Targets.length; i++)
			{
				// если X + Y до новой цели меньше, чем до текущей, то меняем текущую цель на новую 
				if(Math.abs(json_params.Targets[i].X() - this.X()) + Math.abs(json_params.Targets[i].Y() - this.Y()) < 
					 Math.abs(this.Members.Target.X() - this.X()) + Math.abs(this.Members.Target.Y() - this.Y()))
					 {
						 this.Members.Target = json_params.Targets[i];
					 }
			}
		}
	}
}

_Rat.prototype.selectRandomTarget = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.selectRandomTarget: Targets array is empty!!!!");
				return;
			}
			
		this.Members.Target = json_params.Targets[Math.round(Math.random() * (json_params.Targets.length - 1))];
		}
	}
}

_Rat.prototype.select1of5Target = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.select1of3Target: Targets array is empty!!!!");
				return;
			}
			if(json_params.Targets.length <= 5)
			{
				this.Members.Target = json_params.Targets[Math.round(Math.random() * (json_params.Targets.length - 1))];
				return;
			} else
			{
				this.timeTargArr = json_params.Targets.slice(0);
				this.selectTimeArr = [];
				this.nearestTargIndex = 0;
				for (var j = 0; j < 5; j++)
				{
					for(var i = 0; i < this.timeTargArr.length; i++)
					{
						// если X + Y до новой цели меньше, чем до текущей, то меняем текущую цель на новую 
						if(Math.abs(this.timeTargArr[i].X() - this.X()) + Math.abs(this.timeTargArr[i].Y() - this.Y()) < 
							 Math.abs(this.timeTargArr[this.nearestTargIndex].X() - this.X()) + Math.abs(this.timeTargArr[this.nearestTargIndex].Y() - this.Y()))
						{
							this.nearestTargIndex = i;
						}	
					}
					this.selectTimeArr.push(this.timeTargArr[this.nearestTargIndex]);
					this.timeTargArr.splice(this.nearestTargIndex, 1);
					this.nearestTargIndex = 0;
				}
				this.Members.Target = this.selectTimeArr[Math.round(Math.random() * (this.selectTimeArr.length - 1))];
				
			}
		}
	}
}


// данная функция рассчитывает и возвращает точку атаки, в которую нужно идти!
// возвращается объект со следующими членами:
// X - положение точки по оси X
// Y - положение точки по оси Y
// duration - время, за которое крысакан должен дойти до точки атаки!
// данные параметры используются в Konva.Image.to()
_Rat.prototype.calculateAttackPoint = function (json_params)
{
	if (json_params.Target !== undefined)
	{
		var toObj = {};
		// вычисление координаты точки X
		if (this.X() < json_params.Target.X())
		{
			toObj.X = Math.round(json_params.Target.X() - json_params.Target.Width() / 2);
		} else
		{
			toObj.X = Math.round(json_params.Target.X() + this.Width() / 2);
		} 
		// вычисление координаты точки Y;
		
		if (this.Y() < json_params.Target.Y())
		{
			toObj.Y = Math.round(json_params.Target.Y() - json_params.Target.Height() / 2 );
		} else
		{
			toObj.Y = Math.round(json_params.Target.Y()  + this.Height() / 2);
		} 
		timeX = toObj.X - this.X();
		timeY = toObj.Y - this.Y();
		toObj.duration = Math.round(Math.sqrt(timeX * timeX + timeY * timeY) / this.Speed());
		return toObj;
		
	} else
	{
		console.log("from _Rat.calculateAttackPoint: Нет цели!");
	}
}

_Rat.prototype.isCanAttack = function ()
{
	if (this.Members.AttackPoint !== undefined && 
			this.Members.AttackPoint !== null && 
			this.Target() !== null && 
			this.Target() !== undefined)
	{
		if (this.X() == this.Members.AttackPoint.X &&
				this.Y() == this.Members.AttackPoint.Y)
		{
			this.Status("CanAttack");
			return 1;
		} else
		{
			return 0;
		}
	} else
	{
		return 0;
	}
	
}

/*
_Rat.prototype.calculateAttackDistance = function (json_params)
{
	if (this.Target() != null)
	{
		if (Math.abs(this.X() - (this.Target().X() + this.Target().Width() / 2)))
		{
		}
		
		
	} else
	{
		console.log("from _Rat.calculateAttackDistance: Нет цели!");
	}
}
*/


_Rat.prototype.increaseSpeed = function (json_params) 
{
	if(json_params !== undefined)
	{
		if(json_params.IncreaseValue !== undefined){
				this.Speed(this.Speed() + json_params.IncreaseValue);	
		}
	}
}

// понижение скорости
_Rat.prototype.reduceSpeed = function (json_params) 
{
	if(json_params !== undefined)
	{
		if(json_params.ReduceValue){
				this.Speed(this.Speed() - json_params.ReduceValue);	
		}
	}
}
// когда крыса убита
_Rat.prototype.onKill = function (json_params) 
{
	this.Image().image(this.ImgObjs().Dead);
	if(this.comeTween !== undefined)
	{
		this.comeTween.pause();
	}
	this.Image().off("click");
	this.Status("Dead");
}
// уменьшение здоровья!
// и проверка, установление смерти!
_Rat.prototype.reduceHealth = function (json_params)
{
	if (json_params !== undefined)
	{
		if(json_params.ReduceValue !== undefined){
				this.Health(this.Health() - json_params.ReduceValue);	
		}
	}
	if (this.Health() <= 0)
	{
		this.onKill();
	}
}
// прибавление здоровья!
_Rat.prototype.increaseHealth = function (json_params)
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}

// когда крысакана атакуют
_Rat.prototype.onAttackMe = function (json_params) 
{
	if (json_params !== undefined)
	{
		if (json_params.Damage)
		{
			this.reduceHealth({ "ReduceValue" : json_params.Damage});
		}
	}
}
//атака цели
_Rat.prototype.attackTarget = function (json_params)
{
	if (json_params.Target !== undefined)
	{
		json_params.Target.onAttackMe({"Damage" : this.Damage() * this.DamageFactor()});
	} else
	{
		console.log(this.constructor.name + ".onAttackTarget: Нет цели!");
	}
}

//поворот к цели!
// данная функция вызывается в _Rat.comeTo
_Rat.prototype.turnToTarget = function (json_params) 
{
	this.Image().rotation(3/2 * 180);
	if (json_params.Target !== undefined)
	{
		this.Image().rotate(Math.atan2(json_params.Target.Y() - this.Y(), json_params.Target.X() - this.X()) / Math.PI * 180);
	}
}

/*
_Rat.prototype.startAttackAnim = function (json_params) 
{
	this.Image().image(this.ImgObjs().Attack);
}

_Rat.prototype.stopAttackAnim = function (json_params) 
{
	this.Image().image(this.ImgObjs().Default);	
}

_Rat.prototype.startDeadAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Default));
}

_Rat.prototype.stopDeadAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Dead));	
}
*/
_Rat.prototype.onClick = function (json_params)
{
	if(json_params !== undefined)
	{
		if(json_params.Weapon !== undefined)
		{
			json_params.Weapon.attackTarget({"Target" : this});
		}
	}
}

//////////////////////////////////////////////////////
/////////		!DOING FUNCTIONS 	///////////////////////

// функция запускает 

_Rat.prototype.comeTo = function (json_params)
{
		if (json_params)
		{
			if (json_params.Target)
			{
				// здесь параметры движения
				this.Members.AttackPoint = this.calculateAttackPoint(json_params);
				// установка перемещения!
				this.comeTween = new Konva.Tween({
					node: this.Image(),
					x: this.Members.AttackPoint.X,// CheeseImage.x(),
					y: this.Members.AttackPoint.Y,//CheeseImage.y(),
					duration: this.Members.AttackPoint.duration
				});
				// запуск перемещения!
				this.comeTween.play();
				// устанавливаем статус на "Иду"
				this.Status("Going");				
			}
		}
}

/*
_Rat.prototype.comeTo2 = function (json_params)
{
	// заменить на пересечение картинок!
	if (json_params){
		if (json_params.Target) {
			if (this.X() != json_params.Target.X() && this.Y() != json_params.Target.Y())
			{
				if (json_params.Target.X() - this.X() > 0)
				{
					this.X(this.X() - this.Step());
				} else
				{
					this.X(this.X() + this.Step());
				}
				
				if (json_params.Target.Y() - this.Y() > 0)
				{
					this.Y(this.Y() - this.Step() * Math.abs((json_params.Target.Y() - this.Y()) / (json_params.Target.X() - this.X())));
				} else
				{
					this.Y(this.Y() + (this.Step() * Math.abs((json_params.Target.Y() - this.Y()) / (json_params.Target.X() - this.X()))));
				}
			}
		}
	}
}
*/

////////////////////////		_Food 	//////////////////////////////
function _Food (json_params) // это цель, за которой будут охотиться крысы!
{

		this.Members = {};

		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = null;
		this.Members.ImgObjs.Damage = null;
		this.Members.ImgObjs.Eaten = null;
		
		this.Members.Image = new Konva.Image(); // изображение, которое будет перемещаться по экрану. в Konva здесь уже содержатся все необходимые значения 
		this.Members.Layer = null;

		this.Members.Health = null;
		this.Members.X = null;
		this.Members.Y = null;

		this.Members.Status = null;
		
		if (json_params)
		{
				this.init(json_params);
		}
		
		// установка стандартной картинки!
		this.Image().image(this.ImgObjs().Default);
		this.Layer().add(this.Image());
		if (json_params.Scale !== undefined)
		{
			this.Image().width(this.Image().width() * json_params.Scale.x);
			this.Image().height(this.Image().height() * json_params.Scale.y);
			this.Image().draw();
		}

		this.Layer().draw();
		console.log("_Food: Я родился");
}	
// убавление здоровья
// получение ущерба

_Food.prototype.Image = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}


_Food.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Food.prototype.X = function (X)
{
	if (X !== undefined)
	{
		this.Members.Image.x(X);
	} else
	{
		return this.Members.Image.x();
	}
}
_Food.prototype.Y = function (Y)
{
	if (Y !== undefined)
	{
		this.Members.Image.y(Y);
	} else
	{
		return this.Members.Image.y();
	}
}

_Food.prototype.Health = function (Health)
{
	if (Health !== undefined)
	{
		this.Members.Health = Health;
	} else
	{
		return this.Members.Health;
	}
}

_Food.prototype.Layer = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Layer = Value;
	} else
	{
		return this.Members.Layer;
	}
}

_Food.prototype.Height = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Image.height(Value);
	} else
	{
		return this.Members.Image.height();
	}
}

_Food.prototype.Width = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Image.width(Value);
	} else
	{
		return this.Members.Image.width();
	}
}

_Food.prototype.Status = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Status = Value;
		console.log(this.constructor.name + " " + this.Members.Status);
	} else
	{
		return this.Members.Status;
	}
}


_Food.prototype.init = function (json_params)
{
	
	if (json_params.X !== undefined)
	{
		this.X(json_params.X);
	}
	if (json_params.Y !== undefined)
	{
		this.Y(json_params.Y);
	}
	if (json_params.Health !== undefined)
	{
		this.Health(json_params.Health);
	}		
	if (json_params.Layer !== undefined)
	{
		this.Layer(json_params.Layer);
	}	
	if (json_params.ImgObjs !== undefined)
	{
		this.ImgObjs(json_params.ImgObjs);
	}
	if (json_params.Image !== undefined)
	{
		this.Image(json_params.Image);
	}
	if (json_params.Status !== undefined)
	{
		this.Status(json_params.Status);
	}
}

// если пища съедена!
_Food.prototype.isEaten = function ()
{
	if(this.Status() == "Eaten")
	{
		return 1;
	} else
	{
		return 0;
	}
}


// когда пищу съели
_Food.prototype.onEaten = function (json_params) 
{
	this.Image().image(this.ImgObjs().Eaten);
	this.Status("Eaten");
}

// уменьшение жизни
// 
_Food.prototype.reduceHealth = function (json_params)
{
	if (json_params !== undefined)
	{
		if(json_params.Damage !== undefined){
				this.Health(this.Health() - json_params.Damage);	
				console.log(this.constructor.name + ": " + this.Health());
		}
	}
	
	if (this.Health() <= 0)
	{
		this.onEaten();
	}
}
// увеличение здоровья
_Food.prototype.increaseHealth = function (json_params)
{
	if(json_params !== undefined)
	{
		if(json_params.IncreaseValue !== undefined){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}


// параметры:
// Damage -- который будет нанесен еде.
_Food.prototype.onAttackMe = function (json_params)
{
	if (json_params !== undefined)
	{
		if (json_params.Damage !== undefined)
		{
			this.reduceHealth(json_params);
		}
	}	
}


///////////////////////////		_Weapon		/////////////////////////
/// Оружие, которым будем бить крыс!
function _Hammer (json_params)
{
		this.Members = {};

		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = null;
		this.Members.ImgObjs.Attack = null;
		
		this.Members.Image = null; // изображение, которое будет перемещаться по экрану. в Konva здесь уже содержатся все необходимые значения 

		this.Members.Health = null; // жизнь, если оружие будет изнашиваться
		this.Members.DamageFactor = null;
		this.Members.Damage = null; // множитель, с которым он оружие бьет по цели
		
		this.Members.Status = null; // множитель, с которым он оружие бьет по цели
		
		if (json_params)
		{
			this.init(json_params);
		}
//	document.body.style.cursor = 'url("../games_resources/Cheeser/images/hammer.png"), pointer';
		console.log("_Hammer: Я родился");
}


_Hammer.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Hammer.prototype.Image = function (Image) 
{
		if (Image !== undefined)
		{
			this.Members.Image.image(Image);
		}else
		{
			return this.Members.Image;
		}
}

_Hammer.prototype.Damage = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.Damage = Value;
		}else
		{
			return this.Members.Damage;
		}
}

_Hammer.prototype.DamageFactor = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.DamageFactor = Value;
		}else
		{
			return this.Members.DamageFactor;
		}
}

_Hammer.prototype.Status = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.Status = Value;
		}else
		{
			return this.Members.Status;
		}
}

_Hammer.prototype.Health = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.Health = Value;
		}else
		{
			return this.Members.Health;
		}
}

_Hammer.prototype.init = function (json_params)
{
	if (json_params !== undefined)
	{	
		if (json_params.ImgObjs !== undefined)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Status !== undefined)
		{
			this.Status(json_params.Status); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Image !== undefined)
		{
			this.Image(json_params.Image); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Layer !== undefined)
		{
			this.Layer(json_params.Layer); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Damage !== undefined)
		{
			this.Damage(json_params.Damage); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.DamageFactor !== undefined)
		{
			this.DamageFactor(json_params.DamageFactor); // здоровье, которое будет убывать, когда мы будем их бить.
		}

	}
}

_Hammer.prototype.attackTarget = function (json_params) 
{
	if(json_params !== undefined)
	{
		if(json_params.Target !== undefined)
		{
			json_params.Target.onAttackMe({"Damage": this.Damage() * this.DamageFactor()});
		}
	}
}

_Hammer.prototype.startAttackAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Attack));
}

_Hammer.prototype.stopAttackAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Default));	
}

/////////////////////////////////////////////////////////////////////////////////////
///////////////////			_FloorHole CLASS!!!!!!!!!	//////////////////////////////////

function _FloorHole (json_params) 
{
	this.Members = {};
	
	this.Members.ImgObjs = {};
	this.Members.ImgObjs.Default = null;
	this.Members.ImgObjs.Repaired = null;
	
	this.Members.Image = new Konva.Image();
	this.Members.Layer = null;
	this.Members.Status = null; // статус
	
	this.Members.Health = null;
	this.Members.Timers = {};
	this.Members.Timers.ratCreationTime = null;
	
	this.Members.Rats = null;
	
	if (json_params)
	{
		this.init(json_params);
	}
	this.Image().image(this.ImgObjs().Default);
	this.Layer().add(this.Image());
	this.Image().FloorHoleObj = this;
	this.Image().on('click', function (event) {
		event.target.FloorHoleObj.onClick({"Weapon" : Weapon});
	});
		if (json_params.Scale !== undefined)
		{
			this.Image().width(this.Image().width() * json_params.Scale.x);
			this.Image().height(this.Image().height() * json_params.Scale.y);
			this.Image().draw();
		}

	this.Layer().draw();
	console.log(this.constructor.name + ": Я родился");
};

_FloorHole.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_FloorHole.prototype.Image = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}

_FloorHole.prototype.Layer = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Layer = Value;
	}else
	{
		return this.Members.Layer;
	}
}

_FloorHole.prototype.X = function (X)
{
	if (X !== undefined)
	{
		this.Members.Image.x(X);
	} else
	{
		return this.Members.Image.x();
	}
}
_FloorHole.prototype.Y = function (Y)
{
	if (Y !== undefined)
	{
		this.Members.Image.y(Y);
	} else
	{
		return this.Members.Image.y();
	}
}

_FloorHole.prototype.Status = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Status = Value;
		console.log(this.constructor.name + ": " + this.Members.Status);
	} else
	{
		return this.Members.Status;
	}
}

_FloorHole.prototype.Health = function (Health)
{
	if (Health !== undefined)
	{
		this.Members.Health = Health;
		console.log(this.constructor.name + " health: " + this.Members.Health);
	} else
	{
		return this.Members.Health;
	}
}

_FloorHole.prototype.Height = function (Height)
{
	if (Height !== undefined)
	{
		this.Members.Image.height(Height);
	} else
	{
		return this.Members.Image.height();
	}
}

_FloorHole.prototype.Width = function (Width)
{
	if (Width !== undefined)
	{
		this.Members.Image.width(Width);
	} else
	{
		return this.Members.Image.width();
	}
}



_FloorHole.prototype.init = function (json_params)
{
	if (json_params !== undefined)
	{	
		if (json_params.ImgObjs !== undefined)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Status !== undefined)
		{
			this.Status(json_params.Status); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.X !== undefined)
		{
			this.X(json_params.X);
		}
		if (json_params.Y !== undefined)
		{
			this.Y(json_params.Y); 
		}
		if (json_params.Health !== undefined)
		{
			this.Health(json_params.Health); 
		}
		if (json_params.Width !== undefined)
		{
			this.Width(json_params.Width); 
		}
		if (json_params.Height !== undefined)
		{
			this.Height(json_params.Height); 
		}
		if (json_params.Image !== undefined)
		{
			this.Image(json_params.Image); 
		}
		if (json_params.Layer !== undefined)
		{
			this.Layer(json_params.Layer); 
		}
		if (json_params.Rats !== undefined)
		{
			this.Rats = json_params.Rats; // массив
		}

	}
}

// для жизни необходим список параметров инициализации!
_FloorHole.prototype.Life = function (json_params)
{
	if (this.Status() == "Open")
	{
			this.createRat(json_params);
	}
}



// функция создания мышей!

_FloorHole.prototype.createRat = function (json_params)
{
	
// могут возникнуть проблемы!	
	var FloorThat = this;
	this.ratCreationTimer = setTimeout(function () 
		{
			InitDatas._Rat.X = FloorThat.X() + (Math.random() * 10 -3);
			InitDatas._Rat.Y = FloorThat.Y() + (Math.random() * 10 -3);
			// добавление крысы 
			FloorThat.Rats.push(new _Rat(InitDatas._Rat));
			// возвращаем статус на открыта!
			FloorThat.Status("Open");
		},
		// здесь параметры
		(Math.random() * json_params.InitDatas._FloorHole.createRatTimeTo + json_params.InitDatas._FloorHole.createRatTimeFrom) * 1000);
	// устанавливаем статус создание крысы, чтобы нас не удалили из
	// массива!	
	this.Status("RatCreating");
}


// обработка нажатия на картинку дыры

_FloorHole.prototype.onClick = function (json_params)
{
	if(json_params !== undefined)
	{
		if(json_params.Weapon !== undefined)
		{
			json_params.Weapon.attackTarget({"Target" : this});
		}
	}
}


// обработка закалачивания
_FloorHole.prototype.onRepaired = function (json_params)
{
	clearTimeout(this.ratCreationTimer);
	this.Image().image(this.ImgObjs().Repaired);
	this.Status("Repaired");
	this.Image().off("click");

}
// возвращает, заколочено или нет!
_FloorHole.prototype.isRepaired = function ()
{
		if (this.Status() == "Repaired")
		{
			return 1;
		} else
		{
			return 0;
		}
}

// когда крысакана атакуют
_FloorHole.prototype.onAttackMe = function (json_params) 
{
	if (json_params !== undefined)
	{
		if (json_params.Damage)
		{
			this.reduceHealth({ "ReduceValue" : json_params.Damage});
		}
	}
}

// уменьшение здоровья!
// и проверка, установление смерти!
_FloorHole.prototype.reduceHealth = function (json_params)
{
	if (json_params !== undefined)
	{
		if(json_params.ReduceValue !== undefined){
				this.Health(this.Health() - json_params.ReduceValue);	
		}
	}
	if (this.Health() <= 0)
	{
		this.onRepaired();
	}
}
// прибавление здоровья!
_FloorHole.prototype.increaseHealth = function (json_params)
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}




////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/////////////////////		GLOBAL FUNCTIONS AND OBJECTS
///////////////////////////////////////////////////////////////////

var GameContainer = null;
var MainStage = null;
var MainLayer = null;
var Rats = null;
var FloorHoles = null;
var Foods = null;
var Weapon = null; // это оружие
var InitDatas = null;
////////////////////////////////////////////////////////////////////

var DefaultInitDatas = {
	_Rat : {
		Health: 170,
		ImgObjs: {
			Default: document.getElementById("Rat_img"),
			Dead: document.getElementById("RatDead_img"),
			Damage: document.getElementById("Rat_img"),
			Attack: document.getElementById("Rat_img")
		},
		Speed: 30,
		SpeedLimit: 100,
		SpeedFactor: 1,
		Step: 2,
		Damage: 60,
		DamageFactor: 1,
		Layer: null,
		Status: "Live"
	},
	_Food : {
		Health: 200,
		ImgObjs: {
			Default: document.getElementById("Cheese_img"),
			Eaten: document.getElementById("Crumbs_img"),
		},
		Status: "NotEaten",
		Layer: null
	},
	_Hammer : {
		Damage: 50,
		DamageFactor: 1
	},
	_FloorHole : {
		ImgObjs:{
			Default: document.getElementById("FloorHole_img"),
			Repaired: document.getElementById("FloorHoleRepaired_img")
		},
		Layer: null,
		Status: "Open",
		Rats: null,
		Health: 5000,
		createRatTimeTo: 8,
		createRatTimeFrom: 2
	}
};

function createFloorHole(InitDatas, FloorHoles, W, H)
{
	// рандомно выбирается место создания очередной дыры
	InitDatas._FloorHole.X = Math.random() * (W - 200) + 100;
	InitDatas._FloorHole.Y = Math.random() * (H - 200) + 100;
	// добавляем дыру в массив!!!
	FloorHoles.push(new _FloorHole(InitDatas._FloorHole));
	gamestats.increaseFloorHolesCounter();
}
function createFood(InitDatas, Foods, W, H)
{
	InitDatas._Food.X = Math.random() * (W - 200) + 100;
	InitDatas._Food.Y = Math.random() * (H - 200) + 100;
	
	Foods.push(new _Food(InitDatas._Food));
	gamestats.increaseFoodsCounter();
}




function showGameMenu (json_params)
{
		$("#GameMenu").show("slow");
		$("#SurvivalGame").on("click", function () {
			$("#GameMenu").hide();
			GAMEMODE = "survival";
			gamestats.clearStats();
			Game();
		});
		$("#GameResult").hide();
}

function showGameResult (json_params)
{
	if(json_params !== undefined)
	{
		if (json_params.Status == "win")
		{
			$("#ResultStatus").html("Победа!");
			$("#ResultStatus").animate({backgroundColor : "#20e80e"}, 1000);
		} else
		{
			$("#ResultStatus").html("Проигрыш...");
			$("#ResultStatus").animate({backgroundColor : "#cc0000"}, 1000);
		}
		
		$("#RatsKilled").html("Крыс убито: " + json_params.Stats.RatsKilledCounter);
		$("#TimeResult").html("Время: " + json_params.Stats.Timer);
		$("#GameResult").show("slow");
		$("#RestartButton").on("click", function () {
			$("#GameResult").hide("slow");
			showGameMenu();
		});

	} else
	{
		console.log("showGameResult function have no parameters!");
	}	
}


// функция обработки игрового процесса!
// будет вызываться постоянно!
// если вся пища съедена - конец игры
function GameProcess ()
{
	
	for(var i = 0; i < FloorHoles.length; i++)
	{
		// если какая-то из дыр заколочена - удаляем из массива ее!
		if (FloorHoles[i].Status() == "Repaired")
		{
			FloorHoles.splice(i,1);
			gamestats.reduceFloorHolesCounter();
		} else
		// если какая-то из дыр открыта!
		if (FloorHoles[i].Status() == "Open")
		{
			FloorHoles[i].Life({"InitDatas" : InitDatas});
		}
	}
	
	for(var i = 0; i < Rats.length; i++)
	{
		if (Rats[i].Status() == "Dead")
		{
			Rats.splice(i, 1);
			gamestats.increaseRatsKilledCounter();
		} else
		{
			Rats[i].Life({"Targets" : Foods});
		}
	}

	if (Foods.length == 0)
	{
		showGameResult({"Status" : "loss", "Stats" : gamestats});
		clearInterval(gameProcessTimer);
	} else 
	if (Foods.length != 0 && FloorHoles.length ==	0)
	{
		showGameResult({"Status" : "win", "Stats" : gamestats});
		clearInterval(gameProcessTimer);
	}

	for(var i = 0; i < Foods.length; i++)
	{
		if (Foods[i].isEaten())
		{
			Foods.splice(i, 1);
			gamestats.reduceFoodsCounter();
		}
	}
	MainLayer.draw();
}	

// инициализация игры
// создание пищи, первой дыры в полу!
function GameInit(json_params)
{
	if (GameContainer != null)
		document.body.removeChild(GameContainer);
	GameContainer = document.createElement("div");
	GameContainer.setAttribute("id", "GameContainer");
	GameContainer.style.position = "absolute";
	GameContainer.style.left = "0px";
	GameContainer.style.top = "30px";
	GameContainer.style.width = W + "px";
	GameContainer.style.height = H + "px";
	document.body.appendChild(GameContainer);
	
	MainStage = new Konva.Stage({
			container: 'GameContainer',
			width: W,
			height: H
	});
	
	MainLayer = new Konva.Layer();
	// массивы объектов!
	
	MainStage.add(MainLayer);
	MainLayer.draw();
	
	Rats = [];
	FloorHoles = [];
	Foods = [];

	// нужен только один экземпляр.
	// по умолчанию объект Weapon будет
	// создавать из класса _Hammer
	Weapon = null; // это оружие

	InitDatas = jQuery.extend(true, {}, DefaultInitDatas);
	InitDatas._Rat.Layer = MainLayer;
	InitDatas._FloorHole.Layer = MainLayer;
	InitDatas._Food.Layer = MainLayer;
	InitDatas._FloorHole.Rats = Rats;
	
	if (json_params.GameMode == "survival") 
	{
		if (json_params.FloorHolesCount !== undefined)
		{	
			for (var i = 0; i < json_params.FloorHolesCount; i++)
				createFloorHole(InitDatas, FloorHoles, W, H);
		}
		if (json_params.FoodsCount !== undefined)
		{	
			for (var i = 0; i < json_params.FoodsCount; i++)
			createFood(InitDatas, Foods, W, H);
		}
	}
	
	Weapon = new _Hammer(InitDatas._Hammer);
	MainLayer.draw();	
}

function Game() {

	GameInit({"GameMode" : GAMEMODE, "FloorHolesCount" : 1, "FoodsCount" : 1});
	gameProcessTimer = setInterval(function () {GameProcess();}, 1000);	
};	

//showGameMenu();
showGameResult({"Status" : "win", "Stats" : gamestats});

</script>


</body>

</html>
