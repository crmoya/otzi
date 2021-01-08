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
class GastoCompleta extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			/*array('gasto_id', 'required'),
			array('gasto_id', 'numerical', 'integerOnly'=>true),
			array('retenido, cantidad, centro_costo_faena, departamento, faena, impuesto_especifico, iva, km_carguio, litros_combustible, monto_neto, nombre_quien_rinde, nro_documento, periodo_planilla, rut_proveedor, supervisor_combustible, tipo_documento, unidad, vehiculo_equipo, vehiculo_oficina_central', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.*/
			array('id, fecha_inicio, fecha_fin, igual', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria();

		/*
		$criteria->join = "	join gasto on t.gasto_id = gasto.id 
							join informe_gasto on gasto.report_id = informe_gasto.id";
*/
		//$criteria->compare('id',$this->id);
		
		$criteria->compare('estado',1);
		//$criteria->compare('politica',$this->policy);

		if($this->policy == GastoCompleta::POLICY_COMBUSTIBLES){
			$criteria->compare('politica',$this->policy);
		}
		else{
			$criteria->addCondition('politica <> :politica');
			$criteria->params[':politica'] = GastoCompleta::POLICY_COMBUSTIBLES;
		}

		if($this->igual == "SIN ERRORES"){
			$criteria->addCondition('total = total_calculado');
			if($this->policy == GastoCompleta::POLICY_COMBUSTIBLES){
				$criteria->addCondition("
					(tipo_documento = 'Factura Combustible' and monto_neto > 0 and impuesto_especifico > 0 and iva > 0) or tipo_documento = 'Boleta' or tipo_documento = 'Factura afecta' or tipo_documento = 'Vale'
				");
			}
			else{
				$criteria->addCondition("
					(tipo_documento = 'Factura afecta' and (monto_neto <> iva and total <> iva)) or tipo_documento <> 'Factura afecta'
				");
			}
		}
		if($this->igual == "CON ERRORES"){
			if($this->policy == GastoCompleta::POLICY_COMBUSTIBLES){
				$criteria->addCondition("
					(tipo_documento <> 'Factura Combustible' and tipo_documento <> 'Boleta' and tipo_documento <> 'Factura afecta' and tipo_documento <> 'Vale') or 
					(tipo_documento = 'Factura Combustible' and (iva = 0 or impuesto_especifico = 0 or monto_neto = 0)) or
					total <> total_calculado
				");
			}
			else{
				$criteria->addCondition("
					(tipo_documento = 'Factura afecta' and (monto_neto = iva and total = iva)) or
					total <> total_calculado
				");
			}
		}
		
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
		
		return $criteria;
	}

	public $policy;
	public $igual;
	public $fecha_inicio;
	public $fecha_fin;

	const POLICY_COMBUSTIBLES = 44639;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vGastoCompleta';
	}

	
	public function getGasto(){
		return Gasto::model()->findByPk($this->gasto_id);
	}

	/**
	 * @return array relational rules.
	 */
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
			'id' => 'ID',
			'retenido' => 'Retenido',
			'cantidad' => 'Cantidad',
			'centro_costo_faena' => 'Centro Costo Faena',
			'departamento' => 'Departamento',
			'faena' => 'Faena',
			'impuesto_especifico' => 'Impuesto Especifico',
			'iva' => 'IVA',
			'km_carguio' => 'Km Carguio',
			'litros_combustible' => 'Litros Combustible',
			'monto_neto' => 'Monto Neto',
			'nombre_quien_rinde' => 'Nombre Quien Rinde',
			'nro_documento' => 'Nro Documento',
			'periodo_planilla' => 'Periodo Planilla',
			'rut_proveedor' => 'Rut Proveedor',
			'supervisor_combustible' => 'Supervisor Combustible',
			'tipo_documento' => 'Tipo Documento',
			'unidad' => 'Unidad',
			'vehiculo_equipo' => 'Vehiculo Equipo',
			'vehiculo_oficina_central' => 'Vehiculo Oficina Central',
			'gasto_id' => 'Gasto',
			'grupocategoria' => 'Grupo Categoría',
			'categoria' => 'Categoría',
			'supplier' => 'Proveedor',
			'commerce' => 'Comercio', 
			'date' => 'Fecha',
			'tot' => 'Total',
			'category' => 'Categoría',
			'categorygroup' => 'Grupo Categoría',
			'note' => 'Nota',
			'folio' => 'Folio Informe',
			'igual' => 'Registros erróneos',
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
	protected function gridDataColumn($data, $row)
	{
		return Tools::backFecha($data->date);
	}

}
