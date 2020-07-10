<?php

/**
 * This is the model class for table "financiero".
 *
 * The followings are the available columns in table 'financiero':
 * @property string $id
 * @property string $nombre_contrato
 * @property string $mes
 * @property string $tipo
 * @property string $saldo_por_cobrar_retenciones
 * @property string $venta_facturada_neta
 * @property string $venta_facturada_acumulada_neta
 * @property integer $costo
 * @property string $costo_acumulado
 * @property string $resultado_mensual_neto
 * @property string $porc_rent_sobre_valor_contrato
 * @property string $resultado_acumulado_neto
 * @property string $porc_rent_sobre_valor_contrato_acum
 */
class Financiero extends CActiveRecord
{
	public $desde_mes;
	public $hasta_mes;
	public $agrupar_por;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Financiero the static model class
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
		return 'financiero';
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
			array('costo', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>210),
			array('nombre_contrato', 'length', 'max'=>200),
			array('tipo', 'length', 'max'=>15),
			array('saldo_por_cobrar_retenciones', 'length', 'max'=>40),
			array('venta_facturada_neta, resultado_mensual_neto', 'length', 'max'=>20),
			array('venta_facturada_acumulada_neta', 'length', 'max'=>35),
			array('costo_acumulado', 'length', 'max'=>32),
			array('porc_rent_sobre_valor_contrato', 'length', 'max'=>25),
			array('resultado_acumulado_neto', 'length', 'max'=>36),
			array('porc_rent_sobre_valor_contrato_acum', 'length', 'max'=>50),
			array('mes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre_contrato, mes, tipo, saldo_por_cobrar_retenciones, venta_facturada_neta, venta_facturada_acumulada_neta, costo, costo_acumulado, resultado_mensual_neto, porc_rent_sobre_valor_contrato, resultado_acumulado_neto, porc_rent_sobre_valor_contrato_acum', 'safe', 'on'=>'search'),
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
			'tipo' => 'Tipo EP',
			'saldo_por_cobrar_retenciones' => 'Saldo Por Cobrar Retenciones',
			'venta_facturada_neta' => 'Venta Facturada Neta',
			'venta_facturada_acumulada_neta' => 'Venta Facturada Acum Neta',
			'costo' => 'Costo',
			'costo_acumulado' => 'Costo Acumulado',
			'resultado_mensual_neto' => 'Resultado Mensual Neto',
			'porc_rent_sobre_valor_contrato' => '% Rent. Valor Contrato',
			'resultado_acumulado_neto' => 'Resultado Acumulado Neto',
			'porc_rent_sobre_valor_contrato_acum' => '% Rent.Valor Contr.Acum',
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
			$criteria->addCondition("CONCAT(nombre_contrato,mes) IN (SELECT CONCAT(nombre_contrato,mes) FROM ultimos_datos_financieros)");
			$criteria->group = 'nombre_contrato';
		}
		if($this->agrupar_por == 'mes')
		{
			$criteria->select = array('GROUP_CONCAT(DISTINCT nombre_contrato SEPARATOR "-") AS nombre_contrato','mes','SUM(saldo_por_cobrar_retenciones) AS saldo_por_cobrar_retenciones','SUM(venta_facturada_neta) AS venta_facturada_neta', 'SUM(venta_facturada_acumulada_neta) AS venta_facturada_acumulada_neta', 'SUM(costo) AS costo', 'SUM(costo_acumulado) AS costo_acumulado', 'SUM(resultado_mensual_neto) AS resultado_mensual_neto', 'AVG(porc_rent_sobre_valor_contrato) AS porc_rent_sobre_valor_contrato', 'SUM(resultado_acumulado_neto) AS resultado_acumulado_neto', 'AVG(porc_rent_sobre_valor_contrato_acum) AS porc_rent_sobre_valor_contrato_acum');
			$criteria->group = 'mes';
		}
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
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('saldo_por_cobrar_retenciones',$this->saldo_por_cobrar_retenciones,true);
		$criteria->compare('venta_facturada_neta',$this->venta_facturada_neta,true);
		$criteria->compare('venta_facturada_acumulada_neta',$this->venta_facturada_acumulada_neta,true);
		$criteria->compare('costo',$this->costo,true);
		$criteria->compare('costo_acumulado',$this->costo_acumulado,true);
		$criteria->compare('resultado_mensual_neto',$this->resultado_mensual_neto,true);
		$criteria->compare('porc_rent_sobre_valor_contrato',$this->porc_rent_sobre_valor_contrato,true);
		$criteria->compare('resultado_acumulado_neto',$this->resultado_acumulado_neto,true);
		$criteria->compare('porc_rent_sobre_valor_contrato_acum',$this->porc_rent_sobre_valor_contrato_acum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}