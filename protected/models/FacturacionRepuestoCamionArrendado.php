<?php

/**
 * This is the model class for table "facturacionRepuestoCamionArrendado".
 *
 * The followings are the available columns in table 'facturacionRepuestoCamionArrendado':
 * @property integer $id
 * @property string $guia
 * @property string $factura
 * @property integer $compra_id
 * @property integer $monto
 */
class FacturacionRepuestoCamionArrendado extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FacturacionRepuestoCamionArrendado the static model class
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
		return 'facturacionRepuestoCamionArrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('compra_id, monto', 'required'),
			array('compra_id, monto', 'numerical', 'integerOnly'=>true),
			array('guia, factura', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, guia, factura, compra_id, monto', 'safe', 'on'=>'search'),
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
			'guia' => 'Guia',
			'factura' => 'Factura',
			'compra_id' => 'Compra',
			'monto' => 'Monto Neto',
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
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('compra_id',$this->compra_id);
		$criteria->compare('monto',$this->monto);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}