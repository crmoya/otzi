<?php

/**
 * This is the model class for table "instituciones".
 *
 * The followings are the available columns in table 'instituciones':
 * @property integer $id
 * @property string $nombre
 *
 * The followings are the available model relations:
 * @property Garantias[] $garantiases
 */
class Instituciones extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Instituciones the static model class
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
		return 'instituciones';
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
			array('nombre','unique'),	
			array('nombre', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre', 'safe', 'on'=>'search'),
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
			'garantiases' => array(self::HAS_MANY, 'Garantias', 'instituciones_id'),
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
	
	public function listar(){
		$instituciones = array();
		$instituciones[0]=array('nombre'=>"Seleccione Institución",'id'=>'-1');
		$instis = Instituciones::model()->findAllByAttributes(array('vigente'=>'SÍ'));
		$i=1;
		foreach($instis as $ins){
			$instituciones[$i]=array('nombre'=>$ins->nombre,'id'=>$ins->id);
			$i++;
		}
		return $instituciones;
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}