<?php

class GerenciaController extends Controller
{
	
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }
	
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

	function actionExportarProduccionMaquinaria()
	{
		// generate a resultset
		$data = InformeProduccionMaquinaria::model()->findAll();
		
		$this->toExcel($data,
			array('maquina','operador','centroGestion','pu','horas','horasMin','produccion','produccionMin'),
			'Producción Maquinaria',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ProduccionMaquinaria.xls');
	}
	
	function actionExportarDetalleGastoRepuesto()
	{
		// generate a resultset
		$data = InformeDetalleGastoRepuesto::model()->findAll();
		// render data to xlsx format and echo resultant file back to browser.
		$this->toExcel($data,
			array('fecha','reporte','operario','maquina','repuesto','montoNeto','guia','factura','cantidad','numero','nombre','fechaRendicion'),
			'Detalle Gasto Repuesto',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'DetalleGastoRepuesto.xls');
	}
	
	function actionExportarDetalleGastoCombustible()
	{
		// generate a resultset
		$data = InformeDetalleGastoCombustible::model()->findAll();
		$this->toExcel($data,
			array('fecha','reporte','operario','maquina','petroleoLts','kmCarguio','guia','factura','precioUnitario','valorTotal','faena','tipoCombustible','supervisorCombustible','numero','nombre','fechaRendicion'),
			'Detalle Gasto Combustible',
			array()
		);
		// render data to xlsx format and echo resultant file back to browser.
		//ExcelExporter::sendAsXLS($data, true, 'DetalleGastoCombustible.xls');
	}
	
	function actionExportarProduccionCamiones()
	{
		// generate a resultset
		$data = InformeProduccionCamiones::model()->findAll();
		// render data to xlsx format and echo resultant file back to browser.
		$this->toExcel($data,
			array('camion','chofer','centroGestion','totalTransportado','produccion','produccionReal','diferencia'),
			'Producción Camiones',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ProduccionCamiones.xls');
	}
	
	function actionExportarGastoCombustible()
	{
		// generate a resultset
		$data = InformeGastoCombustible::model()->findAll();
		$this->toExcel($data,
			array('maquina','operador','centroGestion','consumoLts','consumoPesos'),
			'Gasto Combustible',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'GastoCombustible.xls');
	}
	
	function actionExportarGastoRepuesto()
	{
		// generate a resultset
		$data = InformeGastoRepuesto::model()->findAll();
		$this->toExcel($data,
			array('maquina','operador','consumoPesos'),
			'Gasto Repuesto',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'GastoRepuesto.xls');
	}
	
	function actionExportarResultados()
	{
		// generate a resultset
		$data = InformeResultados::model()->findAll();
		$this->toExcel($data,
			array('maquina','operador','centroGestion','produccion','repuesto','combustible','resultado'),
			'Resultados',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'Resultados.xls');
	}
	
	function actionExportarConsumoMaquinaria()
	{
		// generate a resultset
		$data = InformeConsumoMaquinaria::model()->findAll();
		$this->toExcel($data,
			array('maquina','operador','ltsFisicos','hrsFisicas','consumo','hrsGps','consumoGps','consumoEsperado'),
			'Consumo Maquinaria',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ConsumoMaquinaria.xls');
	}
	
	function actionExportarConsumoCamiones()
	{
		// generate a resultset
		$data = InformeConsumoCamiones::model()->findAll();
		$this->toExcel($data,
			array('maquina','operador','ltsFisicos','kmsFisicos','consumoReal','kmsGps','consumoGps','consumoSugerido'),
			'Consumo Camiones',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ConsumoCamiones.xls');
	}
	
	function actionExportarOperario()
	{
		// generate a resultset
		$data = InformeOperario::model()->findAll();
		$this->toExcel($data,
			array('operario','maquina','consumoPromedio','coeficiente','horas','horasContratadas','valorHora','total'),
			'Uso Maquinaria por Operador',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'UsoMaquinasOperario.xls');
	}
	
	function actionExportarChofer()
	{
		// generate a resultset
		$data = InformeChofer::model()->findAll();
		$this->toExcel($data,
			array('chofer','camion','produccionDia','produccionMinima','coeficienteCombustible','gastoCombustible','diferencia'),
			'Uso Camiones por Chofer',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'UsoCamionesChofer.xls');
	}


	public function actionIndex(){
		$this->render("indexGerencia",array('nombre'=>Yii::app()->user->nombre));
	}

	public function actionProduccionMaquinaria(){
		$model=new InformeProduccionMaquinaria('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeProduccionMaquinaria'])){
			$model->attributes=$_GET['InformeProduccionMaquinaria'];
			$model->fechaInicio=$_GET['InformeProduccionMaquinaria']['fechaInicio'];
			$model->fechaFin=$_GET['InformeProduccionMaquinaria']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeProduccionMaquinaria']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeProduccionMaquinaria']['agruparPor'];
		}
		$model->generarInforme();
		$this->render('produccionMaquinaria/informe',array('model'=>$model,));
	}

	public function actionDiarioMaquinaria(){
		$model=new InformeDiarioMaquinaria('search');
		$model->unsetAttributes();

		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeDiarioMaquinaria'])){
			$model->attributes=$_GET['InformeDiarioMaquinaria'];
			$model->fechaInicio=$_GET['InformeDiarioMaquinaria']['fechaInicio'];
			$model->fechaFin=$_GET['InformeDiarioMaquinaria']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeDiarioMaquinaria']['propiosOArrendados'];
		}
		$model->generarInforme();
		$this->render('diarioMaquinaria/informe',array('model'=>$model,));
	}
	
	public function actionDiarioCamiones(){
		$model=new InformeDiarioCamiones('search');
		$model->unsetAttributes();

		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeDiarioCamiones'])){
			$model->attributes=$_GET['InformeDiarioCamiones'];
			$model->fechaInicio=$_GET['InformeDiarioCamiones']['fechaInicio'];
			$model->fechaFin=$_GET['InformeDiarioCamiones']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeDiarioCamiones']['propiosOArrendados'];
		}
		$model->generarInforme();
		$this->render('diarioCamiones/informe',array('model'=>$model,));
	}
	
	
	public function actionProduccionCamiones(){
		$model=new InformeProduccionCamiones('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeProduccionCamiones'])){
			$model->attributes=$_GET['InformeProduccionCamiones'];
			$model->fechaInicio=$_GET['InformeProduccionCamiones']['fechaInicio'];
			$model->fechaFin=$_GET['InformeProduccionCamiones']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeProduccionCamiones']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeProduccionCamiones']['agruparPor'];
		}
		$model->generarInforme();
		$this->render('produccionCamiones/informe',array('model'=>$model,));
	}
	
	public function actionGastoCombustible(){
		$model=new InformeGastoCombustible('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");
		$model->tipoCombustible_id = "";

		if(isset($_GET['InformeGastoCombustible'])){
			$model->attributes=$_GET['InformeGastoCombustible'];
			$model->fechaInicio=$_GET['InformeGastoCombustible']['fechaInicio'];
			$model->fechaFin=$_GET['InformeGastoCombustible']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeGastoCombustible']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeGastoCombustible']['agruparPor'];
			$model->tipoCombustible_id=$_GET['InformeGastoCombustible']['tipoCombustible_id'];
		}
		$model->generarInforme();
		$this->render('gastoCombustible/informe',array('model'=>$model,));
	}
	
	public function actionGastoRepuesto(){
		$model=new InformeGastoRepuesto('search');
		$model->unsetAttributes();

		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeGastoRepuesto'])){
			$model->attributes=$_GET['InformeGastoRepuesto'];
			$model->fechaInicio=$_GET['InformeGastoRepuesto']['fechaInicio'];
			$model->fechaFin=$_GET['InformeGastoRepuesto']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeGastoRepuesto']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeGastoRepuesto']['agruparPor'];
		}
		$model->generarInforme();
		$this->render('gastoRepuesto/informe',array('model'=>$model,));
	}
		
	public function actionResultados(){
		$model=new InformeResultados('search');
		$model->unsetAttributes();

		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeResultados'])){
			$model->attributes=$_GET['InformeResultados'];
			$model->fechaInicio=$_GET['InformeResultados']['fechaInicio'];
			$model->fechaFin=$_GET['InformeResultados']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeResultados']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeResultados']['agruparPor'];
		}
		$model->generarInforme();
		$this->render('resultados/informe',array('model'=>$model,));
	}
	
	public function actionConsumoMaquinaria(){
		$model=new InformeConsumoMaquinaria('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");
		$model->tipoCombustible_id = "";

		if(isset($_GET['InformeConsumoMaquinaria'])){
			$model->attributes=$_GET['InformeConsumoMaquinaria'];
			$model->fechaInicio=$_GET['InformeConsumoMaquinaria']['fechaInicio'];
			$model->fechaFin=$_GET['InformeConsumoMaquinaria']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeConsumoMaquinaria']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeConsumoMaquinaria']['agruparPor'];
			$model->tipoCombustible_id=$_GET['InformeConsumoMaquinaria']['tipoCombustible_id'];
		}
		$model->generarInforme();
		$this->render('consumoMaquinaria/informe',array('model'=>$model,));
	}
	
	public function actionConsumoCamiones(){
		$model=new InformeConsumoCamiones('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");
		$model->tipoCombustible_id = "";

		if(isset($_GET['InformeConsumoCamiones'])){
			$model->attributes=$_GET['InformeConsumoCamiones'];
			$model->fechaInicio=$_GET['InformeConsumoCamiones']['fechaInicio'];
			$model->fechaFin=$_GET['InformeConsumoCamiones']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeConsumoCamiones']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeConsumoCamiones']['agruparPor'];
			$model->tipoCombustible_id=$_GET['InformeConsumoCamiones']['tipoCombustible_id'];
		}
		$model->generarInforme();
		$this->render('consumoCamiones/informe',array('model'=>$model,));
	}
	
	public function actionOperario(){
		$model=new InformeOperario('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeOperario'])){
			$model->attributes=$_GET['InformeOperario'];
			$model->fechaInicio=$_GET['InformeOperario']['fechaInicio'];
			$model->fechaFin=$_GET['InformeOperario']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeOperario']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeOperario']['agruparPor'];
		}
		$model->generarInforme();
		$this->render('operario/informe',array('model'=>$model,));
	}
	
	public function actionChofer(){
		$model=new InformeChofer('search');
		$model->unsetAttributes();


		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeChofer'])){
			$model->attributes=$_GET['InformeChofer'];
			$model->fechaInicio=$_GET['InformeChofer']['fechaInicio'];
			$model->fechaFin=$_GET['InformeChofer']['fechaFin'];
			$model->propiosOArrendados=$_GET['InformeChofer']['propiosOArrendados'];
			$model->agruparPor=$_GET['InformeChofer']['agruparPor'];
		}
		$model->generarInforme();
		$this->render('chofer/informe',array('model'=>$model,));
	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
			echo $error['message'];
			else
			$this->render('error', $error);
		}
	}


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewGastoRepuesto($id)
	{
		$model = new InformeDetalleGastoRepuesto();
		$model->generarInforme($id);
		$this->render('detalleGastoRepuesto/view',array(
			'model'=>$model,
		));
	}
	
	public function actionViewGastoCombustible($id)
	{
		$model = new InformeDetalleGastoCombustible();
		$model->generarInforme($id);
		$this->render('detalleGastoCombustible/view',array(
			'model'=>$model,
		));
	}

	public function accessRules()
	{
		return array(
			array('allow',
					'actions'=>array('viewGastoRepuesto','viewGastoCombustible','produccionMaquinaria','exportarProduccionMaquinaria','consumoMaquinaria','exportarConsumoMaquinaria','consumoCamiones','exportarConsumoCamiones','produccionCamiones','exportarProduccionCamiones','gastoCombustible','exportarGastoCombustible','gastoRepuesto','exportarGastoRepuesto','resultados','exportarResultados','operario','exportarOperario','chofer','exportarChofer','exportarDetalleGastoRepuesto','exportarDetalleGastoCombustible'),
					'roles'=>array('gerencia'),
			),
			array('deny',  // deny all users
					'users'=>array('*'),
			),
		);
	}
}