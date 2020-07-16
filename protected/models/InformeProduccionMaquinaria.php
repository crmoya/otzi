<?php

/**
 * This is the model class for table "informeProduccionMaquinaria".
 *
 * The followings are the available columns in table 'informeProduccionMaquinaria':
 * @property integer $id
 * @property string $maquina
 * @property string $operador
 * @property string $centroGestion
 * @property string $pu
 * @property string $horas
 * @property string $produccion
 */
class InformeProduccionMaquinaria extends CActiveRecord
{
	
	public $fechaInicio;
	public $fechaFin;
	public $propiosOArrendados;
	public $agruparPor;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return InformeProduccionMaquinaria the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'informeProduccionMaquinaria';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maquina, operador, centroGestion, pu, horas, produccion', 'required'),
			array('maquina', 'length', 'max'=>150),
			array('operador', 'length', 'max'=>215),
			array('centroGestion', 'length', 'max'=>50),
			array('pu, horas, produccion', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, maquina, operador, centroGestion, pu, horas, produccion', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'maquina' => 'Máquina',
			'operador' => 'Operador',
			'centroGestion' => 'Centro Gestión',
			'pu' => 'PU',
			'horas' => 'Horas Físicas',
			'horasMin' => 'Horas Contratadas',
			'produccion' => 'Producción Física',
			'produccionMin' => 'Producción Contratada',
			'propiosOArrendados' => 'Mostrar Equipos'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('maquina',$this->maquina,true);
		$criteria->compare('operador',$this->operador,true);
		$criteria->compare('centroGestion',$this->centroGestion,true);
		$criteria->compare('pu',$this->pu,true);
		$criteria->compare('horas',$this->horas,true);
		$criteria->compare('produccion',$this->produccion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$insertSql = "
		insert into informeProduccionMaquinaria
			(maquina,operador,centroGestion,pu,horas,horasMin,produccion,produccionMin)
			
		";		
		
		$inicioAgrupacionTodos = "
			maquina,
			operador,
			centroGestion,
		";
		$inicioAgrupacionPropios = "
			concat(e.codigo,' / ',e.nombre) as maquina,
			concat(o.rut,' / ',o.nombre) as operador,
			f.nombre as centroGestion,
		";
		$inicioAgrupacionArrendados = "
			e.nombre as maquina,
			concat(o.rut,' / ',o.nombre) as operador,
			f.nombre as centroGestion,
		";
		
		$finAgrupacion = "group by maquina,operador,centroGestion";
		
		if(isset($this->agruparPor)){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacionPropios = "
					concat(e.codigo,' / ',e.nombre) as maquina,
					'' as operador,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					e.nombre as maquina,
					'' as operador,
					'' as centroGestion,
				";
				$finAgrupacion = "group by maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacionPropios = "
					'' as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					'' as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
					'' as centroGestion,
				";
				$finAgrupacion = "group by operador";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacionPropios = "
					'' as maquina,
					'' as operador,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					'' as maquina,
					'' as operador,
					f.nombre as centroGestion,
				";
				$finAgrupacion = "group by centroGestion";
			}
			if($this->agruparPor == "CENTROMAQUINA"){
				$inicioAgrupacionPropios = "
					concat(e.codigo,' / ',e.nombre) as maquina,
					'' as operador,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					e.nombre as maquina,
					'' as operador,
					f.nombre as centroGestion,
				";
				$finAgrupacion = "group by maquina,centroGestion";
			}	
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacionPropios = "
					'' as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					'' as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
					f.nombre as centroGestion,
				";
				$finAgrupacion = "group by centroGestion,operador";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacionPropios = "
					concat(e.codigo,' / ',e.nombre) as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					e.nombre as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
					'' as centroGestion,
				";
				$finAgrupacion = "group by operador,maquina";
			}
		
		}
		
		$filtroFecha = "";
		if(isset($this->fechaInicio) && isset($this->fechaFin)){
			if($this->fechaInicio != "" && $this->fechaFin != ""){
				$filtroFecha = "	
					and		fecha >= :fechaInicio 
					and		fecha <= :fechaFin
				";
			}
		}
		
