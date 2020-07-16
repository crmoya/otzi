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
class InformeOperario extends CActiveRecord
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
		return 'informeOperario';
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
			array('id, maquina, consumoPromedio, horas,valorHora,total,operario', 'safe', 'on'=>'search'),
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
			'consumoPromedio' => 'Consumo [$]',
			'propiosOArrendados' => 'Mostar Equipos',
			'agruparPor' => 'Agrupar Por Operador',
			'operario' => 'Operador',
			'horas' => 'Horas Físicas',
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
		$criteria->compare('consumoPromedio',$this->consumoPromedio,true);
		$criteria->compare('horas',$this->horas,true);
		$criteria->compare('valorHora',$this->valorHora,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('operario',$this->operario,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generarInforme(){
		
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$finAgrupacion = "group by operario,maquina";
		$inicioAgrupacionPropios = "
			concat(o.rut,' / ',o.nombre) as operario,
			concat(m.codigo,' / ',m.nombre) as maquina,";
		
		$inicioAgrupacionArrendados = "
			concat(o.rut,' / ',o.nombre) as operario,
			m.nombre as maquina,";
		
		$inicioAgrupacionTodos = "
			operario,
			maquina,";
		
		if($this->agruparPor == 1){
			$finAgrupacion = "group by operario";
			$inicioAgrupacionPropios = "	
				concat(o.rut,' / ',o.nombre) as operario,
				'' as maquina,";
			$inicioAgrupacionArrendados = "	
				concat(o.rut,' / ',o.nombre) as operario,
				'' as maquina,";
			$inicioAgrupacionTodos = "	
				operario,
				'' as maquina,";
		}
		
		$insertSql = "
		insert into informeOperario
			(operario,maquina,consumoPromedio,horas,horasContratadas,valorHora,total,coeficiente)
			
		";		
		
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
			
			select 	operario,
					maquina,
					sum(consumoPromedio) as consumoPromedio,
					sum(horasReales) as horas,
					sum(horasContratadas) as horasContratadas,
					avg(valorHora) as valorHora,
					sum(horasContratadas)*avg(valorHora) - IFNULL(sum(consumoPromedio),0)*avg(coeficienteDeTrato) as total,
					avg(coeficienteDeTrato) as coeficiente	
			from	(			
					select	operario,
							maquina,
							fecha,
							sum(horasReales) as horasReales,
							GREATEST(sum(horasReales),GREATEST(horasMin - (sum(minPanne)/60),0)) as horasContratadas,
							valorHora,
							coeficienteDeTrato,
							tDiario.id as id,
							consumoPromedio
					from 	(
							select 	operario,
									maquina,
									fecha,
									horasReales,
									horasMin,
									minPanne,
									valorHora,
									coeficienteDeTrato,
									tr.id as id,
									IFNULL(sum(c.valorTotal),0) as consumoPromedio
							from	(
									select 	$inicioAgrupacionPropios
											fecha,
											r.horas as horasReales,
											m.horasMin,
											r.minPanne as minPanne,
											m.valorHora as valorHora,
											m.coeficienteDeTrato as coeficienteDeTrato,
											r.id as id
									from 	rEquipoPropio as r,
											equipoPropio as m,
											operador as o
									where	m.id = r.equipoPropio_id and
											o.id = r.operador_id 
											$filtroFecha
									) as tr
							left join	
									cargaCombEquipoPropio as c	
							on		c.rEquipoPropio_id = tr.id 
							group by tr.id
							) as tDiario
					group by operario,maquina,fecha
					) as tTodo
			$finAgrupacion
			";
			
		}
		elseif($this->propiosOArrendados == 'ARRENDADOS'){
			$sql = "
			select 	operario,
					maquina,
					sum(consumoPromedio) as consumoPromedio,
					sum(horasReales) as horas,
					sum(horasContratadas) as horasContratadas,
					avg(valorHora) as valorHora,
					sum(horasContratadas)*avg(valorHora) - IFNULL(sum(consumoPromedio),0)*avg(coeficienteDeTrato) as total,
					avg(coeficienteDeTrato) as coeficiente	
			from	(			
					select	operario,
							maquina,
							fecha,
							sum(horasReales) as horasReales,
							GREATEST(sum(horasReales),GREATEST(horasMin - (sum(minPanne)/60),0)) as horasContratadas,
							valorHora,
							coeficienteDeTrato,
							tDiario.id as id,
							consumoPromedio
					from 	(
							select 	operario,
									maquina,
									fecha,
									horasReales,
									horasMin,
									minPanne,
									valorHora,
									coeficienteDeTrato,
									tr.id as id,
									IFNULL(sum(c.valorTotal),0) as consumoPromedio
							from	(
									select 	$inicioAgrupacionArrendados
											fecha,
											r.horas as horasReales,
											m.horasMin,
											r.minPanne as minPanne,
											m.valorHora as valorHora,
											m.coeficienteDeTrato as coeficienteDeTrato,
											r.id as id
									from 	rEquipoArrendado as r,
											equipoArrendado as m,
											operador as o
									where	m.id = r.equipoArrendado_id and
											o.id = r.operador_id 
											$filtroFecha
									) as tr
							left join	
									cargaCombEquipoArrendado as c	
							on		c.rEquipoArrendado_id = tr.id 
							group by tr.id
							) as tDiario
					group by operario,maquina,fecha
					) as tTodo
			$finAgrupacion
			";
		}
		else{
			$sql = "
			select 	operario,
					maquina,
					sum(consumoPromedio) as consumoPromedio,
					sum(horas) as horas,
					sum(horasContratadas) as horasContratadas,
					avg(valorHora),
					sum(total) as total,
					avg(coeficiente)
			from
				(
				
				select 	operario,
						maquina,
						sum(consumoPromedio) as consumoPromedio,
						sum(horasReales) as horas,
						sum(horasContratadas) as horasContratadas,
						avg(valorHora) as valorHora,
						sum(horasContratadas)*avg(valorHora) - IFNULL(sum(consumoPromedio),0)*avg(coeficienteDeTrato) as total,
						avg(coeficienteDeTrato) as coeficiente	
				from	(			
						select	operario,
								maquina,
								fecha,
								sum(horasReales) as horasReales,
								GREATEST(sum(horasReales),GREATEST(horasMin - (sum(minPanne)/60),0)) as horasContratadas,
								valorHora,
								coeficienteDeTrato,
								tDiario.id as id,
								consumoPromedio
						from 	(
								select 	operario,
										maquina,
										fecha,
										horasReales,
										horasMin,
										minPanne,
										valorHora,
										coeficienteDeTrato,
										tr.id as id,
										IFNULL(sum(c.valorTotal),0) as consumoPromedio
								from	(
										select 	$inicioAgrupacionPropios
												fecha,
												r.horas as horasReales,
												m.horasMin,
												r.minPanne as minPanne,
												m.valorHora as valorHora,
												m.coeficienteDeTrato as coeficienteDeTrato,
												r.id as id
										from 	rEquipoPropio as r,
												equipoPropio as m,
												operador as o
										where	m.id = r.equipoPropio_id and
												o.id = r.operador_id 
												$filtroFecha
										) as tr
								left join	
										cargaCombEquipoPropio as c	
								on		c.rEquipoPropio_id = tr.id 
								group by tr.id
								) as tDiario
						group by operario,maquina,fecha
						) as tPropios
				$finAgrupacion
				
				union all
				
				select 	operario,
						maquina,
						sum(consumoPromedio) as consumoPromedio,
						sum(horasReales) as horas,
						sum(horasContratadas) as horasContratadas,
						avg(valorHora) as valorHora,
						sum(horasContratadas)*avg(valorHora) - IFNULL(sum(consumoPromedio),0)*avg(coeficienteDeTrato) as total,
						avg(coeficienteDeTrato) as coeficiente	
				from	(			
						select	operario,
								maquina,
								fecha,
								sum(horasReales) as horasReales,
								GREATEST(sum(horasReales),GREATEST(horasMin - (sum(minPanne)/60),0)) as horasContratadas,
								valorHora,
								coeficienteDeTrato,
								tDiario.id as id,
								consumoPromedio
						from 	(
								select 	operario,
										maquina,
										fecha,
										horasReales,
										horasMin,
										minPanne,
										valorHora,
										coeficienteDeTrato,
										tr.id as id,
										IFNULL(sum(c.valorTotal),0) as consumoPromedio
								from	(
										select 	$inicioAgrupacionArrendados
												fecha,
												r.horas as horasReales,
												m.horasMin,
												r.minPanne as minPanne,
												m.valorHora as valorHora,
												m.coeficienteDeTrato as coeficienteDeTrato,
												r.id as id
										from 	rEquipoArrendado as r,
												equipoArrendado as m,
												operador as o
										where	m.id = r.equipoArrendado_id and
												o.id = r.operador_id 
												$filtroFecha
										) as tr
								left join	
										cargaCombEquipoArrendado as c	
								on		c.rEquipoArrendado_id = tr.id 
								group by tr.id
								) as tDiario
						group by operario,maquina,fecha
						) as tArrendados
				$finAgrupacion
				
				) as tTodo
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
			truncate informeOperario;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
}