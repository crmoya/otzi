<?php

class VehiculoRindegastosForm extends CFormModel
{

	public $vehiculosam;
	public $vehiculo;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vehiculo,vehiculosam', 'required'),
			array('vehiculo','ext.MyValidators.NoBlanco'),
			array('id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, vehiculo, vehiculosam', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'vehiculo' => 'Vehículo Rindegastos',
			'vehiculosam' => 'Camion / Equipo - Propio / Arrendado'
		);
	}

}