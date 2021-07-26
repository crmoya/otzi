<?php

/**
 * This is the model class for table "facturacionCombustibleEquipoArrendado".
 *
 * The followings are the available columns in table 'facturacionCombustibleEquipoArrendado':
 * @property integer $id
 * @property string $guia
 * @property string $factura
 * @property integer $centroGestion_id
 * @property integer $tipoCombustible_id
 * @property integer $precioUnitario
 * @property integer $valorTotal
 * @property integer $supervisor_id
 * @property integer $carga_id
 */
class FacturacionCombustibleEquipoArrendado extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FacturacionCombustibleEquipoArrendado the static model class
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
		return 'facturacionCombustibleEquipoArrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipoCombustible_id, precioUnitario, valorTotal, supervisor_id, carga_id', 'required'),
			array('centroGestion_id, tipoCombustible_id, precioUnitario, valorTotal, supervisor_id, carga_id', 'numerical', 'integerOnly'=>true),
			array('guia, factura', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, guia, factura, centroGestion_id, tipoCombustible_id, precioUnitario, valorTotal, supervisor_id, carga_id', 'safe', 'on'=>'search'),
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
			'guia' => 'Guia',
			'factura' => 'Factura',
			'centroGestion_id' => 'Centro Gestion',
			'tipoCombustible_id' => 'Tipo Combustible',
			'precioUnitario' => 'Precio Unitario',
			'valorTotal' => 'Valor Total',
			'supervisor_id' => 'Supervisor',
			'carga_id' => 'Carga',
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
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('centroGestion_id',$this->centroGestion_id);
		$criteria->compare('tipoCombustible_id',$this->tipoCombustible_id);
		$criteria->compare('precioUnitario',$this->precioUnitario);
		$criteria->compare('valorTotal',$this->valorTotal);
		$criteria->compare('supervisor_id',$this->supervisor_id);
		$criteria->compare('carga_id',$this->carga_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}