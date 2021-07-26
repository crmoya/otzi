<?php
class NoBlanco extends CValidator{
	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
	    if($attribute=="")return;
	}
	
	/**
	 * Returns the JavaScript needed for performing client-side validation.
	 * @param CModel $object the data object being validated
	 * @param string $attribute the name of the attribute to be validated.
	 * @return string the client-side validation script.
	 * @see CActiveForm::enableClientValidation
	 */
	public function clientValidateAttribute($object,$attribute)
	{
	    $vacio="value == ''";
 	
	    return "
	    value = value.replace(/^\s*|\s*$/g,'');
	    if(".$vacio.") {
		    messages.push(".CJSON::encode('Error, no puede ser blanco').");
		    return;
		}
		";
	}
}
