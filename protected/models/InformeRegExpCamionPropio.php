<?php

/**
 * This is the model class for table "informeRegExpEquipoPropio".
 *
 * The followings are the available columns in table 'informeRegExpEquipoPropio':
 * @property integer $id
 * @property string $fecha
 * @property string $reporte
 * @property string $observaciones
 * @property string $equipo
 * @property string $codigo
 * @property string $horasReales
 * @property string $combustible
 * @property integer $repuesto
 * @property string $horasPanne
 */
class InformeRegExpCamionPropio extends CActiveRecord
{
	
	public $fechaInicio;
	public $fechaFin;
	public $camion_id;
	public $validador_nm;
		
	/**
	 * Returns the static model of the specified AR class.
	 * @return InformeRegExpEquipoPropio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public static function getImagenValidado($id){
		$report = RCamionPropio::model()->findByPk($id);
		if($report != null){
                    if($report->validado == 1)
                        return Yii::app()->request->baseUrl.'/images/ok.png';
                    else 
                        return Yii::app()->request->baseUrl.'/images/eliminar.png';
		}
	}
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'informeRegExpCamionPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, reporte, observaciones, camion, codigo, kmRecorridos, kmGps, combustible, repuesto, produccionReal, horasPanne', 'required'),
			array('repuesto', 'numerical', 'integerOnly'=>true),
			array('reporte, kmRecorridos, kmGps, produccionReal, combustible, horasPanne', 'length', 'max'=>12),
			array('codigo', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('reporte,camion_id,fechaInicio,fechaFin', 'safe', 'on'=>'search'),
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
                    'validador'=>array(self::BELONGS_TO, 'Usuario', 'validador_id'),
                    'registro'=>array(self::BELONGS_TO, 'RCamionPropio', 'id_reg'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fecha' => 'Fecha',
			'reporte' => 'Reporte',
			'observaciones' => 'Observaciones',
			'camion' => 'Camión',
			'codigo' => 'Código',
			'kmRecorridos' => 'KM Recorridos',
			'kmGps' => 'KM Gps',
			'combustible' => 'Combustible (Lt)',
			'repuesto' => 'Repuesto ($)',
			'produccionReal' => 'Producción Real',
			'horasPanne' => 'Horas Panne',
                    'validador_nm' => 'Validado Por',
                    'faena_id'=>'Faena',
                    'camion_id'=>'Camión',
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

                $thisF = Tools::fixFecha($this->fecha);
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('fecha',$thisF,true);
		$criteria->compare('reporte',$this->reporte,true);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('camion',$this->camion,true);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('kmRecorridos',$this->kmRecorridos,true);
		$criteria->compare('kmGps',$this->kmGps,true);
		$criteria->compare('combustible',$this->combustible,true);
		$criteria->compare('repuesto',$this->repuesto);
		$criteria->compare('horasPanne',$this->horasPanne,true);
		$criteria->compare('produccionReal',$this->produccionReal,true);
		$criteria->compare('panne',$this->panne);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                            'defaultOrder'=>'t.id DESC',
                            'attributes'=>array(
                                '*',
                            ),
                        ),
		));
	}
	
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
				
		$filtroReporte = "";
		$insertSql = "
		insert into informeRegExpCamionPropio
			(fecha,reporte,observaciones,observaciones_obra,camion,codigo,kmRecorridos,kmGps,combustible,repuesto,produccionReal,horasPanne,panne,id_reg)
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
				
		$filtroCamion = "";
		if(isset($this->camion_id)){
			if($this->camion_id != ""){
				$filtroCamion = "	
					and		c.id = :camion_id 
				";
			}
		}
                
		
		
		$sql = "
			select 	fecha,
					reporte,
					tc.observaciones,
                                        tc.observaciones_obra,
					nombreM,
					codigo,
					kmRecorridos,
					kmGps,
					combustible,
					IFNULL(sum(cr.montoNeto),0) as repuesto,
					produccionReal,
					horasPanne,
					panne,
					tc.id
			from	
					(		
					select 	fecha,
							reporte,
							tr.observaciones,
                                                        tr.observaciones_obra,
							nombreM,
							codigo,
							IFNULL(kmFinal - kmInicial,0) as kmRecorridos,
							kmGps,
							IFNULL(sum(cc.petroleoLts),0) as combustible,
							produccionReal,
							horasPanne,
							panne,
							tr.id as id
					from	(
							select	rv.fecha,
                                                                rv.reporte,
                                                                rv.observaciones,
                                                                rv.observaciones_obra,
                                                                rv.nombreM,
                                                                rv.codigo,
                                                                rv.kmGps,
                                                                rv.kmInicial,
                                                                rv.kmFinal,
                                                                IFNULL(sum(v.total),0) as produccionReal,
                                                                rv.horasPanne,
                                                                rv.panne,
                                                                rv.id
							from	(						
									select 	r.fecha,
											r.reporte,
											r.observaciones,
                                                                                        r.observaciones_obra,
											c.nombre as nombreM,
											c.codigo,
											r.kmGps,
											r.kmInicial,
											r.kmFinal,
											r.minPanne/60 as horasPanne,
											IF(r.panne = 1,'SÍ','NO') as panne,
											r.id as id
									from 	rCamionPropio as r,
											camionPropio as c
									where 	r.camionPropio_id = c.id 
											$filtroCamion
											$filtroFecha
											$filtroReporte
									) as rv
							left join	viajeCamionPropio as v
							on			v.rCamionPropio_id = rv.id
							group by rv.id
							) as tr
					left join 
							cargaCombCamionPropio as cc
					on		cc.rCamionPropio_id = tr.id
					group by tr.id
					) as tc	
			left join 
					compraRepuestoCamionPropio as cr
			on		cr.rCamionPropio_id = tc.id
			group by tc.id
		";
					
		$command=$connection->createCommand($insertSql.$sql);
		
		if($filtroFecha!=""){
			$f_inicio = Tools::fixFecha($this->fechaInicio);
			$f_fin = Tools::fixFecha($this->fechaFin);
			$command->bindParam(":fechaInicio",$f_inicio,PDO::PARAM_STR);
			$command->bindParam(":fechaFin",$f_fin,PDO::PARAM_STR);
		}
		
		if($filtroReporte!=""){
			$command->bindParam(":reporte",$this->reporte,PDO::PARAM_STR);
		}
		
		if($filtroCamion!=""){
			$command->bindParam(":camion_id",$this->camion_id,PDO::PARAM_STR);
		}
                
		$command->execute();
		
		$connection->active=false;
		$command = null;
		
		
	}
	public function getReg($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id_reg
			from		informeRegExpCamionPropio
			where 		id = :id
			"
		);
		$command->bindParam(":id",$id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['id_reg'];
		}
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informeRegExpCamionPropio;
			"
		);

		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fecha);   
	} 
	
	
}