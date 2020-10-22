<?php

/**
 * This is the model class for table "informeGastoCombustible".
 *
 * The followings are the available columns in table 'informeGastoCombustible':
 * @property integer $id
 * @property string $maquina
 * @property string $operador
 * @property string $centroGestion
 * @property string $consumoLts
 * @property string $consumoPesos
 * @property string $fInicio
 * @property string $fFin
 * @property integer $maquina_id
 * @property integer $operador_id
 * @property integer $centroGestion_id
 * @property string $tipo
 * @property integer $tipo_comb
 * @property string $tipo_maquina
 */
class InformeGastoCombustible extends CActiveRecord
{
	
	public $fechaInicio;
	public $fechaFin;
	public $propiosOArrendados;
	public $agruparPor;
	public $tipoCombustible_id;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return InformeGastoCombustible the static model class
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
		return 'informeGastoCombustible';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('maquina_id, operador_id, centroGestion_id, tipo, tipo_comb, tipo_maquina', 'required'),
				array('maquina_id, operador_id, centroGestion_id, tipo_comb', 'numerical', 'integerOnly'=>true),
				array('maquina', 'length', 'max'=>150),
				array('operador', 'length', 'max'=>220),
				array('centroGestion', 'length', 'max'=>50),
				array('consumoLts, consumoPesos', 'length', 'max'=>12),
				array('tipo, tipo_maquina', 'length', 'max'=>2),
				array('fInicio, fFin', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, maquina, operador, centroGestion, consumoLts, consumoPesos, fInicio, fFin, maquina_id, operador_id, centroGestion_id, tipo, tipo_comb, tipo_maquina', 'safe', 'on'=>'search'),
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
			'maquina' => 'Máquina o camión, camioneta, auto',
			'operador' => 'Operador o Chofer',
			'centroGestion' => 'Centro Gestión',
			'consumoLts' => 'Consumo en Litros',
			'consumoPesos' => 'Consumo en $',
			'propiosOArrendados' => 'Mostrar Maquinaria o camiones, camionetas, autos',
			'tipoCombustible_id' => 'Tipo de Combustible',
				
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
		$criteria->compare('consumoLts',$this->consumoLts,true);
		$criteria->compare('consumoPesos',$this->consumoPesos,true);
		$criteria->compare('fInicio',$this->fInicio,true);
		$criteria->compare('fFin',$this->fFin,true);
		$criteria->compare('maquina_id',$this->maquina_id);
		$criteria->compare('operador_id',$this->operador_id);
		$criteria->compare('centroGestion_id',$this->centroGestion_id);
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('tipo_comb',$this->tipo_comb);
		$criteria->compare('tipo_maquina',$this->tipo_maquina,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;

		$insertSql = "
		insert into informeGastoCombustible
		(maquina,operador,centroGestion,consumoLts,consumoPesos,fInicio,fFin,maquina_id,operador_id,centroGestion_id,tipo,tipo_comb,tipo_maquina)
			
		";

		$inicioAgrupacionTodos = "
		maquina,
		operador,
		centroGestion,
		";
		$inicioAgrupacionPropios = "
		concat(m.codigo,' / ',m.nombre) as maquina,
		concat(o.rut,' / ',o.nombre) as operador,
		cg.nombre as centroGestion,
		";
		$inicioAgrupacionArrendados = "
		m.nombre as maquina,
		concat(o.rut,' / ',o.nombre) as operador,
		cg.nombre as centroGestion,
		";

		$finAgrupacion = "group by maquina,operador,centroGestion,maquina_id,operador_id,centroGestion_id";

		if(isset($this->agruparPor)){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacionPropios = "
				concat(m.codigo,' / ',m.nombre) as maquina,
				'' as operador,
				'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
				m.nombre as maquina,
				'' as operador,
				'' as centroGestion,
				";
				$finAgrupacion = "group by maquina,maquina_id";
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
				$finAgrupacion = "group by operador,operador_id";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacionPropios = "
				'' as maquina,
				'' as operador,
				cg.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
				'' as maquina,
				'' as operador,
				cg.nombre as centroGestion,
				";
				$finAgrupacion = "group by centroGestion,centroGestion_id";
			}
			if($this->agruparPor == "CENTROMAQUINA"){
				$inicioAgrupacionPropios = "
				concat(m.codigo,' / ',m.nombre) as maquina,
				'' as operador,
				cg.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
				m.nombre as maquina,
				'' as operador,
				cg.nombre as centroGestion,
				";
				$finAgrupacion = "group by maquina,centroGestion,maquina_id,centroGestion_id";
			}
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacionPropios = "
				'' as maquina,
				concat(o.rut,' / ',o.nombre) as operador,
				cg.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
				'' as maquina,
				concat(o.rut,' / ',o.nombre) as operador,
				cg.nombre as centroGestion,
				";
				$finAgrupacion = "group by centroGestion,operador,centroGestion_id,operador_id";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacionPropios = "
				concat(m.codigo,' / ',m.nombre) as maquina,
				concat(o.rut,' / ',o.nombre) as operador,
				'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
				m.nombre as maquina,
				concat(o.rut,' / ',o.nombre) as operador,
				'' as centroGestion,
				";
				$finAgrupacion = "group by operador,maquina,operador_id,maquina_id";
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

		if($this->propiosOArrendados == 'CAMIONESPROPIOS'){
			$sql = "
			select 	$inicioAgrupacionPropios
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			:fI,
			:fF,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'CP' as tipo,
			:tipoComb,
			'CP' as tipo_maquina
			from 	camionPropio as m 
			join 	rCamionPropio as r on r.camionPropio_id = m.id
			join	cargaCombCamionPropio as c on c.rCamionPropio_id = r.id
			join	faena as cg on cg.id = c.faena_id 
			join	chofer as o on o.id = r.chofer_id
			where	1 = 1
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'CAMIONESARRENDADOS'){
			$sql = "
			select 	$inicioAgrupacionArrendados
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			:fI,
			:fF,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'CA' as tipo,
			:tipoComb,
			'CA' as tipo_maquina
			from 	cargaCombCamionArrendado as c,
			rCamionArrendado as r,
			faena as cg,
			camionArrendado as m,
			chofer as o
			where	r.id = c.rCamionArrendado_id and
			cg.id = c.faena_id and
			m.id = r.camionArrendado_id and
			o.id = r.chofer_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'CAMIONES'){
			$sql = "
			select 	$inicioAgrupacionTodos
			sum(consumoLts) as consumoLts,
			sum(consumoPesos) as consumoPesos,
			:fI,
			:fF,
			maquina_id,
			operador_id,
			centroGestion_id,
			tipo,
			:tipoComb,
			tipo_maquina
			from (

			select 	$inicioAgrupacionArrendados
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'CT' as tipo,
			'CA' as tipo_maquina
			from 	cargaCombCamionArrendado as c,
			rCamionArrendado as r,
			faena as cg,
			camionArrendado as m,
			chofer as o
			where	r.id = c.rCamionArrendado_id and
			cg.id = c.faena_id and
			m.id = r.camionArrendado_id and
			o.id = r.chofer_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion

			union all

			select 	$inicioAgrupacionPropios
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'CT' as tipo,
			'CP' as tipo_maquina
			from 	cargaCombCamionPropio as c,
			rCamionPropio as r,
			faena as cg,
			camionPropio as m,
			chofer as o
			where	r.id = c.rCamionPropio_id and
			cg.id = c.faena_id and
			m.id = r.camionPropio_id and
			o.id = r.chofer_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
			) as t1

			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINASPROPIAS'){
			$sql = "
			select 	$inicioAgrupacionPropios
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			:fI,
			:fF,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'MP' as tipo,
			:tipoComb,
			'MP' as tipo_maquina
			from 	cargaCombEquipoPropio as c,
			rEquipoPropio as r,
			faena as cg,
			equipoPropio as m,
			operador as o
			where	r.id = c.rEquipoPropio_id and
			cg.id = c.faena_id and
			m.id = r.equipoPropio_id and
			o.id = r.operador_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINASARRENDADAS'){
			$sql = "
			select 	$inicioAgrupacionArrendados
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			:fI,
			:fF,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'MA' as tipo,
			:tipoComb,
			'MA' as tipo_maquina
			from 	cargaCombEquipoArrendado as c,
			rEquipoArrendado as r,
			faena as cg,
			equipoArrendado as m,
			operador as o
			where	r.id = c.rEquipoArrendado_id and
			cg.id = c.faena_id and
			m.id = r.equipoArrendado_id and
			o.id = r.operador_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINAS'){
			$sql = "
			select 	$inicioAgrupacionTodos
			sum(consumoLts) as consumoLts,
			sum(consumoPesos) as consumoPesos,
			:fI,
			:fF,
			maquina_id,
			operador_id,
			centroGestion_id,
			tipo,
			:tipoComb,
			tipo_maquina
			from (
				
			select 	$inicioAgrupacionPropios
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'MT' as tipo,
			'MP' as tipo_maquina
			from 	cargaCombEquipoPropio as c,
			rEquipoPropio as r,
			faena as cg,
			equipoPropio as m,
			operador as o
			where	r.id = c.rEquipoPropio_id and
			cg.id = c.faena_id and
			m.id = r.equipoPropio_id and
			o.id = r.operador_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
				
			union all
				
			select 	$inicioAgrupacionArrendados
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'MT' as tipo,
			'MA' as tipo_maquina
			from 	cargaCombEquipoArrendado as c,
			rEquipoArrendado as r,
			faena as cg,
			equipoArrendado as m,
			operador as o
			where	r.id = c.rEquipoArrendado_id and
			cg.id = c.faena_id and
			m.id = r.equipoArrendado_id and
			o.id = r.operador_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
			) as t1
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	$inicioAgrupacionTodos
			sum(consumoLts) as consumoLts,
			sum(consumoPesos) as consumoPesos,
			:fI,
			:fF,
			maquina_id,
			operador_id,
			centroGestion_id,
			tipo,
			:tipoComb,
			tipo_maquina
			from (
				
			select 	$inicioAgrupacionPropios
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'TT' as tipo,
			'MP' as tipo_maquina
			from 	cargaCombEquipoPropio as c,
			rEquipoPropio as r,
			faena as cg,
			equipoPropio as m,
			operador as o
			where	r.id = c.rEquipoPropio_id and
			cg.id = c.faena_id and
			m.id = r.equipoPropio_id and
			o.id = r.operador_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
				
			union all
				
			select 	$inicioAgrupacionArrendados
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'TT' as tipo,
			'MA' as tipo_maquina
			from 	cargaCombEquipoArrendado as c,
			rEquipoArrendado as r,
			faena as cg,
			equipoArrendado as m,
			operador as o
			where	r.id = c.rEquipoArrendado_id and
			cg.id = c.faena_id and
			m.id = r.equipoArrendado_id and
			o.id = r.operador_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
				
			union all
				
			select 	$inicioAgrupacionArrendados
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'TT' as tipo,
			'CA' as tipo_maquina
			from 	cargaCombCamionArrendado as c,
			rCamionArrendado as r,
			faena as cg,
			camionArrendado as m,
			chofer as o
			where	r.id = c.rCamionArrendado_id and
			cg.id = c.faena_id and
			m.id = r.camionArrendado_id and
			o.id = r.chofer_id
			$filtroFecha
			$filtroCombustible
			$finAgrupacion
				
			union all

			select 	$inicioAgrupacionPropios
			sum(c.petroleoLts) as consumoLts,
			sum(c.valorTotal) as consumoPesos,
			m.id as maquina_id,
			o.id as operador_id,
			cg.id as centroGestion_id,
			'TT' as tipo,
			'CP' as tipo_maquina
			from 	cargaCombCamionPropio as c,
			rCamionPropio as r,
			faena as cg,
			camionPropio as m,
			chofer as o
			where	r.id = c.rCamionPropio_id and
			cg.id = c.faena_id and
			m.id = r.camionPropio_id and
			o.id = r.chofer_id
			$filtroFecha
			$filtroCombustible
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
			$command->bindParam(":fI",$fInicio,PDO::PARAM_STR);
			$command->bindParam(":fF",$fFin,PDO::PARAM_STR);
		}

		$command->bindParam(":tipoComb",$this->tipoCombustible_id,PDO::PARAM_INT);
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
				truncate informeGastoCombustible;
				"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
}