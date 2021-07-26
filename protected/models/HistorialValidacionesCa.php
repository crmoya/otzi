<?php

/**
 * This is the model class for table "historial_validaciones_ca".
 *
 * The followings are the available columns in table 'historial_validaciones_ca':
 * @property integer $id
 * @property string $fecha
 * @property integer $rCamionArrendado_id
 * @property integer $usuario_id
 *
 * The followings are the available model relations:
 * @property Rcamionarrendado $rCamionArrendado
 * @property Usuario $usuario
 */
class HistorialValidacionesCa extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'historial_validaciones_ca';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, rCamionArrendado_id, usuario_id', 'required'),
			array('rCamionArrendado_id, usuario_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fecha, rCamionArrendado_id, usuario_id', 'safe', 'on'=>'search'),
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
			'rCamionArrendado' => array(self::BELONGS_TO, 'Rcamionarrendado', 'rCamionArrendado_id'),
			'usuario' => array(self::BELONGS_TO, 'Usuario', 'usuario_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fecha' => 'Fecha',
			'rCamionArrendado_id' => 'R Camion Arrendado',
			'usuario_id' => 'Usuario',
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
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('rCamionArrendado_id',$this->rCamionArrendado_id);
		$criteria->compare('usuario_id',$this->usuario_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HistorialValidacionesCa the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
