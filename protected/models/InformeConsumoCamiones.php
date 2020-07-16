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
class InformeConsumoCamiones extends CActiveRecord
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
		return 'informeConsumoCamion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maquina, operador,consumoReal,consumoGps,consumoSugerido', 'required'),
			array('maquina', 'length', 'max'=>150),
			array('operador', 'length', 'max'=>215),
			array('consumoReal', 'length', 'max'=>11),
			array('consumoGps', 'length', 'max'=>11),
			array('consumoSugerido', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, maquina, operador, consumoReal,consumoGps,consumoSugerido', 'safe', 'on'=>'search'),
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
			'maquina' => 'Vehículo',
			'operador' => 'Chofer',
			'consumoReal' => 'Kms/Lt Físicos [Km/Lt]',
			'consumoGps' => 'Kms/Lt GPS [Km/Lt]',
			'consumoSugerido' => 'Kms/Lt Esperados [Km/Lt]',
			'ltsFisicos'=>'Lts. Físicos',
			'kmsFisicos'=>'Km. Físicos',
			'kmsGps'=>'Km. GPS',
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
		$criteria->compare('consumoReal',$this->consumoReal,true);
		$criteria->compare('consumoGps',$this->consumoGps,true);
		$criteria->compare('consumoSugerido',$this->consumoSugerido,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$insertSql = "
		insert into informeConsumoCamion
			(maquina,operador,consumoReal,consumoGps,consumoSugerido,ltsFisicos,kmsFisicos,kmsGps)
			
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
					sum(kmRecorridos)/sum(consumo) as consumoReal,
					sum(kmGps)/sum(consumo) as consumoGps,
					avg(consumoPromedio) as consumoSugerido,
					sum(consumo) as ltsFisicos,
					sum(kmRecorridos) as kmsFisicos,
					sum(kmGps) as kmsGps
			from (
			
				select 	maquina,
						operador,
						tr.kmRecorridos as kmRecorridos,
						IFNULL(sum(c.petroleoLts),0) as consumo,
						tr.kmGps as kmGps, 
						tr.consumoPromedio as consumoPromedio, 
						tr.id
				from  (			
					select 	$inicioAgrupacionPropios
								r.kmGps as kmGps,
								IFNULL(kmFinal - kmInicial,0) as kmRecorridos,
								m.consumoPromedio as consumoPromedio,
								r.id as id
						from 	rCamionPropio as r,
								camionPropio as m,
								chofer as o
						where	m.id = r.camionPropio_id and
								o.id = r.chofer_id
								$filtroFecha ) as tr
				left join
					cargaCombCamionPropio as c
				on		tr.id = c.rCamionPropio_id 
						$filtroCombustible
				$finAgrupacion,tr.id) as tc
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'ARRENDADOS'){
			$sql = "
			select 	maquina,
					operador,
					sum(kmRecorridos)/sum(consumo) as consumoReal,
					sum(kmGps)/sum(consumo) as consumoGps,
					avg(consumoPromedio) as consumoSugerido,
					sum(consumo) as ltsFisicos,
					sum(kmRecorridos) as kmsFisicos,
					sum(kmGps) as kmsGps
			from (
			
				select 	maquina,
						operador,
						tr.kmRecorridos as kmRecorridos,
						IFNULL(sum(c.petroleoLts),0) as consumo,
						tr.kmGps as kmGps, 
						tr.consumoPromedio as consumoPromedio, 
						tr.id
				from  (			
					select 	$inicioAgrupacionArrendados
							r.kmGps as kmGps,
							IFNULL(kmFinal - kmInicial,0) as kmRecorridos,
							m.consumoPromedio as consumoPromedio,
							r.id as id
					from 	rCamionArrendado as r,
							camionArrendado as m,
							chofer as o
					where	m.id = r.camionArrendado_id and
							o.id = r.chofer_id
							$filtroFecha ) as tr
				left join
					cargaCombCamionArrendado as c
				on		tr.id = c.rCamionArrendado_id 
						$filtroCombustible
				$finAgrupacion,tr.id) as tc
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	$inicioAgrupacionTodos
					avg(consumoReal) as consumoReal,
					avg(consumoGps) as consumoGps,
					avg(consumoSugerido) as consumoSugerido,
					sum(ltsFisicos) as ltsFisicos,
					sum(kmsFisicos) as kmsFisicos,
					sum(kmsGps) as kmsGps	
			from
				(	
				select 	maquina,
						operador,
						sum(kmRecorridos)/sum(consumo) as consumoReal,
						sum(kmGps)/sum(consumo) as consumoGps,
						avg(consumoPromedio) as consumoSugerido,
						sum(consumo) as ltsFisicos,
						sum(kmRecorridos) as kmsFisicos,
						sum(kmGps) as kmsGps
					from (
					
						select 	maquina,
								operador,
								tr.kmRecorridos as kmRecorridos,
								IFNULL(sum(c.petroleoLts),0) as consumo,
								tr.kmGps as kmGps, 
								tr.consumoPromedio as consumoPromedio, 
								tr.id
						from  (			
							select 	$inicioAgrupacionPropios
									r.kmGps as kmGps,
									IFNULL(kmFinal - kmInicial,0) as kmRecorridos,
									m.consumoPromedio as consumoPromedio,
									r.id as id
							from 	rCamionPropio as r,
									camionPropio as m,
									chofer as o
							where	m.id = r.camionPropio_id and
									o.id = r.chofer_id
									$filtroFecha ) as tr
						left join
							cargaCombCamionPropio as c
						on		tr.id = c.rCamionPropio_id 
								$filtroCombustible
						$finAgrupacion,tr.id) as tc
					$finAgrupacion
				
				union all
				
					select 	maquina,
							operador,
							sum(kmRecorridos)/sum(consumo) as consumoReal,
							sum(kmGps)/sum(consumo) as consumoGps,
							avg(consumoPromedio) as consumoSugerido,	
							sum(consumo) as ltsFisicos,
							sum(kmRecorridos) as kmsFisicos,
							sum(kmGps) as kmsGps
					from (
					
						select 	maquina,
								operador,
								tr.kmRecorridos as kmRecorridos,
								IFNULL(sum(c.petroleoLts),0) as consumo,
								tr.kmGps as kmGps, 
								tr.consumoPromedio as consumoPromedio, 
								tr.id
						from  (			
							select 	$inicioAgrupacionArrendados
									r.kmGps as kmGps,
									IFNULL(kmFinal - kmInicial,0) as kmRecorridos,
									m.consumoPromedio as consumoPromedio,
									r.id as id
							from 	rCamionArrendado as r,
									camionArrendado as m,
									chofer as o
							where	m.id = r.camionArrendado_id and
									o.id = r.chofer_id
									$filtroFecha ) as tr
						left join
							cargaCombCamionArrendado as c
						on		tr.id = c.rCamionArrendado_id 
								$filtroCombustible
						$finAgrupacion,tr.id) as tc
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
			truncate informeConsumoCamion;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}