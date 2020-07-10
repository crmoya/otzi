<?php

/**
 * This is the model class for table "administrativo".
 *
 * The followings are the available columns in table 'administrativo':
 * @property string $id
 * @property string $nombre_contrato
 * @property string $mes
 * @property integer $numero_resolucion
 * @property string $observacion
 * @property string $fecha_final
 * @property string $suma_monto_contrato
 */
class Administrativo extends CActiveRecord
{
	
	// Propiedades adicionales definidas para la busqueda avanzada
	public $agrupar_por;
	public $desde_mes;
	public $hasta_mes;
	public $desde_fecha;
	public $hasta_fecha;
        
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Administrativo the static model class
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
		return 'administrativo';
	}
	/**
	 * @return entrega el nombre del campo que representa la pk de la vista (para autogenerar el CRUD)
	 */
	public function primaryKey()
	{
		return 'id';
	}
	protected function gridDataColumnMes($data,$row)
    {
     	return Tools::backFecha($data->mes);   
	}
	protected function gridDataColumnFechaFinal($data,$row)
    {
     	return Tools::backFecha($data->fecha_final);   
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre_contrato, numero_resolucion, fecha_final', 'required'),
			array('numero_resolucion', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>44),
			//array('nombre_contrato', 'length', 'max'=>200),
			array('suma_monto_contrato', 'length', 'max'=>32),
			array('mes, observacion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre_contrato, mes, numero_resolucion, observacion, fecha_final, suma_monto_contrato', 'safe', 'on'=>'search'),
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
			'nombre_contrato' => 'Proyecto',
			'mes' => 'Mes',
			'numero_resolucion' => 'Numero Resolucion',
			'observacion' => 'Observacion',
			'fecha_final' => 'Fecha Final',
			'suma_monto_contrato' => 'Monto Contrato Neto',
			'desde_mes' => 'Desde',
			'hasta_mes' => 'Hasta',
			'desde_fecha' => 'Desde',
			'hasta_fecha' => 'Hasta'
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

		if($this->agrupar_por == 'nombre')
		{
			$criteria->select = array('CONCAT(nombre_contrato,": Desde ",MIN(mes), " hasta ",MAX(mes)) AS nombre_contrato','"VARIOS" AS MES','"VARIOS" AS numero_resolucion','"VARIOS" AS observacion','MAX(fecha_final) AS fecha_final','MAX(suma_monto_contrato) AS suma_monto_contrato');
			$criteria->group = 'nombre_contrato';
		}
		if($this->agrupar_por == 'mes')
		{
			$criteria->select = array('"VARIOS" AS nombre_contrato','mes','"VARIOS" AS numero_resolucion','"VARIOS" AS observacion','"VARIOS" AS fecha_final','SUM(suma_monto_contrato) AS suma_monto_contrato');
			$criteria->group = 'mes';
		}
		$criteria->compare('id',$this->id,true);
		$criteria->compare('nombre_contrato',$this->nombre_contrato,true);
		$criteria->compare('mes',Tools::fixFecha($this->mes),true);
		if(!empty($this->desde_mes) && empty($this->hasta_mes))
        {
            $criteria->addCondition("mes >= '".Tools::fixFecha($this->desde_mes)."'");
        }elseif(!empty($this->hasta_mes) && empty($this->desde_mes))
        {
            $criteria->addCondition("mes <= '".Tools::fixFecha($this->hasta_mes)."'");
        }elseif(!empty($this->desde_mes) && !empty($this->hasta_mes))
        {
            $criteria->addCondition("mes  >= '".Tools::fixFecha($this->desde_mes)."'");
			$criteria->addCondition("mes <= '".Tools::fixFecha($this->hasta_mes)."'");
        }
		$criteria->compare('numero_resolucion',$this->numero_resolucion);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('fecha_final',Tools::fixFecha($this->fecha_final),true);
		if(!empty($this->desde_fecha) && empty($this->hasta_fecha))
        {
            $criteria->addCondition("fecha_final >= '".Tools::fixFecha($this->desde_fecha)."'");
        }elseif(!empty($this->hasta_fecha) && empty($this->desde_fecha))
        {
            $criteria->addCondition("fecha_final <= '".Tools::fixFecha($this->fecha_fin)."'");
        }elseif(!empty($this->desde_fecha) && !empty($this->hasta_fecha))
        {
            $criteria->addCondition("fecha_final  >= '".Tools::fixFecha($this->desde_fecha)."'");
			$criteria->addCondition("fecha_final <= '".Tools::fixFecha($this->hasta_fecha)."'");
        }
		$criteria->compare('suma_monto_contrato',$this->suma_monto_contrato,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}