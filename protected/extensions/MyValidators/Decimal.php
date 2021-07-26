<?php
class Decimal extends CValidator{
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
	    $novacio="value != ''";
	    $largo="value.length > 11";
	    $negativo="value < 0";
	    $nonumero="isNaN(value)";
 	
	    return "
	    value = value.replace(',','.');
	    if(".$novacio." && (".$nonumero." ||".$largo.")) {
		    messages.push(".CJSON::encode('Error, número muy grande').");
		    return;
		}
	   	if(".$novacio." && (".$nonumero." ||".$largo." || ".$negativo.")) {
		    messages.push(".CJSON::encode('Error, debe ser número positivo').");
		}
		";
	}
}
