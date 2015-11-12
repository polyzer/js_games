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
// EmergenceCoords - координаты появления;
// 
function _Rat (json_params) 
{
		this.Members = {};
		
		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = document.getElementById("Rat_img");
		this.Members.ImgObjs.Attack = document.getELementById("Rat_img");
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

		this.Members.Image.image(this.Members.ImgObjs.Default);
		this.Layer.add(this.Image);		
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
	if (Value)
	{
		this.Members.Layer = Value;
	} else
	{
		return this.Members.Layer;
	}
}


_Rat.prototype.Speed = function(Speed)
{
	if (Speed)
	{
		this.Members.Speed = Speed;
	} else
	{
		return this.Members.Speed;
	}
}

_Rat.prototype.SpeedLimit = function(SpeedLimit)
{
	if (SpeedLimit)
	{
		this.Members.SpeedLimit = SpeedLimit;
	} else
	{
		return this.Members.SpeedLimit;
	}
}

_Rat.prototype.Step = function(Value)
{
	if (Value)
	{
		this.Members.Step = Value;
	} else
	{
		return this.Members.Step;
	}
}

_Rat.prototype.X = function (X)
{
	if (X)
	{
		this.Members.Image.X = X;
	} else
	{
		return this.Members.Image.X;
	}
}
_Rat.prototype.Y = function (Y)
{
	if (Y)
	{
		this.Members.Image.Y = Y;
	} else
	{
		return this.Members.Image.Y;
	}
}

_Rat.prototype.Health = function (Health)
{
	if (Health)
	{
		this.Members.Health = Health;
	} else
	{
		return this.Members.Health;
	}
}

_Rat.prototype.Height = function (Height)
{
	if (Height)
	{
		this.Members.Image.height(Height);
	} else
	{
		return this.Members.Image.height();
	}
}

_Rat.prototype.Width = function (Width)
{
	if (Width)
	{
		this.Members.Image.width(Width);
	} else
	{
		return this.Members.Image.width();
	}
}

_Rat.prototype.Damage = function (Value)
{
	if (Value)
	{
		this.Members.Damage = Value;
	} else
	{
		return this.Members.Damage;
	}
}

_Rat.prototype.DamageFactor = function (Value)
{
	if (Value)
	{
		this.Members.DamageFactor = Value;
	} else
	{
		return this.Members.DamageFactor;
	}
}

_Rat.prototype.AttackDistance = function (Value)
{
	if (Value)
	{
		this.Members.AttackDistance = Value;
	}else
	{
		return this.Members.AttackDistance;
	}
}


