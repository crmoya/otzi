<?php

/**
 * This is the model class for table "expedicionportiempoeqarr".
 *
 * The followings are the available columns in table 'expedicionportiempoeqarr':
 * @property integer $id
 * @property string $cantidad
 * @property string $total
 * @property integer $faena_id
 * @property integer $requipoarrendado_id
 * @property integer $unidadfaena_equipo_id
 *
 * The followings are the available model relations:
 * @property Faena $faena
 * @property Requipoarrendado $requipoarrendado
 * @property UnidadfaenaEquipo $unidadfaenaEquipo
 */
class Expedicionportiempoeqarr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expedicionportiempoeqarr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cantidad, total, faena_id, requipoarrendado_id, unidadfaena_equipo_id', 'required'),
			array('faena_id, requipoarrendado_id, unidadfaena_equipo_id', 'numerical', 'integerOnly'=>true),
			array('cantidad, total', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cantidad, total, faena_id, requipoarrendado_id, unidadfaena_equipo_id', 'safe', 'on'=>'search'),
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
			'requipoarrendado' => array(self::BELONGS_TO, 'Requipoarrendado', 'requipoarrendado_id'),
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
			'requipoarrendado_id' => 'Requipoarrendado',
			'unidadfaena_equipo_id' => 'Unidadfaena Equipo',
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
		$criteria->compare('requipoarrendado_id',$this->requipoarrendado_id);
		$criteria->compare('unidadfaena_equipo_id',$this->unidadfaena_equipo_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Expedicionportiempoeqarr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
