<?php

/**
 * This is the model class for table "historial_pu_propio".
 *
 * The followings are the available columns in table 'historial_pu_propio':
 * @property integer $id
 * @property integer $equipopropio_id
 * @property integer $pu
 * @property string $fecha_desde
 * @property string $fecha_hasta
 *
 * The followings are the available model relations:
 * @property Equipopropio $equipopropio
 */
class HistorialPuPropio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'historial_pu_propio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('equipopropio_id, pu, fecha_desde', 'required'),
			array('equipopropio_id, pu', 'numerical', 'integerOnly'=>true),
			array('fecha_hasta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, equipopropio_id, pu, fecha_desde, fecha_hasta', 'safe', 'on'=>'search'),
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
			'equipopropio' => array(self::BELONGS_TO, 'Equipopropio', 'equipopropio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'equipopropio_id' => 'Equipopropio',
			'pu' => 'Pu',
			'fecha_desde' => 'Fecha Desde',
			'fecha_hasta' => 'Fecha Hasta',
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
		$criteria->compare('equipopropio_id',$this->equipopropio_id);
		$criteria->compare('pu',$this->pu);
		$criteria->compare('fecha_desde',$this->fecha_desde,true);
		$criteria->compare('fecha_hasta',$this->fecha_hasta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HistorialPuPropio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
