<?php

/**
 * This is the model class for table "informeProduccionMaquinaria".
 *
 * The followings are the available columns in table 'informeProduccionMaquinaria':
 * @property integer $id
 * @property string $maquina
 * @property string $operador
 * @property string $pu
 * @property string $horas
 * @property string $produccion
 */
class InformeGastoRepuesto extends CActiveRecord
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
		return 'informeGastoRepuesto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maquina, operador, consumoPesos', 'required'),
			array('maquina', 'length', 'max'=>150),
			array('operador', 'length', 'max'=>215),
			array('consumoPesos', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, maquina, operador, consumoPesos', 'safe', 'on'=>'search'),
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
			'centroGestion' => 'Centro de gestión',
			'operador' => 'Operador o Chofer',
			'consumoPesos' => 'Consumo en $',
			'propiosOArrendados' => 'Mostrar Maquinaria o camiones, camionetas, autos'
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
		$criteria->compare('consumoPesos',$this->consumoPesos,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$insertSql = "
		insert into informeGastoRepuesto
			(maquina,operador,centroGestion,consumoPesos,fInicio,fFin,propiosOArrendados,maquina_id,operador_id,tipo,centroGestion_id,tipo_maquina)
			
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
			if($this->agruparPor == "CENTRO"){
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
				$finAgrupacion = "group by centroGestion,maquina,centroGestion_id,maquina_id";
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
		
		}
		
		if($this->propiosOArrendados == null || $this->propiosOArrendados == ""){
			$this->propiosOArrendados = "TODOS";
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
		
		
		if($this->propiosOArrendados == 'CAMIONESPROPIOS'){
			$sql = "

			select 	$inicioAgrupacionTodos
					sum(consumoPesos) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					maquina_id,
					operador_id,
					tipo,
					centroGestion_id,
					tipo_maquina

				from (
				
					select 	$inicioAgrupacionPropios
							sum(c.montoNeto) as consumoPesos,
							:fInicio,
							:fFin,
							:propOArr,
							m.id as maquina_id,
							o.id as operador_id,
							'CP' as tipo,
							cg.id as centroGestion_id,
							'CP' as tipo_maquina
					from 	compraRepuestoCamionPropio as c,
							rCamionPropio as r,
							camionPropio as m,
							chofer as o,
							faena as cg
					where	r.id = c.rCamionPropio_id and
							m.id = r.camionPropio_id and
							o.id = r.chofer_id and
							cg.id = c.faena_id
							$filtroFecha

					union all

					select  concat(m.codigo,' / ',m.nombre) as maquina,
							'' as operador,
							cg.nombre as centroGestion,
							sum(consumoPesos) as consumoPesos,
							:fInicio,
							:fFin,
							:propOArr,
							m.id as maquina_id,
							0 as operador_id,
							'CP' as tipo,
							cg.id as centroGestion_id,
							'CP' as tipo_maquina
					from 	camionPropio as m 
					join	nocombustible_rindegasto as cr on cr.camionpropio_id = m.id
					left join faena as cg on cg.id = cr.faena_id
					where	cr.fecha <= :fF and
							cr.fecha >= :fI
					$finAgrupacion
				) as t1
				$finAgrupacion
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'CAMIONESARRENDADOS'){
			$sql = "
			select 	$inicioAgrupacionArrendados
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'CA' as tipo,
					cg.id as centroGestion_id,
					'CA' as tipo_maquina
			from 	compraRepuestoCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'CAMIONES'){
			$sql = "
			select 	$inicioAgrupacionTodos
					sum(consumoPesos) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					maquina_id,
					operador_id,
					tipo,
					centroGestion_id,
					tipo_maquina
			from (
			
			select 	$inicioAgrupacionArrendados
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'CT' as tipo,
					cg.id as centroGestion_id,
					'CA' as tipo_maquina
			from 	compraRepuestoCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			
			union all

			select 	$inicioAgrupacionPropios
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'CT' as tipo,
					cg.id as centroGestion_id,
					'CP' as tipo_maquina
			from 	compraRepuestoCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id
					$filtroFecha
			$finAgrupacion
			) as t1
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINASPROPIAS'){
			$sql = "
			select 	$inicioAgrupacionPropios
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'MP' as tipo,
					cg.id as centroGestion_id,
					'MP' as tipo_maquina
			from 	compraRepuestoEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					cg.id = c.faena_id
					$filtroFecha
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINASARRENDADAS'){
			$sql = "
			select 	$inicioAgrupacionArrendados
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'MA' as tipo,
					cg.id as centroGestion_id,
					'MA' as tipo_maquina
			from 	compraRepuestoEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINAS'){
			$sql = "
			select 	$inicioAgrupacionTodos
					sum(consumoPesos) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					maquina_id,
					operador_id,
					tipo,
					centroGestion_id,
					tipo_maquina
			from (
			
			select 	$inicioAgrupacionPropios
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'MT' as tipo,
					cg.id as centroGestion_id,
					'MP' as tipo_maquina
			from 	compraRepuestoEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			
			union all
			
			select 	$inicioAgrupacionArrendados
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'MT' as tipo,
					cg.id as centroGestion_id,
					'MA' as tipo_maquina
			from 	compraRepuestoEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			) as t1
			
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	$inicioAgrupacionTodos
					sum(consumoPesos) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					maquina_id,
					operador_id,
					tipo,
					centroGestion_id,
					tipo_maquina
			from (
			
			select 	$inicioAgrupacionPropios
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'TT' as tipo,
					cg.id as centroGestion_id,
					'MP' as tipo_maquina
			from 	compraRepuestoEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			
			union all
			
			select 	$inicioAgrupacionArrendados
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'TT' as tipo,
					cg.id as centroGestion_id,
					'MA' as tipo_maquina
			from 	compraRepuestoEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			
			union all 
			
			select 	$inicioAgrupacionArrendados
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'TT' as tipo,
					cg.id as centroGestion_id,
					'CA' as tipo_maquina
			from 	compraRepuestoCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			
			union all

			select 	$inicioAgrupacionPropios
					sum(c.montoNeto) as consumoPesos,
					:fInicio,
					:fFin,
					:propOArr,
					m.id as maquina_id,
					o.id as operador_id,
					'TT' as tipo,
					cg.id as centroGestion_id,
					'CP' as tipo_maquina
			from 	compraRepuestoCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id 
					$filtroFecha
			$finAgrupacion
			) as t1
			$finAgrupacion
			";
		}

		$command=$connection->createCommand($insertSql.$sql);
		
		if($filtroFecha!=""){
			$fInicio = Tools::fixFecha($this->fechaInicio);
			$fFin = Tools::fixFecha($this->fechaFin);
			$command->bindParam(":fInicio",$fInicio,PDO::PARAM_STR);
			$command->bindParam(":fFin",$fFin,PDO::PARAM_STR);
			$command->bindParam(":fechaInicio",$fInicio,PDO::PARAM_STR);
			$command->bindParam(":fechaFin",$fFin,PDO::PARAM_STR);
			$command->bindParam(":propOArr",$this->propiosOArrendados,PDO::PARAM_STR);
		}
		
		$command->execute();	
		
		
		$connection->active=false;
		$command = null;
		
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informeGastoRepuesto;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
	public function getMaquinaOperador($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		maquina,operador
			from		informeGastoRepuesto
			where 		id = :id  
			"
		);
		$command->bindParam(":id",$id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data = array();
		foreach($rows as $row){
			$data[0]=$row['maquina'];
			$data[1]=$row['operador'];
		}
		return $data;
	}
	
}