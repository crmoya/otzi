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
class InformeConsumoMaquinaria extends CActiveRecord
{
	
	public $fechaInicio;
	public $fechaFin;
	public $propiosOArrendados;
	public $agruparPor;
	public $tipoCombustible_id;
	
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
		return 'informeConsumoMaquinaria';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maquina, operador,consumo,consumoEsperado', 'required'),
			array('maquina', 'length', 'max'=>150),
			array('operador', 'length', 'max'=>215),
			array('consumo,consumoEsperado,consumoGps', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, maquina, operador, consumo,consumoGps,consumoEsperado', 'safe', 'on'=>'search'),
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
			'consumo' => 'Lts/Hr Físicos [Lt/Hr]',
			'consumoEsperado' => 'Lts/Hr Esperados[Lt/Hr]',
			'consumoGps' => 'Lts/Hr GPS [Lt/Hr]',
			'ltsFisicos' => 'Lts. Totales',
			'hrsFisicas' => 'Hr. Físicas',
			'hrsGps' => 'Hr. GPS',
			'tipoCombustible_id'=>'Tipo Combustible',
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
		$criteria->compare('consumo',$this->consumo,true);
		$criteria->compare('consumoEsperado',$this->consumoEsperado,true);
		$criteria->compare('consumoGps',$this->consumoGps,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$insertSql = "
		insert into informeConsumoMaquinaria
			(maquina,operador,consumoEsperado,consumo,consumoGps,ltsFisicos,hrsFisicas,hrsGps)
			
		";		
		
		$inicioAgrupacionTodos = "
			maquina,
			operador,
		";
		$inicioAgrupacionPropios = "
			concat(m.codigo,' / ',m.nombre) as maquina,
			concat(o.rut,' / ',o.nombre) as operador,
		";
		$inicioAgrupacionArrendados = "
			m.nombre as maquina,
			concat(o.rut,' / ',o.nombre) as operador,
		";
		
		$finAgrupacion = "group by maquina,operador";
		
		if(isset($this->agruparPor)){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacionPropios = "
					concat(m.codigo,' / ',m.nombre) as maquina,
					'' as operador,
				";
				$inicioAgrupacionArrendados = "
					m.nombre as maquina,
					'' as operador,
				";
				$finAgrupacion = "group by maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacionPropios = "
					'' as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
				";
				$inicioAgrupacionArrendados = "
					'' as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
				";
				$finAgrupacion = "group by operador";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacionPropios = "
					concat(m.codigo,' / ',m.nombre) as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
				";
				$inicioAgrupacionArrendados = "
					m.nombre as maquina,
					concat(o.rut,' / ',o.nombre) as operador,
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
		
		$filtroCombustible = "";
		if(isset($this->tipoCombustible_id)){
			if($this->tipoCombustible_id != ""){
				$filtroCombustible = "	
					and	c.tipoCombustible_id = :tipoCombustible 
				";
			}
		}
		
		
		if($this->propiosOArrendados == 'PROPIOS'){
			$sql = "
			
			select 	maquina,
					operador,
					avg(consumoEsperado) as consumoEsperado,
					sum(consumo)/sum(horas) as consumo,
					sum(consumo)/sum(horasGps) as consumoGps,
					sum(consumo) as ltsFisicos,
					sum(horas) as hrsFisicas,
					sum(horasGps) as hrsGps
			from (
			
					select 	maquina,
							operador,
							tr.consumoEsperado as consumoEsperado,
							IFNULL(sum(c.petroleoLts),0) as consumo,
							horas as horas,
							horasGps as horasGps,
							tr.trid as trid
					from 	
						(	select 	$inicioAgrupacionPropios
									m.consumoEsperado as consumoEsperado,
									r.horas as horas,
									r.horasGps as horasGps,
									r.id as trid
							from 	rEquipoPropio as r,
									equipoPropio as m,
									operador as o
							where	m.id = r.equipoPropio_id and
									o.id = r.operador_id
									$filtroFecha) 
						as tr
					left join	
							cargaCombEquipoPropio as c		
					on		tr.trid = c.rEquipoPropio_id
							$filtroCombustible
					$finAgrupacion,trid 
				) as td
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'ARRENDADOS'){
			$sql = "
			select 	maquina,
					operador,
					avg(consumoEsperado) as consumoEsperado,
					sum(consumo)/sum(horas) as consumo,
					sum(consumo)/sum(horasGps) as consumoGps,
					sum(consumo) as ltsFisicos,
					sum(horas) as hrsFisicas,
					sum(horasGps) as hrsGps
			from (
			
					select 	maquina,
							operador,
							tr.consumoEsperado as consumoEsperado,
							IFNULL(sum(c.petroleoLts),0) as consumo,
							horas as horas,
							horasGps as horasGps,
							tr.trid as trid
					from 	
						(	select 	$inicioAgrupacionArrendados
									m.consumoEsperado as consumoEsperado,
									r.horas as horas,
									r.horasGps as horasGps,
									r.id as trid
							from 	rEquipoArrendado as r,
									equipoArrendado as m,
									operador as o
							where	m.id = r.equipoArrendado_id and
									o.id = r.operador_id
									$filtroFecha) 
						as tr
					left join	
							cargaCombEquipoArrendado as c		
					on		tr.trid = c.rEquipoArrendado_id
							$filtroCombustible
					$finAgrupacion,trid 
				) as td
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	maquina,
					operador,
					avg(consumoEsperado) as consumoEsperado,	
					sum(consumo) as consumo,
					sum(consumoGps) as consumoGps,
					sum(ltsFisicos) as ltsFisicos,
					sum(hrsFisicas) as hrsFisicas,
					sum(hrsGps) as hrsGps
			from
				(
					select 	maquina,
							operador,
							avg(consumoEsperado) as consumoEsperado,
							sum(consumo)/sum(horas) as consumo,
							sum(consumo)/sum(horasGps) as consumoGps,
							sum(consumo) as ltsFisicos,
							sum(horas) as hrsFisicas,
							sum(horasGps) as hrsGps
					from (
					
							select 	maquina,
									operador,
									tr.consumoEsperado as consumoEsperado,
									IFNULL(sum(c.petroleoLts),0) as consumo,
									horas as horas,
									horasGps as horasGps,
									tr.trid as trid
							from 	
								(	select 	$inicioAgrupacionPropios
											m.consumoEsperado as consumoEsperado,
											r.horas as horas,
											r.horasGps as horasGps,
											r.id as trid
									from 	rEquipoPropio as r,
											equipoPropio as m,
											operador as o
									where	m.id = r.equipoPropio_id and
											o.id = r.operador_id
											$filtroFecha) 
								as tr
							left join	
									cargaCombEquipoPropio as c		
							on		tr.trid = c.rEquipoPropio_id
									$filtroCombustible
							$finAgrupacion,trid 
						) as td
					$finAgrupacion
						
				union all
				
					select 	maquina,
					operador,
					avg(consumoEsperado) as consumoEsperado,
					sum(consumo)/sum(horas) as consumo,
					sum(consumo)/sum(horasGps) as consumoGps,
					sum(consumo) as ltsFisicos,
					sum(horas) as hrsFisicas,
					sum(horasGps) as hrsGps
					from (
					
							select 	maquina,
									operador,
									tr.consumoEsperado as consumoEsperado,
									IFNULL(sum(c.petroleoLts),0) as consumo,
									horas as horas,
									horasGps as horasGps,
									tr.trid as trid
							from 	
								(	select 	$inicioAgrupacionArrendados
											m.consumoEsperado as consumoEsperado,
											r.horas as horas,
											r.horasGps as horasGps,
											r.id as trid
									from 	rEquipoArrendado as r,
											equipoArrendado as m,
											operador as o
									where	m.id = r.equipoArrendado_id and
											o.id = r.operador_id
											$filtroFecha) 
								as tr
							left join	
									cargaCombEquipoArrendado as c		
							on		tr.trid = c.rEquipoArrendado_id
									$filtroCombustible
							$finAgrupacion,trid 
						) as td
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
		if($filtroCombustible!=""){
			$command->bindParam(":tipoCombustible",$this->tipoCombustible_id,PDO::PARAM_INT);
		}
		
		$command->execute();	
		
		$connection->active=false;
		$command = null;
		
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informeConsumoMaquinaria;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}