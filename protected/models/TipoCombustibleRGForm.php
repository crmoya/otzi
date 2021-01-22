<?php

class TipoCombustibleRGForm extends CFormModel
{

	public $tipocombustiblesam;
	public $tipocombustible;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipocombustible,tipocombustiblesam', 'required'),
			array('tipocombustible','ext.MyValidators.NoBlanco'),
			array('id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tipocombustible, tipocombustiblesam', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tipocombustible' => 'Tipo de Combustible Rindegastos',
			'tipocombustiblesam' => 'Tipo de Combustible en SAM'
		);
	}

}