<?php

/**
 * This is the model class for table "faena".
 *
 * The followings are the available columns in table 'faena':
 * @property integer $id
 * @property string $nombre
 *
 * The followings are the available model relations:
 * @property Cargacombcamionarrendado[] $cargacombcamionarrendados
 * @property Cargacombcamionpropio[] $cargacombcamionpropios
 * @property Cargacombequipoarrendado[] $cargacombequipoarrendados
 * @property Cargacombequipopropio[] $cargacombequipopropios
 * @property OrigendestinoFaena[] $origendestinoFaenas
 * @property Requipoarrendado[] $requipoarrendados
 * @property Requipopropio[] $requipopropios
 * @property Viajecamionarrendado[] $viajecamionarrendados
 * @property Viajecamionpropio[] $viajecamionpropios
 */
class Faena extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Faena the static model class
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
		return 'faena';
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
			array('nombre', 'length', 'max'=>200),
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
			'cargacombcamionarrendados' => array(self::HAS_MANY, 'Cargacombcamionarrendado', 'faena_id'),
			'cargacombcamionpropios' => array(self::HAS_MANY, 'Cargacombcamionpropio', 'faena_id'),
			'cargacombequipoarrendados' => array(self::HAS_MANY, 'Cargacombequipoarrendado', 'faena_id'),
			'cargacombequipopropios' => array(self::HAS_MANY, 'Cargacombequipopropio', 'faena_id'),
			'origendestinoFaenas' => array(self::HAS_MANY, 'OrigendestinoFaena', 'faena_id'),
			'requipoarrendados' => array(self::HAS_MANY, 'Requipoarrendado', 'faena_id'),
			'requipopropios' => array(self::HAS_MANY, 'Requipopropio', 'faena_id'),
			'viajecamionarrendados' => array(self::HAS_MANY, 'Viajecamionarrendado', 'faena_id'),
			'viajecamionpropios' => array(self::HAS_MANY, 'Viajecamionpropio', 'faena_id'),
		);
	}

	public function getNombre($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		nombre
			from		faena
			where 		id = :id
			"
		);
		$command->bindParam(":id",$id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['nombre'];
		}
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
	
	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre
			from		faena
			where		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un faena",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=array('id'=>$row['id'],'nombre'=>$row['nombre']);
			$i++;
		}
		return $data;
	}

	public function listarODs($id){

		$ods = OrigendestinoFaena::model()->findAllByAttributes(array('faena_id'=>$id));
		return $ods;
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
		$criteria->compare('vigente',$this->vigente);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function borrarODs($id){
		$ods = OrigendestinoFaena::model()->findAllByAttributes(array('faena_id'=>$id));
		foreach($ods as $od){
			$od->delete();
		}
	}
}