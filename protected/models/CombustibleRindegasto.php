<?php

/**
 * This is the model class for table "combustible_rindegasto".
 *
 * The followings are the available columns in table 'combustible_rindegasto':
 * @property integer $id
 * @property integer $camionpropio_id
 * @property integer $camionarrendado_id
 * @property integer $equipoarrendado_id
 * @property integer $equipopropio_id
 * @property string $fecha
 * @property integer $litros
 * @property integer $total
 * @property integer $gasto_completa_id
 *
 * The followings are the available model relations:
 * @property GastoCompleta $gastoCompleta
 * @property Camionarrendado $camionarrendado
 * @property Camionpropio $camionpropio
 * @property Equipoarrendado $equipoarrendado
 * @property Equipopropio $equipopropio
 */
class CombustibleRindegasto extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'combustible_rindegasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, litros, total, gasto_completa_id', 'required'),
			array('camionpropio_id, camionarrendado_id, equipoarrendado_id, equipopropio_id, litros, total, gasto_completa_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, camionpropio_id, camionarrendado_id, equipoarrendado_id, equipopropio_id, fecha, litros, total, gasto_completa_id', 'safe', 'on'=>'search'),
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
			'gastoCompleta' => array(self::BELONGS_TO, 'GastoCompleta', 'gasto_completa_id'),
			'camionarrendado' => array(self::BELONGS_TO, 'Camionarrendado', 'camionarrendado_id'),
			'camionpropio' => array(self::BELONGS_TO, 'Camionpropio', 'camionpropio_id'),
			'equipoarrendado' => array(self::BELONGS_TO, 'Equipoarrendado', 'equipoarrendado_id'),
			'equipopropio' => array(self::BELONGS_TO, 'Equipopropio', 'equipopropio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'camionpropio_id' => 'Camionpropio',
			'camionarrendado_id' => 'Camionarrendado',
			'equipoarrendado_id' => 'Equipoarrendado',
			'equipopropio_id' => 'Equipopropio',
			'fecha' => 'Fecha',
			'litros' => 'Litros',
			'total' => 'Total',
			'gasto_completa_id' => 'Gasto Completa',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('camionpropio_id',$this->camionpropio_id);
		$criteria->compare('camionarrendado_id',$this->camionarrendado_id);
		$criteria->compare('equipoarrendado_id',$this->equipoarrendado_id);
		$criteria->compare('equipopropio_id',$this->equipopropio_id);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('litros',$this->litros);
		$criteria->compare('total',$this->total);
		$criteria->compare('gasto_completa_id',$this->gasto_completa_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CombustibleRindegasto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
