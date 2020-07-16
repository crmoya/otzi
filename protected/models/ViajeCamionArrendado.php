<?php

/**
 * This is the model class for table "viajeCamionArrendado".
 *
 * The followings are the available columns in table 'viajeCamionArrendado':
 * @property integer $id
 * @property integer $nVueltas
 * @property string $totalTransportado
 * @property string $total
 * @property string $kmInicial
 * @property string $kmFinal
 * @property string $kmRecorridos
 * @property string $kmGps
 * @property integer $faena_id
 * @property integer $rCamionArrendado_id
 * @property integer $origendestino_faena_id
 */
class ViajeCamionArrendado extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ViajeCamionArrendado the static model class
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
		return 'viajeCamionArrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nVueltas, totalTransportado, total, faena_id, rCamionArrendado_id, origendestino_faena_id', 'required'),
			array('nVueltas, faena_id, rCamionArrendado_id, origendestino_faena_id,coeficiente', 'numerical', 'integerOnly'=>true),
			array('totalTransportado, total, kmRecorridos', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nVueltas, totalTransportado, total, kmRecorridos, faena_id, rCamionArrendado_id, origendestino_faena_id', 'safe', 'on'=>'search'),
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
			'nVueltas' => 'N Vueltas',
			'totalTransportado' => 'Total Transportado',
			'total' => 'Total en $',
			'kmRecorridos' => 'Km Recorridos',
			'faena_id' => 'Faena',
			'rCamionArrendado_id' => 'R Camion Arrendado',
			'origendestino_faena_id' => 'Origendestino Faena',
			'coeficiente' => 'Coeficiente Carga Parcial (en %)',
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
		$criteria->compare('nVueltas',$this->nVueltas);
		$criteria->compare('totalTransportado',$this->totalTransportado,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('kmRecorridos',$this->kmRecorridos,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('rCamionArrendado_id',$this->rCamionArrendado_id);
		$criteria->compare('origendestino_faena_id',$this->origendestino_faena_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}