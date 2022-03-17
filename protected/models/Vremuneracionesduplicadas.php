<?php

/**
 * This is the model class for table "vremuneracionesduplicadas".
 *
 * The followings are the available columns in table 'vremuneracionesduplicadas':
 * @property string $descripcion
 * @property integer $montoNeto
 * @property string $guia
 * @property string $documento
 * @property double $cantidad
 * @property string $unidad
 * @property integer $vehiculo_id
 * @property string $vehiculo
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
 * @property string $cant
 * @property string $tipo
 * @property integer $id
 */
class Vremuneracionesduplicadas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vremuneracionesduplicadas';
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
			array('descripcion, faena', 'length', 'max'=>200),
			array('guia, documento', 'length', 'max'=>45),
			array('unidad, tipo', 'length', 'max'=>2),
			array('vehiculo, nombre, nombre_proveedor', 'length', 'max'=>100),
			array('numero, fechaRendicion', 'length', 'max'=>20),
			array('rut_rinde, rut_proveedor', 'length', 'max'=>15),
			array('tipo_documento', 'length', 'max'=>40),
			array('cuenta, cant', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('descripcion, montoNeto, guia, documento, cantidad, unidad, vehiculo_id, vehiculo, faena_id, faena, numero, nombre, fechaRendicion, rut_rinde, cuenta, nombre_proveedor, rut_proveedor, observaciones, tipo_documento, cant, tipo, id', 'safe', 'on'=>'search'),
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
			'descripcion' => 'Descripcion',
			'montoNeto' => 'Monto Neto',
			'guia' => 'Guia',
			'documento' => 'Documento',
			'cantidad' => 'Cantidad',
			'unidad' => 'Unidad',
			'vehiculo_id' => 'Vehiculo',
			'vehiculo' => 'Vehiculo',
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

		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('montoNeto',$this->montoNeto);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('documento',$this->documento,true);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('unidad',$this->unidad,true);
		$criteria->compare('vehiculo_id',$this->vehiculo_id);
		$criteria->compare('vehiculo',$this->vehiculo,true);
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
	 * @return Vremuneracionesduplicadas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
