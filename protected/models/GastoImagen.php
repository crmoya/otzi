<?php

/**
 * This is the model class for table "gasto_imagen".
 *
 * The followings are the available columns in table 'gasto_imagen':
 * @property integer $id
 * @property string $file_name
 * @property string $extension
 * @property string $original
 * @property string $large
 * @property string $medium
 * @property string $small
 * @property integer $gasto_id
 *
 * The followings are the available model relations:
 * @property Gasto $gasto
 */
class GastoImagen extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gasto_imagen';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gasto_id', 'required'),
			array('gasto_id', 'numerical', 'integerOnly'=>true),
			array('extension', 'length', 'max'=>10),
			array('file_name, original, large, medium, small', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, file_name, extension, original, large, medium, small, gasto_id', 'safe', 'on'=>'search'),
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
			'gasto' => array(self::BELONGS_TO, 'Gasto', 'gasto_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'file_name' => 'File Name',
			'extension' => 'Extension',
			'original' => 'Original',
			'large' => 'Large',
			'medium' => 'Medium',
			'small' => 'Small',
			'gasto_id' => 'Gasto',
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
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('original',$this->original,true);
		$criteria->compare('large',$this->large,true);
		$criteria->compare('medium',$this->medium,true);
		$criteria->compare('small',$this->small,true);
		$criteria->compare('gasto_id',$this->gasto_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GastoImagen the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
