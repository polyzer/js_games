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
		this.Members.AttackPoint = null;
				/// Проработать установку значений по-умолчанию
		if (json_params)
		{
			this.init(json_params);
		}

		this.Image().image(this.ImgObjs().Default);
		this.Image().rotate(3/2 * 180);
		this.Layer().add(this.Image());
		this.Image().RatObj = this;
		this.Image().on('click', function (event) {
			event.target.RatObj.onClick({"Weapon" : Weapon});
		});
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
		this.selectTarget(json_params);
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
		if (this.isGoing()){
			return;
		} else
		// если мы можем атаковать - атакуем....
		if (this.isCanAttack()){
			this.attackTarget({"Target" : this.Target()});
		}
	}
	
}

_Rat.prototype.isCanAttack = function (json_params)
{
	if (this.Status() == "CanAttack")
	{
		return 1;
	}else
	{
		return 0;
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

_Rat.prototype.selectTarget = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.selectTarget: Targets array is empty!!!!");
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
			toObj.X = json_params.Target.X() - this.Width();
		} else
		{
			toObj.X = json_params.Target.X() + json_params.Target.Width();
		} 
		// вычисление координаты точки Y;
		
		if (this.Y() < json_params.Target.Y())
		{
			toObj.Y = json_params.Target.Y() - this.Height();
		} else
		{
			toObj.Y = json_params.Target.Y() + json_params.Target.Height();
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
				var toXY = this.calculateAttackPoint(json_params);
				// установка перемещения!
				this.comeTween = new Konva.Tween({
					node: this.Image(),
					x: toXY.X,// CheeseImage.x(),
					y: toXY.Y,//CheeseImage.y(),
					duration: toXY.duration
				});
				// запуск перемещения!
				this.comeTween.play();
				// устанавливаем статус на "Иду"
				this.Status("Going");
				// это значение для разных крыс может перебиваться!
				var RatThat = this;
				setTimeout(function () {
					RatThat.Status("CanAttack");
				}, toXY.duration * 1000);
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
		this.Image().on('click', function () {
			this.onClick();
		});
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
	
	this.Members.Timers = {};
	this.Members.Timers.ratCreationTime = null;
	
	this.Members.Rats = null;
	
	if (json_params)
	{
		this.init(json_params);
	}
	this.Image().image(this.ImgObjs().Default);
	this.Layer().add(this.Image());
	this.Members.Image.on('click', function () {
		this.onClick();
	});
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

// функция создания мышей!
_FloorHole.prototype.createRat = function (json_params)
{
	var FloorThat = this;
	this.ratCreationTimer = setTimeout(function () 
		{
			InitDatas._Rat.X = FloorThat.X();
			InitDatas._Rat.Y = FloorThat.Y();
			// добавление крысы 
			FloorThat.Rats.push(new _Rat(InitDatas._Rat));
			// возвращаем статус на открыта!
			FloorThat.Status("Open");
		},
		(Math.random() * 10 + 5) * 1000);
	// устанавливаем статус создание крысы, чтобы нас не удалили из
	// массива!	
	this.Status("RatCreating");
}


// обработка нажатия на картинку дыры
_FloorHole.prototype.onClick = function ()
{
	this.onRepaired();
}

// обработка закалачивания
_FloorHole.prototype.onRepaired = function (json_params)
{
	clearTimeout(this.ratCreationTimer);
	this.Image().image(this.ImgObjs().Repaired);
	this.Status("Repaired");
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
////////////////////////////////////////////////////////////////
var MainLayer = new Konva.Layer();
// массивы
var Rats = [];
var FloorHoles = [];
var Foods = [];

// нужен только один экземпляр.
// по умолчанию объект Weapon будет
// создавать из класса _Hammer
var Weapon = null; // это оружие

////////////////////////////////////////////////////////////////////
var InitDatas = {
	_Rat : {
		Health: 170,
		ImgObjs: {
			Default: document.getElementById("Rat_img"),
			Dead: document.getElementById("RatDead_img"),
			Damage: document.getElementById("Rat_img"),
			Attack: document.getElementById("Rat_img")
		},
		Speed: 200,
		SpeedLimit: 100,
		SpeedFactor: 1,
		Step: 2,
		Damage: 60,
		DamageFactor: 1,
		Layer: MainLayer,
		Status: "Live"
	},
	_Food : {
		Health: 200,
		ImgObjs: {
			Default: document.getElementById("Cheese_img"),
			Eaten: document.getElementById("Crumbs_img"),
		},
		Status: "NotEaten",
		Layer: MainLayer
	},
	_Hammer : {
		Damage: 50,
		DamageFactor: 1
	},
	_FloorHole : {
		ImgObjs:{
			Default: document.getElementById("FloorHole_img"),
			Repaired: document.getElementById("FloorHole_img")
		},
		Layer: MainLayer,
		Status: "Open",
		Rats: Rats
	}
};
////////////////////////////////////////////////////////////////////
function createFloorHole(InitDatas, FloorHoles, W, H)
{
	// рандомно выбирается место создания очередной дыры
	InitDatas._FloorHole.X = Math.random() * (W - 200) + 100;
	InitDatas._FloorHole.Y = Math.random() * (H - 200) + 100;
	// добавляем дыру в массив!!!
	FloorHoles.push(new _FloorHole(InitDatas._FloorHole));
}
/*
function createRat(InitDatas, FloorHoles, Rats)
{
	var holenum = Math.random() * (FloorHoles.length - 1);
	InitDatas._Rat.X = FloorHoles[holenum].X();
	InitDatas._Rat.Y = FloorHoles[holenum].Y();
	// добавление крысы
	Rats.push(new _Rat(InitDatas._Rat));
}
*/
function createFood(InitDatas, Foods, W, H)
{
	InitDatas._Food.X = Math.random() * (W - 200) + 100;
	InitDatas._Food.Y = Math.random() * (H - 200) + 100;
	
	Foods.push(new _Food(InitDatas._Food));
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
		} else
		// если какая-то из дыр открыта!
		if (FloorHoles[i].Status() == "Open")
		{
			FloorHoles[i].createRat({"InitDatas" : InitDatas});
		}
	}
	
	for(var i = 0; i < Rats.length; i++)
	{
		if (Rats[i].Status() == "Dead")
		{
			Rats.splice(i, 1);
		} else
		{
			Rats[i].Life({"Targets" : Foods});
		}
	}

	if (Foods.length == 0)
	{
		window.alert("Ты проиграл!!!");
	}

	for(var i = 0; i < Foods.length; i++)
	{
		if (Foods[i].isEaten())
		{
			Foods.splice(i, 1);
		}
	}
	MainLayer.draw();
}	

// инициализация игры
// создание пищи, первой дыры в полу!
function GameInit()
{
	if (FloorHoles.length == 0)
	{
		createFloorHole(InitDatas, FloorHoles, W, H);
		createFloorHole(InitDatas, FloorHoles, W, H);
		createFloorHole(InitDatas, FloorHoles, W, H);


	}
	if (Foods.length == 0)
	{
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
		createFood(InitDatas, Foods, W, H);
	}
	Weapon = new _Hammer(InitDatas._Hammer);
	MainLayer.draw();	
}

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
	
	var MainStage = new Konva.Stage({
			container: 'GameContainer',
			width: W,
			height: H
	});

	
function Game() {

	MainStage.add(MainLayer);
	MainLayer.draw();
	
	// GameProcess
	GameInit();
	setInterval(GameProcess, 1000);
	
	
	
};	




Game();



</script>


</body>

</html>
