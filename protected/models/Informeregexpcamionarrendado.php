<?php

/**
 * This is the model class for table "informeregexpcamionarrendado".
 *
 * The followings are the available columns in table 'informeregexpcamionarrendado':
 * @property integer $id
 * @property string $fecha
 * @property string $reporte
 * @property string $observaciones
 * @property string $camion
 * @property string $kmRecorridos
 * @property string $kmGps
 * @property string $combustible
 * @property integer $repuesto
 * @property string $produccionReal
 * @property string $horasPanne
 * @property integer $id_reg
 * @property string $panne
 * @property integer $validado
 * @property integer $validador_id
 */
class Informeregexpcamionarrendado extends CActiveRecord
{
    public $fechaInicio;
    public $fechaFin;
    public $camion_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'informeRegExpCamionArrendado';
	}

        public function getReg($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id_reg
			from		informeRegExpCamionArrendado
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
        
        public function generarInforme(){
            
		$this->limpiar();
	
                $connection=Yii::app()->db;
		$connection->active=true;
				
		$insertSql = "
		insert into informeRegExpCamionArrendado
			(fecha,reporte,observaciones,observaciones_obra,camion,kmRecorridos,kmGps,combustible,repuesto,produccionReal,horasPanne,panne,id_reg)
		";	

                $filtroReporte = "";
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
									rv.kmGps,
									rv.kmFinal,
									rv.kmInicial,
									sum(IFNULL(v.total,0)) + sum(IFNULL(e.total,0)) as produccionReal,
									rv.horasPanne,
									rv.panne,
									rv.id
							from	(						
									select 	r.fecha,
											r.reporte,
											r.observaciones,
                                                                                        r.observaciones_obra,
											c.nombre as nombreM,
											r.kmGps,
											r.kmFinal,
											r.kmInicial,
											r.minPanne/60 as horasPanne,
											IF(r.panne=1,'SÍ','NO') as panne,
											r.id as id
									from 	rCamionArrendado as r,
											camionArrendado as c
									where 	r.camionArrendado_id = c.id 
											$filtroCamion
											$filtroFecha
											$filtroReporte
									) as rv
							left join	viajeCamionArrendado as v on v.rCamionArrendado_id = rv.id
							left join	expedicionportiempoarr e on e.rcamionarrendado_id = rv.id
							group by rv.id
							) as tr
					left join 
							cargaCombCamionArrendado as cc
					on		cc.rCamionArrendado_id = tr.id
					group by tr.id
					) as tc	
			left join 
					compraRepuestoCamionArrendado as cr
			on		cr.rCamionArrendado_id = tc.id
			group by tc.id
		";
			
                
                
		$command=$connection->createCommand($insertSql.$sql);
		
                $fechInicio = Tools::fixFecha($this->fechaInicio);
                $fechFin = Tools::fixFecha($this->fechaFin);
		if($filtroFecha!=""){
			$command->bindParam(":fechaInicio",$fechInicio,PDO::PARAM_STR);
			$command->bindParam(":fechaFin",$fechFin,PDO::PARAM_STR);
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
        public function limpiar(){
            $connection=Yii::app()->db;
            $connection->active=true;
            $command=$connection->createCommand("
                    truncate informeRegExpCamionArrendado;
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
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, reporte, observaciones, camion, kmRecorridos, kmGps, combustible, repuesto, produccionReal, horasPanne, id_reg, panne', 'required'),
			array('repuesto, id_reg, validado, validador_id', 'numerical', 'integerOnly'=>true),
			array('reporte, kmRecorridos, kmGps, combustible, produccionReal, horasPanne', 'length', 'max'=>12),
			array('camion', 'length', 'max'=>100),
			array('panne', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fecha, fechaInicio,fechaFin,camion_id, reporte, observaciones,observaciones_obra, camion, kmRecorridos, kmGps, combustible, repuesto, produccionReal, horasPanne, id_reg, panne, validado, validador_id', 'safe', 'on'=>'search'),
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
                    'registro'=>array(self::BELONGS_TO, 'RCamionArrendado', 'id_reg'),
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
			'camion' => 'Camion',
			'kmRecorridos' => 'Km Recorridos',
			'kmGps' => 'Km Gps',
			'combustible' => 'Combustible',
			'repuesto' => 'Repuesto',
			'produccionReal' => 'Produccion Real',
			'horasPanne' => 'Horas Panne',
			'id_reg' => 'Id Reg',
			'panne' => 'Panne',
			'validado' => 'Validado',
			'validador_id' => 'Validador',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('reporte',$this->reporte,true);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('camion',$this->camion,true);
		$criteria->compare('kmRecorridos',$this->kmRecorridos,true);
		$criteria->compare('kmGps',$this->kmGps,true);
		$criteria->compare('combustible',$this->combustible,true);
		$criteria->compare('repuesto',$this->repuesto);
		$criteria->compare('produccionReal',$this->produccionReal,true);
		$criteria->compare('horasPanne',$this->horasPanne,true);
		$criteria->compare('id_reg',$this->id_reg);
		$criteria->compare('panne',$this->panne,true);
		$criteria->compare('validado',$this->validado);
		$criteria->compare('validador_id',$this->validador_id);

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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Informeregexpcamionarrendado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