_Rat.prototype.ImgObjs = function (Value) 
{
	if (Value)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Rat.prototype.Status = function (Value)
{
	if (Value)
	{
		this.Members.Status = Value;
	} else
	{
		return this.Members.Status();
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


_Rat.prototype.Life = function (json_params)
{
	// если я мертва - то ничего не делать!
	if (this.isDead())
		return;
	
	// если у нас нет цели, то выбирваем!	
	if (this.Target() == null)
	{
		// выбираем цель из полученного списка!
		this.selectTarget(json_params);
		this.comeTo({"Target" : this.Target()});
	}
	else 
	{
		this.comeTo({"Target" : this.Target()});
	}
	
}


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




// передается массив с целями, которые еще присутствуют в игре:
// данные вида {"Targets" : targets}

_Rat.prototype.selectTarget = function (json_params)
{	
	if (json_params) // если есть входные параметры
	{
		if (json_params.Targets) //если есть массив с целями
		{
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.selectTarget: Targets array is empty!!!!");
				return;
			}
			
			this.Target = json_params.Targets[0]; // сначала выбираем 0 элемент
			
			if (json_params.Targets.length == 1)
			{
				return;
			}
			
			for(var i = 1; i < json_params.Targets.length; i++)
			{
				// если X + Y до новой цели меньше, чем до текущей, то меняем текущую цель на новую 
				if(Math.abs(json_params.Targets[i].X() - this.X()) + Math.abs(json_params.Targets[i].Y() - this.Y()) < 
					 Math.abs(this.Target.X() - this.X()) + Math.abs(this.Target.X() - this.X()))
					 {
						 this.Target = json_params.Targets[i];
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
	if (json_params.Target != null)
	{
		var toObj = {};
		// вычисление координаты точки X
		if (this.X() < json_params.Target().X())
		{
			toObj.X = json_params.Target().X() - this.Width();
		} else
		{
			toObj.X = json_params.Target().X() + json_params.Target.Width();
		} 
		// вычисление координаты точки Y;
		if (this.Y() < json_params.Target().Y())
		{
			toObj.Y = json_params.Target().Y() - this.Height();
		} else
		{
			toObj.Y = json_params.Target().Y() + json_params.Target.Height();
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



_Rat.prototype.increaseSpeed = function (json_params) 
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Speed(this.Speed() + json_params.IncreaseValue);	
		}
	}
}
// понижение скорости
_Rat.prototype.reduceSpeed = function (json_params) 
{
	if(json_params)
	{
		if(json_params.ReduceValue){
				this.Speed(this.Speed() - json_params.ReduceValue);	
		}
	}
}
// когда крыса убита
_Rat.prototype.onKill = function (json_params) 
{
	this.Image(this.ImgObjs().Dead);
}

_Rat.prototype.reduceHealth = function (json_params)
{
	if (json_params)
	{
		if(json_params.ReduceValue){
				this.Health(this.Health() - json_params.ReduceValue);	
		}
	}
}

_Rat.prototype.increaseHealth = function (json_params)
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}

// когда атакуют крысу
_Rat.prototype.onAttackMe = function (json_params) 
{
	if (json_params)
	{
		if (json_params.Damage)
		{
			this.reduceHealth({ "ReduceValue" : json_params.Damage});
		}
	}
}
//атака цели

_Rat.prototype.AttackTarget = function (json_params)
{
	if (this.Target() != null)
	{
		this.onAttackTarget({"Target": this.Target()});
	}
}

_Rat.prototype.onAttackTarget = function (json_params) 
{
	if (json_params.Target)
	{
		json_params.Target.onAttackMe({"Damage" : this.Damage() * this.DamageFactor()});
	} else
	{
		console.log("from _Rat.onAttackTarget: Нет цели!");
	}
}

//атака цели
_Rat.prototype.turnToTarget = function (json_params) 
{
	if (json_params.Target)
	{
		this.Image().rotate(Math.atan2((json_params.Target.X() - this.X()), (json_params.Target.Y() - this.Y())));
	}
}

_Rat.prototype.startAttackAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Attack));
}

_Rat.prototype.stopAttackAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Default));	
}

_Rat.prototype.startDeadAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Default));
}

_Rat.prototype.stopDeadAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Dead));	
}

_Rat.prototype.onClick = function ()
{
	
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
				var toXY = this.calculateAttackPoint(json_params);
				this.Image.to({
					x: toXY.X,// CheeseImage.x(),
					y: toXY.Y,//CheeseImage.y(),
					duration: toXY.duration
				});
				// устанавливаем статус на "Иду"
				this.Status("Going");
			}
		}
}


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


////////////////////////		_Food 	//////////////////////////////
function _Food (json_params) // это цель, за которой будут охотиться крысы!
{

		this.Members = {};

		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = null;
		this.Members.ImgObjs.Damage = null;
		this.Members.ImgObjs.Eaten = null;
		
		this.Members.Image = null; // изображение, которое будет перемещаться по экрану. в Konva здесь уже содержатся все необходимые значения 
		this.Members.Layer = null;

		this.Members.Health = null;
		this.Members.X = null;
		this.Members.Y = null;

		this.Members.Status = null;
	
		if (json_params)
		{
				this.init(json_params);
		}
}	
// убавление здоровья
// получение ущерба

_Food.prototype.Image = function (Value) 
{
	if (Value)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}


