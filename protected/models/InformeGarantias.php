<?php

/**
 * This is the model class for table "informe_garantias".
 *
 * The followings are the available columns in table 'informe_garantias':
 * @property integer $id
 * @property string $numero
 * @property string $tipo_garantia
 * @property string $institucion
 * @property string $monto
 * @property string $moneda
 * @property string $contrato
 * @property string $objeto_garantia
 * @property string $fecha_vencimiento
 * @property string $estado
 * @property string $fecha_devolucion
 */
class InformeGarantias extends CActiveRecord
{
	
	// Propiedades adicionales definidas para la busqueda avanzada
	public $desde_fecha;
	public $hasta_fecha;
	
	
	public $desde_fecha_d;
	public $hasta_fecha_d;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InformeGarantias the static model class
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
		return 'informe_garantias';
	}

	/**
	 * @return entrega el nombre del campo que representa la pk de la vista (para autogenerar el CRUD)
	 */
	public function primaryKey()
	{
		return 'id';
	}
	
	protected function gridDataColumnFechaVencimiento($data,$row)
    {
     	return Tools::backFecha($data->fecha_vencimiento);   
	}
	
	protected function gridDataColumnFechaDevolucion($data,$row)
	{
		return Tools::backFecha($data->fecha_devolucion);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('numero, tipo_garantia, institucion, monto, moneda, contrato, objeto_garantia, fecha_vencimiento, fecha_devolucion', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('numero', 'length', 'max'=>20),
			array('tipo_garantia, institucion', 'length', 'max'=>45),
			array('monto', 'length', 'max'=>13),
			array('moneda', 'length', 'max'=>5),
			array('contrato, objeto_garantia', 'length', 'max'=>200),
			array('estado', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, numero, tipo_garantia, institucion, monto, moneda, contrato, objeto_garantia, fecha_vencimiento, estado, fecha_devolucion', 'safe', 'on'=>'search'),
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
			'numero' => 'Numero Garantía',
			'tipo_garantia' => 'Tipo Garantia',
			'institucion' => 'Institucion',
			'monto' => 'Monto',
			'moneda' => 'Moneda',
			'contrato' => 'Contrato',
			'objeto_garantia' => 'Objeto Garantia',
			'fecha_vencimiento' => 'Fecha Vencimiento',
			'fecha_devolucion' => 'Fecha Devolución',
			'estado' => 'Estado',
			'desde_fecha_d'=>'Desde Fecha Devolución',
			'hasta_fecha_d'=>'Hasta Fecha Devolución',
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
		$criteria->compare('numero',$this->numero,true);
		$criteria->compare('tipo_garantia',$this->tipo_garantia,true);
		$criteria->compare('institucion',$this->institucion,true);
		$criteria->compare('monto',$this->monto,true);
		$criteria->compare('moneda',$this->moneda,true);
		$criteria->compare('contrato',$this->contrato,true);
		$criteria->compare('objeto_garantia',$this->objeto_garantia,true);
		$criteria->compare('fecha_vencimiento',Tools::fixFecha($this->fecha_vencimiento),true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('fecha_devolucion',Tools::fixFecha($this->fecha_devolucion),true);
		
		if(!empty($this->desde_fecha) && empty($this->hasta_fecha))
        {
        	$criteria->addCondition("fecha_vencimiento >= '".Tools::fixFecha($this->desde_fecha)."'");
        }elseif(!empty($this->hasta_fecha) && empty($this->desde_fecha))
        {
            $criteria->addCondition("fecha_vencimiento <= '".Tools::fixFecha($this->hasta_fecha)."'");
        }elseif(!empty($this->desde_fecha) && !empty($this->hasta_fecha))
        {
            $criteria->addCondition("fecha_vencimiento >= '".Tools::fixFecha($this->desde_fecha)."'");
            $criteria->addCondition("fecha_vencimiento <= '".Tools::fixFecha($this->hasta_fecha)."'");
        }
         
        if(!empty($this->desde_fecha_d) && empty($this->hasta_fecha_d))
        {
        	$criteria->addCondition("fecha_devolucion >= '".Tools::fixFecha($this->desde_fecha_d)."'");
        }elseif(!empty($this->hasta_fecha_d) && empty($this->desde_fecha_d))
        {
        	$criteria->addCondition("fecha_devolucion <= '".Tools::fixFecha($this->hasta_fecha_d)."'");
        }elseif(!empty($this->desde_fecha_d) && !empty($this->hasta_fecha_d))
        {
        	$criteria->addCondition("fecha_devolucion  >= '".Tools::fixFecha($this->desde_fecha_d)."'");
			$criteria->addCondition("fecha_devolucion <= '".Tools::fixFecha($this->hasta_fecha_d)."'");
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}