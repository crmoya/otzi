<?php

/**
 * This is the model class for table "supervisorCombustible".
 *
 * The followings are the available columns in table 'supervisorCombustible':
 * @property integer $id
 * @property string $nombre
 */
class SupervisorCombustible extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SupervisorCombustible the static model class
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
		return 'supervisorCombustible';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre,rut', 'required'),
			array('nombre', 'length', 'max'=>200),
			array('rut', 'length', 'max'=>15),
			array('rut', 'esRut'),
			array('rut','unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre,rut,vigente', 'safe', 'on'=>'search'),
		);
	}


	public function esRut($attribute,$params)
	{
		if(!Tools::validaRut($this->$attribute))
			$this->addError($attribute, 'Rut no válido');
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
			'nombre' => 'Nombre',
			'rut'=>'Rut',
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
		$criteria->compare('rut',$this->rut,true);
		$criteria->compare('vigente',$this->vigente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getNombre($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		nombre
			from		supervisorCombustible
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
	
	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select 	id,nombre,rut
			from 	supervisorCombustible
			where		vigente = 'SÍ'
			order by nombre
			
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un supervisor",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=array('id'=>$row['id'],'nombre'=>$row['nombre'].', '.$row['rut']);
			$i++;
		}
		return $data;
	}
}