_Food.prototype.ImgObjs = function (Value) 
{
	if (Value)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Food.prototype.X = function (X)
{
	if (X)
	{
		this.Members.Image.X = X;
	} else
	{
		return this.Members.Image.X;
	}
}
_Food.prototype.Y = function (Y)
{
	if (Y)
	{
		this.Members.Y = Y;
	} else
	{
		return this.Members.Y;
	}
}

_Food.prototype.Health = function (Health)
{
	if (Health)
	{
		this.Members.Health = Health;
	} else
	{
		return this.Members.Health;
	}
}

_Food.prototype.Layer = function (Value)
{
	if (Value)
	{
		this.Members.Layer = Value;
	} else
	{
		return this.Members.Layer;
	}
}

_Food.prototype.Status = function (Value)
{
	if (Value)
	{
		this.Members.Status = Value;
	} else
	{
		return this.Members.Status;
	}
}


_Food.prototype.init = function (json_params)
{
	
	if (json_params.X)
	{
		this.X(json_params.X);
	}
	if (json_params.Y)
	{
		this.Y(json_params.Y);
	}
	if (json_params.Health)
	{
		this.Health(json_params.Health);
	}		
	if (json_params.Layer)
	{
		this.Layer(json_params.Layer);
	}	
	if (json_params.ImgObjs)
	{
		this.ImgObjs(json_params.ImgObjs);
	}
	if (json_params.Image)
	{
		this.Image(json_params.Image);
	}
	if (json_params.Status)
	{
		this.Status(json_params.Status);
	}
}


// когда пищу съели
_Food.prototype.onKill = function (json_params) 
{
	this.startDeadAnim();
}

// уменьшение жизни
_Food.prototype.reduceHealth = function (json_params)
{
	if (json_params)
	{
		if(json_params.ReduceValue){
				this.Health(this.Health() - json_params.ReduceValue);	
		}
	}
}
// увеличение здоровья
_Food.prototype.increaseHealth = function (json_params)
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}


// параметры:
// Damage -- который будет нанесен еде.

