<?php

/**
 * This is the model class for table "garantias".
 *
 * The followings are the available columns in table 'garantias':
 * @property integer $id
 * @property string $numero
 * @property string $monto
 * @property string $fecha_vencimiento
 * @property integer $instituciones_id
 * @property integer $tipos_garantias_id
 * @property integer $contratos_id
 * @property integer $objetos_garantias_id
 * @property integer $modificador_id
 * @property integer $creador_id
 * @property string $observacion
 * @property string $tipo_monto
 * @property integer $estado_garantia
 * @property string $fecha_devolucion
 *
 * The followings are the available model relations:
 * @property AdjuntosGarantias[] $adjuntosGarantiases
 * @property Instituciones $instituciones
 * @property TiposGarantias $tiposGarantias
 * @property Contratos $contratos
 * @property ObjetosGarantias $objetosGarantias
 * @property Usuarios $modificador
 * @property Usuarios $creador
 */
class Garantias extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Garantias the static model class
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
		return 'garantias';
	}
	
	public function getAdjuntos(){
		$adjuntos = AdjuntosGarantias::model()->findAllByAttributes(array('garantias_id'=>$this->id));
		return $adjuntos;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('numero, monto, fecha_vencimiento, instituciones_id, tipos_garantias_id, contratos_id, objetos_garantias_id, modificador_id, creador_id, tipo_monto', 'required'),
			array('instituciones_id, tipos_garantias_id, contratos_id, objetos_garantias_id, modificador_id, creador_id', 'numerical', 'integerOnly'=>true),
			array('numero', 'length', 'max'=>20),
			array('monto', 'length', 'max'=>13),
			array('tipo_monto', 'length', 'max'=>5),
			array('observacion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, numero, monto, fecha_vencimiento, instituciones_id, tipos_garantias_id, contratos_id, objetos_garantias_id, modificador_id, creador_id, observacion, tipo_monto', 'safe', 'on'=>'search'),
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
			'adjuntosGarantiases' => array(self::HAS_MANY, 'AdjuntosGarantias', 'garantias_id'),
			'instituciones' => array(self::BELONGS_TO, 'Instituciones', 'instituciones_id'),
			'tiposGarantias' => array(self::BELONGS_TO, 'TiposGarantias', 'tipos_garantias_id'),
			'contratos' => array(self::BELONGS_TO, 'Contratos', 'contratos_id'),
			'objetosGarantias' => array(self::BELONGS_TO, 'ObjetosGarantias', 'objetos_garantias_id'),
			'modificador' => array(self::BELONGS_TO, 'Usuarios', 'modificador_id'),
			'creador' => array(self::BELONGS_TO, 'Usuarios', 'creador_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'numero' => 'Número',
			'monto' => 'Monto',
			'fecha_vencimiento' => 'Fecha Vencimiento',
			'instituciones_id' => 'Instituciones',
			'tipos_garantias_id' => 'Tipo de Garantía',
			'contratos_id' => 'Contratos',
			'objetos_garantias_id' => 'Objeto de Garantía',
			'modificador_id' => 'Modificador',
			'creador_id' => 'Creador',
			'observacion' => 'Observación',
			'tipo_monto' => 'Monto en',
			'estado_garantia' => 'Estado Garantia',
			'fecha_devolucion' => 'Fecha Devolución',
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
		$criteria->compare('monto',$this->monto,true);
		$criteria->compare('fecha_vencimiento',$this->fecha_vencimiento,true);
		$criteria->compare('instituciones_id',$this->instituciones_id);
		$criteria->compare('tipos_garantias_id',$this->tipos_garantias_id);
		$criteria->compare('contratos_id',$this->contratos_id);
		$criteria->compare('objetos_garantias_id',$this->objetos_garantias_id);
		$criteria->compare('modificador_id',$this->modificador_id);
		$criteria->compare('creador_id',$this->creador_id);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('tipo_monto',$this->tipo_monto,true);
		$criteria->compare('estado_garantia',$this->estado_garantia);
		$criteria->compare('fecha_devolucion',$this->fecha_devolucion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}