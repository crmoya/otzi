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
class InformeChofer extends CActiveRecord
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
		return 'informeChofer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// Please remove those attributes that should not be searched.
			array('id, camion, produccionDia, gastoCombustible,diferencia,chofer', 'safe', 'on'=>'search'),
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
			'propiosOArrendados' => 'Mostar camiones, camionetas, autos',
			'produccionDia' => 'Producción Real',
			'produccionMinima' => 'Producción Contratada',
			'chofer' => 'Chofer',
			'agruparPor' => 'Agrupar Por Chofer',
			'gastoCombustible' => 'Gasto Combustible en $',
			'diferencia' => 'Producción para Trato',
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
		$criteria->compare('produccionDia',$this->produccionDia,true);
		$criteria->compare('gastoCombustible',$this->gastoCombustible,true);
		$criteria->compare('diferencia',$this->diferencia,true);
		$criteria->compare('chofer',$this->chofer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$insertSql = "
		insert into informeChofer
			(chofer,camion,produccionDia,produccionMinima,coeficienteCombustible,gastoCombustible,diferencia)
			
		";	

		$finAgrupacion = "group by chofer,camion";
		$inicioAgrupacionPropios = "
			concat(o.rut,' / ',o.nombre) as chofer,
			concat(m.codigo,' / ',m.nombre) as camion,";
		
		$inicioAgrupacionArrendados = "
			concat(o.rut,' / ',o.nombre) as chofer,
			m.nombre as camion,";
		
		$inicioAgrupacionTodos = "
			chofer,
			camion,";
		
		if($this->agruparPor == 1){
			$finAgrupacion = "group by chofer";
			$inicioAgrupacionPropios = "	
				concat(o.rut,' / ',o.nombre) as chofer,
				'' as camion,";
			$inicioAgrupacionArrendados = "	
				concat(o.rut,' / ',o.nombre) as chofer,
				'' as camion,";
			$inicioAgrupacionTodos = "	
				concat(o.rut,' / ',o.nombre) as chofer,
				'' as camion,";
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
						
			select 	tTodo.chofer as chofer,
					tTodo.camion as camion,
					sum(tTodo.produccionReal) as produccionDia,
					sum(tTodo.produccionContratada) as produccionMinima,
					avg(tTodo.coeficienteDeTrato) as coeficienteCombustible,
					sum(tTodo.gastoCombustible) as gastoCombustible,
					sum(tTodo.produccionContratada) - avg(tTodo.coeficienteDeTrato)/100*sum(tTodo.gastoCombustible) as diferencia
			from
					(
			
					select  tDiario.chofer,
							tDiario.camion,
							tDiario.fecha,
							GREATEST(IFNULL(GREATEST((1 - (sum(tDiario.minPanne)/60)/tDiario.horasMin)*tDiario.produccionMinima,0),0),sum(tDiario.produccionReal)) as produccionContratada,
							tDiario.coeficienteDeTrato as coeficienteDeTrato,
							sum(tDiario.produccionReal) as produccionReal,
							sum(tDiario.gastoCombustible) as gastoCombustible			
					from
							(
							select 	tr.chofer,
									tr.camion,
									tr.fecha,
									tr.id,
									tr.minPanne,
									tr.horasMin,
									tr.produccionMinima,
									tr.coeficienteDeTrato,
									tr.produccionReal,
									IFNULL(sum(c.valorTotal),0) as gastoCombustible			
							from 
									(			
									select	tReg.chofer as chofer,
											tReg.camion as camion,
											tReg.fecha,
											tReg.id as id,
											tReg.minPanne as minPanne,
											tReg.horasMin as horasMin,
											tReg.produccionMinima as produccionMinima,
											tReg.coeficienteDeTrato as coeficienteDeTrato,
											IFNULL(sum(v.total),0) as produccionReal
									from (
											select  $inicioAgrupacionPropios
													fecha,
													r.id as id,
													r.minPanne,
													m.horasMin,
													m.produccionMinima,
													m.coeficienteDeTrato
											from 	rCamionPropio as r,
													camionPropio as m,
													chofer as o
											where	m.id = r.camionPropio_id and
													o.id = r.chofer_id 
													$filtroFecha
											) as tReg
									left join 	viajeCamionPropio as v
									on			v.rCamionPropio_id = tReg.id
									group by 	id
									) as tr
							left join	cargaCombCamionPropio as c
							on			c.rCamionPropio_id = tr.id 
							group by 	id
						) as tDiario
					group by fecha,camion,chofer
				) as tTodo
			$finAgrupacion
			";
		}
		elseif($this->propiosOArrendados == 'ARRENDADOS'){
			$sql = "
			select 	tTodo.chofer as chofer,
					tTodo.camion as camion,
					sum(tTodo.produccionReal) as produccionDia,
					sum(tTodo.produccionContratada) as produccionMinima,
					avg(tTodo.coeficienteDeTrato) as coeficienteCombustible,
					sum(tTodo.gastoCombustible) as gastoCombustible,
					sum(tTodo.produccionContratada) - avg(tTodo.coeficienteDeTrato)/100*sum(tTodo.gastoCombustible) as diferencia
			from
					(
			
					select  tDiario.chofer,
							tDiario.camion,
							tDiario.fecha,
							GREATEST(IFNULL(GREATEST((1 - (sum(tDiario.minPanne)/60)/tDiario.horasMin)*tDiario.produccionMinima,0),0),sum(tDiario.produccionReal)) as produccionContratada,
							tDiario.coeficienteDeTrato as coeficienteDeTrato,
							sum(tDiario.produccionReal) as produccionReal,
							sum(tDiario.gastoCombustible) as gastoCombustible			
					from
							(
							select 	tr.chofer,
									tr.camion,
									tr.fecha,
									tr.id,
									tr.minPanne,
									tr.horasMin,
									tr.produccionMinima,
									tr.coeficienteDeTrato,
									tr.produccionReal,
									IFNULL(sum(c.valorTotal),0) as gastoCombustible			
							from 
									(			
									select	tReg.chofer as chofer,
											tReg.camion as camion,
											tReg.fecha,
											tReg.id as id,
											tReg.minPanne as minPanne,
											tReg.horasMin as horasMin,
											tReg.produccionMinima as produccionMinima,
											tReg.coeficienteDeTrato as coeficienteDeTrato,
											IFNULL(sum(v.total),0) as produccionReal
									from (
											select  $inicioAgrupacionArrendados
													fecha,
													r.id as id,
													r.minPanne,
													m.horasMin,
													m.produccionMinima,
													m.coeficienteDeTrato
											from 	rCamionArrendado as r,
													camionArrendado as m,
													chofer as o
											where	m.id = r.camionArrendado_id and
													o.id = r.chofer_id 
													$filtroFecha
											) as tReg
									left join 	viajeCamionArrendado as v
									on			v.rCamionArrendado_id = tReg.id
									group by 	id
									) as tr
							left join	cargaCombCamionArrendado as c
							on			c.rCamionArrendado_id = tr.id 
							group by 	id
						) as tDiario
					group by fecha,camion,chofer
				) as tTodo
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	chofer,
					camion,
					sum(produccionDia) as produccionDia,
					sum(produccionMinima) as produccionMinima,
					avg(coeficienteCombustible) as coeficienteCombustible,
					sum(gastoCombustible) as gastoCombustible,
					sum(diferencia) as diferencia
			from
				(
				
					select 	tTodo.chofer as chofer,
							tTodo.camion as camion,
							sum(tTodo.produccionReal) as produccionDia,
							sum(tTodo.produccionContratada) as produccionMinima,
							avg(tTodo.coeficienteDeTrato) as coeficienteCombustible,
							sum(tTodo.gastoCombustible) as gastoCombustible,
							sum(tTodo.produccionContratada) - avg(tTodo.coeficienteDeTrato)/100*sum(tTodo.gastoCombustible) as diferencia
					from
							(
					
							select  tDiario.chofer as chofer,
									tDiario.camion as camion,
									tDiario.fecha as fecha,
									GREATEST(IFNULL(GREATEST((1 - (sum(tDiario.minPanne)/60)/tDiario.horasMin)*tDiario.produccionMinima,0),0),sum(tDiario.produccionReal)) as produccionContratada,
									tDiario.coeficienteDeTrato as coeficienteDeTrato,
									sum(tDiario.produccionReal) as produccionReal,
									sum(tDiario.gastoCombustible) as gastoCombustible			
							from
									(
									select 	tr.chofer as chofer,
											tr.camion as camion,
											tr.fecha as fecha, 
											tr.id as id,
											tr.minPanne as minPanne,
											tr.horasMin as horasMin,
											tr.produccionMinima as produccionMinima,
											tr.coeficienteDeTrato as coeficienteDeTrato,
											tr.produccionReal as produccionReal,
											IFNULL(sum(c.valorTotal),0) as gastoCombustible			
									from 
											(			
											select	tReg.chofer as chofer,
													tReg.camion as camion,
													tReg.fecha as fecha,
													tReg.id as id,
													tReg.minPanne as minPanne,
													tReg.horasMin as horasMin,
													tReg.produccionMinima as produccionMinima,
													tReg.coeficienteDeTrato as coeficienteDeTrato,
													IFNULL(sum(v.total),0) as produccionReal
											from (
													select  $inicioAgrupacionPropios
															fecha as fecha,
															r.id as id,
															r.minPanne as minPanne,
															m.horasMin as horasMin,
															m.produccionMinima as produccionMinima,
															m.coeficienteDeTrato as coeficienteDeTrato
													from 	rCamionPropio as r,
															camionPropio as m,
															chofer as o
													where	m.id = r.camionPropio_id and
															o.id = r.chofer_id 
															$filtroFecha
													) as tReg
											left join 	viajeCamionPropio as v
											on			v.rCamionPropio_id = tReg.id
											group by 	id
											) as tr
									left join	cargaCombCamionPropio as c
									on			c.rCamionPropio_id = tr.id 
									group by 	id
								) as tDiario
							group by fecha,camion,chofer
						) as tTodo
					$finAgrupacion
				
					
					union all
					
					
					select 	tTodo.chofer as chofer,
							tTodo.camion as camion,
							sum(tTodo.produccionReal) as produccionDia,
							sum(tTodo.produccionContratada) as produccionMinima,
							avg(tTodo.coeficienteDeTrato) as coeficienteCombustible,
							sum(tTodo.gastoCombustible) as gastoCombustible,
							sum(tTodo.produccionContratada) - avg(tTodo.coeficienteDeTrato)/100*sum(tTodo.gastoCombustible) as diferencia
					from
							(
					
							select  tDiario.chofer,
									tDiario.camion,
									tDiario.fecha,
									GREATEST(IFNULL(GREATEST((1 - (sum(tDiario.minPanne)/60)/tDiario.horasMin)*tDiario.produccionMinima,0),0),sum(tDiario.produccionReal)) as produccionContratada,
									tDiario.coeficienteDeTrato as coeficienteDeTrato,
									sum(tDiario.produccionReal) as produccionReal,
									sum(tDiario.gastoCombustible) as gastoCombustible			
							from
									(
									select 	tr.chofer,
											tr.camion,
											tr.fecha,
											tr.id,
											tr.minPanne,
											tr.horasMin,
											tr.produccionMinima,
											tr.coeficienteDeTrato,
											tr.produccionReal,
											IFNULL(sum(c.valorTotal),0) as gastoCombustible			
									from 
											(			
											select	tReg.chofer as chofer,
													tReg.camion as camion,
													tReg.fecha,
													tReg.id as id,
													tReg.minPanne as minPanne,
													tReg.horasMin as horasMin,
													tReg.produccionMinima as produccionMinima,
													tReg.coeficienteDeTrato as coeficienteDeTrato,
													IFNULL(sum(v.total),0) as produccionReal
											from (
													select  $inicioAgrupacionArrendados
															fecha,
															r.id as id,
															r.minPanne,
															m.horasMin,
															m.produccionMinima,
															m.coeficienteDeTrato
													from 	rCamionArrendado as r,
															camionArrendado as m,
															chofer as o
													where	m.id = r.camionArrendado_id and
															o.id = r.chofer_id 
															$filtroFecha
													) as tReg
											left join 	viajeCamionArrendado as v
											on			v.rCamionArrendado_id = tReg.id
											group by 	id
											) as tr
									left join	cargaCombCamionArrendado as c
									on			c.rCamionArrendado_id = tr.id 
									group by 	id
								) as tDiario
							group by fecha,camion,chofer
						) as tTodo
					$finAgrupacion
				
				
				) as tCamiones
				
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
			truncate informeChofer;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}