_Food.prototype.onAttackMe = function (json_params)
{
	if (json_params)
	{
		if (json_params.Damage)
		{
			this.obtainDamage(json_params);
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
}


_Hammer.prototype.ImgObjs = function (Value) 
{
	if (Value)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Hammer.prototype.Image = function (Image) 
{
		if (Image)
		{
			this.Members.Image.image(Image);
		}else
		{
			return this.Members.Image;
		}
}

_Hammer.prototype.Damage = function (Value) 
{
		if (Value)
		{
			this.Members.Damage = Value;
		}else
		{
			return this.Members.Damage;
		}
}

_Hammer.prototype.DamageFactor = function (Value) 
{
		if (Value)
		{
			this.Members.DamageFactor = Value;
		}else
		{
			return this.Members.DamageFactor;
		}
}

_Hammer.prototype.Status = function (Value) 
{
		if (Value)
		{
			this.Members.Status = Value;
		}else
		{
			return this.Members.Status;
		}
}

_Hammer.prototype.Health = function (Value) 
{
		if (Value)
		{
			this.Members.Health = Value;
		}else
		{
			return this.Members.Health;
		}
}

_Hammer.prototype.init = function (json_params)
{
	if (json_params)
	{	
		if (json_params.ImgObjs)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Status)
		{
			this.Status(json_params.Status); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Image)
		{
			this.Image(json_params.Image); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Layer)
		{
			this.Layer(json_params.Layer); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Damage)
		{
			this.Damage(json_params.Damage); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.DamageFactor)
		{
			this.DamageFactor(json_params.DamageFactor); // здоровье, которое будет убывать, когда мы будем их бить.
		}

	}
}

_Hammer.prototype.attackTarget = function (json_params) 
{
	if(json_params)
	{
		if(json_params.Target)
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
	
	this.Members.Image = null;
	
	this.Members.Layer = null;
	
	this.Members.Status = null; // статус
	
	if (json_params)
	{
		this.init(json_params);
	}
	
};

_FloorHole.prototype.ImgObjs = function (Value) 
{
	if (Value)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_FloorHole.prototype.Image = function (Value) 
{
	if (Value)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}

_FloorHole.prototype.Layer = function (Value) 
{
	if (Value)
	{
		this.Members.Layer = Value;
	}else
	{
		return this.Members.Layer;
	}
}

_FloorHole.prototype.X = function (X)
{
	if (X)
	{
		this.Members.Image.X = X;
	} else
	{
		return this.Members.Image.X;
	}
}
_FloorHole.prototype.Y = function (Y)
{
	if (Y)
	{
		this.Members.Y = Y;
	} else
	{
		return this.Members.Y;
	}
}

_FloorHole.prototype.Status = function (Value)
{
	if (Value)
	{
		this.Members.Status = Value;
	} else
	{
		return this.Members.Status;
	}
}

_FloorHole.prototype.init = function (json_params)
{
	if (json_params)
	{	
		if (json_params.ImgObjs)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Status)
		{
			this.Status(json_params.Status); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.X)
		{
			this.X(json_params.X); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Y)
		{
			this.Y(json_params.Y); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Image)
		{
			this.Image(json_params.Image); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Layer)
		{
			this.Layer(json_params.Layer); // здоровье, которое будет убывать, когда мы будем их бить.
		}

	}
}


_FloorHole.prototype.onRepaired = function (json_params)
{
	this.Image().image(this.ImgObjs().Repaired);
	this.Status("Repaired");
}

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

var MainLayer = new Konva.Layer();


var InitDatas = {
	_Rat : {
		Health: 100,
		ImgObjs: {
			Default: document.getElementById("Rat_img"),
			Dead: document.getElementById("RatDead_img"),
			Damage: document.getElementById("Rat_img"),
			Attack: document.getElementById("Rat_img")
		},
		Speed: 60,
		SpeedLimit: 100,
		SpeedFactor: 1,
		Step: 2,
		Damage: 20,
		DamageFactor: 1,
		Layer: MainLayer,
		Status: "Live"
	},
	_Food : {
		Health: 200,
		ImgObjs: {
			Default: document.getElementById("Cheese_img"),
			Eaten: document.getElementById("Crumbs_img")
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
		Status: "Open"
	}
};

// массивы


var Rats = [];
var FloorHoles = [];
var Foods = [];

var CreateHolesTime = 5000 //миллисекунд создается дыра в полу

function createHole(InitDatas, FloorHoles, W, H)
{
	// рандомно выбирается место создания очередной дыры
	InitDatas._FloorHole.X = Math.random() * (W - 100);
	InitDatas._FloorHole.Y = Math.random() * (H - 100);
	// добавляем дыру в массив!!!
	FloorHoles.push(new _FloorHole(InitDatas._FloorHole));
}

function createRat(InitDatas, FloorHoles, Rats)
{
	var holenum = Math.random() * (FloorHoles.length - 1);
	InitDatas._Rat.X = FloorHoles[holenum].X();
	InitDatas._Rat.Y = FloorHoles[holenum].Y();
	// добавление крысы
	Rats.push(new _Rat(InitDatas._Rat));
}

function createFood(InitDatas, Foods, W, H)
{
	InitDatas._Foods.X = Math.random() * W;
	InitDatas._Foods.Y = Math.random() * H;
	
	Foods.push(new _Food(InitDatas._Foods));
}

function GameProcess ()
{
	if (
}	

function GameInit()
{
	if (Foods.length == 0)
	{
		createFood(InitDatas, Foods, W, H);
	}
	setTimeout(function () {
		createRat(InitDatas, FloorHoles, Rats);
	},
	6000);	
}
	
function Game() {


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
	
	MainStage.add(MainLayer);
	MainLayer.draw();
	
	// GameProcess

	
	
	
};	




Game();



</script>


</body>

</html>
