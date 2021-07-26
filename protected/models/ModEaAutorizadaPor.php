<?php

/**
 * This is the model class for table "mod_ea_autorizada_por".
 *
 * The followings are the available columns in table 'mod_ea_autorizada_por':
 * @property integer $id
 * @property integer $usuario_1
 * @property integer $usuario_2
 * @property string $fecha
 * @property integer $rEquipoArrendado_id
 *
 * The followings are the available model relations:
 * @property Requipoarrendado $rEquipoArrendado
 * @property Usuario $usuario1
 * @property Usuario $usuario2
 */
class ModEaAutorizadaPor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mod_ea_autorizada_por';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usuario_1, usuario_2, fecha, rEquipoArrendado_id', 'required'),
			array('usuario_1, usuario_2, rEquipoArrendado_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, usuario_1, usuario_2, fecha, rEquipoArrendado_id', 'safe', 'on'=>'search'),
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
			'rEquipoArrendado' => array(self::BELONGS_TO, 'Requipoarrendado', 'rEquipoArrendado_id'),
			'usuario1' => array(self::BELONGS_TO, 'Usuario', 'usuario_1'),
			'usuario2' => array(self::BELONGS_TO, 'Usuario', 'usuario_2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'usuario_1' => 'Usuario 1',
			'usuario_2' => 'Usuario 2',
			'fecha' => 'Fecha',
			'rEquipoArrendado_id' => 'R Equipo Arrendado',
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
		$criteria->compare('usuario_1',$this->usuario_1);
		$criteria->compare('usuario_2',$this->usuario_2);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('rEquipoArrendado_id',$this->rEquipoArrendado_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModEaAutorizadaPor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
