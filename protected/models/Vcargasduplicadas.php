<?php

/**
 * This is the model class for table "vcargasduplicadas".
 *
 * The followings are the available columns in table 'vcargasduplicadas':
 * @property string $petroleoLts
 * @property string $carguio
 * @property string $guia
 * @property string $factura
 * @property integer $precioUnitario
 * @property string $valorTotal
 * @property integer $faena_id
 * @property string $faena
 * @property string $numero
 * @property string $nombre
 * @property string $fechaRendicion
 * @property string $rut_rinde
 * @property string $cuenta
 * @property string $nombre_proveedor
 * @property string $rut_proveedor
 * @property string $observaciones
 * @property string $tipo_documento
 * @property integer $tipoCombustible_id
 * @property integer $supervisorCombustible_id
 * @property integer $vehiculo_id
 * @property string $vehiculo
 * @property string $cant
 * @property string $tipo
 * @property integer $id
 */
class Vcargasduplicadas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vcargasduplicadas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('precioUnitario, faena_id, tipoCombustible_id, supervisorCombustible_id, vehiculo_id, id', 'numerical', 'integerOnly'=>true),
			array('petroleoLts, carguio, valorTotal', 'length', 'max'=>12),
			array('guia, factura', 'length', 'max'=>45),
			array('faena', 'length', 'max'=>200),
			array('numero, fechaRendicion', 'length', 'max'=>20),
			array('nombre, cuenta, nombre_proveedor, observaciones, vehiculo', 'length', 'max'=>100),
			array('rut_rinde, rut_proveedor', 'length', 'max'=>15),
			array('tipo_documento', 'length', 'max'=>3),
			array('tipo', 'length', 'max'=>2),
			array('cant', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('petroleoLts, carguio, guia, factura, precioUnitario, valorTotal, faena_id, faena, numero, nombre, fechaRendicion, rut_rinde, cuenta, nombre_proveedor, rut_proveedor, observaciones, tipo_documento, tipoCombustible_id, supervisorCombustible_id, vehiculo_id, vehiculo, cant, tipo, id', 'safe', 'on'=>'search'),
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
			'petroleoLts' => 'Petroleo Lts',
			'carguio' => 'Carguio',
			'guia' => 'Guia',
			'factura' => 'Factura',
			'precioUnitario' => 'Precio Unitario',
			'valorTotal' => 'Valor Total',
			'faena_id' => 'Faena',
			'faena' => 'Faena',
			'numero' => 'Numero',
			'nombre' => 'Nombre',
			'fechaRendicion' => 'Fecha Rendicion',
			'rut_rinde' => 'Rut Rinde',
			'cuenta' => 'Cuenta',
			'nombre_proveedor' => 'Nombre Proveedor',
			'rut_proveedor' => 'Rut Proveedor',
			'observaciones' => 'Observaciones',
			'tipo_documento' => 'Tipo Documento',
			'tipoCombustible_id' => 'Tipo Combustible',
			'supervisorCombustible_id' => 'Supervisor Combustible',
			'vehiculo_id' => 'Vehiculo',
			'vehiculo' => 'Vehiculo',
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

		$criteria->compare('petroleoLts',$this->petroleoLts,true);
		$criteria->compare('carguio',$this->carguio,true);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('precioUnitario',$this->precioUnitario);
		$criteria->compare('valorTotal',$this->valorTotal,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('faena',$this->faena,true);
		$criteria->compare('numero',$this->numero,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fechaRendicion',$this->fechaRendicion,true);
		$criteria->compare('rut_rinde',$this->rut_rinde,true);
		$criteria->compare('cuenta',$this->cuenta,true);
		$criteria->compare('nombre_proveedor',$this->nombre_proveedor,true);
		$criteria->compare('rut_proveedor',$this->rut_proveedor,true);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('tipo_documento',$this->tipo_documento,true);
		$criteria->compare('tipoCombustible_id',$this->tipoCombustible_id);
		$criteria->compare('supervisorCombustible_id',$this->supervisorCombustible_id);
		$criteria->compare('vehiculo_id',$this->vehiculo_id);
		$criteria->compare('vehiculo',$this->vehiculo,true);
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
	 * @return Vcargasduplicadas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
