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
		$us = Unidadfaena::model()->findAllByAttributes(['faena_id'=>$id]);
		$use = UnidadfaenaEquipo::model()->findAllByAttributes(['faena_id'=>$id]);
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

		$i++;
		$iTitulos = $i;

		$i++;
		foreach($us as $u){
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, isset($u->camionpropio)?$u->camionpropio->nombre." (propio)":$u->camionarrendado->nombre." (arrendado)")
					->setCellValue('B'.$i, Unidadfaena::getUnidad($u->unidad))
					->setCellValue('C'.$i, $u->pu)
					->setCellValue('D'.$i, $u->observaciones);
			$i++;
		}
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$iTitulos,'Camión')
		->setCellValue('B'.$iTitulos,'Unidad')
		->setCellValue('C'.$iTitulos, 'PU')
		->setCellValue('D'.$iTitulos, 'Observaciones');
		$i++;
		$iTitulos2 = $i;
		$i++;

		foreach($use as $u){
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, isset($u->equipopropio)?$u->equipopropio->nombre." (propio)":$u->equipoarrendado->nombre." (arrendado)")
					->setCellValue('B'.$i, Unidadfaena::getUnidad($u->unidad))
					->setCellValue('C'.$i, $u->pu)
					->setCellValue('D'.$i, $u->observaciones);
			$i++;
		}
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$iTitulos2,'Equipo')
		->setCellValue('B'.$iTitulos2,'Unidad')
		->setCellValue('C'.$iTitulos2, 'PU')
		->setCellValue('D'.$iTitulos2, 'Observaciones');

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
		$sheet->getStyle('A'.$iTitulos)->applyFromArray($styleArray);
		$sheet->getStyle('B'.$iTitulos)->applyFromArray($styleArray);
		$sheet->getStyle('C'.$iTitulos)->applyFromArray($styleArray);
		$sheet->getStyle('D'.$iTitulos)->applyFromArray($styleArray);
		$sheet->getStyle('A'.$iTitulos2)->applyFromArray($styleArray);
		$sheet->getStyle('B'.$iTitulos2)->applyFromArray($styleArray);
		$sheet->getStyle('C'.$iTitulos2)->applyFromArray($styleArray);
		$sheet->getStyle('D'.$iTitulos2)->applyFromArray($styleArray);
		
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
                'actions' => array('index', 'admin', 'view', 'createv', 'update', 'adminv', 'delete','exportar','export','listunits','getunit'),
                'roles' => array('administrador'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('listunits','getunit'),
                'roles' => array('operativo'),
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
		$us = Unidadfaena::model()->findAllByAttributes(['faena_id'=>$id]);
		$use = UnidadfaenaEquipo::model()->findAllByAttributes(['faena_id'=>$id]);
        $this->render('view', array(
            'model' => $this->loadModel($id),
			'ods' => $ods,
			'us' => $us,
			'use' => $use,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreatev() {
        $model = new Faena();
    	if (isset($_POST['Faena'])) {
    		$model->attributes = $_POST['Faena'];
    		$model->vigente = $_POST['Faena']['vigente'];
			$valid = true;
			if(isset($_POST['OrigendestinoFaena'])){
				for($j=0;$j<count($_POST['OrigendestinoFaena']);$j++){
					$od = new OrigendestinoFaena();
					$od->origen_id = $_POST['OrigendestinoFaena'][$j]['origen'];
					$od->destino_id = $_POST['OrigendestinoFaena'][$j]['destino'];
					$od->faena_id = 1;
					$od->pu = $_POST['OrigendestinoFaena'][$j]['pu'];
					$od->kmRecorridos = $_POST['OrigendestinoFaena'][$j]['kmRecorridos'];
					$valid = $od->validate() && $valid;
				}  
			}

			if(isset($_POST['Unidadfaena'])){
				for($j=0;$j<count($_POST['Unidadfaena']);$j++){
					$od = new Unidadfaena();
					$od->unidad = $_POST['Unidadfaena'][$j]['unidad'];
					$od->faena_id = 1;
					$od->pu = $_POST['Unidadfaena'][$j]['pu'];
					$valid = $od->validate() && $valid;
				}  
			}

			if(isset($_POST['UnidadfaenaEquipo'])){
				for($j=0;$j<count($_POST['UnidadfaenaEquipo']);$j++){
					$od = new UnidadfaenaEquipo();
					$od->unidad = $_POST['UnidadfaenaEquipo'][$j]['unidad'];
					$od->faena_id = 1;
					$od->pu = $_POST['UnidadfaenaEquipo'][$j]['pu'];
					$valid = $od->validate() && $valid;
				}  
			}
    		          
            if(!$valid){
            	Yii::app()->user->setFlash('errorGrabarFaena',"Error en el formulario. Por favor revise los datos.");
            	$this->refresh();
            }
    		if ($valid && $model->validate()) {  
	    		if ($model->save()) {
					if(isset($_POST['OrigendestinoFaena'])){
						for($j=0;$j<count($_POST['OrigendestinoFaena']);$j++){
							$od = new OrigendestinoFaena();
							$od->origen_id = $_POST['OrigendestinoFaena'][$j]['origen'];
							$od->destino_id = $_POST['OrigendestinoFaena'][$j]['destino'];
							$od->faena_id = $model->id;
							$od->pu = $_POST['OrigendestinoFaena'][$j]['pu'];
							$od->kmRecorridos = $_POST['OrigendestinoFaena'][$j]['kmRecorridos'];
							$od->save();
						} 
					}
					if(isset($_POST['Unidadfaena'])){
						for($j=0;$j<count($_POST['Unidadfaena']);$j++){
							$od = new Unidadfaena();
							$od->unidad = $_POST['Unidadfaena'][$j]['unidad'];
							$od->faena_id = $model->id;
							if((int)$_POST['Unidadfaena'][$j]['camionpropio_id']>0){
								$od->camionpropio_id = (int)$_POST['Unidadfaena'][$j]['camionpropio_id'];
							}
							if((int)$_POST['Unidadfaena'][$j]['camionarrendado_id']>0){
								$od->camionarrendado_id = (int)$_POST['Unidadfaena'][$j]['camionarrendado_id'];
							}
							$od->pu = $_POST['Unidadfaena'][$j]['pu'];
							$od->save();
						}  
					}
					if(isset($_POST['UnidadfaenaEquipo'])){
						for($j=0;$j<count($_POST['UnidadfaenaEquipo']);$j++){
							$od = new UnidadfaenaEquipo();
							$od->unidad = $_POST['UnidadfaenaEquipo'][$j]['unidad'];
							$od->faena_id = $model->id;
							if((int)$_POST['UnidadfaenaEquipo'][$j]['equipopropio_id']>0){
								$od->equipopropio_id = (int)$_POST['UnidadfaenaEquipo'][$j]['equipopropio_id'];
							}
							if((int)$_POST['UnidadfaenaEquipo'][$j]['equipoarrendado_id']>0){
								$od->equipoarrendado_id = (int)$_POST['UnidadfaenaEquipo'][$j]['equipoarrendado_id'];
							}
							$od->pu = $_POST['UnidadfaenaEquipo'][$j]['pu'];
							$od->save();
						}  
					}
	                $this->redirect(array('view', 'id' => $model->id));
	            }
    		}
           
        }
        $this->render('createv', array(
            'model' => $model,
        ));
	}
	
	

	public function actionAdmin(){
		$this->redirect(CController::createUrl("//site/index"));
	}

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = $this->loadModel($id);
		$ods = Faena::model()->listarODs($id);
		$unidades = Unidadfaena::model()->findAllByAttributes(['faena_id'=>$id]);
		$unidadesE = UnidadfaenaEquipo::model()->findAllByAttributes(['faena_id'=>$id]);
        
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
					
					if(isset($_POST['OrigendestinoFaena'])){
						foreach($_POST['OrigendestinoFaena'] as $odp){
							$od = null;
							if(isset($odp['id'])){
								if(in_array($odp['id'],$ids)){
									$key = array_search($odp['id'],$ids);
									unset($ids[$key]);
									$ids = array_values($ids);
								}
								$od=OrigendestinoFaena::model()->findByPk($odp['id']);  
							}							          		
							
							if($od == null){
								$od = new OrigendestinoFaena();
							}
							$od->origen_id = $odp['origen'];
							$od->destino_id = $odp['destino'];
							$od->kmRecorridos = $odp['kmRecorridos'];
							$od->faena_id = $id;
							$od->pu = $odp['pu'];
							
							if($od->validate()){
								$od->save();
							}
						}
					}
            		
		            foreach($ids as $oId){
		            	OrigendestinoFaena::model()->deleteByPk($oId);
					}

					
					//Borrar los ods que no están en la lista
	            	
            		//hacer una lista de los id's que estén en la BD
            		//ir borrando de la lista los que estén en el update
            		//hacer delete de los que queden en la lista
            		$ues=Unidadfaena::model()->findAllByAttributes(array('faena_id'=>$id));
            		$ids = array();
            		foreach($ues as $u){
            			array_push($ids,$u->id);
					}

					$uese=UnidadfaenaEquipo::model()->findAllByAttributes(array('faena_id'=>$id));
            		$idse = array();
            		foreach($uese as $ue){
            			array_push($idse,$ue->id);
					}

					if(isset($_POST['Unidadfaena'])){
						foreach($_POST['Unidadfaena'] as $up){
							$u = null;
							if(isset($up['id'])){
								if(in_array($up['id'],$ids)){
									$key = array_search($up['id'],$ids);
									unset($ids[$key]);
									$ids = array_values($ids);
								}
								$u = Unidadfaena::model()->findByPk($up['id']);      
							} 		
							
							if($u == null){
								$u = new Unidadfaena();
							}

							$u->unidad = $up['unidad'];
							$u->faena_id = $id;
							if($up['tipo_camion'] == "propios"){
								$u->camionpropio_id = $up['camionpropio_id'];
								$u->camionarrendado_id = null;
							}
							if($up['tipo_camion'] == "arrendados"){
								$u->camionarrendado_id = $up['camionarrendado_id'];
								$u->camionpropio_id = null;
							}
							$u->pu = $up['pu'];
							
							$u->observaciones = $up['observaciones'];
							if($u->validate()){
								$u->save();
							}
						}
					}

					if(isset($_POST['UnidadfaenaEquipo'])){
						foreach($_POST['UnidadfaenaEquipo'] as $upe){
							$ue = null;
							if(isset($upe['id'])){
								if(in_array($upe['id'],$idse)){
									$key = array_search($upe['id'],$idse);
									unset($idse[$key]);
									$idse = array_values($idse);
								}
								$ue = UnidadfaenaEquipo::model()->findByPk($upe['id']);      
							} 		
							
							if($ue == null){
								$ue = new UnidadfaenaEquipo();
							}

							$ue->unidad = $upe['unidad'];
							$ue->faena_id = $id;
							if($upe['tipo_equipo'] == "propios"){
								$ue->equipopropio_id = $upe['equipopropio_id'];
								$ue->equipoarrendado_id = null;
							}
							if($upe['tipo_equipo'] == "arrendados"){
								$ue->equipoarrendado_id = $upe['equipoarrendado_id'];
								$ue->equipopropio_id = null;
							}
							$ue->pu = $upe['pu'];
							$ue->observaciones = $upe['observaciones'];
							if($ue->validate()){
								$ue->save();
							}
						}
					}

		            foreach($ids as $uId){
		            	Unidadfaena::model()->deleteByPk($uId);
					}

					foreach($idse as $uIde){
		            	UnidadfaenaEquipo::model()->deleteByPk($uIde);
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
			'unidades' => $unidades,
			'unidadesE' => $unidadesE,
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

	public function actionListunits() {
		$faena_id = (int)$_POST['faena_id'];
		$camion_id = (int)$_POST['camion_id'];
		$selunidad = "";
		$arrendado = false;
		if(isset($_POST['selunidad'])){
			$selunidad = $_POST['selunidad'];
		}
		if(isset($_POST['arrendado'])){
			$arrendado = true;
		}
		$unidades = Unidadfaena::model()->findAllByAttributes(['faena_id'=>$faena_id,'camionpropio_id'=>$camion_id]);
		if($arrendado){
			$unidades = Unidadfaena::model()->findAllByAttributes(['faena_id'=>$faena_id,'camionarrendado_id'=>$camion_id]);
		}
		$dev = "";
		$pu = 0;
		$primero = true;
		foreach($unidades as $unidad){
			$selected = "";
			if($selunidad == $unidad->id){
				$selected = "selected";
			}
			if($primero){
				$primero = false;
				$pu = $unidad->pu;
			}
			$dev .= "<option $selected value='" . $unidad->id . "'>" . Unidadfaena::getUnidad($unidad->unidad) . "</option>";
		}
		echo $dev."-||-".$pu;
	}
	
	public function actionGetunit() {
		if(!isset($_POST['unidad_id'])){
			echo "ERROR";
		}
		else{
			$unidad_id = (int)$_POST['unidad_id'];
			$unidad = Unidadfaena::model()->findByPk($unidad_id);
			echo $unidad->pu;
		}
		
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
    public function actionAdminv() {
        $model = new Faena('search');
		$model->unsetAttributes();  // clear any default values
        if (isset($_GET['Faena']))
            $model->attributes = $_GET['Faena'];

        $this->render('adminv', array(
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