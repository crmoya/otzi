<?php

class TestController extends Controller
{
	/**
	 * Declares class-based actions.
	 */

	public function actions()
	{
		return array(
		// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
		),
		// page action renders "static" pages stored under 'protected/views/site/pages'
		// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
		),
		);
	}

	public function actionCiudades()
	{
		//please enter current controller name because yii send multi dim array
		$val = 0;
		if(isset($_POST['camionPropio'])){
			$val = $_POST['camionPropio'];
		}
		//$data=Origen::model()->findAll('id=:parent_id',array(':parent_id'=>(int)$val));
		$data=Origen::model()->findAll();
		$data=CHtml::listData($data,'id','nombre');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',
			array('value'=>$value),$val,true);
		}
	}

	public function actionIndex(){
		$this->render("paises");
	}

}