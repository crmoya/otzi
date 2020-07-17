<?php

class FaenaController extends Controller {

	public function actionExport($id){
		Yii::import('application.vendors.PHPExcel',true);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
						
		$locale = 'es_es';
		$validLocale = PHPExcel_Settings::setLocale($locale);
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("")
			->setLastModifiedBy("")
			->setTitle('Faena_'.$id)
			->setSubject("")
			->setDescription("")
			->setKeywords("")
			->setCategory("Test result file");
		
		$ods = Faena::model()->listarODs($id);
		$faena=$this->loadModel($id);
		
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Faena #'.$id)
			->setCellValue('A3', 'ID')
			->setCellValue('A4', $id)
			->setCellValue('B3', 'Nombre')
			->setCellValue('B4', $faena->nombre)
			->setCellValue('C3', 'Vigente')
			->setCellValue('C4', $faena->vigente)
			->setCellValue('A6','Orígenes-Destinos de la Faena:')
			
			->setCellValue('A7', 'Origen')
			->setCellValue('B7','Destino')
			->setCellValue('C7', 'PU')
			->setCellValue('D7','KMs');
		
		$i = 8;
		foreach($ods as $fila){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $fila->origen->nombre)
				->setCellValue('B'.$i, $fila->destino->nombre)
				->setCellValue('C'.$i, $fila->pu)
				->setCellValue('D'.$i, $fila->kmRecorridos);
			$i++;
		}
		
		$sheet = $objPHPExcel->getActiveSheet();
		$styleArray = array('font' => array('bold' => true));
		$sheet->getStyle('A7')->applyFromArray($styleArray);
		$sheet->getStyle('B7')->applyFromArray($styleArray);
		$sheet->getStyle('C7')->applyFromArray($styleArray);
		$sheet->getStyle('D7')->applyFromArray($styleArray);
		$sheet->getStyle('A1')->applyFromArray($styleArray);
		$sheet->getStyle('A3')->applyFromArray($styleArray);
		$sheet->getStyle('B3')->applyFromArray($styleArray);
		$sheet->getStyle('C3')->applyFromArray($styleArray);
		$sheet->getStyle('A6')->applyFromArray($styleArray);
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Informe');
		
		// Set active sheet index to the first sheet,
		// so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Faena_'.$id.'.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		Yii::app()->end();
		
