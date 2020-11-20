<?php

/**
 * This is the model class for table "origen".
 *
 * The followings are the available columns in table 'origen':
 * @property integer $id
 * @property string $nombre
 *
 * The followings are the available model relations:
 * @property OrigendestinoFaena[] $origendestinoFaenas
 */
class Origen extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Origen the static model class
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
		return 'origen';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'required'),
			array('nombre','ext.MyValidators.NoBlanco'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre,vigente', 'safe', 'on'=>'search'),
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
			'origendestinoFaenas' => array(self::HAS_MANY, 'OrigendestinoFaena', 'origen_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('vigente',$this->vigente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listar($selected_id = null){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre
			from		origen
			where		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un origen",'id'=>'');
		$i=1;
		$selected_exists = false;
		foreach($rows as $row){
			$data[$i]=array('id'=>$row['id'],'nombre'=>$row['nombre']);
			$i++;
			if($row['id'] == $selected_id){
				$selected_exists = true;
			}
		}
		if(!$selected_exists && (int)$selected_id > 0){
			$origen = Origen::model()->findByPk($selected_id);
			$data[] = ['id'=>$selected_id,'nombre'=>$origen->nombre." (NO VIGENTE)"];
		}
		return $data;
	}
	
	public function listarNombres(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		nombre
			from		origen
                        where		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$i=0;
		foreach($rows as $row){
			$data[$i]=$row['nombre'];
			$i++;
		}
		return $data;
	}
}