		if($this->propiosOArrendados == 'PROPIOS'){
			$sql = "			
			select 	maquina,
					operador,
					centroGestion,
					avg(pu) as pu,
					sum(horas) as horas,
					sum(horasMin) as horasMin,
					sum(produccion) as produccion,
					sum(produccionMin) as produccionMin
			from
				(
				
				select 	$inicioAgrupacionPropios
						fecha,
						avg(e.precioUnitario) as pu,
						sum(r.horas) as horas,
						sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as horasMin,
						avg(e.precioUnitario)*sum(r.horas) as produccion,
						avg(e.precioUnitario)*sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as produccionMin
				from 	rEquipoPropio as r,
						equipoPropio as e,
						operador as o,
						faena as f
				where	r.equipoPropio_id = e.id and
						o.id = r.operador_id and
						f.id = r.faena_id 
						$filtroFecha
				$finAgrupacion,fecha
				
				) as tPropios
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'ARRENDADOS'){
			$sql = "
			select  maquina,
					operador,
					centroGestion,
					avg(pu) as pu,
					sum(horas) as horas,
					sum(horasMin) as horasMin,
					sum(produccion) as produccion,
					sum(produccionMin) as produccionMin
			from
				(
				
				select $inicioAgrupacionArrendados
						fecha,
						avg(e.precioUnitario) as pu,
						sum(r.horas) as horas,
						sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as horasMin,
						avg(e.precioUnitario)*sum(r.horas) as produccion,
						avg(e.precioUnitario)*sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as produccionMin
				from 	rEquipoArrendado as r,
						equipoArrendado as e,
						operador as o,
						faena as f
				where	r.equipoArrendado_id = e.id and
						o.id = r.operador_id and
						f.id = r.faena_id 
						$filtroFecha
				$finAgrupacion,fecha
				
				) as tArrendados
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	maquina,
					operador,
					centroGestion,
					avg(pu) as pu,
					sum(horas) as horas,
					sum(horasMin) as horasMin,
					sum(produccion) as produccion,
					sum(produccionMin) as produccionMin	
			from
				(
				select 	maquina,
						operador,
						centroGestion,
						avg(pu) as pu,
						sum(horas) as horas,
						sum(horasMin) as horasMin,
						sum(produccion) as produccion,
						sum(produccionMin) as produccionMin
				from
					(
					
					select $inicioAgrupacionPropios
							fecha,
							avg(e.precioUnitario) as pu,
							sum(r.horas) as horas,
							sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as horasMin,
							avg(e.precioUnitario)*sum(r.horas) as produccion,
							avg(e.precioUnitario)*sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as produccionMin
					from 	rEquipoPropio as r,
							equipoPropio as e,
							operador as o,
							faena as f
					where	r.equipoPropio_id = e.id and
							o.id = r.operador_id and
							f.id = r.faena_id 
							$filtroFecha
					$finAgrupacion,fecha
					
					) as tPropios
				$finAgrupacion
						
				union all
				
				select  maquina,
						operador,
						centroGestion,
						avg(pu) as pu,
						sum(horas) as horas,
						sum(horasMin) as horasMin,
						sum(produccion) as produccion,
						sum(produccionMin) as produccionMin
				from
					(
					
					select $inicioAgrupacionArrendados
							fecha,
							avg(e.precioUnitario) as pu,
							sum(r.horas) as horas,
							sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as horasMin,
							avg(e.precioUnitario)*sum(r.horas) as produccion,
							avg(e.precioUnitario)*sum(GREATEST(r.horas,GREATEST(e.horasMin - r.minPanne/60,0))) as produccionMin
					from 	rEquipoArrendado as r,
							equipoArrendado as e,
							operador as o,
							faena as f
					where	r.equipoArrendado_id = e.id and
							o.id = r.operador_id and
							f.id = r.faena_id 
							$filtroFecha
					$finAgrupacion,fecha
					
					) as tArrendados
				$finAgrupacion
				
				) as t1
						
			$finAgrupacion
			";
		}

		
		$command=$connection->createCommand($insertSql.$sql);
		
		if($filtroFecha!=""){
			$fInicio = Tools::fixFecha($this->fechaInicio);
			$fFin = Tools::fixFecha($this->fechaFin);
			$command->bindParam(":fechaInicio",$fInicio,PDO::PARAM_STR);
			$command->bindParam(":fechaFin",$fFin,PDO::PARAM_STR);
		}
				
		$command->execute();	
			
		$connection->active=false;
		$command = null;
		
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informeProduccionMaquinaria;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}