<?php

/**
 * This is the model class for table "flujos_programados".
 *
 * The followings are the available columns in table 'flujos_programados':
 * @property integer $id
 * @property integer $produccion
 * @property integer $costo
 * @property integer $mes
 * @property integer $agno
 * @property string $comentarios
 * @property integer $resoluciones_id
 *
 * The followings are the available model relations:
 * @property Resoluciones $resoluciones
 */
class FlujosProgramados extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FlujosProgramados the static model class
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
		return 'flujos_programados';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mes, agno, resoluciones_id', 'required'),
			array('mes, agno, resoluciones_id', 'numerical', 'integerOnly'=>true),
			array('produccion, costo', 'length', 'max'=>14),
			array('comentarios', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, produccion, costo, mes, agno, comentarios, resoluciones_id', 'safe', 'on'=>'search'),
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
			'resoluciones' => array(self::BELONGS_TO, 'Resoluciones', 'resoluciones_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'produccion' => 'Producción Programada Neta',
			'costo' => 'Costo Programado Neto',
			'mes' => 'Mes',
			'agno' => 'Año',
			'comentarios' => 'Comentarios',
			'resoluciones_id' => 'Resoluciones',
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
		$criteria->compare('produccion',$this->produccion);
		$criteria->compare('costo',$this->costo);
		$criteria->compare('mes',$this->mes);
		$criteria->compare('agno',$this->agno);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('resoluciones_id',$this->resoluciones_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getEPs(){
		//EPs asociados al flujo programado 
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$eps = array();
		$existen_ep = false;
		
		//primero saco los eps de obra
		$sql = "
			select 	*
			from	ep_obra as fr
			where	fr.resoluciones_id = :resoluciones_id and
					fr.mes = :mes and
					fr.agno = :agno 
		";		
		
		$command=$connection->createCommand($sql);
		
		$res = $this->resoluciones_id;
		$agno = $this->agno;
		$mes = $this->mes;
		$command->bindParam(":resoluciones_id",$res,PDO::PARAM_INT);
		$command->bindParam(":mes",$mes,PDO::PARAM_INT);
		$command->bindParam(":agno",$agno,PDO::PARAM_INT);
		$results=$command->queryAll(); 
		
		$eps_obra = array();
		foreach($results AS $result){
			$ep_obra = new EpObra();
		   	$ep_obra->agno = $result['agno'];
		   	$ep_obra->comentarios = $result['comentarios'];
		   	$ep_obra->costo = $result['costo'];
		   	$ep_obra->descuento = $result['descuento'];
		   	$ep_obra->id = $result['id'];
		   	$ep_obra->mes = $result['mes'];
		   	$ep_obra->produccion = $result['produccion'];
		   	$ep_obra->reajuste = $result['reajuste'];
		   	$ep_obra->resoluciones_id = $result['resoluciones_id'];
			$ep_obra->retencion = $result['retencion'];
			$eps_obra[] = $ep_obra;
			$existen_ep = true;
		}
		
		//Ahora saco los eps de anticipo
		$sql = "
			select 	*
			from	ep_anticipo as fr
			where	fr.resoluciones_id = :resoluciones_id and
					fr.mes = :mes and
					fr.agno = :agno 
		";		
		
		$command=$connection->createCommand($sql);
		
		$res = $this->resoluciones_id;
		$agno = $this->agno;
		$mes = $this->mes;
		$command->bindParam(":resoluciones_id",$res,PDO::PARAM_INT);
		$command->bindParam(":mes",$mes,PDO::PARAM_INT);
		$command->bindParam(":agno",$agno,PDO::PARAM_INT);
		$results=$command->queryAll(); 
		
		$eps_anticipo = array();
		foreach($results AS $result){
			$ep_anticipo = new EpAnticipo();
		   	$ep_anticipo->agno = $result['agno'];
		   	$ep_anticipo->comentarios = $result['comentarios'];
		   	$ep_anticipo->valor = $result['valor'];
		   	$ep_anticipo->id = $result['id'];
		   	$ep_anticipo->mes = $result['mes'];
		   	$ep_anticipo->resoluciones_id = $result['resoluciones_id'];
			$eps_anticipo[] = $ep_anticipo;
			$existen_ep = true;
		}
		
		//Ahora saco los eps de canje de retenciones
		$sql = "
			select 	*
			from	ep_canje_retencion as fr
			where	fr.resoluciones_id = :resoluciones_id and
					fr.mes = :mes and
					fr.agno = :agno 
		";		
		
		$command=$connection->createCommand($sql);
		
		$res = $this->resoluciones_id;
		$agno = $this->agno;
		$mes = $this->mes;
		$command->bindParam(":resoluciones_id",$res,PDO::PARAM_INT);
		$command->bindParam(":mes",$mes,PDO::PARAM_INT);
		$command->bindParam(":agno",$agno,PDO::PARAM_INT);
		$results=$command->queryAll(); 
		
		$eps_canje = array();
		foreach($results AS $result){
			$ep_canje = new EpAnticipo();
		   	$ep_canje->agno = $result['agno'];
		   	$ep_canje->comentarios = $result['comentarios'];
		   	$ep_canje->valor = $result['valor'];
		   	$ep_canje->id = $result['id'];
		   	$ep_canje->mes = $result['mes'];
		   	$ep_canje->resoluciones_id = $result['resoluciones_id'];
			$eps_canje[] = $ep_canje;
			$existen_ep = true;
		}
		
		$connection->active=false;
		$command = null;
		
		$eps[] = array('tipo'=>'obra','eps'=>$eps_obra);
		$eps[] = array('tipo'=>'anticipo','eps'=>$eps_anticipo);
		$eps[] = array('tipo'=>'canje_retencion','eps'=>$eps_canje);
		
		if(!$existen_ep){
			return null;
		}else{
			return $eps;
		}
	}
	
	/*
	public function getFlujoReal(){
		//flujo real asociado al flujo programado 
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$sql = "
			select 	*
			from	flujos_reales as fr
			where	fr.resoluciones_id = :resoluciones_id and
					fr.mes = :mes and
					fr.agno = :agno 
		";		
		
		$command=$connection->createCommand($sql);
		
		$res = $this->resoluciones_id;
		$agno = $this->agno;
		$mes = $this->mes;
		$command->bindParam(":resoluciones_id",$res,PDO::PARAM_INT);
		$command->bindParam(":mes",$mes,PDO::PARAM_INT);
		$command->bindParam(":agno",$agno,PDO::PARAM_INT);
		$results=$command->queryAll(); 
		
		$flujo = null;
		foreach($results AS $result){
			$flujo = new FlujosReales();
		   	$flujo->agno = $result['agno'];
		   	$flujo->comentarios = $result['comentarios'];
		   	$flujo->costo = $result['costo'];
		   	$flujo->descuento = $result['descuento'];
		   	$flujo->id = $result['id'];
		   	$flujo->mes = $result['mes'];
		   	$flujo->produccion = $result['produccion'];
		   	$flujo->reajuste = $result['reajuste'];
		   	$flujo->resoluciones_id = $result['resoluciones_id'];
			$flujo->retencion = $result['retencion'];
			$flujo->anticipo = $result['anticipo'];
			break;
		}
		
		$connection->active=false;
		$command = null;
		return $flujo;
	}
	*/
	
}