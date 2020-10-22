<?php

class FaenaRindegastosForm extends CFormModel
{

	public $faenasam;
	public $faena;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('faena,faenasam', 'required'),
			array('faena','ext.MyValidators.NoBlanco'),
			array('id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, faena, faenasam', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'faena' => 'Faena Rindegastos',
			'faenasam' => 'Centro de Gestión'
		);
	}

}