<?php

/**
 * This is the model class for table "cargaCombEquipoPropio".
 *
 * The followings are the available columns in table 'cargaCombEquipoPropio':
 * @property integer $id
 * @property string $petroleoLts
 * @property string $hCarguio
 * @property string $guia
 * @property string $factura
 * @property integer $precioUnitario
 * @property string $valorTotal
 * @property integer $faena_id
 * @property integer $tipoCombustible_id
 * @property integer $supervisorCombustible_id
 * @property integer $rEquipoPropio_id
 * @property string $factura
 */
class CargaCombEquipoPropio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CargaCombEquipoPropio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cargaCombEquipoPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('petroleoLts, precioUnitario, valorTotal, faena_id, tipoCombustible_id, supervisorCombustible_id, rEquipoPropio_id', 'required'),
			array('precioUnitario, faena_id, tipoCombustible_id, supervisorCombustible_id, rEquipoPropio_id', 'numerical', 'integerOnly'=>true),
			array('petroleoLts, hCarguio, valorTotal', 'length', 'max'=>12),
			array('guia, factura', 'length', 'max'=>45),
			array('nombre', 'length', 'max'=>100),
			array('fechaRendicion,numero', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, petroleoLts, hCarguio, guia, factura, precioUnitario, valorTotal, faena_id, tipoCombustible_id, supervisorCombustible_id, rEquipoPropio_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'petroleoLts' => 'Combustible Lts',
			'hCarguio' => 'Hr.Carguío',
			'guia' => 'N°Guia',
			'factura' => 'N°Documento',
			'precioUnitario' => 'Precio Unitario',
			'valorTotal' => 'Valor Total',
			'faena_id' => 'Faena',
			'tipoCombustible_id' => 'Tipo Combustible',
			'supervisorCombustible_id' => 'Supervisor Combustible',
			'rEquipoPropio_id' => 'R Equipo Propio',
			'numero'=>'N°Rendición',
			'nombre'=>'Nombre quien rinde o Proveedor',
			'fechaRendicion'=>'Fecha de Documento',
			'rut_rinde'=>'Rut quien rinde',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('petroleoLts',$this->petroleoLts,true);
		$criteria->compare('hCarguio',$this->hCarguio,true);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('precioUnitario',$this->precioUnitario);
		$criteria->compare('valorTotal',$this->valorTotal,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('tipoCombustible_id',$this->tipoCombustible_id);
		$criteria->compare('supervisorCombustible_id',$this->supervisorCombustible_id);
		$criteria->compare('rEquipoPropio_id',$this->rEquipoPropio_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}