		//
		// Once we have finished using the library, give back the
		// power to Yii...
		spl_autoload_register(array('YiiBase','autoload'));
	}
	
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }
    
	function actionExportar()
	{
                $data = Faena::model()->findAll();
		
		$this->toExcel($data,
			array('nombre','id','vigente'),
			'Faenas',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ProduccionMaquinaria.xls');
	}
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete','exportar','export'),
                'roles' => array('administrador'),
            ),
            array('allow',
            	'actions'=> array('getOrigenesDestinos'),
            	'roles'=> array('gerencia'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $ods = Faena::model()->listarODs($id);
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'ods' => $ods,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Faena();
    	if (isset($_POST['Faena'])) {
    		$model->attributes = $_POST['Faena'];
    		$model->vigente = $_POST['Faena']['vigente'];
			$valid = true;
    		for($j=0;$j<count($_POST['OrigendestinoFaena']);$j++){
            	$od = new OrigendestinoFaena();
            	$od->origen_id = $_POST['OrigendestinoFaena'][$j]['origen'];
            	$od->destino_id = $_POST['OrigendestinoFaena'][$j]['destino'];
            	$od->faena_id = 1;
            	$od->pu = $_POST['OrigendestinoFaena'][$j]['pu'];
            	$od->kmRecorridos = $_POST['OrigendestinoFaena'][$j]['kmRecorridos'];
            	$valid = $od->validate() && $valid;
            }            
            if(!$valid){
            	Yii::app()->user->setFlash('errorGrabarFaena',"Error en el formulario. Por favor revise los datos.");
            	$this->refresh();
            }
    		if ($valid && $model->validate()) {  
	    		if ($model->save()) {
	    			for($j=0;$j<count($_POST['OrigendestinoFaena']);$j++){
		            	$od = new OrigendestinoFaena();
		            	$od->origen_id = $_POST['OrigendestinoFaena'][$j]['origen'];
		            	$od->destino_id = $_POST['OrigendestinoFaena'][$j]['destino'];
		            	$od->faena_id = $model->id;
		            	$od->pu = $_POST['OrigendestinoFaena'][$j]['pu'];
		            	$od->kmRecorridos = $_POST['OrigendestinoFaena'][$j]['kmRecorridos'];
		            	$od->save();
		            } 
	                $this->redirect(array('view', 'id' => $model->id));
	            }
    		}
           
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = $this->loadModel($id);
        $ods = Faena::model()->listarODs($id);
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Faena'])) {

            $model->attributes = $_POST['Faena'];
            $model->vigente = $_POST['Faena']['vigente'];
            
            if ($model->validate()) {
            	try{
            		//Borrar los ods que no están en la lista
	            	
            		//hacer una lista de los id's que estén en la BD
            		//ir borrando de la lista los que estén en el update
            		//hacer delete de los que queden en la lista
            		$odes=OrigendestinoFaena::model()->findAllByAttributes(array('faena_id'=>$id));
            		
            		$ids = array();
            		foreach($odes as $od){
            			array_push($ids,$od->id);
            		}
            		
            		$final = count($_POST['OrigendestinoFaena']);
            		for($j=0;$j<$final;$j++){
            			if(!isset($_POST['OrigendestinoFaena'][$j])){
            				$final++;
	            			continue;
            			}
						$od = null;
	            		if(isset($_POST['OrigendestinoFaena'][$j]['id'])){
	            			$odId = $_POST['OrigendestinoFaena'][$j]['id'];
	            			if(in_array($odId,$ids)){
	            				$key = array_search($odId,$ids);
							    unset($ids[$key]);
							    $ids = array_values($ids);
	            			}
	            			
	            			$od=OrigendestinoFaena::model()->findByPk($odId);
	            		}            		
	            		
	            		if($od == null){
		            		$od = new OrigendestinoFaena();
	            		}
		            	$od->origen_id = $_POST['OrigendestinoFaena'][$j]['origen'];
		            	$od->destino_id = $_POST['OrigendestinoFaena'][$j]['destino'];
		            	$od->faena_id = $id;
		            	$od->pu = $_POST['OrigendestinoFaena'][$j]['pu'];
		            	$od->kmRecorridos = $_POST['OrigendestinoFaena'][$j]['kmRecorridos'];
		            	if($od->validate()){
		            		$od->save();
		            	}
		            }
		            foreach($ids as $oId){
		            	OrigendestinoFaena::model()->deleteByPk($oId);
		            }
		         	if($model->validate()){
		         		$model->save();
		         	}            
		         	
            	}catch(Exception $e){
            		Yii::app()->user->setFlash('errorGrabarFaena',"No se pudieron guardar los cambios, por favor revise que el Origen/Destino para esta Faena no esté actualmente utilizado en algún reporte.");
            		$this->refresh();
            	}
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'ods' => $ods,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            
            $faena = new Faena();
            $faena->borrarODs($id);            
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Faena');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Faena('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Faena']))
            $model->attributes = $_GET['Faena'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Faena::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'faena-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetOrigenesDestinos(){
    	$val = -1;
    	if(isset($_POST['faena_id'])){
			$val = $_POST['faena_id'];
		}
    	if(isset($_POST['Expediciones']['faena_id'])){
			$val = $_POST['Expediciones']['faena_id'];
		}
		echo CHtml::tag('option',array('value'=>''),CHtml::encode("TODOS de la Faena"),true);
		$ods = OrigendestinoFaena::model()->findAllByAttributes(array('faena_id'=>$val));
		foreach($ods as $od){
			$origen = Origen::model()->findByPk($od->origen_id);
			$destino = Destino::model()->findByPk($od->destino_id);
			echo CHtml::tag('option',array('value'=>$od->id),CHtml::encode("$origen->nombre -> $destino->nombre"),true);
		}
    }
    
}