<?php

/**
 * This is the model class for table "datos".
 *
 * The followings are the available columns in table 'datos':
 * @property string $id
 * @property string $nombre
 * @property string $mes
 * @property intenger $tipo
 * @property string $valor_contrato_neto
 * @property integer $anticipo
 * @property string $saldo_por_cobrar
 * @property integer $reajuste
 * @property string $reajustes_acumulados
 * @property integer $retencion
 * @property string $retenciones_acumuladas
 * @property integer $descuento
 * @property string $descuentos_acumulados
 * @property integer $produccion
 * @property string $produccion_acumulada
 */
class Datos extends CActiveRecord
{
	public $desde_mes;
	public $hasta_mes;
	public $agrupar_por;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Datos the static model class
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
		return 'datos';
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
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('anticipo, reajuste, retencion, descuento, produccion', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>210),
			array('nombre', 'length', 'max'=>200),
			array('tipo', 'length', 'max'=>15),
			array('valor_contrato_neto', 'length', 'max'=>38),
			array('saldo_por_cobrar', 'length', 'max'=>39),
			array('reajustes_acumulados, retenciones_acumuladas, descuentos_acumulados, produccion_acumulada', 'length', 'max'=>32),
			array('mes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre, mes, tipo, valor_contrato_neto, anticipo, saldo_por_cobrar, reajuste, reajustes_acumulados, retencion, retenciones_acumuladas, descuento, descuentos_acumulados, produccion, produccion_acumulada', 'safe', 'on'=>'search'),
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
			'nombre' => 'Proyecto',
			'mes' => 'Mes',
			'tipo' => 'Tipo EP',
			'valor_contrato_neto' => 'Valor Contrato Neto',
			'anticipo' => 'Anticipo',
			'saldo_por_cobrar' => 'Saldo Por Ejecutar',
			'reajuste' => 'Reajuste',
			'reajustes_acumulados' => 'Reajuste Acum.',
			'retencion' => 'Retencion',
			'retenciones_acumuladas' => 'Retenciones Acumuladas',
			'descuento' => 'Descuento',
			'descuentos_acumulados' => 'Dctos. Acum.',
			'produccion' => 'Ejecutado',
			'produccion_acumulada' => 'Prod. Acum.',
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
			$criteria->addCondition("CONCAT(nombre,mes) IN (SELECT CONCAT(nombre_contrato,mes) FROM ultimos_datos_financieros)");
			$criteria->group = 'nombre';
		}
		if($this->agrupar_por == 'mes')
		{
			$criteria->select = array('"VARIOS" AS nombre','mes','SUM(valor_contrato_neto) AS valor_contrato_neto','SUM(saldo_por_cobrar) AS saldo_por_cobrar', 'SUM(reajuste) AS reajuste', 'SUM(reajustes_acumulados) AS reajustes_acumulados', 'SUM(retencion) AS retencion', 'SUM(retenciones_acumuladas) AS retenciones_acumuladas', 'SUM(descuento) AS descuento', 'SUM(descuentos_acumulados) AS descuentos_acumulados', 'SUM(produccion) AS produccion', 'SUM(produccion_acumulada) AS produccion_acumulada');
			$criteria->group = 'mes';
		}
		
		$criteria->compare('id',$this->id,true);
		$criteria->compare('nombre',$this->nombre,true);
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
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('valor_contrato_neto',$this->valor_contrato_neto,true);
		$criteria->compare('anticipo',$this->anticipo);
		$criteria->compare('saldo_por_cobrar',$this->saldo_por_cobrar,true);
		$criteria->compare('reajuste',$this->reajuste);
		$criteria->compare('reajustes_acumulados',$this->reajustes_acumulados,true);
		$criteria->compare('retencion',$this->retencion);
		$criteria->compare('retenciones_acumuladas',$this->retenciones_acumuladas,true);
		$criteria->compare('descuento',$this->descuento);
		$criteria->compare('descuentos_acumulados',$this->descuentos_acumulados,true);
		$criteria->compare('produccion',$this->produccion);
		$criteria->compare('produccion_acumulada',$this->produccion_acumulada,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}