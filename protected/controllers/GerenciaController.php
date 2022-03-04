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

	function actionDuplicados()
	{
		$db=Yii::app()->db;
		$db->active=true;

		$sql = "
		select count(*) from 
		   (select g.issue_date as fecha, gc.rut_proveedor as proveedor, gc.monto_neto as neto, gc.nro_documento as nro_documento, gc.vehiculo_equipo as maquina, count(*)
		   from gasto_completa gc
		   join gasto g on g.id = gc.gasto_id 
		   GROUP by g.issue_date, gc.rut_proveedor, gc.monto_neto, gc.nro_documento, gc.vehiculo_equipo
		   HAVING count(*) > 1) t
		";
		$duplicados = $db->createCommand($sql)->queryScalar();
		
		$db->active=false;
		$this->render("duplicados",['duplicados'=>$duplicados]);
	}

	function actionEliminarDuplicados()
	{

		$db=Yii::app()->db;
		$db->active=true;

		$transaction = $db->beginTransaction();
		try
		{
			$sql = "
				select 	g.issue_date as fecha, 
						gc.rut_proveedor as proveedor, 
						gc.monto_neto as neto, 
						gc.nro_documento as nro_documento, 
						gc.vehiculo_equipo as maquina
				from gasto_completa gc
				join gasto g on g.id = gc.gasto_id 
				GROUP by g.issue_date, gc.rut_proveedor, gc.monto_neto, gc.nro_documento, gc.vehiculo_equipo
				HAVING count(*) > 1
				order by g.issue_date
			";
			$duplicados = $db->createCommand($sql)->queryAll();
			$eliminados = 0;
			foreach($duplicados as $duplicado)
			{
				$gastosCompletas = GastoCompleta::model()->findAllByAttributes([
					'rut_proveedor' => $duplicado['proveedor'],
					'iva' => $duplicado['iva'],
					'impuesto_especifico' => $duplicado['impuesto_especifico'],
					'monto_neto' => $duplicado['neto'],
					'nro_documento' => $duplicado['nro_documento'],
					'vehiculo_equipo' => $duplicado['maquina'],
				]);

				$primero = true;
				foreach($gastosCompletas as $gastoCompleta)
				{
					$gasto = Gasto::model()->findByAttributes(['id' => $gastoCompleta->gasto_id]);
					if($primero) 
					{
						$primero = false;
						continue;
					}
					if($gasto->issue_date == $duplicado['fecha'])
					{
						$compra = false;
						$rindegasto = CombustibleRindegasto::model()->findByAttributes(['gasto_completa_id' => $gastoCompleta->id]);
						if($rindegasto == null)
						{
							$compra = true;
							$rindegasto = NocombustibleRindegasto::model()->findByAttributes(['gasto_completa_id' => $gastoCompleta->id]);
						}
						if($rindegasto != null)
						{
							if($rindegasto->equipoarrendado_id != null)
							{
								if($compra)
								{
									CompraRepuestoEquipoArrendado::model()->deleteAllByAttributes(['id' => $rindegasto->compra_id]);
								}
								else
								{
									CargaCombEquipoArrendado::model()->deleteAllByAttributes(['id' => $rindegasto->carga_id]);
								}
							}
							if($rindegasto->equipopropio_id != null)
							{
								if($compra)
								{
									CompraRepuestoEquipoPropio::model()->deleteAllByAttributes(['id' => $rindegasto->compra_id]);
								}
								else
								{
									CargaCombEquipoPropio::model()->deleteAllByAttributes(['id' => $rindegasto->carga_id]);
								}
							}
							if($rindegasto->camionarrendado_id != null)
							{
								if($compra)
								{
									CompraRepuestoCamionArrendado::model()->deleteAllByAttributes(['id' => $rindegasto->compra_id]);
								}
								else
								{
									CargaCombCamionArrendado::model()->deleteAllByAttributes(['id' => $rindegasto->carga_id]);
								}
							}
							if($rindegasto->camionpropio_id != null)
							{
								if($compra)
								{
									CompraRepuestoCamionPropio::model()->deleteAllByAttributes(['id' => $rindegasto->compra_id]);
								}
								else
								{
									CargaCombCamionPropio::model()->deleteAllByAttributes(['id' => $rindegasto->carga_id]);
								}
							}
							if($compra)
							{
								NocombustibleRindegasto::model()->deleteAllByAttributes(['gasto_completa_id' => $gastoCompleta->id]);
							}
							else
							{
								CombustibleRindegasto::model()->deleteAllByAttributes(['gasto_completa_id' => $gastoCompleta->id]);
							}
						}
						GastoCompleta::model()->deleteAllByAttributes(['id' => $gastoCompleta->id]);
						Gasto::model()->deleteAllByAttributes(['id' => $gastoCompleta->gasto_id]);
						$eliminados++;
					}
				}
			}

			$transaction->commit();
			echo CJSON::encode([
				'status'=>'OK',
				'eliminados' => $eliminados,
			]);
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			echo CJSON::encode([
				'status'=>'ERROR',
				'message' => $e->getMessage(),
			]);
		}
		finally
		{
			$db->active=false;
		}

		
		
		
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

	public function actionGenerapdf($ids,$tipo){

		$html = "";
		$ids = explode('*__*',$ids);
		$html .= "<center>";
		foreach($ids as $archivoArr){
			$archivo = explode("_!!_",$archivoArr);
			$path_tipo = "";
			if($tipo == "CA"){
				$path_tipo = "camiones_arrendados";
			}
			if($tipo == "CP"){
				$path_tipo = "camiones_propios";
			}
			if($tipo == "EA"){
				$path_tipo = "equipos_arrendados";
			}
			if($tipo == "EP"){
				$path_tipo = "equipos_propios";
			}
			$path = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR . $path_tipo . DIRECTORY_SEPARATOR . $archivo[0] . DIRECTORY_SEPARATOR . $archivo[1];
			$image = Yii::app()->assetManager->publish($path);
			$size = getimagesize($path);
			$restrictions = "";
			//es más ancha que alta
			if($size[0] > $size[1]){
				$restrictions = "style='width:700px;'";
			}
			else{
				$restrictions = "style='height: 900px;'";
			}
			$extension = strtolower(pathinfo($path)['extension']);
			
			if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
				$html .= "<img " . $restrictions . " src='" . $image . "'/><br/><br/>";
			}
		}
		$this->render("imprimir",['html'=>$html]);

		/*require_once __DIR__ . '../../../vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf();
		$ids = explode('*__*',$ids);
		$html = "";
		foreach($ids as $archivoArr){
			$archivo = explode("_!!_",$archivoArr);
			$path_tipo = "";
			if($tipo == "CA"){
				$path_tipo = "camiones_arrendados";
			}
			if($tipo == "CP"){
				$path_tipo = "camiones_propios";
			}
			if($tipo == "EA"){
				$path_tipo = "equipos_arrendados";
			}
			if($tipo == "EP"){
				$path_tipo = "equipos_propios";
			}
			$path = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR . $path_tipo . DIRECTORY_SEPARATOR . $archivo[0] . DIRECTORY_SEPARATOR . $archivo[1];
			$extension = strtolower(pathinfo($path)['extension']);
			if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
				$html .= '<img src="' . $path . '" alt="" width="100%"><br/>';
			}
		}
		$mpdf->WriteHTML($html);
		$mpdf->Output();
		*/
	}

	public function actionAdjuntos($ids,$tipo){
		$this->render("adjuntos",['ids'=>$ids,'tipo'=>$tipo]);
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
					'actions'=>array('generapdf','adjuntos','viewGastoRepuesto','viewGastoCombustible','produccionMaquinaria','exportarProduccionMaquinaria','consumoMaquinaria','exportarConsumoMaquinaria','consumoCamiones','exportarConsumoCamiones','produccionCamiones','exportarProduccionCamiones','gastoCombustible','exportarGastoCombustible','gastoRepuesto','exportarGastoRepuesto','resultados','exportarResultados','operario','exportarOperario','chofer','exportarChofer','exportarDetalleGastoRepuesto','exportarDetalleGastoCombustible','duplicados','eliminarDuplicados'),
					'roles'=>array('gerencia'),
			),
			array('deny',  // deny all users
					'users'=>array('*'),
			),
		);
	}
}