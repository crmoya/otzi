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
class InformeResultados extends CActiveRecord
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
		return 'informeResultados';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maquina, operador, centroGestion, produccion,combustible,repuesto,resultado', 'required'),
			array('maquina', 'length', 'max'=>150),
			array('operador', 'length', 'max'=>215),
			array('centroGestion', 'length', 'max'=>50),
			array('resultado', 'length', 'max'=>11),
			array('produccion', 'length', 'max'=>11),
			array('combustible', 'length', 'max'=>11),
			array('repuesto', 'length', 'max'=>11),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, maquina, operador, centroGestion, produccion,combustible,repuesto,resultado', 'safe', 'on'=>'search'),
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
			'produccion' => 'Producción',
			'repuesto' => 'Gasto Repuestos',
			'combustible' => 'Gasto Combustible',
			'resultado' => 'Resultado',
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
		$criteria->compare('produccion',$this->produccion,true);
		$criteria->compare('repuesto',$this->repuesto,true);
		$criteria->compare('combustible',$this->combustible,true);
		$criteria->compare('resultado',$this->resultado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$insertSql = "
		insert into informeResultados
			(maquina,operador,centroGestion,produccion,repuesto,combustible,resultado)
			
		";		
				
		$inicioAgrupacionPropios = "
			concat(m.nombre,' / ',m.codigo) as maquina,
			concat(o.nombre,' / ',o.rut) as operador,
			f.nombre as centroGestion,
		";
		
		$inicioAgrupacionArrendados = "
			m.nombre as maquina,
			concat(o.nombre,' / ',o.rut) as operador,
			f.nombre as centroGestion,
		";
		
		$finAgrupacion = "group by maquina,operador,centroGestion";
		
		if(isset($this->agruparPor)){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacionPropios = "
					concat(m.nombre,' / ',m.codigo) as maquina,
					'' as operador,
					'' as centroGestion,
				";
				
				$inicioAgrupacionArrendados = "
					m.nombre as maquina,
					'' as operador,
					'' as centroGestion,
				";
				$finAgrupacion = "group by maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacionPropios = "
					'' as maquina,
					concat(o.nombre,' / ',o.rut) as operador,
					'' as centroGestion,
				";
				
				$inicioAgrupacionArrendados = "
					'' as maquina,
					concat(o.nombre,' / ',o.rut) as operador,
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
					concat(m.nombre,' / ',m.codigo) as maquina,
					'' as operador,
					f.nombre as centroGestion,
				";
				
				$inicioAgrupacionArrendados = "
					m.nombre as maquina,
					'' as operador,
					f.nombre as centroGestion,
				";
				$finAgrupacion = "group by maquina,centroGestion";
			}	
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacionPropios = "
					'' as maquina,
					concat(o.nombre,' / ',o.rut) as operador,
					f.nombre as centroGestion,
				";
				
				$inicioAgrupacionArrendados = "
					'' as maquina,
					concat(o.nombre,' / ',o.rut) as operador,
					f.nombre as centroGestion,
				";
				$finAgrupacion = "group by centroGestion,operador";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacionPropios = "
					concat(m.nombre,' / ',m.codigo) as maquina,
					concat(o.nombre,' / ',o.rut) as operador,
					'' as centroGestion,
				";
				
				$inicioAgrupacionArrendados = "
					m.nombre as maquina,
					concat(o.nombre,' / ',o.rut) as operador,
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
		
		
		if($this->propiosOArrendados == 'CAMIONESPROPIOS'){
							
			$sql = "
			
			select 
				$inicioAgrupacionPropios
				sum(produccion) as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(produccion) - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.produccion) as produccion,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.camionPropio_id as maquina,
							r.chofer_id as operador,
							v.faena_id as centroGestion,
							sum(v.total) as produccion,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionPropio as r,
							viajeCamionPropio as v
					where	v.rCamionPropio_id = r.id
							$filtroFecha
					group by r.id,maquina,operador,centroGestion
					
					union all 
					
					select 	r.camionPropio_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionPropio as r,
							compraRepuestoCamionPropio as c
					where	c.rCamionPropio_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.camionPropio_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rCamionPropio as r,
							cargaCombCamionPropio as c
					where	c.rCamionPropio_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				camionPropio as m,
				faena as f,
				chofer as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion			
						
			";
		}
		elseif($this->propiosOArrendados == 'CAMIONESARRENDADOS'){
			$sql = "
			select 
				$inicioAgrupacionArrendados
				sum(produccion) as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(produccion) - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.produccion) as produccion,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.camionArrendado_id as maquina,
							r.chofer_id as operador,
							v.faena_id as centroGestion,
							sum(v.total) as produccion,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionArrendado as r,
							viajeCamionArrendado as v
					where	v.rCamionArrendado_id = r.id
							$filtroFecha
					group by r.id,maquina,operador,centroGestion
					
					union all 
					
					select 	r.camionArrendado_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionArrendado as r,
							compraRepuestoCamionArrendado as c
					where	c.rCamionArrendado_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.camionArrendado_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rCamionArrendado as r,
							cargaCombCamionArrendado as c
					where	c.rCamionArrendado_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				camionArrendado as m,
				faena as f,
				chofer as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'CAMIONES'){
			$sql = "
			select 	maquina,
					operador,
					centroGestion,
					sum(produccion) as produccion,
					sum(repuesto) as repuesto,
					sum(combustible) as combustible,
					sum(resultado) as resultado
			from (
			
				select 
				$inicioAgrupacionPropios
				sum(produccion) as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(produccion) - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.produccion) as produccion,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.camionPropio_id as maquina,
							r.chofer_id as operador,
							v.faena_id as centroGestion,
							sum(v.total) as produccion,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionPropio as r,
							viajeCamionPropio as v
					where	v.rCamionPropio_id = r.id
							$filtroFecha
					group by r.id,maquina,operador,centroGestion
					
					union all 
					
					select 	r.camionPropio_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionPropio as r,
							compraRepuestoCamionPropio as c
					where	c.rCamionPropio_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.camionPropio_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rCamionPropio as r,
							cargaCombCamionPropio as c
					where	c.rCamionPropio_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				camionPropio as m,
				faena as f,
				chofer as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
			
			$finAgrupacion
			
			union all

				select 
				$inicioAgrupacionArrendados
				sum(produccion) as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(produccion) - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.produccion) as produccion,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.camionArrendado_id as maquina,
							r.chofer_id as operador,
							v.faena_id as centroGestion,
							sum(v.total) as produccion,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionArrendado as r,
							viajeCamionArrendado as v
					where	v.rCamionArrendado_id = r.id
							$filtroFecha
					group by r.id,maquina,operador,centroGestion
					
					union all 
					
					select 	r.camionArrendado_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rCamionArrendado as r,
							compraRepuestoCamionArrendado as c
					where	c.rCamionArrendado_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.camionArrendado_id as maquina,
							r.chofer_id as operador,
							c.faena_id as centroGestion,
							0 as produccion,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rCamionArrendado as r,
							cargaCombCamionArrendado as c
					where	c.rCamionArrendado_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				camionArrendado as m,
				faena as f,
				chofer as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion
			
			) as t1
			
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINASPROPIAS'){
			$sql = "
			select 
				$inicioAgrupacionPropios
				sum(horas)*m.precioUnitario as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(horas)*m.precioUnitario - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.horas) as horas,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.equipoPropio_id as maquina,
							r.operador_id as operador,
							r.faena_id as centroGestion,
							r.horas as horas,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoPropio as r
					where	1=1 
							$filtroFecha
					
					union all 
					
					select 	r.equipoPropio_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoPropio as r,
							compraRepuestoEquipoPropio as c
					where	c.rEquipoPropio_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.equipoPropio_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rEquipoPropio as r,
							cargaCombEquipoPropio as c
					where	c.rEquipoPropio_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				equipoPropio as m,
				faena as f,
				operador as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINASARRENDADAS'){
			$sql = "
			select 
				$inicioAgrupacionArrendados
				sum(horas)*m.precioUnitario as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(horas)*m.precioUnitario - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.horas) as horas,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.equipoArrendado_id as maquina,
							r.operador_id as operador,
							r.faena_id as centroGestion,
							r.horas as horas,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoArrendado as r
					where	1=1 
							$filtroFecha
					
					union all 
					
					select 	r.equipoArrendado_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoArrendado as r,
							compraRepuestoEquipoArrendado as c
					where	c.rEquipoArrendado_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.equipoArrendado_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rEquipoArrendado as r,
							cargaCombEquipoArrendado as c
					where	c.rEquipoArrendado_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				equipoArrendado as m,
				faena as f,
				operador as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'MAQUINAS'){
			$sql = "
			
			select 	maquina,
					operador,
					centroGestion,
					sum(produccion) as produccion,
					sum(repuesto) as repuesto,
					sum(combustible) as combustible,
					sum(resultado) as resultado
			from (
			
			select 
				$inicioAgrupacionPropios
				sum(horas)*m.precioUnitario as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(horas)*m.precioUnitario - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.horas) as horas,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.equipoPropio_id as maquina,
							r.operador_id as operador,
							r.faena_id as centroGestion,
							r.horas as horas,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoPropio as r
					where	1=1 
							$filtroFecha
					
					union all 
					
					select 	r.equipoPropio_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoPropio as r,
							compraRepuestoEquipoPropio as c
					where	c.rEquipoPropio_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.equipoPropio_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rEquipoPropio as r,
							cargaCombEquipoPropio as c
					where	c.rEquipoPropio_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				equipoPropio as m,
				faena as f,
				operador as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion
			
			union all
			
			select 
				$inicioAgrupacionArrendados
				sum(horas)*m.precioUnitario as produccion,
				sum(repuesto) as repuesto,
				sum(combustible) as combustible,
				sum(horas)*m.precioUnitario - sum(repuesto) - sum(combustible) as resultado
			from
				(
				select 
					maquina,
					operador,
					centroGestion,
					sum(tsumas.horas) as horas,
					sum(tsumas.repuesto) as repuesto,
					sum(tsumas.combustible) as combustible
				from
					(			
					select 	r.equipoArrendado_id as maquina,
							r.operador_id as operador,
							r.faena_id as centroGestion,
							r.horas as horas,
							0 as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoArrendado as r
					where	1=1 
							$filtroFecha
					
					union all 
					
					select 	r.equipoArrendado_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							c.montoNeto as repuesto,
							0 as combustible,
							r.id				
					from 	rEquipoArrendado as r,
							compraRepuestoEquipoArrendado as c
					where	c.rEquipoArrendado_id = r.id
							$filtroFecha
							
					union all
						
					select 	r.equipoArrendado_id as maquina,
							r.operador_id as operador,
							c.faena_id as centroGestion,
							0 as horas,
							0 as repuesto,
							c.valorTotal as combustible,
							r.id			
					from 	rEquipoArrendado as r,
							cargaCombEquipoArrendado as c
					where	c.rEquipoArrendado_id = r.id
							$filtroFecha
					) as tsumas
					group by maquina,operador,centroGestion
				) as ts,
				equipoArrendado as m,
				faena as f,
				operador as o
			where
				ts.maquina = m.id and
				ts.centroGestion = f.id and
				ts.operador = o.id 
				
			$finAgrupacion ) as t1
			
			$finAgrupacion
			
			";
		}
		else{
			$sql = "
			select 	maquina,
					operador,
					centroGestion,
					sum(produccion) as produccion,
					sum(repuesto) as repuesto,
					sum(combustible) as combustible,
					sum(resultado) as resultado
			from (
			
			
						select 	maquina,
								operador,
								centroGestion,
								sum(produccion) as produccion,
								sum(repuesto) as repuesto,
								sum(combustible) as combustible,
								sum(resultado) as resultado
						from (
						
							select 
							$inicioAgrupacionPropios
							sum(produccion) as produccion,
							sum(repuesto) as repuesto,
							sum(combustible) as combustible,
							sum(produccion) - sum(repuesto) - sum(combustible) as resultado
						from
							(
							select 
								maquina,
								operador,
								centroGestion,
								sum(tsumas.produccion) as produccion,
								sum(tsumas.repuesto) as repuesto,
								sum(tsumas.combustible) as combustible
							from
								(			
								select 	r.camionPropio_id as maquina,
										r.chofer_id as operador,
										v.faena_id as centroGestion,
										sum(v.total) as produccion,
										0 as repuesto,
										0 as combustible,
										r.id				
								from 	rCamionPropio as r,
										viajeCamionPropio as v
								where	v.rCamionPropio_id = r.id
										$filtroFecha
								group by r.id,maquina,operador,centroGestion
								
								union all 
								
								select 	r.camionPropio_id as maquina,
										r.chofer_id as operador,
										c.faena_id as centroGestion,
										0 as produccion,
										c.montoNeto as repuesto,
										0 as combustible,
										r.id				
								from 	rCamionPropio as r,
										compraRepuestoCamionPropio as c
								where	c.rCamionPropio_id = r.id
										$filtroFecha
										
								union all
									
								select 	r.camionPropio_id as maquina,
										r.chofer_id as operador,
										c.faena_id as centroGestion,
										0 as produccion,
										0 as repuesto,
										c.valorTotal as combustible,
										r.id			
								from 	rCamionPropio as r,
										cargaCombCamionPropio as c
								where	c.rCamionPropio_id = r.id
										$filtroFecha
								) as tsumas
								group by maquina,operador,centroGestion
							) as ts,
							camionPropio as m,
							faena as f,
							chofer as o
						where
							ts.maquina = m.id and
							ts.centroGestion = f.id and
							ts.operador = o.id 
						
						$finAgrupacion
						
						union all
			
							select 
							$inicioAgrupacionArrendados
							sum(produccion) as produccion,
							sum(repuesto) as repuesto,
							sum(combustible) as combustible,
							sum(produccion) - sum(repuesto) - sum(combustible) as resultado
						from
							(
							select 
								maquina,
								operador,
								centroGestion,
								sum(tsumas.produccion) as produccion,
								sum(tsumas.repuesto) as repuesto,
								sum(tsumas.combustible) as combustible
							from
								(			
								select 	r.camionArrendado_id as maquina,
										r.chofer_id as operador,
										v.faena_id as centroGestion,
										sum(v.total) as produccion,
										0 as repuesto,
										0 as combustible,
										r.id				
								from 	rCamionArrendado as r,
										viajeCamionArrendado as v
								where	v.rCamionArrendado_id = r.id
										$filtroFecha
								group by r.id,maquina,operador,centroGestion
								
								union all 
								
								select 	r.camionArrendado_id as maquina,
										r.chofer_id as operador,
										c.faena_id as centroGestion,
										0 as produccion,
										c.montoNeto as repuesto,
										0 as combustible,
										r.id				
								from 	rCamionArrendado as r,
										compraRepuestoCamionArrendado as c
								where	c.rCamionArrendado_id = r.id
										$filtroFecha
										
								union all
									
								select 	r.camionArrendado_id as maquina,
										r.chofer_id as operador,
										c.faena_id as centroGestion,
										0 as produccion,
										0 as repuesto,
										c.valorTotal as combustible,
										r.id			
								from 	rCamionArrendado as r,
										cargaCombCamionArrendado as c
								where	c.rCamionArrendado_id = r.id
										$filtroFecha
								) as tsumas
								group by maquina,operador,centroGestion
							) as ts,
							camionArrendado as m,
							faena as f,
							chofer as o
						where
							ts.maquina = m.id and
							ts.centroGestion = f.id and
							ts.operador = o.id 
							
						$finAgrupacion
						
						) as t1
						
						$finAgrupacion
			

						union all
						
						
						select 	maquina,
								operador,
								centroGestion,
								sum(produccion) as produccion,
								sum(repuesto) as repuesto,
								sum(combustible) as combustible,
								sum(resultado) as resultado
						from (
						
						select 
							$inicioAgrupacionPropios
							sum(horas)*m.precioUnitario as produccion,
							sum(repuesto) as repuesto,
							sum(combustible) as combustible,
							sum(horas)*m.precioUnitario - sum(repuesto) - sum(combustible) as resultado
						from
							(
							select 
								maquina,
								operador,
								centroGestion,
								sum(tsumas.horas) as horas,
								sum(tsumas.repuesto) as repuesto,
								sum(tsumas.combustible) as combustible
							from
								(			
								select 	r.equipoPropio_id as maquina,
										r.operador_id as operador,
										r.faena_id as centroGestion,
										r.horas as horas,
										0 as repuesto,
										0 as combustible,
										r.id				
								from 	rEquipoPropio as r
								where	1=1 
										$filtroFecha
								
								union all 
								
								select 	r.equipoPropio_id as maquina,
										r.operador_id as operador,
										c.faena_id as centroGestion,
										0 as horas,
										c.montoNeto as repuesto,
										0 as combustible,
										r.id				
								from 	rEquipoPropio as r,
										compraRepuestoEquipoPropio as c
								where	c.rEquipoPropio_id = r.id
										$filtroFecha
										
								union all
									
								select 	r.equipoPropio_id as maquina,
										r.operador_id as operador,
										c.faena_id as centroGestion,
										0 as horas,
										0 as repuesto,
										c.valorTotal as combustible,
										r.id			
								from 	rEquipoPropio as r,
										cargaCombEquipoPropio as c
								where	c.rEquipoPropio_id = r.id
										$filtroFecha
								) as tsumas
								group by maquina,operador,centroGestion
							) as ts,
							equipoPropio as m,
							faena as f,
							operador as o
						where
							ts.maquina = m.id and
							ts.centroGestion = f.id and
							ts.operador = o.id 
							
						$finAgrupacion
						
						union all
						
						select 
							$inicioAgrupacionArrendados
							sum(horas)*m.precioUnitario as produccion,
							sum(repuesto) as repuesto,
							sum(combustible) as combustible,
							sum(horas)*m.precioUnitario - sum(repuesto) - sum(combustible) as resultado
						from
							(
							select 
								maquina,
								operador,
								centroGestion,
								sum(tsumas.horas) as horas,
								sum(tsumas.repuesto) as repuesto,
								sum(tsumas.combustible) as combustible
							from
								(			
								select 	r.equipoArrendado_id as maquina,
										r.operador_id as operador,
										r.faena_id as centroGestion,
										r.horas as horas,
										0 as repuesto,
										0 as combustible,
										r.id				
								from 	rEquipoArrendado as r
								where	1=1 
										$filtroFecha
								
								union all 
								
								select 	r.equipoArrendado_id as maquina,
										r.operador_id as operador,
										c.faena_id as centroGestion,
										0 as horas,
										c.montoNeto as repuesto,
										0 as combustible,
										r.id				
								from 	rEquipoArrendado as r,
										compraRepuestoEquipoArrendado as c
								where	c.rEquipoArrendado_id = r.id
										$filtroFecha
										
								union all
									
								select 	r.equipoArrendado_id as maquina,
										r.operador_id as operador,
										c.faena_id as centroGestion,
										0 as horas,
										0 as repuesto,
										c.valorTotal as combustible,
										r.id			
								from 	rEquipoArrendado as r,
										cargaCombEquipoArrendado as c
								where	c.rEquipoArrendado_id = r.id
										$filtroFecha
								) as tsumas
								group by maquina,operador,centroGestion
							) as ts,
							equipoArrendado as m,
							faena as f,
							operador as o
						where
							ts.maquina = m.id and
							ts.centroGestion = f.id and
							ts.operador = o.id 
							
						$finAgrupacion ) as t2
						
						$finAgrupacion
						
						
						
						
						
			) as tTodos
			
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
			truncate informeResultados;
		"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}