<?php

/**
 * This is the model class for table "historial_pu_arrendado".
 *
 * The followings are the available columns in table 'historial_pu_arrendado':
 * @property integer $id
 * @property integer $pu
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property integer $equipoarrendado_id
 *
 * The followings are the available model relations:
 * @property Equipoarrendado $equipoarrendado
 */
class HistorialPuArrendado extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'historial_pu_arrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pu, fecha_desde, equipoarrendado_id', 'required'),
			array('pu, equipoarrendado_id', 'numerical', 'integerOnly'=>true),
			array('fecha_hasta', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pu, fecha_desde, fecha_hasta, equipoarrendado_id', 'safe', 'on'=>'search'),
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
			'equipoarrendado' => array(self::BELONGS_TO, 'Equipoarrendado', 'equipoarrendado_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pu' => 'Pu',
			'fecha_desde' => 'Fecha Desde',
			'fecha_hasta' => 'Fecha Hasta',
			'equipoarrendado_id' => 'Equipoarrendado',
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
		$criteria->compare('pu',$this->pu);
		$criteria->compare('fecha_desde',$this->fecha_desde,true);
		$criteria->compare('fecha_hasta',$this->fecha_hasta,true);
		$criteria->compare('equipoarrendado_id',$this->equipoarrendado_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HistorialPuArrendado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
