<?php

/**
 * This is the model class for table "compraRepuestoCamionPropio".
 *
 * The followings are the available columns in table 'compraRepuestoCamionPropio':
 * @property integer $id
 * @property string $repuesto
 * @property integer $montoNeto
 * @property string $guia
 * @property string $factura
 * @property double $cantidad
 * @property string $unidad
 * @property integer $rCamionPropio_id
 * @property string cuenta
 * @property string rut_proveedor
 * @property string nombre_proveedor
 * @property string observaciones
 */
class CompraRepuestoCamionPropio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CompraRepuestoCamionPropio the static model class
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
		return 'compraRepuestoCamionPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('repuesto, montoNeto, rCamionPropio_id', 'required'),
			array('montoNeto, rCamionPropio_id', 'numerical', 'integerOnly'=>true),
			array('repuesto', 'length', 'max'=>200),
			array('guia, factura', 'length', 'max'=>45),
			array('unidad', 'length', 'max'=>2),
			array('nombre', 'length', 'max'=>100),
			array('cantidad', 'numerical'),
			array('fechaRendicion,numero', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, repuesto, montoNeto, guia, factura, cantidad, unidad, rCamionPropio_id', 'safe', 'on'=>'search'),
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
			'repuesto' => 'Repuesto Descripción',
			'montoNeto' => 'Monto Neto',
			'guia' => 'N°Guia u OC',
			'factura' => 'N°Factura o Boleta',
			'cantidad' => 'Cantidad',
			'unidad' => 'Cantidad en',
			'rCamionPropio_id' => 'R Camion Propio',
			'numero'=>'N°Rendición',
			'nombre'=>'Nombre quien rinde',
			'fechaRendicion'=>'Fecha de Documento',
			'rut_rinde'=>'Rut quien rinde',
			'faena_id' => 'Faena',
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
		$criteria->compare('repuesto',$this->repuesto,true);
		$criteria->compare('montoNeto',$this->montoNeto);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('unidad',$this->unidad,true);
		$criteria->compare('rCamionPropio_id',$this->rCamionPropio_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}