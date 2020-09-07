<?php

class AdminController extends Controller
{
        /**
         * Declares class-based actions.
         */

        public function actions()
        {
                return array(
                        // captcha action renders the CAPTCHA image displayed on the contact page
                        'captcha' => array(
                                'class' => 'CCaptchaAction',
                                'backColor' => 0xFFFFFF,
                        ),
                        // page action renders "static" pages stored under 'protected/views/site/pages'
                        // They can be accessed via: index.php?r=site/page&view=FileName
                        'page' => array(
                                'class' => 'CViewAction',
                        ),
                );
        }

        public function filters()
        {
                return array(
                        'accessControl', // perform access control for CRUD operations
                );
        }

        public function accessRules()
        {
                return array(
                        array(
                                'allow', // allow admin user to perform 'admin' and 'delete' actions
                                'actions' => array('index', 'error', 'dicc', 'masiva',),
                                'roles' => array('administrador'),
                        ),
                        array(
                                'allow', // allow admin user to perform 'admin' and 'delete' actions
                                'actions' => array('download',),
                                'roles' => array('administrador','gerencia','operativo'),
                        ),
                        array(
                                'deny',  // deny all users
                                'users' => array('*'),
                        ),
                );
        }


        public function actionDownload($id, $file, $tipo)
        {
                $file = Tools::removerApostrofes($file);
		$path=Yii::getPathOfAlias('webroot.protected.archivos') . DIRECTORY_SEPARATOR . $tipo  . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $file;
		if (file_exists($path))
		{
                        $extension = strtolower(pathinfo($path)['extension']);
                        $contenttype = "";
                        switch ($extension) {
                                case 'pdf':
                                        $contenttype = "application/pdf";
                                        break;
                                case 'doc':
                                        $contenttype = "application/msword";
                                        break;
                                case 'docx':
                                        $contenttype = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                                        break;
                                case 'xls':
                                        $contenttype = "application/vnd.ms-excel";
                                        break;
                                case 'xlsx':
                                        $contenttype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                                        break;
                                case 'ppt':
                                        $contenttype = "application/vnd.ms-powerpoint";
                                        break;
                                case 'pptx':
                                        $contenttype = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
                                        break;
                                case 'png':
                                        $contenttype = "image/png";
                                        break;
                                case 'jpg':
                                        $contenttype = "image/jpeg";
                                        break;
                                case 'jpeg':
                                        $contenttype = "image/jpeg";
                                        break;
                                case 'txt':
                                        $contenttype = "text/plain";
                                        break;
                                default:
                                        # code...
                                        break;
                        }
                        
                        header('Content-Type: ' . $contenttype);
			readfile($path);
			exit;
		}
        }

        public function actionMasiva()
        {
                $model = new MasivaForm();
                if (isset($_POST['MasivaForm'])) {
                        $model->attributes = $_POST['MasivaForm'];
                        $model->archivo = CUploadedFile::getInstance($model, 'archivo');
                        if ($model->validate()) {
                                var_dump($model->archivo);
                        }
                }
                $this->render('masiva', array('model' => $model));
        }


