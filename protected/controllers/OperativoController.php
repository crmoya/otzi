<?php

class OperativoController extends Controller
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

	public function actionRendidor(){
		$rut = $_POST['rut'];
                $rut = addcslashes($rut, '%_'); 
                $q = new CDbCriteria( array(
                    'condition' => "rut LIKE :rut and vigente = 'SÍ'",         
                    'params'    => array(':rut' => "$rut%")  
                ) );
                $rendidores = Rendidor::model()->findAll( $q );  
		//$rendidor = Rendidor::model()->findByAttributes(array('rut'=>$rut));
                $resp = "";
		foreach($rendidores as $rendidor)
                    $resp .= $rendidor->nombre."|";
                if(count($rendidores)>0){
                    $resp = substr($resp, 0,  strlen($resp)-1);
                }
                echo $resp;
	}
        
        public function actionProveedor(){
		$rut = $_POST['rut'];
                $rut = addcslashes($rut, '%_'); 
                $q = new CDbCriteria( array(
                    'condition' => "rut LIKE :rut",         
                    'params'    => array(':rut' => "$rut%")  
                ) );
                $proveedores = Proveedor::model()->findAll( $q );  
		$resp = "";
		foreach($proveedores as $proveedor)
                    $resp .= $proveedor->nombre."|";
                if(count($proveedores)>0){
                    $resp = substr($resp, 0,  strlen($resp)-1);
                }
                echo $resp;
	}
        
        public function actionRendidorRut(){
		$nombre = $_POST['nombre'];
                $nombre = addcslashes($nombre, '%_'); 
                $q = new CDbCriteria( array(
                    'condition' => "nombre LIKE :nombre  and vigente = 'SÍ'",         
                    'params'    => array(':nombre' => "$nombre%")  
                ) );
                $rendidores = Rendidor::model()->findAll( $q );  
		//$rendidor = Rendidor::model()->findByAttributes(array('rut'=>$rut));
                $resp = "";
		foreach($rendidores as $rendidor)
                    $resp .= $rendidor->rut."|";
                if(count($rendidores)>0){
                    $resp = substr($resp, 0,  strlen($resp)-1);
                }
                echo $resp;
	}
        
        public function actionProveedorRut(){
		$nombre = $_POST['nombre'];
                $nombre = addcslashes($nombre, '%_'); 
                $q = new CDbCriteria( array(
                    'condition' => "nombre LIKE :nombre",         
                    'params'    => array(':nombre' => "$nombre%")  
                ) );
                $proveedores = Proveedor::model()->findAll( $q );  
		$resp = "";
		foreach($proveedores as $proveedor)
                    $resp .= $proveedor->rut."|";
                if(count($proveedores)>0){
                    $resp = substr($resp, 0,  strlen($resp)-1);
                }
                echo $resp;
	}
        
        public function actionRendidorRutExacto(){
		$nombre = $_POST['nombre'];
                $rendidores = Rendidor::model()->findAllByAttributes(array('nombre'=>$nombre,'vigente'=>'SÍ'));  
		$resp = "";
		foreach($rendidores as $rendidor)
                    $resp .= $rendidor->rut."|";
                if(count($rendidores)>0){
                    $resp = substr($resp, 0,  strlen($resp)-1);
                }
                echo $resp;
	}
        
        public function actionProveedorRutExacto(){
		$nombre = $_POST['nombre'];
                $proveedores = Proveedor::model()->findAllByAttributes(array('nombre'=>$nombre));  
		$resp = "";
		foreach($proveedores as $proveedor)
                    $resp .= $proveedor->rut."|";
                if(count($proveedores)>0){
                    $resp = substr($resp, 0,  strlen($resp)-1);
                }
                echo $resp;
	}
	
        
        public function actionValidaReporteUnico(){
            $report_nro = $_POST['report'];
            $report = RCamionArrendado::model()->findByAttributes(array('reporte'=>$report_nro));
            if($report!=null){
                echo 1;
                die;
            }
            $report = RCamionPropio::model()->findByAttributes(array('reporte'=>$report_nro));
            if($report!=null){
                echo 1;
                die;
            }
            $report = REquipoArrendado::model()->findByAttributes(array('reporte'=>$report_nro));
            if($report!=null){
                echo 1;
                die;
            }
            $report = REquipoPropio::model()->findByAttributes(array('reporte'=>$report_nro));
            if($report!=null){
                echo 1;
                die;
            }
            echo 0;
            die;
        }
        
	public function actionCamionesPropios(){

		
		$model=new RCamionPropio();
		if(isset($_POST['RCamionPropio']))
		{
                    
			$model->attributes=$_POST['RCamionPropio'];
                        $model->observaciones_obra = $_POST['RCamionPropio']['observaciones_obra'];
			$model->fecha=Tools::fixFecha($model->fecha);
			$model->usuario_id = Yii::app()->user->id;
			
			
			$model->iniPanne = $_POST['RCamionPropio']['iniPanne'];
			$model->finPanne = $_POST['RCamionPropio']['finPanne'];
			$model->panne = $_POST['RCamionPropio']['panne'];
			
			if($model->panne == 1){
				$iniPanne = str_replace(":","",$_POST['RCamionPropio']['iniPanne']);
				$finPanne = str_replace(":","",$_POST['RCamionPropio']['finPanne']);
				$minutos = $finPanne - $iniPanne;
				$horas = (int)($minutos/100);
				$min = $minutos%100;
				$model->minPanne = $horas * 60 + $min;
			} 
			else{ 	
				$model->minPanne = 0;
			}
			$valid = $model->validate();
			
			if($valid){				
				$model->save();
				if(isset($_POST['ViajeCamionPropio'])){
					foreach($_POST['ViajeCamionPropio'] as $i=>$viajeArr){
						$viaje = new ViajeCamionPropio();
						$viaje->faena_id = $viajeArr['faena_id'];
						$viaje->origendestino_faena_id = $viajeArr['origendestino_faena_id'];
						$viaje->kmRecorridos = $viajeArr['kmRecorridos'];
						$viaje->nVueltas = $viajeArr['nVueltas'];
						$viaje->rCamionPropio_id = $model->id;
						$viaje->total = $viajeArr['total'];
						$viaje->totalTransportado = $viajeArr['totalTransportado'];
						$viaje->coeficiente = $viajeArr['coeficiente'];
						
						$valid = $valid && $viaje->validate();
						if($valid){
							$viaje->save();
						}
					}				
				}
				if(isset($_POST['CargaCombCamionPropio'])){
					foreach($_POST['CargaCombCamionPropio'] as $i=>$cargaArr){
						$carga = new CargaCombCamionPropio();
						$carga->factura = $cargaArr['factura'];
						$carga->faena_id = $cargaArr['faena_id'];
						$carga->guia = $cargaArr['guia'];
						$carga->kmCarguio = $cargaArr['kmCarguio'];
						$carga->petroleoLts = $cargaArr['petroleoLts'];
						$carga->precioUnitario = $cargaArr['precioUnitario'];
						$carga->rCamionPropio_id = $model->id;
						$carga->supervisorCombustible_id = $cargaArr['supervisorCombustible_id'];
						$carga->tipoCombustible_id = $cargaArr['tipoCombustible_id'];
						$carga->valorTotal = $cargaArr['valorTotal'];
						$carga->numero = $cargaArr['numero'];
						$carga->nombre = $cargaArr['nombre'];
						$carga->rut_rinde = $cargaArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($carga->rut_rinde, $carga->nombre);
						$carga->fechaRendicion = $cargaArr['fechaRendicion'];
                                                $carga->observaciones = $cargaArr['observaciones'];
                                                $carga->nombre_proveedor = $cargaArr['nombre_proveedor'];
                                                $carga->rut_proveedor = $cargaArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($carga->rut_proveedor,$carga->nombre_proveedor);
                                                $carga->tipo_documento = $cargaArr['tipo_documento'];
						$valid = $valid && $carga->validate();
						if($valid){
							$carga->save();
						}
					}				
				}
				if(isset($_POST['CompraRepuestoCamionPropio'])){
                                    
					foreach($_POST['CompraRepuestoCamionPropio'] as $i=>$compraArr){
						$compra = new CompraRepuestoCamionPropio();
						$compra->factura = $compraArr['factura'];
						$compra->guia = $compraArr['guia'];
						$compra->montoNeto = $compraArr['montoNeto'];
						$compra->rCamionPropio_id = $model->id;
						$compra->repuesto = $compraArr['repuesto'];
						$compra->cantidad = $compraArr['cantidad'];
						$compra->unidad = $compraArr['unidad'];
						$compra->faena_id = $compraArr['faena_id'];
						$compra->numero = $compraArr['numero'];
						$compra->nombre = $compraArr['nombre'];
						$compra->rut_rinde = $compraArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($compra->rut_rinde, $compra->nombre);
						$compra->tipo_documento = $compraArr['tipo_documento'];
						$compra->fechaRendicion = $compraArr['fechaRendicion'];
                                                $compra->observaciones = $compraArr['observaciones'];
                                                $compra->nombre_proveedor = $compraArr['nombre_proveedor'];
                                                $compra->rut_proveedor = $compraArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($compra->rut_proveedor,$compra->nombre_proveedor);
                                                $compra->cuenta = $compraArr['cuenta'];
						
						$valid = $valid && $compra->validate();
						if($valid){
							$compra->save();
						}
					}				
				}
			}
			else{
				Yii::app()->user->setFlash('camionesError',"Error. Datos del formulario erróneos: ".CHtml::errorSummary($model));
				$this->refresh();
			}
			
			if($valid){
				Yii::app()->user->setFlash('camionesMessage',"Datos guardados correctamente.");
				$this->refresh();
			}		
			
		}
		$this->render('camionesPropios',array('model'=>$model));
	}


	public function actionEquiposPropios(){

		$model=new REquipoPropio();
		if(isset($_POST['REquipoPropio']))
		{
			$model->attributes=$_POST['REquipoPropio'];
                        $model->observaciones_obra = $_POST['REquipoPropio']['observaciones_obra'];
			$model->fecha=Tools::fixFecha($model->fecha);
			
			$model->iniPanne = $_POST['REquipoPropio']['iniPanne'];
			$model->finPanne = $_POST['REquipoPropio']['finPanne'];
			$model->panne = $_POST['REquipoPropio']['panne'];
			$model->usuario_id = Yii::app()->user->id;
			
			if($model->panne == 1){
				$iniPanne = str_replace(":","",$_POST['REquipoPropio']['iniPanne']);
				$finPanne = str_replace(":","",$_POST['REquipoPropio']['finPanne']);
				$minutos = $finPanne - $iniPanne;
				$horas = (int)($minutos/100);
				$min = $minutos%100;
				$model->minPanne = $horas * 60 + $min;
			} 
			else{ 	
				$model->minPanne = 0;
			}
			
			$valid = $model->validate();
			if($valid){				
				$model->save();
				if(isset($_POST['CargaCombEquipoPropio'])){
					foreach($_POST['CargaCombEquipoPropio'] as $i=>$cargaArr){
						$carga = new CargaCombEquipoPropio();
						$carga->factura = $cargaArr['factura'];
						$carga->faena_id = $cargaArr['faena_id'];
						$carga->guia = $cargaArr['guia'];
						$carga->hCarguio = $cargaArr['hCarguio'];
						$carga->petroleoLts = $cargaArr['petroleoLts'];
						$carga->precioUnitario = $cargaArr['precioUnitario'];
						$carga->rEquipoPropio_id = $model->id;
						$carga->supervisorCombustible_id = $cargaArr['supervisorCombustible_id'];
						$carga->tipoCombustible_id = $cargaArr['tipoCombustible_id'];
						$carga->valorTotal = $cargaArr['valorTotal'];
						$carga->numero = $cargaArr['numero'];
						$carga->nombre = $cargaArr['nombre'];
						$carga->rut_rinde = $cargaArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($carga->rut_rinde, $carga->nombre);
						$carga->fechaRendicion = $cargaArr['fechaRendicion'];
                                                $carga->observaciones = $cargaArr['observaciones'];
                                                $carga->nombre_proveedor = $cargaArr['nombre_proveedor'];
                                                $carga->rut_proveedor = $cargaArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($carga->rut_proveedor,$carga->nombre_proveedor);
                                                $carga->tipo_documento = $cargaArr['tipo_documento'];
						$valid = $valid && $carga->validate();
						if($valid){
							$carga->save();
						}
					}				
				}
				if(isset($_POST['CompraRepuestoEquipoPropio'])){
					foreach($_POST['CompraRepuestoEquipoPropio'] as $i=>$compraArr){
						$compra = new CompraRepuestoEquipoPropio();
						$compra->factura = $compraArr['factura'];
						$compra->guia = $compraArr['guia'];
						$compra->montoNeto = $compraArr['montoNeto'];
						$compra->rEquipoPropio_id = $model->id;
						$compra->repuesto = $compraArr['repuesto'];
						$compra->cantidad = $compraArr['cantidad'];
						$compra->unidad = $compraArr['unidad'];
						$compra->faena_id = $compraArr['faena_id'];
						$compra->numero = $compraArr['numero'];
						$compra->nombre = $compraArr['nombre'];
						$compra->rut_rinde = $compraArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($compra->rut_rinde, $compra->nombre);
						$compra->tipo_documento = $compraArr['tipo_documento'];
						$compra->fechaRendicion = $compraArr['fechaRendicion'];
                                                $compra->observaciones = $compraArr['observaciones'];
                                                $compra->nombre_proveedor = $compraArr['nombre_proveedor'];
                                                $compra->rut_proveedor = $compraArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($compra->rut_proveedor,$compra->nombre_proveedor);
                                                $compra->cuenta = $compraArr['cuenta'];
						$valid = $valid && $compra->validate();
						if($valid){
							$compra->save();
						}
					}				
				}
			}
			else{
				Yii::app()->user->setFlash('equiposError',CHtml::errorSummary($model));
				$this->refresh();
			}
			
			if($valid){
				Yii::app()->user->setFlash('equiposMessage',"Datos guardados correctamente.");
				$this->refresh();
			}	
                        else{
				Yii::app()->user->setFlash('equiposError',CHtml::errorSummary($model));
				$this->refresh();
			}
			
		}
		$this->render('equiposPropios',array('model'=>$model));

	}

	public function actionEquiposArrendados(){

		$model=new REquipoArrendado();
		if(isset($_POST['REquipoArrendado']))
		{
			$model->attributes=$_POST['REquipoArrendado'];
                        $model->observaciones_obra = $_POST['REquipoArrendado']['observaciones_obra'];
			$model->fecha=Tools::fixFecha($model->fecha);
			$model->usuario_id = Yii::app()->user->id;
			
			$model->iniPanne = $_POST['REquipoArrendado']['iniPanne'];
			$model->finPanne = $_POST['REquipoArrendado']['finPanne'];
			$model->panne = $_POST['REquipoArrendado']['panne'];
			
			if($model->panne == 1){
				$iniPanne = str_replace(":","",$_POST['REquipoArrendado']['iniPanne']);
				$finPanne = str_replace(":","",$_POST['REquipoArrendado']['finPanne']);
				$minutos = $finPanne - $iniPanne;
				$horas = (int)($minutos/100);
				$min = $minutos%100;
				$model->minPanne = $horas * 60 + $min;
			} 
			else{ 	
				$model->minPanne = 0;
			}
			
			$valid = $model->validate();
			
			if($valid){				
				$model->save();
				if(isset($_POST['CargaCombEquipoArrendado'])){
					foreach($_POST['CargaCombEquipoArrendado'] as $i=>$cargaArr){
						$carga = new CargaCombEquipoArrendado();
						$carga->factura = $cargaArr['factura'];
						$carga->faena_id = $cargaArr['faena_id'];
						$carga->guia = $cargaArr['guia'];
						$carga->hCarguio = $cargaArr['hCarguio'];
						$carga->petroleoLts = $cargaArr['petroleoLts'];
						$carga->precioUnitario = $cargaArr['precioUnitario'];
						$carga->rEquipoArrendado_id = $model->id;
						$carga->supervisorCombustible_id = $cargaArr['supervisorCombustible_id'];
						$carga->tipoCombustible_id = $cargaArr['tipoCombustible_id'];
						$carga->valorTotal = $cargaArr['valorTotal'];
						$carga->numero = $cargaArr['numero'];
						$carga->nombre = $cargaArr['nombre'];
						$carga->rut_rinde = $cargaArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($carga->rut_rinde, $carga->nombre);
						$carga->fechaRendicion = $cargaArr['fechaRendicion'];
                                                $carga->observaciones = $cargaArr['observaciones'];
                                                $carga->nombre_proveedor = $cargaArr['nombre_proveedor'];
                                                $carga->rut_proveedor = $cargaArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($carga->rut_proveedor,$carga->nombre_proveedor);
                                                $carga->tipo_documento = $cargaArr['tipo_documento'];
						$valid = $valid && $carga->validate();
						if($valid){
							$carga->save();
						}
					}				
				}
				if(isset($_POST['CompraRepuestoEquipoArrendado'])){
					foreach($_POST['CompraRepuestoEquipoArrendado'] as $i=>$compraArr){
						$compra = new CompraRepuestoEquipoArrendado();
						$compra->factura = $compraArr['factura'];
						$compra->guia = $compraArr['guia'];
						$compra->montoNeto = $compraArr['montoNeto'];
						$compra->rEquipoArrendado_id = $model->id;
						$compra->repuesto = $compraArr['repuesto'];
						$compra->cantidad = $compraArr['cantidad'];
						$compra->unidad = $compraArr['unidad'];
						$compra->faena_id = $compraArr['faena_id'];
						$compra->numero = $compraArr['numero'];
						$compra->nombre = $compraArr['nombre'];
						$compra->rut_rinde = $compraArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($compra->rut_rinde, $compra->nombre);
						$compra->tipo_documento = $compraArr['tipo_documento'];
						$compra->fechaRendicion = $compraArr['fechaRendicion'];
                                                $compra->observaciones = $compraArr['observaciones'];
                                                $compra->nombre_proveedor = $compraArr['nombre_proveedor'];
                                                $compra->rut_proveedor = $compraArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($compra->rut_proveedor,$compra->nombre_proveedor);
                                                $compra->cuenta = $compraArr['cuenta'];
						$valid = $valid && $compra->validate();
						if($valid){
							$compra->save();
						}
					}				
				}
			}
			else{
				Yii::app()->user->setFlash('equiposError',CHtml::errorSummary($model));
				$this->refresh();
			}
			
			if($valid){
				Yii::app()->user->setFlash('equiposMessage',"Datos guardados correctamente.");
				$this->refresh();
			}		
			
		}
		$this->render('equiposArrendados',array('model'=>$model));

	}

	public function actionCamionesArrendados(){

		$model=new RCamionArrendado();
		
		
		if(isset($_POST['RCamionArrendado']))
		{
                    
			$model->attributes=$_POST['RCamionArrendado'];
                        $model->observaciones_obra = $_POST['RCamionArrendado']['observaciones_obra'];
			$model->fecha=Tools::fixFecha($model->fecha);			
			$model->usuario_id = Yii::app()->user->id;
			$model->iniPanne = $_POST['RCamionArrendado']['iniPanne'];
			$model->finPanne = $_POST['RCamionArrendado']['finPanne'];
			$model->panne = $_POST['RCamionArrendado']['panne'];
			
			if($model->panne == 1){
				$iniPanne = str_replace(":","",$_POST['RCamionArrendado']['iniPanne']);
				$finPanne = str_replace(":","",$_POST['RCamionArrendado']['finPanne']);
				$minutos = $finPanne - $iniPanne;
				$horas = (int)($minutos/100);
				$min = $minutos%100;
				$model->minPanne = $horas * 60 + $min;
			} 
			else{ 	
				$model->minPanne = 0;
			}
			
			$valid = $model->validate();
			
			if($valid){				
				$model->save();
				if(isset($_POST['ViajeCamionArrendado'])){
					foreach($_POST['ViajeCamionArrendado'] as $i=>$viajeArr){
						$viaje = new ViajeCamionArrendado();
						$viaje->faena_id = $viajeArr['faena_id'];
						$viaje->origendestino_faena_id = $viajeArr['origendestino_faena_id'];
						$viaje->kmRecorridos = $viajeArr['kmRecorridos'];
						$viaje->nVueltas = $viajeArr['nVueltas'];
						$viaje->rCamionArrendado_id = $model->id;
						$viaje->total = $viajeArr['total'];
						$viaje->totalTransportado = $viajeArr['totalTransportado'];
						$viaje->coeficiente = $viajeArr['coeficiente'];
						$valid = $valid && $viaje->validate();
						if($valid){
							$viaje->save();
						}
					}				
				}
				if(isset($_POST['CargaCombCamionArrendado'])){
					foreach($_POST['CargaCombCamionArrendado'] as $i=>$cargaArr){
						$carga = new CargaCombCamionArrendado();
						$carga->factura = $cargaArr['factura'];
						$carga->faena_id = $cargaArr['faena_id'];
						$carga->guia = $cargaArr['guia'];
						$carga->kmCarguio = $cargaArr['kmCarguio'];
						$carga->petroleoLts = $cargaArr['petroleoLts'];
						$carga->precioUnitario = $cargaArr['precioUnitario'];
						$carga->rCamionArrendado_id = $model->id;
						$carga->supervisorCombustible_id = $cargaArr['supervisorCombustible_id'];
						$carga->tipoCombustible_id = $cargaArr['tipoCombustible_id'];
						$carga->valorTotal = $cargaArr['valorTotal'];
						$carga->numero = $cargaArr['numero'];
						$carga->nombre = $cargaArr['nombre'];
						$carga->rut_rinde = $cargaArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($carga->rut_rinde, $carga->nombre);
						$carga->fechaRendicion = $cargaArr['fechaRendicion'];
                                                $carga->observaciones = $cargaArr['observaciones'];
                                                $carga->nombre_proveedor = $cargaArr['nombre_proveedor'];
                                                $carga->rut_proveedor = $cargaArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($carga->rut_proveedor,$carga->nombre_proveedor);
                                                $carga->tipo_documento = $cargaArr['tipo_documento'];
						$valid = $valid && $carga->validate();
						if($valid){
							$carga->save();
						}
					}				
				}
                                
				if(isset($_POST['CompraRepuestoCamionArrendado'])){
					foreach($_POST['CompraRepuestoCamionArrendado'] as $i=>$compraArr){
						$compra = new CompraRepuestoCamionArrendado();
						$compra->factura = $compraArr['factura'];
						$compra->guia = $compraArr['guia'];
						$compra->montoNeto = $compraArr['montoNeto'];
						$compra->rCamionArrendado_id = $model->id;
						$compra->repuesto = $compraArr['repuesto'];
						$compra->cantidad = $compraArr['cantidad'];
						$compra->unidad = $compraArr['unidad'];
						$compra->faena_id = $compraArr['faena_id'];
						$compra->numero = $compraArr['numero'];
						$compra->nombre = $compraArr['nombre'];
						$compra->rut_rinde = $compraArr['rut_rinde'];
                                                Rendidor::model()->ingresaRendidor($compra->rut_rinde, $compra->nombre);
						$compra->tipo_documento = $compraArr['tipo_documento'];
						$compra->fechaRendicion = $compraArr['fechaRendicion'];
                                                $compra->observaciones = $compraArr['observaciones'];
                                                $compra->nombre_proveedor = $compraArr['nombre_proveedor'];
                                                $compra->rut_proveedor = $compraArr['rut_proveedor'];
                                                Proveedor::model()->ingresaProveedor($compra->rut_proveedor,$compra->nombre_proveedor);
                                                $compra->cuenta = $compraArr['cuenta'];
						$valid = $valid && $compra->validate();
						if($valid){
							$compra->save();
						}
					}				
				}
			}
			else{
				Yii::app()->user->setFlash('camionesError',CHtml::errorSummary($model));
				$this->refresh();
			}
			
			if($valid){
				Yii::app()->user->setFlash('camionesMessage',"Datos guardados correctamente.");
				$this->refresh();
			}		
			
		}
		$this->render('camionesArrendados',array('model'=>$model));
	}


	public function actionIndex(){
		$this->render("indexOper",array('nombre'=>Yii::app()->user->nombre));
	}

	public function actionLlenaCamion(){
		$val = 0;
		if(isset($_POST['RCamionPropio']['camionPropio_id'])){
			$val = $_POST['RCamionPropio']['camionPropio_id'];
		}
		$camion=CamionPropio::model()->findByPk($val);
		$codigo=$camion['codigo'];
		$capacidad=$camion['capacidad'];
		$pOv = $camion['pesoOVolumen'];
		if($pOv == "P"){
			$pOv = "Kg";
		}elseif($pOv == "V"){
			$pOv = "M3";
		}elseif($pOv == "L"){
			$pOv = "Lts";
		}else{
			$pOv = "";
		}
		echo "<td style='font-size:0.9em;'><b>Código:</b></td><td>".CHtml::encode($codigo)."</td><td style='font-size:0.9em;'><b>Capacidad:</b></td><td>".CHtml::encode($capacidad)." ".CHtml::encode($pOv)."<input type='hidden' id='capacidad' value='".CHtml::encode($capacidad)."'></td>";
	}
	
	public function actionLlenaCamionId($id){
		$val = $id;
		$camion=CamionPropio::model()->findByPk($val);
		$codigo=$camion['codigo'];
		$capacidad=$camion['capacidad'];
		$pOv = $camion['pesoOVolumen'];
		if($pOv == "P"){
			$pOv = "Kg";
		}elseif($pOv == "V"){
			$pOv = "M3";
		}elseif($pOv == "L"){
			$pOv = "Lts";
		}else{
			$pOv = "";
		}
		echo "<td style='font-size:0.9em;'><b>Código:</b></td><td>".CHtml::encode($codigo)."</td><td style='font-size:0.9em;'><b>Capacidad:</b></td><td>".CHtml::encode($capacidad)." ".CHtml::encode($pOv)."<input type='hidden' id='capacidad' value='".CHtml::encode($capacidad)."'></td>";
	}
	
	public function actionLlenaFaena($propio,$id){
		$val = 0;
		$origen = "";
		$destino = "";
		if($propio == "propio"){
			if(isset($_POST['ViajeCamionPropio'][$id]['faena_id'])){
				$val = $_POST['ViajeCamionPropio'][$id]['faena_id'];
			}
		}elseif($propio == "arrendado"){
			if(isset($_POST['ViajeCamionArrendado'][$id]['faena_id'])){
				$val = $_POST['ViajeCamionArrendado'][$id]['faena_id'];
			}
		}
		var_dump($_POST['ViajeCamionPropio']);
		die;
		$faena=Faena::model()->findByPk($val);
		$origen=$faena['origen'];
		$destino=$faena['destino'];
		$pu=$faena['pu'];
		echo "
		 <table cellspacing='0px' cellpadding='0px'>
		  <tr>
			<td style='font-size:0.9em;'><b>Origen: &nbsp;&nbsp;</b>".CHtml::encode($origen)."</td>
			<td style='font-size:0.9em;'><b>Destino: &nbsp;&nbsp;</b>".CHtml::encode($destino)."</td>
			<td style='font-size:0.9em;'><b>PU: &nbsp;&nbsp;</b>".CHtml::encode($pu)."</td>
		  </tr>
		 </table>";
		echo "<input type='hidden' id='pu' value='".CHtml::encode($pu)."'></td>";
	}
	
	public function actionLlenaFaenaId($id){
		$val = $id;
		$origen = "";
		$destino = "";
		$faena=Faena::model()->findByPk($val);
		$origen=$faena['origen'];
		$destino=$faena['destino'];
		$pu=$faena['pu'];
		echo "
		 <table cellspacing='0px' cellpadding='0px'>
		  <tr>
			<td style='font-size:0.9em;'><b>Origen: &nbsp;&nbsp;</b>".CHtml::encode($origen)."</td>
			<td style='font-size:0.9em;'><b>Destino: &nbsp;&nbsp;</b>".CHtml::encode($destino)."</td>
			<td style='font-size:0.9em;'><b>PU: &nbsp;&nbsp;</b>".CHtml::encode($pu)."</td>
		  </tr>
		 </table>";
		echo "<input type='hidden' id='pu' value='".CHtml::encode($pu)."'></td>";
	}
	
	public function actionLlenaEquipo(){
		$val = 0;
		$codigo = "";
		if(isset($_POST['REquipoPropio']['equipoPropio_id'])){
			$val = $_POST['REquipoPropio']['equipoPropio_id'];
			$equipo=EquipoPropio::model()->findByPk($val);
			$codigo=$equipo['codigo'];
		}
		if(isset($_POST['REquipoArrendado']['equipoArrendado_id'])){
			$val = $_POST['REquipoArrendado']['equipoArrendado_id'];
			$equipo=EquipoArrendado::model()->findByPk($val);
			if($equipo !=null){ 
				$codigo=$equipo->getPropietario();
			}
		}

		echo CHtml::encode($codigo);
	}
	
	public function actionLlenaEquipoId($id){
		$val = $id;
		$equipo=EquipoPropio::model()->findByPk($val);
		$codigo=$equipo['codigo'];

		echo CHtml::encode($codigo);
	}

	public function actionLlenaEquipoArrendado(){
		$val = 0;
		if(isset($_POST['EquiposArrendadosForm']['equipoArrendado'])){
			$val = $_POST['EquiposArrendadosForm']['equipoArrendado'];
		}
		$equipo=EquipoArrendado::model()->findByPk($val);
		$propietario_id=$equipo['propietario_id'];
		$propietario=Propietario::model()->findByPk($propietario_id);
		$nom_propietario = $propietario['nombre'];
		echo CHtml::encode($nom_propietario);
	}
	
	public function actionLlenaEquipoArrendadoId($id){
		$val = $id;
		$equipo=EquipoArrendado::model()->findByPk($val);
		$propietario_id=$equipo['propietario_id'];
		$propietario=Propietario::model()->findByPk($propietario_id);
		$nom_propietario = $propietario['nombre'];
		echo CHtml::encode($nom_propietario);
	}

	public function actionLlenaCamionArr(){
		$val = 0;
		if(isset($_POST['RCamionArrendado']['camionArrendado_id'])){
			$val = $_POST['RCamionArrendado']['camionArrendado_id'];
		}
		$camion=CamionArrendado::model()->findByPk($val);
		$capacidad=$camion['capacidad'];
		$pOv = $camion['pesoOVolumen'];
		if($pOv == "P"){
			$pOv = "Kg";
		}elseif($pOv == "V"){
			$pOv = "M3";
		}elseif($pOv == "L"){
			$pOv = "Lts";
		}else{
			$pOv = "";
		}
		echo CHtml::encode($capacidad)." ".CHtml::encode($pOv)."<input type='hidden' id='capacidad' value='".CHtml::encode($capacidad)."'>";
	}
	
	public function actionLlenaCamionArrId($id){
		$val = $id;

		$camion=CamionArrendado::model()->findByPk($val);
		$capacidad=$camion['capacidad'];
		$pOv = $camion['pesoOVolumen'];
		if($pOv == "P"){
			$pOv = "Kg";
		}elseif($pOv == "V"){
			$pOv = "M3";
		}elseif($pOv == "L"){
			$pOv = "Lts";
		}else{
			$pOv = "";
		}
		echo CHtml::encode($capacidad)." ".CHtml::encode($pOv)."<input type='hidden' id='capacidad' value='".CHtml::encode($capacidad)."'></td>";
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

	public function accessRules()
	{
		return array(
		array('allow',
				'actions'=>array('validaReporteUnico','rendidor','proveedor','rendidorRut','proveedorRut','rendidorRutExacto','proveedorRutExacto','camionesPropios','equiposArrendados','equiposPropios','llenaEquipoArrendado','index','llenaFaena','llenaEquipo','llenaCamion','llenaCamionArr','camionesArrendados'),
				'roles'=>array('operativo'),
		),	
		array('allow',
				'actions'=>array('rendidor','proveedor','proveedorRut','rendidorRut','rendidorRutExacto','proveedorRutExacto','llenaCamion','llenaCamionId','llenaCamionArrId','llenaFaenaId','llenaCamionArr','llenaFaena','llenaEquipoArrendadoId','llenaEquipoArrendado','llenaEquipo','llenaEquipoId'),
				'roles'=>array('administrador'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}
}