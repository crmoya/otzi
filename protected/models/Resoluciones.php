<?php

/**
 * This is the model class for table "resoluciones".
 *
 * The followings are the available columns in table 'resoluciones':
 * @property integer $id
 * @property integer $numero
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property integer $monto
 * @property integer $contratos_id
 * @property string $observacion
 *
 * The followings are the available model relations:
 * @property Garantias[] $garantiases
 * @property Contratos $contratos
 */
class Resoluciones extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Resoluciones the static model class
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
		return 'resoluciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('numero, monto,plazo,fecha_resolucion,contratos_id,fecha_tramitada', 'required'),
			array('numero','unique'),	
			array('numero', 'length', 'max'=>50),
			array('monto', 'length', 'max'=>14),
			array('contratos_id,plazo', 'numerical', 'integerOnly'=>true),
			array('observacion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, numero, fecha_inicio, fecha_final, monto, contratos_id, observacion', 'safe', 'on'=>'search'),
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
			'contratos' => array(self::BELONGS_TO, 'Contratos', 'contratos_id'),
			'flujosProgramadoses' => array(self::HAS_MANY, 'FlujosProgramados', 'resoluciones_id'),
			'flujosReales' => array(self::HAS_MANY, 'FlujosReales', 'resoluciones_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'numero' => 'Resolución u Orden de Compra',
			'fecha_inicio' => 'Fecha de Res. tramitada u OC emitida',
			'fecha_final' => 'Fecha Final',
			'fecha_resolucion' => 'Fecha Resolución u Orden de Compra',
			'monto' => 'Monto de adjudicación con IVA',
			'contratos_id' => 'Contratos',
			'observacion' => 'Observación',
			'plazo'=>'Plazo en Días',
			'file'=>'Archivo',
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
		$criteria->compare('numero',$this->numero);
		$criteria->compare('fecha_inicio',$this->fecha_inicio,true);
		$criteria->compare('fecha_final',$this->fecha_final,true);
		$criteria->compare('monto',$this->monto);
		$criteria->compare('contratos_id',$this->contratos_id);
		$criteria->compare('observacion',$this->observacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function calculaPlazo(){
		$plazo = 0;
		$fecha_menor = $this->fecha_inicio;
		$fecha_mayor = $this->fecha_final;		
		$mes_inicio = date("n",$fecha_menor);
		$agno_inicio = date("Y",$fecha_menor);
		$mes_fin = date("n",$fecha_mayor);
		$agno_fin = date("Y",$fecha_mayor);
		$agnos_diff = $agno_fin - $agno_inicio;			
		$plazo = $mes_fin - $mes_inicio + 1 + 12*$agnos_diff;
	
		return $plazo;
	}
	
	public function getFlujosProgramados(){
		$criteria = new CDbCriteria(array('order'=>'id'));
		$flujos = FlujosProgramados::model()->findAllByAttributes(array('resoluciones_id'=>$this->id),$criteria);
		return $flujos;
	}
	
	public function getFlujosProgramadosHasta($mes,$agno){
		$criteria=new CDbCriteria();
		return FlujosProgramados::model()->findAll(
			'agno < :agno OR (agno = :agno AND mes <= :mes)',
			array('agno'=>$agno,'mes'=>$mes)
		);
	}
	
	public function getAdjuntos(){
		$adjuntos = AdjuntosResoluciones::model()->findAllByAttributes(array('resoluciones_id'=>$this->id));
		return $adjuntos;
	}
	
	public function getFlujosReales(){
		$flujos = FlujosReales::model()->findAllByAttributes(array('resoluciones_id'=>$this->id));
		return $flujos;
	}

	public function getInicioProximoFlujoProgramado(){
		$fFin_ultimaRes = $this->fecha_final;
		$mes_fin = Tools::getMonth($fFin_ultimaRes);
		$agno_fin = Tools::getYear($fFin_ultimaRes);
		return Tools::avanzaMes($mes_fin, $agno_fin);
	}
	
}