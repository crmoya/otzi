<?php

class ContratosController extends Controller
{
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }
    
    public function actionExportarCon(){
        // generate a resultset
        $data = ContratosDeUsuario::model()->findAllByAttributes(array('estados_contratos_id'=>'1','usuarios_id'=>Yii::app()->user->id));
        $this->toExcel($data,
            array('nombre','fecha_inicio','tipo_reajuste','observacion','tipo_contrato','codigo_safi','presupuesto_oficial','fecha_inicio_obra','nombre_mandante','rut_mandante','estados_contratos_nombre'),
            'Contratos Sin Resolución u orden de compra',
            array()
        );
    }
    
    public function actionExportarConAdj(){
        // generate a resultset
        $data = ContratosDeUsuario::model()->findAllByAttributes(array('estados_contratos_id'=>'2','usuarios_id'=>Yii::app()->user->id));

        $this->toExcel($data,
            array('nombre','fecha_inicio','tipo_reajuste','plazo','monto_inicial','modificaciones_monto','monto_actualizado','observacion','tipo_contrato','codigo_safi','presupuesto_oficial','valor_neto','fecha_inicio_obra','nombre_mandante','rut_mandante','estados_contratos_nombre'),
            'Contratos Adjudicados',
            array()
        );
    }
    
    public function actionExportarAdmin(){
        // generate a resultset
        $data = Contratos::model()->findAll();
        $datos = array();
        foreach($data as $dato){
            $estado = EstadosContratos::model()->findByPk($dato->estados_contratos_id);
            $dato->estado_contrato = $estado->nombre;
            $tipo_reajuste = TiposReajustes::model()->findByPk($dato->tipos_reajustes_id);
            $dato->tipo_reajuste = $tipo_reajuste->nombre;
            $tipo_contrato = TiposContratos::model()->findByPk($dato->tipos_contratos_id);
            $dato->tipo_contrato = $tipo_contrato->nombre;
            $datos[] = $dato;
        }
        
        $this->toExcel($datos,
            array('nombre','fecha_inicio','tipo_reajuste','plazo','monto_inicial','modificaciones_monto','monto_actualizado','observacion','tipo_contrato','codigo_safi','presupuesto_oficial','valor_neto','fecha_inicio_obra','nombre_mandante','rut_mandante','estado_contrato'),
            'Contratos',
            array()
        );
    }
    
    
    
    public function actionExportar($id){
    	Yii::import('application.vendors.PHPExcel',true);
    	// Create new PHPExcel object
    	$objPHPExcel = new PHPExcel();
    	
    	$contrato = Contratos::model()->findByPk($id);
    
    	if($contrato == null){
    		return;
    	}
    	
    	$resoluciones_antiguas = $contrato->getResoluciones();
    	$ultima_resolucion = null;
    	$flujosProgramados = array();
    	$garantias_asociadas = $contrato->getGarantias();
    	$ultima_resolucion = $contrato->getUltimaResolucion();
    	
    	$locale = 'es_es';
    	$validLocale = PHPExcel_Settings::setLocale($locale);
    
    	// Set properties
    	$objPHPExcel->getProperties()->setCreator("")
			    	->setLastModifiedBy("")
			    	->setTitle("Contrato ".$contrato->id)
			    	->setSubject("")
			    	->setDescription("")
			    	->setKeywords("")
			    	->setCategory("");
    
    	$fecha_final = "";
    	if($ultima_resolucion != null)
    		$fecha_final = CHtml::encode(Tools::backFecha($ultima_resolucion->fecha_final));
    	
    	$tipoContrato = TiposContratos::model()->findByPk($contrato->tipos_contratos_id);
    	$tipoReajuste = TiposReajustes::model()->findByPk($contrato->tipos_reajustes_id);
    	$tipoC = "";
    	$tipoR = "";
    	if($tipoContrato != null && $tipoReajuste != null):
    		$tipoC = $tipoContrato->nombre;
    		$tipoR = $tipoReajuste->nombre;
    	endif;
    	
    	$objPHPExcel->setActiveSheetIndex(0)
    				->mergeCells('A1:K1');
    	
    	$objPHPExcel->setActiveSheetIndex(0)
    				->setCellValue('A1',$contrato->nombre)
                                ->setCellValue('A3','RUT Mandante')
    				->setCellValue('B3',CHtml::encode($contrato->rut_mandante))
                                ->setCellValue('D3','Nombre Mandante')
    				->setCellValue('E3',CHtml::encode($contrato->nombre_mandante))
    				->setCellValue('A4','Fecha Oferta Técnica')
    				->setCellValue('B4',CHtml::encode(Tools::backFecha($contrato->fecha_inicio)))
    				->setCellValue('D4','Fecha Final')
    				->setCellValue('E4',$fecha_final)
    				->setCellValue('G4','Estado')
    				->setCellValue('H4',CHtml::encode(EstadosContratos::model()->findByPk($contrato->estados_contratos_id)->nombre))
    				->setCellValue('J4','Plazo')
    				->setCellValue('K4',$contrato->plazo." Días")
    				
    				->setCellValue('A5','Monto Inicial')
    				->setCellValue('B5',$contrato->monto_inicial)
    				->setCellValue('D5','Modificaciones Monto')
    				->setCellValue('E5',$contrato->modificaciones_monto)
    				->setCellValue('G5','Monto Actualizado')
    				->setCellValue('H5',$contrato->monto_actualizado)
    	
			    	->setCellValue('A6','Presupuesto Oficial con IVA')
			    	->setCellValue('B6',$contrato->presupuesto_oficial)
			    	->setCellValue('D6','Código SAFI')
			    	->setCellValue('E6',$contrato->codigo_safi)
			    	->setCellValue('G6','Creador')
			    	->setCellValue('H6',Usuarios::model()->findByPk($contrato->creador_id)->nombre)
			    	->setCellValue('J6','Modificador')
			    	->setCellValue('K6',Usuarios::model()->findByPk($contrato->modificador_id)->nombre)
			    	
			    	->setCellValue('A7','Tipo de Contrato')
			    	->setCellValue('B7',$tipoC)
			    	->setCellValue('D7','Tipo de Reajuste')
			    	->setCellValue('E7',$tipoR)
    
    				->setCellValue('A8','Observaciones del Contrato')
    				->setCellValue('B8',$contrato->observacion);
    	$objPHPExcel->setActiveSheetIndex(0)
    				->mergeCells('B8:K8');
    	
    	$fila = 10;
    	$fila++;
    	if(count($resoluciones_antiguas)>0){
    		$objPHPExcel->setActiveSheetIndex(0)
    					->setCellValue('A'.$fila,"Resoluciones del Contrato");	
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$fila++;
    	}
    	
    	$primera = true;
    	foreach($resoluciones_antiguas as $reso){
    		$fecha_inicio = "";
    		if($primera){
    			$primera = false;
    			$fecha_inicio = Tools::backFecha($reso->fecha_inicio);
    		}
    		else{
    			$fecha_inicio = Tools::backFecha($reso->fecha_tramitada);
    		}
    		$usuario = Usuarios::model()->findByPk($reso->creador_id);
    		$creador = "";
    		if($usuario != null){
    			$creador = $usuario->nombre;
    		}
    		$usuario = Usuarios::model()->findByPk($reso->modificador_id);
    		$modificador = "";
    		if($usuario != null){
    			$modificador = $usuario->nombre;
    		}
    		
    		$objPHPExcel->setActiveSheetIndex(0)
    					->setCellValue('A'.$fila,"N°Resolución")
    					->setCellValue('B'.$fila,$reso->numero)
    					->setCellValue('D'.$fila,"Monto")
    					->setCellValue('E'.$fila,$reso->monto)
    					->setCellValue('G'.$fila,"Plazo")
    					->setCellValue('H'.$fila,$reso->plazo)
    					->setCellValue('J'.$fila,"Fecha Resolución")
    					->setCellValue('K'.$fila,Tools::backFecha($reso->fecha_resolucion));
    		
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('G'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('J'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$objPHPExcel->setActiveSheetIndex(0)
    					->setCellValue('A'.$fila,"Fecha Inicio")
    					->setCellValue('B'.$fila,$fecha_inicio)
    					->setCellValue('D'.$fila,"Fecha Fin")
    					->setCellValue('E'.$fila,Tools::backFecha($reso->fecha_final))
    					->setCellValue('G'.$fila,"Generada Por")
    					->setCellValue('H'.$fila,$creador)
    					->setCellValue('J'.$fila,"Modificada Por")
    					->setCellValue('K'.$fila,$modificador);
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('G'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('J'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$objPHPExcel->setActiveSheetIndex(0)
    					->setCellValue('A'.$fila,"Observaciones")
    					->setCellValue('B'.$fila,$reso->observacion);

    		$objPHPExcel->setActiveSheetIndex(0)
    					->mergeCells('B'.$fila.':K'.$fila);
    		
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$programados = $reso->getFlujosProgramados();
    		foreach($programados as $prog){
    			$objPHPExcel->setActiveSheetIndex(0)
			    			->setCellValue('A'.$fila,Tools::getMes($prog->mes)." ".$prog->agno);
    			$sheet = $objPHPExcel->getActiveSheet();
    			$styleArray = array('font' => array('bold' => true));
    			$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    			$fila++;
    			
    			$objPHPExcel->setActiveSheetIndex(0)
    						->setCellValue('A'.$fila,'Producción Programada Neta')
    						->setCellValue('B'.$fila,$prog->produccion)
    						->setCellValue('D'.$fila,'Costo Programado Neto')
    						->setCellValue('E'.$fila,$prog->costo);    			
    			$sheet = $objPHPExcel->getActiveSheet();
    			$styleArray = array('font' => array('bold' => true));
    			$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    			$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
    			$fila++;
    			
    			$objPHPExcel->setActiveSheetIndex(0)
			    			->setCellValue('A'.$fila,'Comentarios Flujo Programado')
			    			->setCellValue('B'.$fila,$prog->comentarios);
    			$objPHPExcel->setActiveSheetIndex(0)
    						->mergeCells('B'.$fila.':K'.$fila);
    			$sheet = $objPHPExcel->getActiveSheet();
    			$styleArray = array('font' => array('bold' => true));
    			$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    			$fila++;
    			
    			$flujos = array();
    			$eps = $prog->getEPs();
				if($eps != null){
					$flujos = Tools::getEP($eps,$prog->mes,$prog->agno,$prog->resoluciones_id);	
				}
				
				foreach($flujos as $ft){
					
					if($ft['tipo'] == "obra"):
						$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'EP de Obra');
						$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
						$objPHPExcel->setActiveSheetIndex(0)
	    						->mergeCells('A'.$fila.':K'.$fila);
	    				$fila++;
						$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'Producción Real Neta')
				    				->setCellValue('B'.$fila,$ft['ep']->produccion)
				    				->setCellValue('D'.$fila,'Costo Real Neto')
				    				->setCellValue('E'.$fila,$ft['ep']->costo)
				    				->setCellValue('G'.$fila,'Reajuste Neto')
				    				->setCellValue('H'.$fila,$ft['ep']->reajuste)
				    				->setCellValue('J'.$fila,'Retención Neta')
				    				->setCellValue('K'.$fila,$ft['ep']->retencion)
				    				->setCellValue('M'.$fila,'Descuentos/Abonos Netos')
				    				->setCellValue('N'.$fila,$ft['ep']->descuento);
	    							    				
	    				$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
	    				$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
	    				$sheet->getStyle('G'.$fila)->applyFromArray($styleArray);
	    				$sheet->getStyle('J'.$fila)->applyFromArray($styleArray);
	    				$sheet->getStyle('M'.$fila)->applyFromArray($styleArray);
	    				$sheet->getStyle('P'.$fila)->applyFromArray($styleArray);
	    				
	    				$fila++;
	    				
	    				$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'Comentarios EP')
				    				->setCellValue('B'.$fila,$ft['ep']->comentarios);
	    					
	    				$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
	    				$objPHPExcel->setActiveSheetIndex(0)
	    						->mergeCells('B'.$fila.':K'.$fila);
	    				
	    				$fila++;
					endif; //flujo obra
					
					if($ft['tipo'] == "anticipo"):
						$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'EP de Anticipo');
						$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
						$objPHPExcel->setActiveSheetIndex(0)
	    						->mergeCells('A'.$fila.':K'.$fila);
	    				$fila++;
	    				
						$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'Valor')
				    				->setCellValue('B'.$fila,$ft['ep']->valor);
	    							    				
	    				$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
	    				
	    				$fila++;
	    				
	    				$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'Comentarios EP')
				    				->setCellValue('B'.$fila,$ft['ep']->comentarios);
	    					
	    				$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
	    				$objPHPExcel->setActiveSheetIndex(0)
	    						->mergeCells('B'.$fila.':K'.$fila);
	    				
	    				$fila++;
					endif; //flujo anticipo
					
					if($ft['tipo'] == "canje_retencion"):
						$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'EP de Canje de Retenciones');
						$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
						$objPHPExcel->setActiveSheetIndex(0)
	    						->mergeCells('A'.$fila.':K'.$fila);
	    				$fila++;
						$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'Valor')
				    				->setCellValue('B'.$fila,$ft['ep']->valor);
	    							    				
	    				$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
	    				
	    				$fila++;
	    				
	    				$objPHPExcel->setActiveSheetIndex(0)
				    				->setCellValue('A'.$fila,'Comentarios EP')
				    				->setCellValue('B'.$fila,$ft['ep']->comentarios);
	    					
	    				$sheet = $objPHPExcel->getActiveSheet();
	    				$styleArray = array('font' => array('bold' => true));
	    				$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
	    				$objPHPExcel->setActiveSheetIndex(0)
	    						->mergeCells('B'.$fila.':K'.$fila);
	    				
	    				$fila++;
					endif; //flujo canje_retencion
					
				}//foreach flujos
    			
    		}    		
    		$fila++;
    	}	
	
    	if(count($garantias_asociadas)>0){
    		$fila++;
    		$objPHPExcel->setActiveSheetIndex(0)
    					->setCellValue('A'.$fila,"Garantías del Contrato");
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$objPHPExcel->setActiveSheetIndex(0)
    					->mergeCells('B'.$fila.':K'.$fila);
    		$fila++;
    	}
    	
    	foreach($garantias_asociadas as $garantia){
    		$usuario = Usuarios::model()->findByPk($garantia->creador_id);
    		$creador = "";
    		if($usuario != null){
    			$creador = $usuario->nombre;
    		}
    		$usuario = Usuarios::model()->findByPk($garantia->modificador_id);
    		$modificador = "";
    		if($usuario != null){
    			$modificador = $usuario->nombre;
    		}
    		
    		$objPHPExcel->setActiveSheetIndex(0)
    					->setCellValue('A'.$fila,"Generada por")
    					->setCellValue('B'.$fila,$creador)
    					->setCellValue('D'.$fila,"Modificada por")
    					->setCellValue('E'.$fila,$modificador);
    					
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$objPHPExcel->setActiveSheetIndex(0)
			    		->setCellValue('A'.$fila,"N°Garantía")
			    		->setCellValue('B'.$fila,$garantia->numero)
			    		->setCellValue('D'.$fila,"Monto")
			    		->setCellValue('E'.$fila,$garantia->monto)
			    		->setCellValue('G'.$fila,"Fecha Vencimiento")
			    		->setCellValue('H'.$fila,Tools::backFecha($garantia->fecha_vencimiento));
    			
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('G'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$institucion = Instituciones::model()->findByPk($garantia->instituciones_id);
    		if($institucion != null){
    			$institucion = $institucion->nombre;
    		}
    		$tipo = TiposGarantias::model()->findByPk($garantia->tipos_garantias_id);
    		if($tipo != null){
    			$tipo = $tipo->nombre;
    		}
    		$objeto = ObjetosGarantias::model()->findByPk($garantia->objetos_garantias_id);
    		if($objeto != null){
    			$objeto = $objeto->descripcion;
    		}
    		$objPHPExcel->setActiveSheetIndex(0)
			    		->setCellValue('A'.$fila,"Institución asociada")
			    		->setCellValue('B'.$fila,$institucion)
			    		->setCellValue('D'.$fila,"Tipo de Garantía")
			    		->setCellValue('E'.$fila,$tipo)
			    		->setCellValue('G'.$fila,"Objeto de garantía")
			    		->setCellValue('H'.$fila,$objeto);
    		 
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('D'.$fila)->applyFromArray($styleArray);
    		$sheet->getStyle('G'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$estado = "No Vigente";
    		if($garantia->estado_garantia == 1)
    			$estado = "Vigente";
    		$objPHPExcel->setActiveSheetIndex(0)
			    		->setCellValue('A'.$fila,"Estado")
			    		->setCellValue('B'.$fila,$estado);
    		 
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		
    		$objPHPExcel->setActiveSheetIndex(0)
			    		->setCellValue('A'.$fila,"Observación")
			    		->setCellValue('B'.$fila,Tools::ponePorcentaje($garantia->observacion));
    		$objPHPExcel->setActiveSheetIndex(0)
    					->mergeCells('B'.$fila.':K'.$fila);
    		$sheet = $objPHPExcel->getActiveSheet();
    		$styleArray = array('font' => array('bold' => true));
    		$sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
    		$fila++;
    		$fila++;
    	}
    	
    	
    	
    	$sheet = $objPHPExcel->getActiveSheet();
    	$styleArray = array('font' => array('bold' => true));
    	$sheet->getStyle('A1')->applyFromArray($styleArray);
    	$sheet->getStyle('A3')->applyFromArray($styleArray);
    	$sheet->getStyle('D3')->applyFromArray($styleArray);
    	$sheet->getStyle('G3')->applyFromArray($styleArray);
    	$sheet->getStyle('J3')->applyFromArray($styleArray);
    	$sheet->getStyle('A4')->applyFromArray($styleArray);
    	$sheet->getStyle('D4')->applyFromArray($styleArray);
    	$sheet->getStyle('G4')->applyFromArray($styleArray);
    	$sheet->getStyle('A5')->applyFromArray($styleArray);
    	$sheet->getStyle('D5')->applyFromArray($styleArray);
    	$sheet->getStyle('G5')->applyFromArray($styleArray);
    	$sheet->getStyle('J5')->applyFromArray($styleArray);
    	$sheet->getStyle('A6')->applyFromArray($styleArray);
    	$sheet->getStyle('D6')->applyFromArray($styleArray);
    	$sheet->getStyle('A7')->applyFromArray($styleArray);
    	
    	// Rename sheet
    	$objPHPExcel->getActiveSheet()->setTitle("Contrato ".$contrato->id);
    
    	// Set active sheet index to the first sheet,
    	// so Excel opens this as the first sheet
    	$objPHPExcel->setActiveSheetIndex(0);
    
    	// Redirect output to a client’s web browser (Excel2007)
    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    	header('Content-Disposition: attachment;filename="'.$contrato->nombre.'.xlsx"');
    	header('Cache-Control: max-age=0');
    
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	$objWriter->save('php://output');
    	Yii::app()->end();
    
    	//
    	// Once we have finished using the library, give back the
    	// power to Yii...
    	spl_autoload_register(array('YiiBase','autoload'));
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
                    'actions'=>array('exportar','exportarCon','exportarConAdj','resolucionesAdj','garantias','libros','adjuntar','adminAdjuntar','create','existeResolucion','adjudicar','editar','cerrar','flujosReales','flujosProgramados','adminNuevos','adminAdjudicados','adminAdjudicados2','adminAdjudicados3','adminNoCerrados','adminContratosOp','view'),
                    'roles'=>array('operador'),
		),
		array('allow',
                    'actions'=>array('exportar','exportarAdmin','exportarCon','exportarConAdj','eliminarDigiResoluciones','eliminarDigiLibros','eliminarDigiGarantias','eliminarDigiContratos','delDigiContratos','delDigiLibros','delDigiGarantias','delDigiResoluciones','adminDig','delete','edit','editResolucion','admin','adminConRes','view','adminCerrados','reabrir','existeContrato'),
                    'roles'=>array('administrador'),
				),
		array('deny',  // deny all users
                    'users'=>array('*'),
		),
		);
	}
	
	public function actionAdminAdjuntar(){
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();  
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminAdjuntar',array(
			'model'=>$model,
		));
	}
	
	public function actionAdminContratosOp(){
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminContratosOp',array(
				'model'=>$model,
		));
	}
	
	public function actionDelDigiContratos($id){
		$contrato = Contratos::model()->findByPk($id);
		$nombre = "";
		if($contrato != null) $nombre = $contrato->nombre; 
		$model=new AdjuntosContratos('search');
		$this->render('adminDigiContratos',array(
				'model'=>$model,
				'nombre_contrato'=>$nombre,
				'id'=>$id
		));
	}
	
	public function actionDelDigiLibros($id){
		$contrato = Contratos::model()->findByPk($id);
		$nombre = "";
		if($contrato != null) $nombre = $contrato->nombre;
		$model=new AdjuntosLibros('search');
		$this->render('adminDigiLibros',array(
				'model'=>$model,
				'nombre_contrato'=>$nombre,
				'id'=>$id
		));
	}
	
	public function actionDelDigiGarantias($id){
		$contrato = Contratos::model()->findByPk($id);
		$nombre = "";
		if($contrato != null) $nombre = $contrato->nombre;
		$model=new AdjuntosGarantias('search');
		$this->render('adminDigiGarantias',array(
				'model'=>$model,
				'nombre_contrato'=>$nombre,
				'id'=>$id
		));
	}
	
	public function actionDelDigiResoluciones($id){
		$contrato = Contratos::model()->findByPk($id);
		$nombre = "";
		if($contrato != null) $nombre = $contrato->nombre;
		$model=new AdjuntosResoluciones('search');
		$this->render('adminDigiResoluciones',array(
				'model'=>$model,
				'nombre_contrato'=>$nombre,
				'id'=>$id
		));
	}
		
	public function actionCreate(){
		$model=new Contratos();
		if(isset($_POST['ajax']) && $_POST['ajax']==='contratos-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['Contratos']))
		{
			$model->attributes=$_POST['Contratos'];
			$model->fecha_inicio=Tools::fixFecha($model->fecha_inicio);
			$model->modificador_id = Yii::app()->user->id;
			$model->creador_id = Yii::app()->user->id;
			$model->estados_contratos_id = Contratos::ESTADO_NUEVO;
			$model->tipos_contratos_id = $_POST['Contratos']['tipos_contratos_id'];
			$model->tipos_reajustes_id = $_POST['Contratos']['tipos_reajustes_id'];
			$model->codigo_safi = $_POST['Contratos']['codigo_safi'];
			$model->presupuesto_oficial = Tools::fixPlata($_POST['Contratos']['presupuesto_oficial']);
			
			$valid = $model->validate();

			if($valid){				
				$model->save();
				//asociar automáticamente el usuario al contrato que creó
				$u_c = new UsuariosContratos(); 
				$u_c->contratos_id = $model->primaryKey;
				$u_c->usuarios_id = Yii::app()->user->id;
				if($u_c->validate()){
					$u_c->save();
					Yii::app()->user->setFlash('contratosMessage',"Datos guardados correctamente.");
					$this->refresh();	
				}
				else{
					Contratos::model()->deleteAllByAttributes(array('id'=>$model->primaryKey));
					Yii::app()->user->setFlash('contratosError',"Error al guardar el contrato, intente nuevamente.");
					$this->refresh();
				}
				
			}	
		}
		$this->render('create',array('model'=>$model));
	}
	
	public function actionEditResolucion($id){
		$contrato = Contratos::model()->findByPk($id);
		
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$cambiado = false;
		if(isset($_POST['Resoluciones'])){
						
			$valid = true;
			
			$ultima_resolucion->numero = $_POST['Resoluciones']['numero'];
			$ultima_resolucion->fecha_resolucion = Tools::fixFecha($_POST['Resoluciones']['fecha_resolucion']);
			$ultima_resolucion->fecha_tramitada = Tools::fixFecha($_POST['Resoluciones']['fecha_tramitada']);
			$ultima_resolucion->monto = Tools::fixPlata($_POST['Resoluciones']['monto']);
			$ultima_resolucion->plazo = $_POST['Resoluciones']['plazo'];
			$ultima_resolucion->fecha_inicio = Tools::fixFecha($_POST['Resoluciones']['fecha_inicio']);
			$ultima_resolucion->fecha_final = Tools::fixFecha($_POST['Resoluciones']['fecha_final']);
			$ultima_resolucion->observacion = $_POST['Resoluciones']['observacion'];
			$ultima_resolucion->modificador_id = Yii::app()->user->id;
			
			$valid = $valid && $ultima_resolucion->validate();
			if($valid){
				$ultima_resolucion->save();
			}
			//solo si hay 1 y solo 1 resolucion se debe actualizar el monto inicial del contrato, pues
			//este depende del monto asignado a la primera resolucion del contrato.
			$cuantas = count($contrato->getResoluciones());
			if($cuantas == 1){
				$contrato->monto_inicial = $ultima_resolucion->monto;
			}
			$contrato->modificador_id = Yii::app()->user->id;
			$contrato->plazo = $contrato->calculaPlazo();
			$contrato->monto_actualizado = $contrato->calculaMonto();
			$contrato->valor_neto = (int)Tools::calculaNeto($contrato->monto_actualizado);
			$contrato->modificaciones_monto = $contrato->monto_actualizado - $contrato->monto_inicial;
						
			$valid = $valid && $contrato->validate();
			if($valid){
				$contrato->save();
			}
                        
			/*
                        //eliminar todos los flujos programados y reales asociados a esta resolución
			FlujosProgramados::model()->deleteAllByAttributes(array('resoluciones_id'=>$ultima_resolucion->id));
			EpObra::model()->deleteAllByAttributes(array('resoluciones_id'=>$ultima_resolucion->id));
			EpAnticipo::model()->deleteAllByAttributes(array('resoluciones_id'=>$ultima_resolucion->id));
			EpCanjeRetencion::model()->deleteAllByAttributes(array('resoluciones_id'=>$ultima_resolucion->id));
                        */
                        
			if(isset($_POST['FlujosProgramados'])){
                            //borro los flujos que están en BD pero no están en el $_POST
                            $posts = array();
                            foreach($_POST['FlujosProgramados'] as $i=>$fProg){
                                if(isset($fProg['id'])){
                                    $posts[] = $fProg['id'];
                                }
                            }
                            
                            $flujosP = $contrato->getFlujosProgramados();
                            foreach($flujosP as $flujoP){
                                if(!in_array($flujoP->id, $posts)){
                                    $flujoP->delete();
                                }
                            }
				foreach($_POST['FlujosProgramados'] as $i=>$fProg){
                                    $flujo = null;
                                    if(isset($fProg['id'])){
                                        $flujo = FlujosProgramados::model()->findByPk($fProg['id']);
                                    }
                                    if($flujo == null){
                                        $flujo = new FlujosProgramados();
                                        $flujo->resoluciones_id = $ultima_resolucion->id;
                                    }
                                    $flujo->produccion = Tools::fixPlata($fProg['produccion']);
                                    $flujo->costo = Tools::fixPlata($fProg['costo']);
                                    $flujo->mes = Tools::backMes($fProg['mes']);
                                    $flujo->agno = $fProg['agno'];
                                    $flujo->comentarios = $fProg['comentarios'];

                                    $valid = $valid && $flujo->validate();
                                    if($valid){
                                            $flujo->save();
                                    }
				}
			}
			if($valid){
				Yii::app()->user->setFlash('resolucionesMessage',"Resolución modificada correctamente.");
				$this->refresh();
			}else{
				Yii::app()->user->setFlash('resolucionesError',"Error: No se pudo modificar la resolución.");
				$this->refresh();
			}
		}
		
		$flujosP = $contrato->getFlujosProgramados();
		$proximoFlujo = $ultima_resolucion->getInicioProximoFlujoProgramado();
		$mesInicio = $proximoFlujo['mes'];
		$agnoInicio = $proximoFlujo['agno'];
		
		$this->render('editResolucion',array(
				'contrato'=>$contrato,
				'ultima_res'=>$ultima_resolucion,
				'flujosP'=>$flujosP,
				'mesInicio'=>$mesInicio,
				'agnoInicio'=>$agnoInicio,
		));
	}
	
	public function actionEdit($id){
		$contrato = Contratos::model()->findByPk($id);
		
		$res_cambiadas = array();
		$j = 0;
		$cambiado = false;
		if(isset($_POST['FlujosProgramados'])){
                    
                    
                    foreach($_POST['FlujosProgramados'] as $i=>$fProg){
                        
                        $flujo = FlujosProgramados::model()->findByPk($fProg['id']);
                        $flujo->produccion = Tools::fixPlata($fProg['produccion']);
                        $flujo->costo = Tools::fixPlata($fProg['costo']);
                        $flujo->comentarios = $fProg['comentarios'];
                        if($flujo->validate()){
                            $flujo->save();
                            $res_cambiadas[$j]=$flujo['resoluciones_id'];
                            $j++;
                            $cambiado = true;
                        }
                        
                        $eps = $flujo->getEPs();
                        if($eps == null){
                            $eps = array();
                        }
                        $epsObra = array();
                        $epsAnticipo = array();
                        $epsCanje = array();
                        
                        foreach($eps as $ep){
                            if($ep['tipo'] == 'obra'){
                                $epsObra = $ep['eps'];
                            }
                            if($ep['tipo'] == 'anticipo'){
                                $epsAnticipo = $ep['eps'];
                            }
                            if($ep['tipo'] == 'canje_retencion'){
                                $epsCanje = $ep['eps'];
                            }
                        }
                        
                       
                        foreach($epsObra as $modelo){
                            $idEpObra = $modelo->id;
                            $epObra = EpObra::model()->findByPk($idEpObra);
                            $epObra->delete();
                        }
                        if(isset($_POST['EpObra'])){
                            foreach($_POST['EpObra'] as $r=>$fReal){
                                $flujo = new EpObra();
                                $flujo->produccion = Tools::fixPlata($fReal['produccion']);
                                $flujo->mes = $fReal['mes'];
                                $flujo->agno = $fReal['agno'];
                                $flujo->resoluciones_id = $fReal['resoluciones_id'];
                                $flujo->costo = Tools::fixPlata($fReal['costo']);
                                $flujo->reajuste = Tools::fixPlata($fReal['reajuste']);
                                $flujo->retencion = Tools::fixPlata($fReal['retencion']);
                                $flujo->descuento = Tools::fixPlata($fReal['descuento']);
                                $flujo->comentarios = $fReal['comentarios'];
                                if($flujo->validate()){
                                    $flujo->save();
                                    $cambiado = true;
                                }
                            }
                        }
                        foreach($epsAnticipo as $modelo){
                            $idEpAnticipo = $modelo->id;
                            $epAnticipo = EpAnticipo::model()->findByPk($idEpAnticipo);
                            $epAnticipo->delete();
                        }
                        if(isset($_POST['EpAnticipo'])){
                            foreach($_POST['EpAnticipo'] as $r=>$fReal){
                                $flujo = new EpAnticipo();
                                $flujo->valor = Tools::fixPlata($fReal['valor']);
                                $flujo->mes = $fReal['mes'];
                                $flujo->agno = $fReal['agno'];
                                $flujo->resoluciones_id = $fReal['resoluciones_id'];
                                $flujo->comentarios = $fReal['comentarios'];
                                if($flujo->validate()){
                                    $flujo->save();
                                    $cambiado = true;
                                }
                            }
                        }

                        foreach($epsCanje as $modelo){
                            $idEpCanje = $modelo->id;
                            $epCanje = EpCanje::model()->findByPk($idEpCanje);
                            $epCanje->delete();
                        }
                        if(isset($_POST['EpCanjeRetencion'])){
                            foreach($_POST['EpCanjeRetencion'] as $r=>$fReal){
                                $flujo = new EpCanjeRetencion();
                                $flujo->valor = Tools::fixPlata($fReal['valor']);
                                $flujo->mes = $fReal['mes'];
                                $flujo->agno = $fReal['agno'];
                                $flujo->resoluciones_id = $fReal['resoluciones_id'];
                                $flujo->comentarios = $fReal['comentarios'];
                                if($flujo->validate()){
                                    $flujo->save();
                                    $cambiado = true;
                                }
                            }
                        }
                    } 
		}
                
		if(isset($_POST['Contratos'])){
			$contrato->presupuesto_oficial = Tools::fixPlata($_POST['Contratos']['presupuesto_oficial']);
			$contrato->nombre = $_POST['Contratos']['nombre'];
                        $contrato->rut_mandante = $_POST['Contratos']['rut_mandante'];
                        $contrato->nombre_mandante = $_POST['Contratos']['nombre_mandante'];
			$contrato->tipos_contratos_id = $_POST['Contratos']['tipos_contratos_id'];
			$contrato->tipos_reajustes_id = $_POST['Contratos']['tipos_reajustes_id'];
			$contrato->codigo_safi = $_POST['Contratos']['codigo_safi'];
			$contrato->observacion = $_POST['Contratos']['observacion'];
			$cambiado = true;
		}
		
		if($cambiado){
			$contrato->modificador_id = Yii::app()->user->id;
			if($contrato->validate()){
				$contrato->save();
			}
			foreach($res_cambiadas as $resol){
				$resolucion = Resoluciones::model()->findByPk($resol);
				$resolucion->modificador_id = Yii::app()->user->id;
				if($resolucion->validate()){
					$resolucion->save();
				}
			}
			$contrato->plazo = $contrato->calculaPlazo();
			$contrato->monto_actualizado = $contrato->calculaMonto();
			$contrato->valor_neto = (int)Tools::calculaNeto($contrato->monto_actualizado);
			$contrato->modificaciones_monto = $contrato->monto_actualizado - $contrato->monto_inicial;
			if($contrato->validate()){
                            $contrato->save();
                            Yii::app()->user->setFlash('resolucionesMessage',"Contrato modificado correctamente.");
                            $this->refresh();
			}
			else{
                            Yii::app()->user->setFlash('resolucionesError',"ERROR: ".CHtml::errorSummary($contrato));
                            $this->refresh();
                        }
			
		}
		
		
		$resoluciones_antiguas = $contrato->getResoluciones();
		$flujosProgramados = array();
		$i=0;
		foreach($resoluciones_antiguas as $reso){
			$programados = $reso->getFlujosProgramados();
			foreach($programados as $prog){
				$flujosProgramados[$reso->id][$i]=$prog;
				$i++;
			}
		}
		
		$ultima_resolucion = $contrato->getUltimaResolucion();
		
		$this->render('edit',array(
								'contrato'=>$contrato,
								'resoluciones'=>$resoluciones_antiguas,
								'flujosProgramados'=>$flujosProgramados,
								'ultima_res'=>$ultima_resolucion,
							));
	}
	
	public function actionView($id){
		$contrato = Contratos::model()->findByPk($id);
		
		$resoluciones_antiguas = $contrato->getResoluciones();
		$ultima_resolucion = null;
		$flujosProgramados = array();
		$garantias_asociadas = $contrato->getGarantias();		
		$i=0;
		foreach($resoluciones_antiguas as $reso){
			$programados = $reso->getFlujosProgramados();
			foreach($programados as $prog){
				$flujosProgramados[$reso->id][$i]=$prog;
				$i++;
			}
		}
		
		
		
		$ultima_resolucion = $contrato->getUltimaResolucion();
		
		$this->render('view',array(
								'contrato'=>$contrato,
								'resoluciones'=>$resoluciones_antiguas,
								'flujosProgramados'=>$flujosProgramados,
								'ultima_res'=>$ultima_resolucion,
								'garantias'=>$garantias_asociadas,
							));
	}
	
	public function actionCerrar($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id == Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"Contrato no se puede cerrar, pues ya está cerrado.");
			$this->actionAdminNoCerrados();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede cerrar.");
			$this->actionAdminNoCerrados();
			Yii::app()->end();
		}
		
		$contrato->estados_contratos_id = Contratos::ESTADO_CERRADO;
		$contrato->modificador_id = Yii::app()->user->id;
		if($contrato->validate()){
			$contrato->save();
			Yii::app()->user->setFlash('adminMessage',"Contrato cerrado.");
			$this->actionAdminNoCerrados();
			Yii::app()->end();
		}
		
	}
	
	public function actionEliminarDigiContratos($id){
		$adjunto = AdjuntosContratos::model()->findByPk($id);
		if($adjunto!=null){
			$nombre = $adjunto->nombre_archivo;
			$contrato = $adjunto->contratos_id;
			$adjunto->delete();
			if(is_file(Yii::app()->basePath.'/adjuntos/contratos/'.$contrato.$nombre)){
				unlink(Yii::app()->basePath.'/adjuntos/contratos/'.$contrato.$nombre);
				Yii::app()->user->setFlash('adminMessage',"Digitalización eliminada correctamente.");
			}
			else {
				Yii::app()->user->setFlash('adminError',"Archivo no Existe.");
			}
			$this->actionDelDigiContratos($contrato);
		}
	}
	
	public function actionEliminarDigiLibros($id){
		$adjunto = AdjuntosLibros::model()->findByPk($id);
		if($adjunto!=null){
			$nombre = $adjunto->nombre_archivo;
			$contrato = $adjunto->contratos_id;
			$adjunto->delete();
			if(is_file(Yii::app()->basePath.'/adjuntos/libros/'.$contrato.$nombre)){
				unlink(Yii::app()->basePath.'/adjuntos/libros/'.$contrato.$nombre);
				Yii::app()->user->setFlash('adminMessage',"Digitalización eliminada correctamente.");
			}
			else {
				Yii::app()->user->setFlash('adminError',"Archivo no Existe.");
			}
			$this->actionDelDigiLibros($contrato);
		}
	}
	
	public function actionEliminarDigiResoluciones($id,$contrato){
		$adjunto = AdjuntosResoluciones::model()->findByPk($id);
		if($adjunto!=null){
			$nombre = $adjunto->nombre_archivo;
			$resolucion = $adjunto->resoluciones_id;
			$adjunto->delete();
			if(is_file(Yii::app()->basePath.'/adjuntos/resoluciones/'.$resolucion.$nombre)){
				unlink(Yii::app()->basePath.'/adjuntos/resoluciones/'.$resolucion.$nombre);
				Yii::app()->user->setFlash('adminMessage',"Digitalización eliminada correctamente.");
			}
			else {
				Yii::app()->user->setFlash('adminError',"Archivo no Existe.");
			}
			$this->actionDelDigiResoluciones($contrato);
		}
	}
	
	public function actionEliminarDigiGarantias($id,$contrato){
		$adjunto = AdjuntosGarantias::model()->findByPk($id);
		if($adjunto!=null){
			$nombre = $adjunto->nombre_archivo;
			$garantia = $adjunto->garantias_id;
			$adjunto->delete();
			if(is_file(Yii::app()->basePath.'/adjuntos/garantias/'.$garantia.$nombre)){
				unlink(Yii::app()->basePath.'/adjuntos/garantias/'.$garantia.$nombre);
				Yii::app()->user->setFlash('adminMessage',"Digitalización eliminada correctamente.");
			}
			else {
				Yii::app()->user->setFlash('adminError',"Archivo no Existe.");
			}
			$this->actionDelDigiGarantias($contrato);
		}
	}
	
	public function actionAdjuntar($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id == Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"No se pueden adjuntar digitalizaciones, pues contrato ya está cerrado.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede adjuntar digitalizaciones.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		$archivo = new AdjuntosContratos;
		
		$contrato->modificador_id = Yii::app()->user->id;
		if($contrato->validate()){
			if(isset($_POST['AdjuntosContratos']))
	        {
	        	$archivo->attributes=$_POST['AdjuntosContratos'];
		        $archivo->file=CUploadedFile::getInstance($archivo,'file');
				if($archivo->file != null){
		        	$archivo->fecha = date("y-m-d");
	            	$archivo->nombre_archivo = $archivo->file->name;
	            	$archivo->contratos_id = $contrato->id;
	            	$archivo->subidor_id = Yii::app()->user->id;	
				}else{
	        		Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a contrato: Debe adjuntar un archivo. Reintente");
					$this->refresh();
					Yii::app()->end();
	        	}
	        	
            	if(file_exists(Yii::app()->basePath.'/adjuntos/contratos/'.$contrato->id.$archivo->file->name)){
            		Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a contrato: Documento ya existe. Reintente");
					$this->refresh();
					Yii::app()->end();
            	}
            	
	            if($archivo->validate()){
	            	$archivo->save();
	            	$archivo->file->saveAs(Yii::app()->basePath.'/adjuntos/contratos/'.$contrato->id.$archivo->file->name);
	            	$contrato->save();
	            	Yii::app()->user->setFlash('adjuntarMessage',"Digitalización adjunta correctamente.");
					$this->refresh();
					Yii::app()->end();
	            }
	            else{
	            	Yii::app()->user->setFlash('adjuntarError',"Error: no se pudo adjuntar Digitalización.");
					$this->refresh();
					Yii::app()->end();
	            }
	        }
	        	        
		}
		
		$resoluciones = $contrato->getResoluciones();
		$garantias = $contrato->getGarantias();
				
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$this->render('adjuntar',array(
								'contrato'=>$contrato,
								'ultima_res'=>$ultima_resolucion,
								'archivo'=>$archivo,
								'resoluciones'=>$resoluciones,
								'garantias'=>$garantias,
							));
		
	}
	
	
	public function actionGarantias($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id == Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"No se pueden adjuntar digitalizaciones, pues contrato ya está cerrado.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede adjuntar digitalizaciones.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		$archivo = new AdjuntosGarantias;
		
		$contrato->modificador_id = Yii::app()->user->id;
		if($contrato->validate()){
			if(isset($_POST['AdjuntosGarantias']))
	        {
	        	$archivo->attributes=$_POST['AdjuntosGarantias'];
		        $archivo->file=CUploadedFile::getInstance($archivo,'file');
				if($archivo->file != null){
		        	$archivo->fecha = date("y-m-d");
	            	$archivo->nombre_archivo = $archivo->file->name;
	            	$archivo->subidor_id = Yii::app()->user->id;	
				}else{
	        		Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a garantía: Debe adjuntar un archivo. Reintente");
					$this->refresh();
					Yii::app()->end();
	        	}
	        	
            	if(file_exists(Yii::app()->basePath.'/adjuntos/garantias/'.$archivo->garantias_id.$archivo->file->name)){
            		Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a garantía: Documento ya existe. Reintente");
					$this->refresh();
					Yii::app()->end();
            	}
            	
	            if($archivo->validate()){
	            	$archivo->save();
	            	$archivo->file->saveAs(Yii::app()->basePath.'/adjuntos/garantias/'.$archivo->garantias_id.$archivo->file->name);
	            	$contrato->save();
	            	Yii::app()->user->setFlash('adjuntarMessage',"Digitalización adjunta correctamente.");
					$this->refresh();
					Yii::app()->end();
	            }
	            else{
	            	Yii::app()->user->setFlash('adjuntarError',"Error: no se pudo adjuntar Digitalización.");
					$this->refresh();
					Yii::app()->end();
	            }
	        }
	        	        
		}
		
		$garantias = $contrato->getGarantias();
				
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$this->render('adjuntarGarantia',array(
								'contrato'=>$contrato,
								'ultima_res'=>$ultima_resolucion,
								'archivo'=>$archivo,
								'garantias'=>$garantias,
							));
		
	}
	
	public function actionLibros($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id == Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"No se pueden adjuntar digitalizaciones, pues contrato ya está cerrado.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
	
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede adjuntar digitalizaciones.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		$archivo = new AdjuntosLibros;
	
		$contrato->modificador_id = Yii::app()->user->id;
		if($contrato->validate()){
			if(isset($_POST['AdjuntosLibros']))
			{
				$archivo->attributes=$_POST['AdjuntosLibros'];
				$archivo->file=CUploadedFile::getInstance($archivo,'file');
				if($archivo->file != null){
					$archivo->fecha = date("y-m-d");
					$archivo->nombre_archivo = $archivo->file->name;
					$archivo->subidor_id = Yii::app()->user->id;
				}
				else{
					Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a libro de contrato: Debe adjuntar un archivo. Reintente");
					$this->refresh();
					Yii::app()->end();
				}
	
				if(file_exists(Yii::app()->basePath.'/adjuntos/libros/'.$archivo->contratos_id.$archivo->file->name)){
					Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a libro de contrato: Documento ya existe. Reintente");
					$this->refresh();
					Yii::app()->end();
				}
				 
				if($archivo->validate()){
					$archivo->save();
					$archivo->file->saveAs(Yii::app()->basePath.'/adjuntos/libros/'.$archivo->contratos_id.$archivo->file->name);
					$contrato->save();
					Yii::app()->user->setFlash('adjuntarMessage',"Digitalización adjunta correctamente.");
					$this->refresh();
					Yii::app()->end();
				}
				else{
					Yii::app()->user->setFlash('adjuntarError',"Error: no se pudo adjuntar Digitalización.");
					$this->refresh();
					Yii::app()->end();
				}
			}

		}

		$ultima_resolucion = $contrato->getUltimaResolucion();
		$this->render('adjuntarLibro',array(
			'contrato'=>$contrato,
			'ultima_res'=>$ultima_resolucion,
			'archivo'=>$archivo,
		));
	
	}
	
	public function actionResolucionesAdj($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id == Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"No se pueden adjuntar digitalizaciones, pues contrato ya está cerrado.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede adjuntar digitalizaciones.");
			$this->actionAdminAdjuntar();
			Yii::app()->end();
		}
		$archivo = new AdjuntosResoluciones();
		$contrato->modificador_id = Yii::app()->user->id;
		if($contrato->validate()){
			if(isset($_POST['AdjuntosResoluciones']))
	        {
	        	$archivo->attributes=$_POST['AdjuntosResoluciones'];
	        	$archivo->file=CUploadedFile::getInstance($archivo,'file');
	     
				if($archivo->file != null){
		        	$archivo->fecha = date("y-m-d");
	            	$archivo->nombre_archivo = $archivo->file->name;
	            	$archivo->subidor_id = Yii::app()->user->id;	
				}else{
	        		Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a resolución: Debe adjuntar un archivo. Reintente");
					$this->refresh();
					Yii::app()->end();
	        	}
	        	
            	if(file_exists(Yii::app()->basePath.'/adjuntos/resoluciones/'.$archivo->resoluciones_id.$archivo->file->name)){
            		Yii::app()->user->setFlash('adjuntarError',"Error al adjuntar digitalización a resolución: Documento ya existe. Reintente");
					$this->refresh();
					Yii::app()->end();
            	}
            	
	            if($archivo->validate()){
	            	$archivo->save();
	            	$archivo->file->saveAs(Yii::app()->basePath.'/adjuntos/resoluciones/'.$archivo->resoluciones_id.$archivo->file->name);
	            	$contrato->save();
	            	Yii::app()->user->setFlash('adjuntarMessage',"Digitalización adjunta correctamente.");
					$this->refresh();
					Yii::app()->end();
	            }
	            else{
	            	Yii::app()->user->setFlash('adjuntarError',"Error: no se pudo adjuntar Digitalización.");
					$this->refresh();
					Yii::app()->end();
	            }
	        }
	        	        
		}
		
		$resoluciones = $contrato->getResoluciones();
				
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$this->render('adjuntarResolucion',array(
								'contrato'=>$contrato,
								'ultima_res'=>$ultima_resolucion,
								'resoluciones'=>$resoluciones,
							));
		
	}
	
	public function actionReabrir($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id != Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"Contrato no se puede reabrir, pues ya está abierto.");
			$this->actionAdminCerrados();
			Yii::app()->end();
		}
				
		$contrato->estados_contratos_id = Contratos::ESTADO_ADJUDICADO;
		$contrato->modificador_id = Yii::app()->user->id;
		if($contrato->validate()){
			$contrato->save();
			Yii::app()->user->setFlash('adminMessage',"Contrato reabierto.");
			$this->actionAdminCerrados();
			Yii::app()->end();
		}
		
	}
	
	public function actionAdminNuevos()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();  
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionAdminAdjudicados()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();  
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminAdjudicados',array(
			'model'=>$model,
		));
	}
	
	public function actionFlujosReales($id){

		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id != Contratos::ESTADO_ADJUDICADO){
			Yii::app()->user->setFlash('adminError',"No se pueden modificar los EP, pues el contrato no está adjudicado.");
			$this->actionAdminAdjudicados2();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se pueden modificar los EP.");
			$this->actionAdminAdjudicados2();
			Yii::app()->end();
		}
		
		$resoluciones = $contrato->getResoluciones();
		$flujosProgramados = array();
		$i=0;
		foreach($resoluciones as $reso){
			$programados = $reso->getFlujosProgramados();
			foreach($programados as $prog){
				$flujosProgramados[$reso->id][$i]=$prog;
				$i++;
			}
		}
		
		if(isset($_POST['EpObra']) || isset($_POST['EpAnticipo']) || isset($_POST['EpCanjeRetencion']))
		{	
			$borrar = array();
			$k=0;
			$valid = true;
			
			if(isset($_POST['EpObra'])):
			foreach($_POST['EpObra'] as $i=>$flujoArr){
				$flujo = new EpObra();
				$flujo->produccion = Tools::fixPlata($flujoArr['produccion']);
				$flujo->costo = Tools::fixPlata($flujoArr['costo']);
				$flujo->reajuste = Tools::fixPlata($flujoArr['reajuste']);
				$flujo->descuento = Tools::fixPlata($flujoArr['descuento']);
				$flujo->retencion = Tools::fixPlata($flujoArr['retencion']);
				$flujo->agno = $flujoArr['agno'];
				$flujo->mes = $flujoArr['mes'];
				$flujo->comentarios = $flujoArr['comentarios'];
				$flujo->resoluciones_id = $flujoArr['resoluciones_id'];
				
				$resolucion = Resoluciones::model()->findByPk($flujo->resoluciones_id);
				$resolucion->modificador_id = Yii::app()->user->id;
				if($resolucion->validate()){
					$resolucion->save();
				}
				
				$borrar[$k]=array('mes'=>$flujo->mes,'agno'=>$flujo->agno,'resoluciones_id'=>$flujo->resoluciones_id);
				$k++;
				if($flujo->produccion != "" || $flujo->costo != "" || $flujo->reajuste != "" || $flujo->descuento != "" || $flujo->retencion != ""){
					if($flujo->produccion == ""){
						$flujo->produccion = 0;
					}
					$valid = $valid && $flujo->validate();
					if($valid){
						$flujo->save();
					}
				}
			}
			endif;
			if(isset($_POST['EpCanjeRetencion'])):
			foreach($_POST['EpCanjeRetencion'] as $i=>$flujoArr){
				$flujo = new EpCanjeRetencion();
				$flujo->valor = Tools::fixPlata($flujoArr['valor']);
				$flujo->comentarios = $flujoArr['comentarios'];
				$flujo->resoluciones_id = $flujoArr['resoluciones_id'];
				$flujo->agno = $flujoArr['agno'];
				$flujo->mes = $flujoArr['mes'];
				
				$resolucion = Resoluciones::model()->findByPk($flujo->resoluciones_id);
				$resolucion->modificador_id = Yii::app()->user->id;
				if($resolucion->validate()){
					$resolucion->save();
				}
				
				$borrar[$k]=array('mes'=>$flujo->mes,'agno'=>$flujo->agno,'resoluciones_id'=>$flujo->resoluciones_id);
				$k++;
				if($flujo->valor != "" || $flujo->comentarios != ""){
					if($flujo->valor == ""){
						$flujo->valor = 0;
					}
					$valid = $valid && $flujo->validate();
					if($valid){
						$flujo->save();
					}
				}
			}
			endif;
			if(isset($_POST['EpAnticipo'])):
			foreach($_POST['EpAnticipo'] as $i=>$flujoArr){
				$flujo = new EpAnticipo();
				$flujo->valor = Tools::fixPlata($flujoArr['valor']);
				$flujo->comentarios = $flujoArr['comentarios'];
				$flujo->resoluciones_id = $flujoArr['resoluciones_id'];
				$flujo->agno = $flujoArr['agno'];
				$flujo->mes = $flujoArr['mes'];
				
				$resolucion = Resoluciones::model()->findByPk($flujo->resoluciones_id);
				$resolucion->modificador_id = Yii::app()->user->id;
				if($resolucion->validate()){
					$resolucion->save();
				}
				
				$borrar[$k]=array('mes'=>$flujo->mes,'agno'=>$flujo->agno,'resoluciones_id'=>$flujo->resoluciones_id);
				$k++;
				if($flujo->valor != "" || $flujo->comentarios != ""){
					if($flujo->valor == ""){
						$flujo->valor = 0;
					}
					$valid = $valid && $flujo->validate();
					if($valid){
						$flujo->save();
					}
				}
			}
			endif;
			
			if($valid){			
				Yii::app()->user->setFlash('adminMessage',"EP correctamente generados.");
				$this->actionAdminAdjudicados2();
				Yii::app()->end();
			}
			else{
				for($l=0;$l<$k;$l++){
                                    EpObra::model()->deleteAllByAttributes(array('resoluciones_id'=>$borrar[$l]['resoluciones_id'],'mes'=>$borrar[$l]['mes'],'agno'=>$borrar[$l]['agno']));	
				}
				Yii::app()->user->setFlash('adminError',"No se pudieron insertar los flujos reales, reintente");
				$this->actionAdminAdjudicados2();
				Yii::app()->end();
			}
		}
		
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$this->render('flujosReales',array('ultima_res'=>$ultima_resolucion,'flujosProgramados'=>$flujosProgramados,'resoluciones'=>$resoluciones,'contrato'=>$contrato));
		
		
	}
	
	public function actionFlujosProgramados($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id != Contratos::ESTADO_ADJUDICADO){
			Yii::app()->user->setFlash('adminError',"No se pueden modificar los flujos programados, pues el contrato no está adjudicado.");
			$this->actionAdminAdjudicados3();
			Yii::app()->end();
		}
	
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se pueden modificar los flujos programados.");
			$this->actionAdminAdjudicados3();
			Yii::app()->end();
		}
	
		$resoluciones = $contrato->getResoluciones();
		$flujosProgramados = array();
		$i=0;
		foreach($resoluciones as $reso){
			$programados = $reso->getFlujosProgramados();
			foreach($programados as $prog){
				$flujosProgramados[$reso->id][$i]=$prog;
				$i++;
			}
		}
	
		if(isset($_POST['FlujosProgramados']))
		{
			$valid = true;
			foreach($_POST['FlujosProgramados'] as $i=>$flujoArr){
				$flujo = FlujosProgramados::model()->findByPk($flujoArr['id']);
				if($flujo != null){
					$flujo->produccion = Tools::fixPlata($flujoArr['produccion']);
					$flujo->costo = Tools::fixPlata($flujoArr['costo']);
					$flujo->comentarios = $flujoArr['comentarios'];
					
					$resolucion = Resoluciones::model()->findByPk($flujo->resoluciones_id);
					$resolucion->modificador_id = Yii::app()->user->id;
					if($resolucion->validate()){
						$resolucion->save();
					}
		
					$valid = $valid && $flujo->validate();
					if($valid){
						$flujo->save();
					}
				}
			}
				
			if($valid){
				Yii::app()->user->setFlash('adminMessage',"Flujos programados correctamente modificados.");
				$this->refresh();
			}
			else{
				Yii::app()->user->setFlash('adminError',"No se pudieron modificar los flujos programados, reintente");
				$this->refresh();
			}
		}
	
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$this->render('flujosProgramados',array('ultima_res'=>$ultima_resolucion,'flujosProgramados'=>$flujosProgramados,'resoluciones'=>$resoluciones,'contrato'=>$contrato));
	}
	
	public function actionAdminAdjudicados2()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();  
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminAdjudicados2',array(
			'model'=>$model,
		));
	}
	
	public function actionAdminAdjudicados3()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminAdjudicados3',array(
				'model'=>$model,
		));
	}
	
	public function actionAdmin()
	{
		$model=new Contratos('search');
		$model->unsetAttributes();  
		if(isset($_GET['Contratos']))
			$model->attributes=$_GET['Contratos'];
		$this->render('listContratos',array(
			'model'=>$model,
		));
	}
	
	public function actionAdminConRes()
	{
		$model=new Contratos('search');
		$model->unsetAttributes();
		if(isset($_GET['Contratos']))
			$model->attributes=$_GET['Contratos'];
		$this->render('listContratosRes',array(
				'model'=>$model,
		));
	}
	
	public function actionAdminDig()
	{
		$model=new Contratos('search');
		$model->unsetAttributes();
		if(isset($_GET['Contratos']))
			$model->attributes=$_GET['Contratos'];
		$this->render('listContratosDig',array(
				'model'=>$model,
		));
	}
	
	public function actionAdminNoCerrados()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();  
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminNoCerrados',array(
			'model'=>$model,
		));
	}
	
	public function actionAdminCerrados()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();  
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminCerrados',array(
			'model'=>$model,
		));
	}
	
	public function actionEditar($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id != Contratos::ESTADO_ADJUDICADO){
			Yii::app()->user->setFlash('adminError',"Contrato no se puede editar, pues no está adjudicado.");
			$this->actionAdminAdjudicados();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede adjudicar.");
			$this->actionAdminAdjudicados();
			Yii::app()->end();
		}
		
		$resoluciones_antiguas = $contrato->getResoluciones();
		$flujosProgramados = array();
		$i=0;
		foreach($resoluciones_antiguas as $reso){
                    $programados = $reso->getFlujosProgramados();
                    foreach($programados as $prog){
                            $flujosProgramados[$reso->id][$i]=$prog;
                            $i++;
                    }
		}
		
		$ultima_resolucion = $contrato->getUltimaResolucion();
		$resolucion = new Resoluciones();
		if(isset($_POST['Resoluciones']))
		{			
                    $resolucion->attributes=$_POST['Resoluciones'];	
                    $resolucion->fecha_inicio=$ultima_resolucion->fecha_final;
                    $resolucion->fecha_tramitada=Tools::fixFecha($_POST['Resoluciones']['fecha_tramitada']);
                    $resolucion->contratos_id = $id;
                    $resolucion->fecha_resolucion = Tools::fixFecha($resolucion->fecha_resolucion);
                    $resolucion->fecha_final = Tools::fixFecha($_POST['Resoluciones']['fecha_final']);
                    $resolucion->monto = Tools::fixPlata($resolucion->monto);	
                    $resolucion->modificador_id = Yii::app()->user->id;
                    $resolucion->creador_id = Yii::app()->user->id;
                    $resolucion->plazo = $_POST['Resoluciones']['plazo'];

                    $valid = $resolucion->validate();
                    if($valid){
                            $resolucion->save();
                    }
                    else{
                            $this->render('adjudicado', array('mensaje'=>"ERROR en la Resolución: Resolución ".$resolucion->numero." no pudo ser creada, reintente."));
                            Yii::app()->end();
                    }
                    $contrato->modificador_id = Yii::app()->user->id;
                    $valid = $valid && $contrato->validate();
                    if(isset($_POST['FlujosProgramados'])){
                            if($valid){
                                    foreach($_POST['FlujosProgramados'] as $i=>$flujoArr){
                                            $flujo = $contrato->getFlujoProgramado(Tools::backMes($flujoArr['mes']),$flujoArr['agno']);
                                            if($flujo == null){
                                                    $flujo = new FlujosProgramados();
                                                    $flujo->agno = $flujoArr['agno'];
                                                    $flujo->mes = Tools::backMes($flujoArr['mes']);
                                                    $flujo->resoluciones_id = $resolucion->id;
                                            }
                                            $flujo->produccion = Tools::fixPlata($flujoArr['produccion']);
                                            $flujo->costo = Tools::fixPlata($flujoArr['costo']);
                                            $flujo->comentarios = $flujoArr['comentarios'];
                                            $valid = $valid && $flujo->validate();
                                            if($valid){
                                                    $flujo->save();
                                            }
                                    }				
                            }
                    }

                    if($valid){
                            $contrato->plazo = $contrato->calculaPlazo();
                            $contrato->monto_actualizado = $contrato->calculaMonto();
                            $contrato->valor_neto = (int)Tools::calculaNeto($contrato->monto_actualizado);
                            $contrato->modificaciones_monto = $contrato->monto_actualizado - $contrato->monto_inicial;
                            $valid = $valid && $contrato->validate();
                            if($valid){
                                    $contrato->save();
                                    $this->render('adjudicado', array('mensaje'=>"Resolución ".$resolucion->numero." correctamente creada"));
                                    Yii::app()->end();
                            }
                            else{
                                    FlujosProgramados::model()->deleteAllByAttributes(array('resoluciones_id'=>$resolucion->id));
                                    Resoluciones::model()->deleteByPk($resolucion->id);
                                    $this->render('adjudicado', array('mensaje'=>"ERROR: Resolución ".$resolucion->numero." no pudo ser creada, reintente."));
                                    Yii::app()->end();
                            }
                    }
                    else{
                            FlujosProgramados::model()->deleteAllByAttributes(array('resoluciones_id'=>$resolucion->id));
                            Resoluciones::model()->deleteByPk($resolucion->id);
                            $this->render('adjudicado', array('mensaje'=>"ERROR: Resolución ".$resolucion->numero." no pudo ser creada, reintente."));
                            Yii::app()->end();
                    }		
		}
		
		
		$proximoFlujo = $ultima_resolucion->getInicioProximoFlujoProgramado();
		$mesInicio = $proximoFlujo['mes'];
		$agnoInicio = $proximoFlujo['agno'];
		
		$flujosP = $contrato->getFlujosProgramados();
		
		$this->render('editar',array('flujosP'=>$flujosP,'ultima_res'=>$ultima_resolucion,'mesInicio'=>$mesInicio,'agnoInicio'=>$agnoInicio,'contrato'=>$contrato,'resolucion'=>$resolucion,'resoluciones_antiguas'=>$resoluciones_antiguas,'flujosProgramados'=>$flujosProgramados));
	}
		
	
	public function actionExisteResolucion(){
		$resolucion_nro = $_POST['Resoluciones']['numero'];
		$resolucion = Resoluciones::model()->findByAttributes(array('numero'=>$resolucion_nro));
		if($resolucion != null)
		{
			echo "Número resolución ya existe";
		}
	}
	
	public function actionExisteContrato(){
		$nombre = $_POST['Contratos']['nombre'];
		$id = $_POST['Contratos']['id'];
		$contrato = Contratos::model()->findByAttributes(array('nombre'=>$nombre));
		if($contrato != null && $id != $contrato->id)
		{
			echo "Nombre contrato ya existe";
		}
	}
	public function actionAdjudicar($id){
		
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id != Contratos::ESTADO_NUEVO){
			Yii::app()->user->setFlash('adminError',"Contrato ya está adjudicado, no se puede adjudicar.");
			$this->actionAdminNuevos();
			Yii::app()->end();
		}
		
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede adjudicar.");
			$this->actionAdminNuevos();
			Yii::app()->end();
		}
		
		$resolucion = new Resoluciones();
			
		if(isset($_POST['Resoluciones']))
		{
			$resolucion->attributes=$_POST['Resoluciones'];	
			$resolucion->contratos_id = $id;
			$resolucion->fecha_inicio = Tools::fixFecha($_POST['Resoluciones']['fecha_inicio']);
			$resolucion->fecha_tramitada = Tools::fixFecha($_POST['Resoluciones']['fecha_inicio']);
			$resolucion->fecha_resolucion = Tools::fixFecha($resolucion->fecha_resolucion);
			$resolucion->fecha_final = Tools::fixFecha($_POST['Resoluciones']['fecha_final']);	
			$resolucion->modificador_id = Yii::app()->user->id;
			$resolucion->creador_id = Yii::app()->user->id;
						
			$resolucion->monto = Tools::fixPlata($resolucion->monto);
			$valid = $resolucion->validate();
			if($valid){
				$resolucion->save();
			}
			else{
				$this->render('adjudicado', array('mensaje'=>"ERROR en la Resolución: Contrato ".$contrato->nombre." no pudo ser adjudicado, reintente."));
				Yii::app()->end();
			}
			$contrato->monto_inicial = $resolucion->monto;
			$contrato->estados_contratos_id = Contratos::ESTADO_ADJUDICADO;
			$contrato->modificador_id = Yii::app()->user->id;
			//$contrato->presupuesto_oficial = Tools::fixPlata($_POST['Contratos']['presupuesto_oficial']);
			
			
			
			$valid = $valid && $contrato->validate();
			
			if(isset($_POST['FlujosProgramados'])){
				if($valid){
					foreach($_POST['FlujosProgramados'] as $i=>$flujoArr){
						$flujo = new FlujosProgramados();
						$flujo->produccion = $flujoArr['produccion'];
						$flujo->costo = $flujoArr['costo'];
						$flujo->produccion = Tools::fixPlata($flujo->produccion);
						$flujo->costo = Tools::fixPlata($flujo->costo);
						$flujo->agno = $flujoArr['agno'];
						$flujo->mes = Tools::backMes($flujoArr['mes']);
						$flujo->resoluciones_id = $resolucion->id;
						$flujo->comentarios = $flujoArr['comentarios'];
						
						$valid = $valid && $flujo->validate();
						if($valid){
							$flujo->save();
						}
					}				
				}
			}
			
			if($valid){
				$contrato->plazo = $contrato->calculaPlazo();
				$contrato->monto_actualizado = $contrato->calculaMonto();
				$contrato->valor_neto = (int)Tools::calculaNeto($contrato->monto_actualizado);
				$contrato->modificaciones_monto = $contrato->monto_actualizado - $contrato->monto_inicial;
				$contrato->fecha_inicio_obra = Tools::fixFecha($_POST['Resoluciones']['fecha_inicio']);
				$valid = $valid && $contrato->validate();
				if($valid){
					$contrato->save();
					$this->render('adjudicado', array('mensaje'=>"Contrato ".$contrato->nombre." correctamente adjudicado según resolución N°: ".$resolucion->numero));
					Yii::app()->end();
				}
				else{
					FlujosProgramados::model()->deleteAllByAttributes(array('resoluciones_id'=>$resolucion->id));
					Resoluciones::model()->deleteAllByAttributes(array('contratos_id'=>$id));
					$this->render('adjudicado', array('mensaje'=>"ERROR: Contrato ".$contrato->nombre." no pudo ser adjudicado, reintente."));
					Yii::app()->end();
				}
				
			}
			else{
				FlujosProgramados::model()->deleteAllByAttributes(array('resoluciones_id'=>$resolucion->id));
				Resoluciones::model()->deleteAllByAttributes(array('contratos_id'=>$id));
				$this->render('adjudicado', array('mensaje'=>"ERROR: Contrato ".$contrato->nombre." no pudo ser adjudicado, reintente."));
				Yii::app()->end();
			}
		}
		$this->render('adjudicar',array('contrato'=>$contrato,'resolucion'=>$resolucion));
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
	
	public function actionDelete($id){
                $contrato = Contratos::model()->findByPk($id);
		
                if($contrato->estados_contratos_id == Contratos::ESTADO_CERRADO){
			Yii::app()->user->setFlash('adminError',"Contrato no se puede eliminar, pues está cerrado.");
			$this->actionAdminNoCerrados();
			Yii::app()->end();
		}
                
                /*
		if($contrato->estados_contratos_id != Contratos::ESTADO_NUEVO){
			Yii::app()->user->setFlash('adminError',"Contrato no se puede eliminar, pues está adjudicado.");
			$this->actionAdminNoCerrados();
			Yii::app()->end();
		}
		*/
		//adjuntos_contratos
		$adjuntos_contratos = AdjuntosContratos::model()->findAllByAttributes(array('contratos_id'=>$id));
		foreach($adjuntos_contratos as $adjunto){
			if($adjunto!=null){
				$nombre = $adjunto->nombre_archivo;
				$contrato = $adjunto->contratos_id;
				$adjunto->delete();
				if(is_file(Yii::app()->basePath.'/adjuntos/contratos/'.$contrato.$nombre)){
					unlink(Yii::app()->basePath.'/adjuntos/contratos/'.$contrato.$nombre);
				}
			}
		}
		//garantias
		$garantias = Garantias::model()->findAllByAttributes(array('contratos_id'=>$id));
		foreach($garantias as $garantia){
			//adjuntos_garantias
			$garantia_id = $garantia->id;
			$adjuntos_garantias = AdjuntosGarantias::model()->findAllByAttributes(array('garantias_id'=>$garantia_id));
			foreach($adjuntos_garantias as $adjunto){
				if($adjunto!=null){
					$nombre = $adjunto->nombre_archivo;
					$adjunto->delete();
					if(is_file(Yii::app()->basePath.'/adjuntos/garantias/'.$garantia_id.$nombre)){
						unlink(Yii::app()->basePath.'/adjuntos/garantias/'.$garantia_id.$nombre);
					}
				}
			}
			$garantia->delete();
		}
		//resoluciones
		$resoluciones = Resoluciones::model()->findAllByAttributes(array('contratos_id'=>$id));
		foreach($resoluciones as $resolucion){
			//adjuntos_resoluciones
			$resolucion_id = $resolucion->id;
			$adjuntos_resoluciones = AdjuntosResoluciones::model()->findAllByAttributes(array('resoluciones_id'=>$resolucion_id));
			foreach($adjuntos_resoluciones as $adjunto){
				if($adjunto!=null){
					$nombre = $adjunto->nombre_archivo;
					$adjunto->delete();
					if(is_file(Yii::app()->basePath.'/adjuntos/resoluciones/'.$resolucion_id.$nombre)){
						unlink(Yii::app()->basePath.'/adjuntos/resoluciones/'.$resolucion_id.$nombre);
					}
				}
			}
			//flujos_programados
			FlujosProgramados::model()->deleteAllByAttributes(array('resoluciones_id'=>$resolucion_id));
			//flujos_reales
			EpObra::model()->deleteAllByAttributes(array('resoluciones_id'=>$resolucion_id));
			$resolucion->delete();
		}
		//usuarios_contratos
		UsuariosContratos::model()->deleteAllByAttributes(array('contratos_id'=>$id));
		
		//y el contrato al final
		Contratos::model()->deleteByPk($id);
		
		Yii::app()->user->setFlash('adminMessage',"Contrato eliminado.");
		$this->actionAdminNoCerrados();
		Yii::app()->end();
	}

		
}