        public function actionDicc()
        {

                //primero borrar el contenido de /files por si hay algo

                Tools::cleanDirectory(Yii::app()->basePath . "/files");

                Yii::import('application.vendors.PHPExcel', true);

                $locale = 'es_es';
                $validLocale = PHPExcel_Settings::setLocale($locale);

                //CAMIONES ARRENDADOS
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('CamionesArrendados.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("CamionesArrendados");

                $camionesArrendados = CamionArrendado::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "Capacidad")
                        ->setCellValue('C' . $i, "Consumo Promedio [Km por Lts]")
                        ->setCellValue('D' . $i, "Coeficiente de Trato (porcentaje sobre consumo de combustible)")
                        ->setCellValue('E' . $i, "Producción Mínima Diaria [$]")
                        ->setCellValue('F' . $i, "Horas Mínimas Pactadas")
                        ->setCellValue('G' . $i, "Capacidad medida en")
                        ->setCellValue('H' . $i, "Vigente");
                $i++;
                foreach ($camionesArrendados as $camionArrendado) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($camionArrendado->nombre))
                                ->setCellValue('B' . $i, (float)Tools::cleanExport($camionArrendado->capacidad, 'number'))
                                ->setCellValue('C' . $i, (float)Tools::cleanExport($camionArrendado->consumoPromedio, 'number'))
                                ->setCellValue('D' . $i, (float)Tools::cleanExport($camionArrendado->coeficienteDeTrato, 'number'))
                                ->setCellValue('E' . $i, (float)Tools::cleanExport($camionArrendado->produccionMinima, 'number'))
                                ->setCellValue('F' . $i, (float)Tools::cleanExport($camionArrendado->horasMin, 'number'))
                                ->setCellValue('G' . $i, Tools::cleanExport($camionArrendado->pesoOVolumen))
                                ->setCellValue('H' . $i, Tools::cleanExport($camionArrendado->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Camiones Arrendados');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/CamionesArrendados.xlsx');
                //END CAMIONES ARRENDADOS

                //CAMIONES PROPIOS
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('CamionesPropios.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("CamionesPropios");

                $camionesPropios = CamionPropio::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "Código")
                        ->setCellValue('C' . $i, "Capacidad")
                        ->setCellValue('D' . $i, "Consumo Promedio [Km por Lts]")
                        ->setCellValue('E' . $i, "Coeficiente de Trato (porcentaje sobre consumo de combustible)")
                        ->setCellValue('F' . $i, "Producción Mínima Diaria [$]")
                        ->setCellValue('G' . $i, "Horas Mínimas Pactadas")
                        ->setCellValue('H' . $i, "Capacidad medida en")
                        ->setCellValue('I' . $i, "Vigente");
                $i++;
                foreach ($camionesPropios as $camionPropio) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($camionPropio->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($camionPropio->codigo))
                                ->setCellValue('C' . $i, (float)Tools::cleanExport($camionPropio->capacidad, 'number'))
                                ->setCellValue('D' . $i, (float)Tools::cleanExport($camionPropio->consumoPromedio, 'number'))
                                ->setCellValue('E' . $i, (float)Tools::cleanExport($camionPropio->coeficienteDeTrato, 'number'))
                                ->setCellValue('F' . $i, (float)Tools::cleanExport($camionPropio->produccionMinima, 'number'))
                                ->setCellValue('G' . $i, (float)Tools::cleanExport($camionPropio->horasMin, 'number'))
                                ->setCellValue('H' . $i, Tools::cleanExport($camionPropio->pesoOVolumen))
                                ->setCellValue('I' . $i, Tools::cleanExport($camionPropio->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Camiones Propios');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/CamionesPropios.xlsx');
                //END CAMIONES PROPIOS

                //CHOFERES
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Choferes.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Choferes");

                $choferes = Chofer::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "RUT")
                        ->setCellValue('C' . $i, "Vigente");
                $i++;
                foreach ($choferes as $chofer) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($chofer->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($chofer->rut))
                                ->setCellValue('C' . $i, Tools::cleanExport($chofer->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Choferes');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Choferes.xlsx');
                //END CHOFERES

                //CUENTAS CONTABLES DE REPUESTOS    
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Cuentas Contables de Repuestos.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Cuentas Contables de Repuestos");

                $cuentas = CuentaContableRepuesto::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "ID")
                        ->setCellValue('B' . $i, "Nombre");
                $i++;
                foreach ($cuentas as $cuenta) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($cuenta->id))
                                ->setCellValue('B' . $i, Tools::cleanExport($cuenta->nombre));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Cuentas Contables de Repuestos');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Cuentas Contables de Repuestos.xlsx');
                //END CUENTAS CONTABLES DE REPUESTOS

                //EQUIPOS PROPIOS 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('EquiposPropios.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Equipos Propios");

                $equiposPropios = EquipoPropio::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "Código")
                        ->setCellValue('C' . $i, "Horas Mínimas Diarias")
                        ->setCellValue('D' . $i, "Precio Unitario")
                        ->setCellValue('E' . $i, "Consumo Esperado")
                        ->setCellValue('F' . $i, "Coeficiente de Castigo (porcentaje sobre consumo de combustible)")
                        ->setCellValue('G' . $i, "Valor Unitario Trato Operador")
                        ->setCellValue('H' . $i, "Vigente");
                $i++;
                foreach ($equiposPropios as $equipoPropio) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($equipoPropio->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($equipoPropio->codigo))
                                ->setCellValue('C' . $i, Tools::cleanExport($equipoPropio->horasMin, 'number'))
                                ->setCellValue('D' . $i, Tools::cleanExport($equipoPropio->precioUnitario, 'number'))
                                ->setCellValue('E' . $i, Tools::cleanExport($equipoPropio->consumoEsperado, 'number'))
                                ->setCellValue('F' . $i, Tools::cleanExport($equipoPropio->coeficienteDeTrato, 'number'))
                                ->setCellValue('G' . $i, Tools::cleanExport($equipoPropio->valorHora, 'number'))
                                ->setCellValue('H' . $i, Tools::cleanExport($equipoPropio->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Equipos Propios');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/EquiposPropios.xlsx');
                //END EQUIPOS PROPIOS

                //EQUIPOS ARRENDADOS 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('EquiposArrendados.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Equipos Arrendados");

                $equiposArrendados = EquipoArrendado::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "Horas Mínimas Diarias")
                        ->setCellValue('C' . $i, "Precio Unitario")
                        ->setCellValue('D' . $i, "Consumo Esperado")
                        ->setCellValue('E' . $i, "Coeficiente de Castigo (porcentaje sobre consumo de combustible)")
                        ->setCellValue('F' . $i, "Propietario")
                        ->setCellValue('G' . $i, "Valor Unitario Trato Operador")
                        ->setCellValue('H' . $i, "Vigente");
                $i++;
                foreach ($equiposArrendados as $equipoArrendado) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($equipoArrendado->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($equipoArrendado->horasMin, 'number'))
                                ->setCellValue('C' . $i, Tools::cleanExport($equipoArrendado->precioUnitario, 'number'))
                                ->setCellValue('D' . $i, Tools::cleanExport($equipoArrendado->consumoEsperado, 'number'))
                                ->setCellValue('E' . $i, Tools::cleanExport($equipoArrendado->coeficienteDeTrato, 'number'))
                                ->setCellValue('F' . $i, Tools::cleanExport($equipoArrendado->propietarios->nombre))
                                ->setCellValue('G' . $i, Tools::cleanExport($equipoArrendado->valorHora, 'number'))
                                ->setCellValue('H' . $i, Tools::cleanExport($equipoArrendado->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Equipos Arrendados');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/EquiposArrendados.xlsx');
                //END EQUIPOS ARRENDADOS

                //FAENAS 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Faenas.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Faenas");

                $faenas = Faena::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "ID")
                        ->setCellValue('C' . $i, "Vigente");
                $i++;
                foreach ($faenas as $faena) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($faena->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($faena->id))
                                ->setCellValue('C' . $i, Tools::cleanExport($faena->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Faenas');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Faenas.xlsx');
                //END FAENAS

                //OPERADORES 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Operadores.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Operadores");

                $operadores = Operador::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "RUT")
                        ->setCellValue('C' . $i, "Vigente");
                $i++;
                foreach ($operadores as $operador) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($operador->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($operador->rut))
                                ->setCellValue('C' . $i, Tools::cleanExport($operador->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Operadores');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Operadores.xlsx');
                //END OPERADORES

                //PROPIETARIOS 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Propietarios.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Propietarios");

                $propietarios = Propietario::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "RUT")
                        ->setCellValue('C' . $i, "Vigente");
                $i++;
                foreach ($propietarios as $propietario) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($propietario->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($propietario->rut))
                                ->setCellValue('C' . $i, Tools::cleanExport($propietario->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Propietarios');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Propietarios.xlsx');
                //END PROPIETARIOS

                //SUPERVISORES DE COMBUSTIBLE 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Supervisores Combustible.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Supervisores Combustible");

                $supervisores = SupervisorCombustible::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "RUT")
                        ->setCellValue('C' . $i, "Vigente");
                $i++;
                foreach ($supervisores as $supervisor) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($supervisor->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($supervisor->rut))
                                ->setCellValue('C' . $i, Tools::cleanExport($supervisor->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Supervisores Combustible');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Supervisores Combustible.xlsx');
                //END SUPERVISORES DE COMBUSTIBLE

                //SUPERVISORES DE RENDICIÓN 
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Supervisores de Rendición.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Supervisores de Rendición");

                $rendidores = Rendidor::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "RUT")
                        ->setCellValue('C' . $i, "Vigente");
                $i++;
                foreach ($rendidores as $rendidor) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($rendidor->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($rendidor->rut))
                                ->setCellValue('C' . $i, Tools::cleanExport($rendidor->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Supervisores de Rendición');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Supervisores de Rendición.xlsx');
                //END SUPERVISORES DE RENDICIÓN

                //TIPOS COMBUSTIBLE
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Tipos Combustible.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Tipos Combustible");

                $tiposCombustible = TipoCombustible::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "Vigente");
                $i++;
                foreach ($tiposCombustible as $tipoCombustible) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($tipoCombustible->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($tipoCombustible->vigente));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Tipos Combustible');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Tipos Combustible.xlsx');
                //END TIPOS COMBUSTIBLE

                //UNIDADES
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("")
                        ->setLastModifiedBy("")
                        ->setTitle('Unidades.xlsx')
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("Unidades");

                $unidades = Unidad::model()->findAll();
                $i = 1;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, "Nombre")
                        ->setCellValue('B' . $i, "Sigla");
                $i++;
                foreach ($unidades as $unidad) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $i, Tools::cleanExport($unidad->nombre))
                                ->setCellValue('B' . $i, Tools::cleanExport($unidad->sigla));
                        $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Unidades');
                $objPHPExcel->setActiveSheetIndex(0);

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save(Yii::app()->basePath . '/files/Unidades.xlsx');
                //END UNIDADES

                //FAENAS ACTIVAS PARA EL DIRECTORIO FAENA
                $faenasActivas = Faena::model()->findAllByAttributes(array('vigente' => 'SI'));
                foreach ($faenasActivas as $faena) {
                        $objPHPExcel = new PHPExcel();
                        $objPHPExcel->getProperties()->setCreator("")
                                ->setLastModifiedBy("")
                                ->setTitle('Faena_' . $faena->id)
                                ->setSubject("")
                                ->setDescription("")
                                ->setKeywords("")
                                ->setCategory("Faena_" . $faena->id);

                        $ods = Faena::model()->listarODs($faena->id);

                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A1', 'Faena #' . $faena->id)
                                ->setCellValue('A3', 'ID')
                                ->setCellValue('A4', $faena->id)
                                ->setCellValue('B3', 'Nombre')
                                ->setCellValue('B4', Tools::cleanExport($faena->nombre))
                                ->setCellValue('C3', 'Vigente')
                                ->setCellValue('C4', Tools::cleanExport($faena->vigente))
                                ->setCellValue('A6', 'Orígenes-Destinos de la Faena:')

                                ->setCellValue('A7', 'Origen')
                                ->setCellValue('B7', 'Destino')
                                ->setCellValue('C7', 'PU')
                                ->setCellValue('D7', 'KMs');

                        $i = 8;
                        foreach ($ods as $fila) {
                                $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('A' . $i, Tools::cleanExport($fila->origen->nombre))
                                        ->setCellValue('B' . $i, Tools::cleanExport($fila->destino->nombre))
                                        ->setCellValue('C' . $i, Tools::cleanExport($fila->pu, 'numeric'))
                                        ->setCellValue('D' . $i, Tools::cleanExport($fila->kmRecorridos, 'numeric'));
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

                        $objPHPExcel->getActiveSheet()->setTitle('Faena_' . $faena->id);
                        $objPHPExcel->setActiveSheetIndex(0);

                        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                        $objWriter->save(Yii::app()->basePath . '/files/Faena/Faena_' . $faena->id . '.xlsx');
                }
                //END FAENAS ACTIVAS PARA EL DIRECTORIO FAENA


                //COMPRIMIR TODO EL DIRECTORIO
                $rootPath = realpath(Yii::app()->basePath . '/files');
                $zip = new ZipArchive();
                $zip->open(Yii::app()->basePath . '/files/Dicc.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

                $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($rootPath),
                        RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                                $filePath = $file->getRealPath();
                                $relativePath = substr($filePath, strlen($rootPath) + 1);
                                $zip->addFile($filePath, $relativePath);
                        }
                }
                $zip->close();
                //END COMPRIMIR TODO EL DIRECTORIO


                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=Dicc.zip");
                header("Content-length: " . filesize(Yii::app()->basePath . '/files/Dicc.zip'));
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile(Yii::app()->basePath . '/files/Dicc.zip');


                //al finalizar, limpiar el directorio
                Tools::cleanDirectory(Yii::app()->basePath . "/files");

                Yii::app()->end();
                spl_autoload_register(array('YiiBase', 'autoload'));
        }

        /**
         * This is the default 'index' action that is invoked
         * when an action is not explicitly requested by users.
         */
        public function actionIndex()
        {
                $this->render('indexAdmin');
        }

        /**
         * This is the action to handle external exceptions.
         */
        public function actionError()
        {
                if ($error = Yii::app()->errorHandler->error) {
                        if (Yii::app()->request->isAjaxRequest)
                                echo $error['message'];
                        else
                                $this->render('error', $error);
                }
        }





        /**
         * Logs out the current user and redirect to homepage.
         */
        public function actionLogout()
        {
                Yii::app()->user->logout();
                $this->redirect(Yii::app()->homeUrl);
        }
}
