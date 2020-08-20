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
			array('id,proveedor,fecha,neto,total,categoria,grupocategoria,nota,folio', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria();

		//$criteria->join = 'JOIN gasto g ON t.gasto_id = g.id and g.expense_policy_id = '.$this->policy; 

		$criteria->join = "	join gasto on t.gasto_id = gasto.id 
							join informe_gasto on gasto.report_id = informe_gasto.id";
		$criteria->compare('id',$this->id);
		$criteria->compare('gasto.expense_policy_id',$this->policy);
		$criteria->compare('gasto.supplier',$this->proveedor,true);
		$criteria->compare('gasto.issue_date',Tools::fixFecha($this->fecha),true);
		$criteria->compare('gasto.net',$this->neto);
		$criteria->compare('gasto.total',$this->total);
		$criteria->compare('gasto.category',$this->categoria,true);
		$criteria->compare('gasto.category_group',$this->grupocategoria,true);
		$criteria->compare('gasto.note',$this->nota,true);
		$criteria->compare('informe_gasto.numero',$this->folio);
		$criteria->compare('retenido',$this->retenido);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('centro_costo_faena',$this->centro_costo_faena,true);
		$criteria->compare('departamento',$this->departamento,true);
		$criteria->compare('faena',$this->faena,true);
		$criteria->compare('impuesto_especifico',$this->impuesto_especifico);
		$criteria->compare('iva',$this->iva);
		$criteria->compare('km_carguio',$this->km_carguio);
		$criteria->compare('litros_combustible',$this->litros_combustible);
		$criteria->compare('monto_neto',$this->monto_neto);
		$criteria->compare('nombre_quien_rinde',$this->nombre_quien_rinde,true);
		$criteria->compare('nro_documento',$this->nro_documento,true);
		$criteria->compare('periodo_planilla',$this->periodo_planilla,true);
		$criteria->compare('rut_proveedor',$this->rut_proveedor,true);
		$criteria->compare('supervisor_combustible',$this->supervisor_combustible,true);
		$criteria->compare('tipo_documento',$this->tipo_documento,true);
		$criteria->compare('unidad',$this->unidad,true);
		$criteria->compare('vehiculo_equipo',$this->vehiculo_equipo,true);
		$criteria->compare('vehiculo_oficina_central',$this->vehiculo_oficina_central,true);
		
		$criteria->compare('gasto.status',1);

		
		$session=new CHttpSession;
  		$session->open();
		$session['criteria'] = $criteria;
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => 'gasto.issue_date DESC',
				'attributes'=>array(
					'proveedor'=>['asc' => 'gasto.supplier','desc' => 'gasto.supplier DESC',],
					'fecha'=>['asc' => 'gasto.issue_date','desc' => 'gasto.issue_date DESC',],
					'neto'=>['asc' => 'gasto.net','desc' => 'gasto.net DESC',],
					'total'=>['asc' => 'gasto.total','desc' => 'gasto.total DESC',],
					'categoria'=>['asc' => 'gasto.category','desc' => 'gasto.category DESC',],
					'grupocategoria'=>['asc' => 'gasto.category_group','desc' => 'gasto.category_group DESC',],
					'nota'=>['asc' => 'gasto.note','desc' => 'gasto.note DESC',],
					'folio'=>['asc' => 'informe_gasto.numero','desc' => 'informe_gasto.numero DESC',],
					'*',
				),
			),
			'pagination'=>[
				'pageSize'=>10,
			]
		));
	}

	public $policy;
	public $proveedor;
	public $fecha;
	public $neto;
	public $total;
	public $categoria;
	public $grupocategoria;
	public $nota;
	public $folio;

	const POLICY_COMBUSTIBLES = 44639;

	public function getFolioinforme(){
		if(isset($this->gasto))
			return $this->gasto->report_id;
		return "";
	}

	public function getNumeroinforme(){
		if(isset($this->gasto))
			if(isset($this->gasto->informeGasto)){
				return $this->gasto->informeGasto->numero;
			}
		return "";
	}

	public function getSupplier(){
		if(isset($this->gasto))
			return $this->gasto->supplier;
		return "";
	}

	public function getCommerce(){
		if(isset($this->gasto))
			return $this->gasto->supplier;
		return "";
	}

	public function getDate(){
		if(isset($this->gasto))
		return $this->gasto->issue_date;
	}

	public function getNet(){
		if(isset($this->gasto))
		return $this->gasto->net;
	}

	public function getTot(){
		if(isset($this->gasto))
		return $this->gasto->total;
	}

	public function getCategory(){
		if(isset($this->gasto))
		return $this->gasto->category;
	}

	public function getCategorygroup(){
		if(isset($this->gasto))
		return $this->gasto->category_group;
	}

	public function getNote(){
		if(isset($this->gasto))
		return $this->gasto->note;
	}

	public function getImage(){
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
			'net' => 'Neto',
			'tot' => 'Total',
			'category' => 'Categoría',
			'categorygroup' => 'Grupo Categoría',
			'note' => 'Nota',
			'folio' => 'Folio Informe'
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
