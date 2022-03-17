<?php

/**
 * This is the model class for table "vcomprasduplicadas".
 *
 * The followings are the available columns in table 'vcomprasduplicadas':
 * @property string $repuesto
 * @property integer $montoNeto
 * @property string $factura
 * @property integer $vehiculo_id
 * @property string $vehiculo
 * @property string $tipo_documento
 * @property string $rut_proveedor
 * @property string $cuenta
 * @property string $nombre_proveedor
 * @property integer $faena_id
 * @property string $faena
 * @property double $cantidad
 * @property string $unidad
 * @property string $fechaRendicion
 * @property string $observaciones
 * @property string $cant
 * @property string $tipo
 * @property integer $id
 */
class Vcomprasduplicadas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vcomprasduplicadas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('observaciones', 'required'),
			array('montoNeto, vehiculo_id, faena_id, id', 'numerical', 'integerOnly'=>true),
			array('cantidad', 'numerical'),
			array('repuesto, faena', 'length', 'max'=>200),
			array('factura', 'length', 'max'=>45),
			array('vehiculo, nombre_proveedor', 'length', 'max'=>100),
			array('tipo_documento', 'length', 'max'=>40),
			array('rut_proveedor', 'length', 'max'=>15),
			array('unidad, tipo', 'length', 'max'=>2),
			array('fechaRendicion', 'length', 'max'=>20),
			array('cuenta, cant', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('repuesto, montoNeto, factura, vehiculo_id, vehiculo, tipo_documento, rut_proveedor, cuenta, nombre_proveedor, faena_id, faena, cantidad, unidad, fechaRendicion, observaciones, cant, tipo, id', 'safe', 'on'=>'search'),
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
			'repuesto' => 'Repuesto',
			'montoNeto' => 'Monto Neto',
			'factura' => 'Factura',
			'vehiculo_id' => 'Vehiculo',
			'vehiculo' => 'Vehiculo',
			'tipo_documento' => 'Tipo Documento',
			'rut_proveedor' => 'Rut Proveedor',
			'cuenta' => 'Cuenta',
			'nombre_proveedor' => 'Nombre Proveedor',
			'faena_id' => 'Faena',
			'faena' => 'Faena',
			'cantidad' => 'Cantidad',
			'unidad' => 'Unidad',
			'fechaRendicion' => 'Fecha Rendicion',
			'observaciones' => 'Observaciones',
			'cant' => 'Cant',
			'tipo' => 'Tipo',
			'id' => 'ID',
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

		$criteria->compare('repuesto',$this->repuesto,true);
		$criteria->compare('montoNeto',$this->montoNeto);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('vehiculo_id',$this->vehiculo_id);
		$criteria->compare('vehiculo',$this->vehiculo,true);
		$criteria->compare('tipo_documento',$this->tipo_documento,true);
		$criteria->compare('rut_proveedor',$this->rut_proveedor,true);
		$criteria->compare('cuenta',$this->cuenta,true);
		$criteria->compare('nombre_proveedor',$this->nombre_proveedor,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('faena',$this->faena,true);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('unidad',$this->unidad,true);
		$criteria->compare('fechaRendicion',$this->fechaRendicion,true);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('cant',$this->cant,true);
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('id',$this->id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vcomprasduplicadas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
