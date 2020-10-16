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
class InformeProduccionCamiones extends CActiveRecord
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
		return 'informeProduccionCamiones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('camion, chofer, centroGestion, pu, totalTransportado, produccion', 'required'),
			array('camion', 'length', 'max'=>150),
			array('chofer', 'length', 'max'=>215),
			array('centroGestion', 'length', 'max'=>50),
			array('totalTransportado, produccion', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, camion, chofer, centroGestion, totalTransportado, produccion', 'safe', 'on'=>'search'),
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
			'camion' => 'camión, camioneta, auto',
			'chofer' => 'Chofer',
			'centroGestion' => 'Centro Gestión',
			'totalTransportado' => 'Total Transportado',
			'produccionReal' => 'Producción Real',
			'produccion' => 'Producción Contratada',
			'propiosOArrendados' => 'Mostrar camiones, camionetas, autos',
			'totalCobro' => 'Total a Cobro',
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
		$criteria->compare('camion',$this->camion,true);
		$criteria->compare('chofer',$this->chofer,true);
		$criteria->compare('centroGestion',$this->centroGestion,true);
		$criteria->compare('totalTransportado',$this->totalTransportado,true);
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
		insert into informeProduccionCamiones
			(camion,chofer,centroGestion,totalTransportado,produccion,produccionReal,diferencia,totalCobro)
			
		";		
		
		$inicioAgrupacionTodos = "
			camion,
			chofer,
			centroGestion,
		";
		$inicioAgrupacionPropios = "
			concat(c.codigo,' / ',c.nombre) as camion,
			concat(ch.rut,' / ',ch.nombre) as chofer,
			f.nombre as centroGestion,
		";
		$inicioAgrupacionPropiosSinFaena = "
			concat(c.codigo,' / ',c.nombre) as camion,
			concat(ch.rut,' / ',ch.nombre) as chofer,
			'' as centroGestion,
		";
		$inicioAgrupacionArrendados = "
			c.nombre as camion,
			concat(ch.rut,' / ',ch.nombre) as chofer,
			f.nombre as centroGestion,
		";
		$inicioAgrupacionArrendadosSinFaena = "
			c.nombre as camion,
			concat(ch.rut,' / ',ch.nombre) as chofer,
			'' as centroGestion,
		";
		
		$finAgrupacion = "group by camion,chofer,centroGestion";
		
		if(isset($this->agruparPor)){
			if($this->agruparPor == "CAMION"){
				$inicioAgrupacionPropios = "
					concat(c.codigo,' / ',c.nombre) as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					c.nombre as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionPropiosSinFaena = "
					concat(c.codigo,' / ',c.nombre) as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendadosSinFaena = "
					c.nombre as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$finAgrupacion = "group by camion";
			}
			if($this->agruparPor == "CHOFER"){
				$inicioAgrupacionPropios = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionPropiosSinFaena = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendadosSinFaena = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$finAgrupacion = "group by chofer";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacionPropios = "
					'' as camion,
					'' as chofer,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					'' as camion,
					'' as chofer,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionPropiosSinFaena = "
					'' as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendadosSinFaena = "
					'' as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$finAgrupacion = "group by centroGestion";
			}
			if($this->agruparPor == "CENTROCAMION"){
				$inicioAgrupacionPropios = "
					concat(c.codigo,' / ',c.nombre) as camion,
					'' as chofer,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					c.nombre as camion,
					'' as chofer,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionPropiosSinFaena = "
					concat(c.codigo,' / ',c.nombre) as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendadosSinFaena = "
					c.nombre as camion,
					'' as chofer,
					'' as centroGestion,
				";
				$finAgrupacion = "group by camion,centroGestion";
			}	
			if($this->agruparPor == "CENTROCHOFER"){
				$inicioAgrupacionPropios = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					f.nombre as centroGestion,
				";
				$inicioAgrupacionPropiosSinFaena = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendadosSinFaena = "
					'' as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$finAgrupacion = "group by centroGestion,chofer";
			}
			if($this->agruparPor == "CHOFERCAMION"){
				$inicioAgrupacionPropios = "
					concat(c.codigo,' / ',c.nombre) as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendados = "
					c.nombre as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionPropiosSinFaena = "
					concat(c.codigo,' / ',c.nombre) as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$inicioAgrupacionArrendadosSinFaena = "
					c.nombre as camion,
					concat(ch.rut,' / ',ch.nombre) as chofer,
					'' as centroGestion,
				";
				$finAgrupacion = "group by chofer,camion";
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
			select 	camion,
					chofer,
					centroGestion,
					sum(totalTransportado),
					sum(GREATEST(produccionMinima,produccionReal)) as produccion,
					sum(produccionReal) as produccionReal,
					sum(GREATEST(produccionMinima - produccionReal,0)) as diferencia,
					sum(GREATEST(produccionMinima,produccionReal) + GREATEST(produccionMinima - produccionReal,0)) as totalCobro
			from		
				(						
				select  $inicioAgrupacionPropios
						sum(v.totalTransportado) as totalTransportado,
						GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0) as produccionMinima,
						sum(v.total) as produccionReal,
						fecha,
						r.id
				from 	rCamionPropio as r
				join	viajeCamionPropio as v on v.rCamionPropio_id = r.id
				join	camionPropio as c on r.camionPropio_id = c.id
				join	chofer as ch on ch.id = r.chofer_id
				join	faena as f on f.id = v.faena_id
				where	1 = 1 $filtroFecha
				$finAgrupacion,fecha,r.id
				
				union
				
				select  $inicioAgrupacionPropiosSinFaena
						0 as totalTransportado,
						GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0) as produccionMinima,
						0 as produccionReal,
						fecha,
						r.id
				from 	rCamionPropio as r,
						camionPropio as c,
						chofer as ch
				where	r.camionPropio_id = c.id and
						ch.id = r.chofer_id 
						$filtroFecha
						and NOT EXISTS
							(select	*
							 from	viajeCamionPropio as v
							 where	v.rCamionPropio_id = r.id)
				$finAgrupacion,fecha,r.id

				union

				select  $inicioAgrupacionPropios
						0 as totalTransportado,
						0 as produccionMinima,
						sum(e.total) as produccionReal,
						fecha,
						r.id
				from 	rCamionPropio as r
				join	expedicionportiempo as e on e.rcamionpropio_id = r.id
				join	camionPropio as c on r.camionPropio_id = c.id
				join	chofer as ch on ch.id = r.chofer_id
				join	faena as f on f.id = e.faena_id
				where	1 = 1 $filtroFecha
				$finAgrupacion,fecha,r.id

				) as tPropios
			$finAgrupacion
			";
			
		}
		elseif($this->propiosOArrendados == 'ARRENDADOS'){
			$sql = "
			select 	camion,
					chofer,
					centroGestion,
					sum(totalTransportado),
					sum(GREATEST(produccionMinima,produccionReal)) as produccion,
					sum(produccionReal) as produccionReal,
					sum(GREATEST(produccionMinima - produccionReal,0)) as diferencia,
					sum(GREATEST(produccionMinima,produccionReal) + GREATEST(produccionMinima - produccionReal,0)) as totalCobro
			from		
				(						
				select  $inicioAgrupacionArrendados
						sum(v.totalTransportado) as totalTransportado,
						IFNULL(GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0),0) as produccionMinima,
						sum(v.total) as produccionReal,
						fecha,
						r.id
				from 	rCamionArrendado as r,
						viajeCamionArrendado as v,
						camionArrendado as c,
						chofer as ch,
						faena as f
				where	v.rCamionArrendado_id = r.id and
						r.camionArrendado_id = c.id and
						ch.id = r.chofer_id and
						f.id = v.faena_id 
						$filtroFecha
				$finAgrupacion,fecha,r.id
				
				union
				
				select  $inicioAgrupacionArrendadosSinFaena
						0 as totalTransportado,
						GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0) as produccionMinima,
						0 as produccionReal,
						fecha,
						r.id
				from 	rCamionArrendado as r,
						camionArrendado as c,
						chofer as ch
				where	r.camionArrendado_id = c.id and
						ch.id = r.chofer_id 
						$filtroFecha
						and NOT EXISTS
							(select	*
							 from	viajeCamionArrendado as v
							 where	v.rCamionArrendado_id = r.id)
				$finAgrupacion,fecha,r.id

				union

				select  $inicioAgrupacionArrendados
						0 as totalTransportado,
						0 as produccionMinima,
						sum(e.total) as produccionReal,
						fecha,
						r.id
				from 	rCamionArrendado as r
				join	expedicionportiempoarr as e on e.rcamionarrendado_id = r.id
				join	camionArrendado as c on r.camionArrendado_id = c.id
				join	chofer as ch on ch.id = r.chofer_id
				join	faena as f on f.id = e.faena_id
				where	1 = 1 $filtroFecha
				$finAgrupacion,fecha,r.id

				) as tArrendados
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	camion,
					chofer,
					centroGestion,
					sum(totalTransportado),
					sum(produccion),
					sum(produccionReal),
					sum(diferencia),
					sum(totalCobro)
			from
				(	
					select 	camion,
							chofer,
							centroGestion,
							sum(totalTransportado) as totalTransportado,
							sum(GREATEST(produccionMinima,produccionReal)) as produccion,
							sum(produccionReal) as produccionReal,
							sum(GREATEST(produccionMinima - produccionReal,0)) as diferencia,
							sum(GREATEST(produccionMinima,produccionReal) + GREATEST(produccionMinima - produccionReal,0)) as totalCobro
					from		
						(						
						select  $inicioAgrupacionPropios
								sum(v.totalTransportado) as totalTransportado,
								IFNULL(GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0),0) as produccionMinima,
								sum(v.total) as produccionReal,
								fecha,
								r.id
						from 	rCamionPropio as r,
								viajeCamionPropio as v,
								camionPropio as c,
								chofer as ch,
								faena as f
						where	v.rCamionPropio_id = r.id and
								r.camionPropio_id = c.id and
								ch.id = r.chofer_id and
								f.id = v.faena_id 
								$filtroFecha
						$finAgrupacion,fecha,r.id
						
						union
						
						select  $inicioAgrupacionPropiosSinFaena
								0 as totalTransportado,
								GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0) as produccionMinima,
								0 as produccionReal,
								fecha,
								r.id
						from 	rCamionPropio as r,
								camionPropio as c,
								chofer as ch
						where	r.camionPropio_id = c.id and
								ch.id = r.chofer_id 
								$filtroFecha
								and NOT EXISTS
									(select	*
									 from	viajeCamionPropio as v
									 where	v.rCamionPropio_id = r.id)
						$finAgrupacion,fecha,r.id

						union

						select  $inicioAgrupacionPropios
								0 as totalTransportado,
								0 as produccionMinima,
								sum(e.total) as produccionReal,
								fecha,
								r.id
						from 	rCamionPropio as r
						join	expedicionportiempo as e on e.rcamionpropio_id = r.id
						join	camionPropio as c on r.camionPropio_id = c.id
						join	chofer as ch on ch.id = r.chofer_id
						join	faena as f on f.id = e.faena_id
						where	1 = 1 $filtroFecha
						$finAgrupacion,fecha,r.id
									
						) as tPropios
					$finAgrupacion
						
					union all
				
					select 	camion,
							chofer,
							centroGestion,
							sum(totalTransportado) as totalTransportado,
							sum(GREATEST(produccionMinima,produccionReal)) as produccion,
							sum(produccionReal) as produccionReal,
							sum(GREATEST(produccionMinima - produccionReal,0)) as diferencia,
							sum(GREATEST(produccionMinima,produccionReal) + GREATEST(produccionMinima - produccionReal,0)) as totalCobro
					from		
						(						
						select  $inicioAgrupacionArrendados
								sum(v.totalTransportado) as totalTransportado,
								IFNULL(GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0),0) as produccionMinima,
								sum(v.total) as produccionReal,
								fecha,
								r.id
						from 	rCamionArrendado as r,
								viajeCamionArrendado as v,
								camionArrendado as c,
								chofer as ch,
								faena as f
						where	v.rCamionArrendado_id = r.id and
								r.camionArrendado_id = c.id and
								ch.id = r.chofer_id and
								f.id = v.faena_id 
								$filtroFecha
						$finAgrupacion,fecha,r.id
						
						union
				
						select  $inicioAgrupacionArrendadosSinFaena
								0 as totalTransportado,
								GREATEST((1 - (r.minPanne/60)/c.horasMin)*c.produccionMinima,0) as produccionMinima,
								0 as produccionReal,
								fecha,
								r.id
						from 	rCamionArrendado as r,
								camionArrendado as c,
								chofer as ch
						where	r.camionArrendado_id = c.id and
								ch.id = r.chofer_id 
								$filtroFecha
								and NOT EXISTS
									(select	*
									 from	viajeCamionArrendado as v
									 where	v.rCamionArrendado_id = r.id)
						$finAgrupacion,fecha,r.id

						union

						select  $inicioAgrupacionArrendados
								0 as totalTransportado,
								0 as produccionMinima,
								sum(e.total) as produccionReal,
								fecha,
								r.id
						from 	rCamionArrendado as r
						join	expedicionportiempoarr as e on e.rcamionarrendado_id = r.id
						join	camionArrendado as c on r.camionArrendado_id = c.id
						join	chofer as ch on ch.id = r.chofer_id
						join	faena as f on f.id = e.faena_id
						where	1 = 1 $filtroFecha
						$finAgrupacion,fecha,r.id

									
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
			truncate informeProduccionCamiones;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}