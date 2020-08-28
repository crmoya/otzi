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
			array('gasto_id', 'required'),
			array('gasto_id', 'numerical', 'integerOnly'=>true),
			array('retenido, cantidad, centro_costo_faena, departamento, faena, impuesto_especifico, iva, km_carguio, litros_combustible, monto_neto, nombre_quien_rinde, nro_documento, periodo_planilla, rut_proveedor, supervisor_combustible, tipo_documento, unidad, vehiculo_equipo, vehiculo_oficina_central', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fecha_inicio, fecha_fin, igual', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria();

		$criteria->join = "	join gasto on t.gasto_id = gasto.id 
							join informe_gasto on gasto.report_id = informe_gasto.id";

		$criteria->compare('id',$this->id);
		
		$criteria->compare('gasto.status',1);
		$criteria->compare('gasto.expense_policy_id',$this->policy);

		if($this->igual == "SIN ERRORES"){
			$criteria->addCondition('gasto.total = total_calculado');
		}
		if($this->igual == "CON ERRORES"){
			$criteria->addCondition('(gasto.total <> total_calculado or total_calculado is null)');
		}
		
		if($this->fecha_inicio != "" && $this->fecha_fin == ""){
			$criteria->addCondition('gasto.issue_date >= :fecha_inicio');
			$criteria->params[':fecha_inicio'] = $this->fecha_inicio;
		}
		if($this->fecha_inicio == "" && $this->fecha_fin != ""){
			$criteria->addCondition('gasto.issue_date <= :fecha_fin');
			$criteria->params = [':fecha_fin'=>$this->fecha_fin];
		}
		if($this->fecha_inicio != "" && $this->fecha_fin != ""){
			$criteria->addCondition('gasto.issue_date >= :fecha_inicio and gasto.issue_date <= :fecha_fin');
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

	public function getSupplier(){
		if(isset($this->gasto))
			return $this->gasto->supplier;
		return "";
	}

	public function getDate(){
		if(isset($this->gasto))
			return $this->gasto->issue_date;
	}

	public function getTotal(){
		if(isset($this->gasto))
		return $this->gasto->total;
	}

	public function getCategoria(){
		if(isset($this->gasto))
		return $this->gasto->category;
	}

	public function getGrupocategoria(){
		if(isset($this->gasto))
		return $this->gasto->category_group;
	}

	public function getNota(){
		if(isset($this->gasto))
		return $this->gasto->note;
	}

	public function getFolio(){
		if(isset($this->gasto))
			if(isset($this->gasto->informeGasto)){
				return $this->gasto->informeGasto->numero;
			}
		return "";
	}

	public function getImagen(){
		if(isset($this->gasto)){
			if(isset($this->gasto->gastoImagens)){
				if(count($this->gasto->gastoImagens)>0){
					if(isset($this->gasto->gastoImagens[0]->original)){
						return $this->gasto->gastoImagens[0]->original;
					}
					else if(isset($this->gasto->gastoImagens[0]->large)){
						return $this->gasto->gastoImagens[0]->large;
					}
					else if(isset($this->gasto->gastoImagens[0]->medium)){
						return $this->gasto->gastoImagens[0]->medium;
					}
					else if(isset($this->gasto->gastoImagens[0]->small)){
						return $this->gasto->gastoImagens[0]->small;
					}
				}
			}
		}
		return "SIN IMAGEN";
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gasto_completa';
	}

	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'gasto' => array(self::BELONGS_TO, 'Gasto', 'gasto_id'),
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
