<?php

/**
 * This is the model class for table "cargaCombCamionPropio".
 *
 * The followings are the available columns in table 'cargaCombCamionPropio':
 * @property integer $id
 * @property string $petroleoLts
 * @property string $kmCarguio
 * @property string $guia
 * @property string $factura
 * @property integer $precioUnitario
 * @property string $valorTotal
 * @property integer $faena_id
 * @property integer $tipoCombustible_id
 * @property integer $supervisorCombustible_id
 * @property integer $rCamionPropio_id
 */
class CargaCombCamionPropio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CargaCombCamionPropio the static model class
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
		return 'cargaCombCamionPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('petroleoLts, precioUnitario, valorTotal, faena_id, tipoCombustible_id, supervisorCombustible_id, rCamionPropio_id', 'required'),
			array('precioUnitario, faena_id, tipoCombustible_id, supervisorCombustible_id, rCamionPropio_id', 'numerical', 'integerOnly'=>true),
			array('petroleoLts, kmCarguio, valorTotal,precioUnitario', 'length', 'max'=>10),
			array('guia, factura', 'length', 'max'=>45),
			array('nombre', 'length', 'max'=>100),
			array('fechaRendicion,numero', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, petroleoLts, kmCarguio, guia, factura, precioUnitario, valorTotal, faena_id, tipoCombustible_id, supervisorCombustible_id, rCamionPropio_id', 'safe', 'on'=>'search'),
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
			'supervisor' => array(self::BELONGS_TO, 'SupervisorCombustible', 'supervisorCombustible_id'),
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
			'kmCarguio' => 'Km.Carguio',
			'guia' => 'N°Guia',
			'factura' => 'N°Documento',
			'precioUnitario' => 'Precio Unitario',
			'valorTotal' => 'Valor Total',
			'faena_id' => 'Faena',
			'tipoCombustible_id' => 'Tipo Combustible',
			'supervisorCombustible_id' => 'Supervisor Combustible',
			'rCamionPropio_id' => 'R Camion Propio',
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
		$criteria->compare('kmCarguio',$this->kmCarguio,true);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('precioUnitario',$this->precioUnitario);
		$criteria->compare('valorTotal',$this->valorTotal,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('tipoCombustible_id',$this->tipoCombustible_id);
		$criteria->compare('supervisorCombustible_id',$this->supervisorCombustible_id);
		$criteria->compare('rCamionPropio_id',$this->rCamionPropio_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}