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

	public $policy;

	public function getProveedor(){
		if(isset($this->gasto))
			return $this->gasto->supplier;
		return "";
	}

	public function getFecha(){
		if(isset($this->gasto))
		return $this->gasto->issue_date;
	}

	public function getNeto(){
		if(isset($this->gasto))
		return $this->gasto->net;
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

	public function getImagen(){
		if(isset($this->gasto)){
			if(isset($this->gasto->gastoImagens)){
				if(count($this->gasto->gastoImagens)>0){
					return $this->gasto->gastoImagens[0]->original;
				}
			};
		}
		
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gasto_completa';
	}

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
			array('id, retenido, cantidad, centro_costo_faena, departamento, faena, impuesto_especifico, iva, km_carguio, litros_combustible, monto_neto, nombre_quien_rinde, nro_documento, periodo_planilla, rut_proveedor, supervisor_combustible, tipo_documento, unidad, vehiculo_equipo, vehiculo_oficina_central, gasto_id', 'safe', 'on'=>'search'),
		);
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
			'iva' => 'Iva',
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
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria();

		$criteria->join = 'JOIN gasto g ON t.gasto_id = g.id and g.expense_policy_id = '.$this->policy; 

		$criteria->compare('id',$this->id);
		$criteria->compare('retenido',$this->retenido,true);
		$criteria->compare('cantidad',$this->cantidad,true);
		$criteria->compare('centro_costo_faena',$this->centro_costo_faena,true);
		$criteria->compare('departamento',$this->departamento,true);
		$criteria->compare('faena',$this->faena,true);
		$criteria->compare('impuesto_especifico',$this->impuesto_especifico,true);
		$criteria->compare('iva',$this->iva,true);
		$criteria->compare('km_carguio',$this->km_carguio,true);
		$criteria->compare('litros_combustible',$this->litros_combustible,true);
		$criteria->compare('monto_neto',$this->monto_neto,true);
		$criteria->compare('nombre_quien_rinde',$this->nombre_quien_rinde,true);
		$criteria->compare('nro_documento',$this->nro_documento,true);
		$criteria->compare('periodo_planilla',$this->periodo_planilla,true);
		$criteria->compare('rut_proveedor',$this->rut_proveedor,true);
		$criteria->compare('supervisor_combustible',$this->supervisor_combustible,true);
		$criteria->compare('tipo_documento',$this->tipo_documento,true);
		$criteria->compare('unidad',$this->unidad,true);
		$criteria->compare('vehiculo_equipo',$this->vehiculo_equipo,true);
		$criteria->compare('vehiculo_oficina_central',$this->vehiculo_oficina_central,true);
		$criteria->compare('gasto_id',$this->gasto_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>100,
			),
		));
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
}
