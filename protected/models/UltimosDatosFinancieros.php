<?php

/**
 * This is the model class for table "ultimos_datos_financieros".
 *
 * The followings are the available columns in table 'ultimos_datos_financieros':
 * @property string $nombre_contrato
 * @property string $mes
 * @property string $valor_contrato_neto
 * @property string $saldo_por_cobrar
 * @property string $saldo_por_cobrar_retenciones
 * @property string $venta_facturada_neta
 * @property string $venta_facturada_acumulada_neta
 * @property integer $reajuste
 * @property string $reajustes_acumulados
 * @property integer $retencion
 * @property string $retenciones_acumuladas
 * @property integer $descuento
 * @property string $descuentos_acumulados
 * @property integer $produccion
 * @property string $produccion_acumulada
 * @property integer $costo
 * @property string $costo_acumulado
 * @property string $resultado_mensual_neto
 * @property string $porc_rent_sobre_valor_contrato
 * @property string $resultado_acumulado_neto
 * @property string $porc_rent_sobre_valor_contrato_acum
 */
class UltimosDatosFinancieros extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UltimosDatosFinancieros the static model class
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
		return 'ultimos_datos_financieros';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre_contrato, produccion', 'required'),
			array('reajuste, retencion, descuento, produccion, costo', 'numerical', 'integerOnly'=>true),
			array('nombre_contrato', 'length', 'max'=>200),
			array('valor_contrato_neto', 'length', 'max'=>38),
			array('saldo_por_cobrar', 'length', 'max'=>39),
			array('saldo_por_cobrar_retenciones', 'length', 'max'=>40),
			array('venta_facturada_neta', 'length', 'max'=>14),
			array('venta_facturada_acumulada_neta', 'length', 'max'=>35),
			array('reajustes_acumulados, retenciones_acumuladas, descuentos_acumulados, produccion_acumulada, costo_acumulado', 'length', 'max'=>32),
			array('resultado_mensual_neto', 'length', 'max'=>12),
			array('porc_rent_sobre_valor_contrato', 'length', 'max'=>25),
			array('resultado_acumulado_neto', 'length', 'max'=>36),
			array('porc_rent_sobre_valor_contrato_acum', 'length', 'max'=>50),
			array('mes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('nombre_contrato, mes, valor_contrato_neto, saldo_por_cobrar, saldo_por_cobrar_retenciones, venta_facturada_neta, venta_facturada_acumulada_neta, reajuste, reajustes_acumulados, retencion, retenciones_acumuladas, descuento, descuentos_acumulados, produccion, produccion_acumulada, costo, costo_acumulado, resultado_mensual_neto, porc_rent_sobre_valor_contrato, resultado_acumulado_neto, porc_rent_sobre_valor_contrato_acum', 'safe', 'on'=>'search'),
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
			'nombre_contrato' => 'Nombre Contrato',
			'mes' => 'Mes',
			'valor_contrato_neto' => 'Valor Contrato Neto',
			'saldo_por_cobrar' => 'Saldo Por Cobrar',
			'saldo_por_cobrar_retenciones' => 'Saldo Por Cobrar Retenciones',
			'venta_facturada_neta' => 'Venta Facturada Neta',
			'venta_facturada_acumulada_neta' => 'Venta Facturada Acumulada Neta',
			'reajuste' => 'Reajuste',
			'reajustes_acumulados' => 'Reajustes Acumulados',
			'retencion' => 'Retencion',
			'retenciones_acumuladas' => 'Retenciones Acumuladas',
			'descuento' => 'Descuento',
			'descuentos_acumulados' => 'Descuentos Acumulados',
			'produccion' => 'Produccion',
			'produccion_acumulada' => 'Produccion Acumulada',
			'costo' => 'Costo',
			'costo_acumulado' => 'Costo Acumulado',
			'resultado_mensual_neto' => 'Resultado Mensual Neto',
			'porc_rent_sobre_valor_contrato' => 'Porc Rent Sobre Valor Contrato',
			'resultado_acumulado_neto' => 'Resultado Acumulado Neto',
			'porc_rent_sobre_valor_contrato_acum' => 'Porc Rent Sobre Valor Contrato Acum',
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

		$criteria->compare('nombre_contrato',$this->nombre_contrato,true);
		$criteria->compare('mes',$this->mes,true);
		$criteria->compare('valor_contrato_neto',$this->valor_contrato_neto,true);
		$criteria->compare('saldo_por_cobrar',$this->saldo_por_cobrar,true);
		$criteria->compare('saldo_por_cobrar_retenciones',$this->saldo_por_cobrar_retenciones,true);
		$criteria->compare('venta_facturada_neta',$this->venta_facturada_neta,true);
		$criteria->compare('venta_facturada_acumulada_neta',$this->venta_facturada_acumulada_neta,true);
		$criteria->compare('reajuste',$this->reajuste);
		$criteria->compare('reajustes_acumulados',$this->reajustes_acumulados,true);
		$criteria->compare('retencion',$this->retencion);
		$criteria->compare('retenciones_acumuladas',$this->retenciones_acumuladas,true);
		$criteria->compare('descuento',$this->descuento);
		$criteria->compare('descuentos_acumulados',$this->descuentos_acumulados,true);
		$criteria->compare('produccion',$this->produccion);
		$criteria->compare('produccion_acumulada',$this->produccion_acumulada,true);
		$criteria->compare('costo',$this->costo);
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