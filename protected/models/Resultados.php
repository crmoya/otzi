<?php

/**
 * This is the model class for table "gasto_completa".
 *
 * The followings are the available columns in table 'gasto_completa':
 * @property integer $id
 * @property string $retenido
 * @property string $cantidad
 * @property string $centro_costo_faena
 * @property string $departamento
 * @property string $faena
 * @property string $impuesto_especifico
 * @property string $iva
 * @property string $km_carguio
 * @property string $litros_combustible
 * @property string $monto_neto
 * @property string $nombre_quien_rinde
 * @property string $nro_documento
 * @property string $periodo_planilla
 * @property string $rut_proveedor
 * @property string $supervisor_combustible
 * @property string $tipo_documento
 * @property string $unidad
 * @property string $vehiculo_equipo
 * @property string $vehiculo_oficina_central
 * @property integer $gasto_id
 *
 * The followings are the available model relations:
 * @property Gasto $gasto
 */
class Resultados extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, agruparPor,propiosOArrendados,chbRepuestos,chbCombustible', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria();
		
		if($this->fecha_inicio != "" && $this->fecha_fin == ""){
			$criteria->addCondition('fecha >= :fecha_inicio');
			$criteria->params[':fecha_inicio'] = $this->fecha_inicio;
		}
		if($this->fecha_inicio == "" && $this->fecha_fin != ""){
			$criteria->addCondition('fecha <= :fecha_fin');
			$criteria->params = [':fecha_fin'=>$this->fecha_fin];
		}
		if($this->fecha_inicio != "" && $this->fecha_fin != ""){
			$criteria->addCondition('fecha >= :fecha_inicio and fecha <= :fecha_fin');
			$criteria->params[':fecha_inicio'] = $this->fecha_inicio;
			$criteria->params[':fecha_fin'] = $this->fecha_fin;
		}

		if(isset($this->propiosOArrendados) && $this->propiosOArrendados != "TODOS"){
			if($this->propiosOArrendados == "CA" || $this->propiosOArrendados == "CP" || $this->propiosOArrendados == "EA" || $this->propiosOArrendados == "EP"){
				$criteria->addCondition('tipo_maquina = :tipo_maquina');
				$criteria->params[':tipo_maquina'] = $this->propiosOArrendados;
			}
			else if($this->propiosOArrendados == "C"){
				$criteria->addCondition("(tipo_maquina = 'CA' or tipo_maquina = 'CP')");
			}
			else if($this->propiosOArrendados == "E"){
				$criteria->addCondition("(tipo_maquina = 'EA' or tipo_maquina = 'EP')");
			}
		}

		$inicioAgrupacion = "";

		if($this->chbCombustible == 1 && $this->chbRepuestos == 1){
			$inicioAgrupacion = "	maquina,
									operador,
									centro_gestion,
									sum(produccion) as produccion,
									sum(repuestos) as repuestos,
									sum(combustible) as combustible,
									sum(produccion) - sum(repuestos) - sum(combustible) as resultados";
		}
		if($this->chbCombustible == 1 && $this->chbRepuestos == 0){
			$inicioAgrupacion = "	maquina,
									operador,
									centro_gestion,
									sum(produccion) as produccion,
									0 as repuestos,
									sum(combustible) as combustible,
									sum(produccion) - sum(combustible) as resultados";
		}
		if($this->chbCombustible == 0 && $this->chbRepuestos == 1){
			$inicioAgrupacion = "	maquina,
									operador,
									centro_gestion,
									sum(produccion) as produccion,
									sum(repuestos) as repuestos,
									0 as combustible,
									sum(produccion) - sum(repuestos) as resultados";
		}
		if($this->chbCombustible == 0 && $this->chbRepuestos == 0){
			$inicioAgrupacion = "	maquina,
									operador,
									centro_gestion,
									sum(produccion) as produccion,
									0 as repuestos,
									0 as combustible,
									sum(produccion) as resultados";
		}

		

		$finAgrupacion = "		maquina,operador,centro_gestion";

		if(isset($this->agruparPor) && $this->agruparPor != "NINGUNO"){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacion = "	maquina,
										'' as operador,
										'' as centro_gestion,
										sum(produccion) as produccion,
										sum(repuestos) as repuestos,
										sum(combustible) as combustible";
				$finAgrupacion = "		maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacion = "	'' as maquina,
										operador,
										'' as centro_gestion,
										sum(produccion) as produccion,
										sum(repuestos) as repuestos,
										sum(combustible) as combustible";
				$finAgrupacion = "		operador";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacion = "	'' as maquina,
										'' as operador,
										centro_gestion,
										sum(produccion) as produccion,
										sum(repuestos) as repuestos,
										sum(combustible) as combustible";
				$finAgrupacion = "		centro_gestion";
			}
			if($this->agruparPor == "CENTROMAQUINA"){
				$inicioAgrupacion = "	maquina,
										'' as operador,
										centro_gestion,
										sum(produccion) as produccion,
										sum(repuestos) as repuestos,
										sum(combustible) as combustible";
				$finAgrupacion = "		maquina,centro_gestion";
			}
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacion = "	'' as maquina,
										operador,
										centro_gestion,
										sum(produccion) as produccion,
										sum(repuestos) as repuestos,
										sum(combustible) as combustible";
				$finAgrupacion = "		operador,centro_gestion";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacion = "	maquina,
										operador,
										'' as centro_gestion,
										sum(produccion) as produccion,
										sum(repuestos) as repuestos,
										sum(combustible) as combustible";
				$finAgrupacion = "		maquina,operador";
			}
		}

		$criteria->select = $inicioAgrupacion;
		$criteria->group = $finAgrupacion;

		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $propiosOArrendados;
	public $agruparPor;
	public $chbRepuestos;
	public $chbCombustible;


	public function tableName()
	{
		return 'vresultados';
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'gasto' => array(self::BELONGS_TO, 'Gasto', 'gasto_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'propiosOArrendados' => 'Propios / Arrendados',
			'chbRepuestos' => 'Repuestos',
			'chbCombustible' => 'Combustible',
		);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GastoCompleta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getResultados(){
		return $this->produccion - $this->repuestos - $this->combustible;
	}

}
