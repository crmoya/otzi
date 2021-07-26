<?php

/**
 * This is the model class for table "origendestino_faena".
 *
 * The followings are the available columns in table 'origendestino_faena':
 * @property integer $origen_id
 * @property integer $destino_id
 * @property integer $faena_id
 * @property string $pu
 * @property integer $id
 *
 * The followings are the available model relations:
 * @property Origen $origen
 * @property Destino $destino
 * @property Faena $faena
 */
class OrigendestinoFaena extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return OrigendestinoFaena the static model class
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
		return 'origenDestino_faena';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('origen_id, destino_id, faena_id, pu', 'required'),
			array('origen_id, destino_id, faena_id', 'numerical', 'integerOnly'=>true),
			array('pu', 'length', 'max'=>10),
			array('pu','esDecimal'),
			array('kmRecorridos', 'length', 'max'=>10),
			array('kmRecorridos', 'esDecimal'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('origen_id, destino_id, faena_id, pu, id', 'safe', 'on'=>'search'),
		);
	}
	
	public function getNombre($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		d.nombre as destino, o.nombre as origen
			from		origenDestino_faena as od,
						destino as d,
						origen as o
			where 		od.id = :id and
						d.id = od.destino_id and
						o.id = od.origen_id 
			"
		);
		$command->bindParam(":id",$id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['origen']." / ".$row['destino'];
		}
	}
	
	public function listarNombre($faena_id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		d.nombre as destino, o.nombre as origen,od.id as id
			from		origenDestino_faena as od,
						destino as d,
						origen as o
			where 		d.id = od.destino_id and
						o.id = od.origen_id and
						:faena = od.faena_id and
                                         d.vigente = 'SÍ' and
                                         o.vigente = 'SÍ'
			order by 	o.nombre,d.nombre
			"
		);
		$command->bindParam(":faena",$faena_id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$ret = array();
		$ret[0]=array('id'=>'-1','nombre'=>'Seleccione un Origen-Destino');
		$i = 1;
		
		foreach($rows as $row){
			$ret[$i]=array('id'=>$row['id'],'nombre'=>$row['origen']." -> ".$row['destino']);
			$i++;
		}
		return $ret;
	}
	
	public function esDecimal($attribute,$params)
	{
		if($this->$attribute=="")return;
		$numero = str_replace(",", ".", $this->$attribute);
		if(!is_numeric($numero))
			$this->addError($attribute, 'Debe ser número');
		if(strlen($numero."") > 11 )
			$this->addError($attribute, 'Número muy largo');
		if($numero<0){
			$this->addError($attribute, 'Debe ser mayor que 0');
		}
		if(strpos($numero."",".")<strlen($numero."")-3 && strpos($numero."",".")>0)
			$this->addError($attribute, 'No debe tener más de 2 decimales');
		
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'origen' => array(self::BELONGS_TO, 'Origen', 'origen_id'),
			'destino' => array(self::BELONGS_TO, 'Destino', 'destino_id'),
			'faena' => array(self::BELONGS_TO, 'Faena', 'faena_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'origen_id' => 'Origen',
			'destino_id' => 'Destino',
			'faena_id' => 'Faena',
			'pu' => 'Pu',
			'id' => 'ID',
			'kmRecorridos' => 'Kms Recorridos'
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

		$criteria->compare('origen_id',$this->origen_id);
		$criteria->compare('destino_id',$this->destino_id);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('pu',$this->pu,true);
		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listar($idFaena){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		o.nombre as origen,
						d.nombre as destino,
						od.pu as pu,
						od.kmRecorridos as km,
			from		origenDestino_faena as od,
						origen as o,
						destino as d
			where		od.faena_id = :faena and
						od.destino_id = d.id and
						od.origen_id = o.id and
						d.vigente = 'SÍ' and
						o.vigente = 'SÍ'
			"
		);
		$command->bindParam(":faena",$idFaena,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$i=0;
		foreach($rows as $row){
			$data[$i]=$row;
			$i++;
		}
		return $data;
	}
	
	public function getODs(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		o.nombre as origen,
						d.nombre as destino,
						od.pu as pu,
						od.kmRecorridos as km,
						od.faena_id as faena,
						od.id as id
			from		origenDestino_faena as od,
						origen as o,
						destino as d
			where		od.destino_id = d.id and
						od.origen_id = o.id 
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$i=0;
		foreach($rows as $row){
			$data[$i]=array('faena'=>$row['faena'],'pu'=>$row['pu'],'km'=>$row['km'],'nombre'=>$row['origen']." -> ".$row['destino'],'id'=>$row['id'],);
			$i++;
		}
		return $data;
	}
	
	public function getDestino(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		d.nombre as destino
			from		origenDestino_faena as od,
						destino as d
			where		od.id = :od and
						od.destino_id = d.id 
			"
		);
		$command->bindParam(":od",$this->id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['destino'];
		}		
	}
	
	public function getOrigen(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		o.nombre as origen
			from		origenDestino_faena as od,
						origen as o
			where		od.id = :od and
						od.origen_id = o.id 
			"
		);
		$command->bindParam(":od",$this->id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['origen'];
		}		
	}
}