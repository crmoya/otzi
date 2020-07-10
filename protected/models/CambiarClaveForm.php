<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CambiarClaveForm extends CFormModel
{
	public $clave;
	public $nueva;
	public $repita;
	
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('clave, nueva,repita', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'clave'=>'Clave Antigua',
			'nueva'=>'Clave Nueva',
			'repita'=>'Repita Clave Nueva',
		);
	}


}
