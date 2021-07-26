<?php

/**
 * This is the model class for table "cargaPetroleoCamionPropio".
 *
 * The followings are the available columns in table 'cargaPetroleoCamionPropio':
 * @property integer $id
 * @property string $petroleoLts
 * @property string $kmCarguio
 * @property integer $reg_id
 */
class CargaPetroleoCamionPropio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CargaPetroleoCamionPropio the static model class
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
		return 'cargaPetroleoCamionPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('petroleoLts, kmCarguio, reg_id', 'required'),
			array('reg_id', 'numerical', 'integerOnly'=>true),
			array('petroleoLts, kmCarguio', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, petroleoLts, kmCarguio, reg_id', 'safe', 'on'=>'search'),
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
			'petroleoLts' => 'Combustible Lts',
			'kmCarguio' => 'Km Carguio',
			'reg_id' => 'Reg',
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
		$criteria->compare('petroleoLts',$this->petroleoLts,true);
		$criteria->compare('kmCarguio',$this->kmCarguio,true);
		$criteria->compare('reg_id',$this->reg_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}