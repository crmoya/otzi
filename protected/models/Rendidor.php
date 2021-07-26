<?php

/**
 * This is the model class for table "rendidor".
 *
 * The followings are the available columns in table 'rendidor':
 * @property integer $id
 * @property string $nombre
 */
class Rendidor extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Rendidor the static model class
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
		return 'rendidor';
	}

	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		nombre
			from		rendidor
			where 		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un supervisor de rendición",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=array('id'=>$row['nombre'],'nombre'=>$row['nombre']);
			$i++;
		}
		return $data;
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
			array('rut', 'length', 'max'=>15),
			array('rut','esRut'),
			array('rut','unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre,vigente', 'safe', 'on'=>'search'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function ingresaRendidor($rut,$nombre){
            $rendidor = Rendidor::model()->findByAttributes(array('rut'=>$rut));
            if($rendidor == null){
                $rendidor = new Rendidor();
                $rendidor->rut = $rut;
            }
            $rendidor->nombre = $nombre;
            $rendidor->vigente = 'SÍ';
            $rendidor->save();
        }
}