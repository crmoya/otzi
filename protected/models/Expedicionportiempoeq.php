<?php

/**
 * This is the model class for table "expedicionportiempoeq".
 *
 * The followings are the available columns in table 'expedicionportiempoeq':
 * @property integer $id
 * @property string $cantidad
 * @property string $total
 * @property integer $faena_id
 * @property integer $requipopropio_id
 * @property integer $unidadfaena_equipo_id
 *
 * The followings are the available model relations:
 * @property Faena $faena
 * @property Requipopropio $requipopropio
 * @property UnidadfaenaEquipo $unidadfaenaEquipo
 */
class Expedicionportiempoeq extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expedicionportiempoeq';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cantidad, total, faena_id, requipopropio_id, unidadfaena_equipo_id', 'required'),
			array('faena_id, requipopropio_id, unidadfaena_equipo_id', 'numerical', 'integerOnly'=>true),
			array('cantidad, total', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cantidad, total, faena_id, requipopropio_id, unidadfaena_equipo_id', 'safe', 'on'=>'search'),
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
			'faena' => array(self::BELONGS_TO, 'Faena', 'faena_id'),
			'requipopropio' => array(self::BELONGS_TO, 'Requipopropio', 'requipopropio_id'),
			'unidadfaenaEquipo' => array(self::BELONGS_TO, 'UnidadfaenaEquipo', 'unidadfaena_equipo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cantidad' => 'Cantidad',
			'total' => 'Total',
			'faena_id' => 'Faena',
			'requipopropio_id' => 'Requipopropio',
			'unidadfaena_equipo_id' => 'Unidad',
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
		$criteria->compare('cantidad',$this->cantidad,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('requipopropio_id',$this->requipopropio_id);
		$criteria->compare('unidadfaena_equipo_id',$this->unidadfaena_equipo_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Expedicionportiempoeq the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
