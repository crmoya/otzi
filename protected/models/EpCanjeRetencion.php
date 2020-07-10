<?php

/**
 * This is the model class for table "ep_canje_retencion".
 *
 * The followings are the available columns in table 'ep_canje_retencion':
 * @property integer $id
 * @property integer $valor
 * @property string $comentarios
 * @property integer $resoluciones_id
 * @property integer $mes
 * @property integer $agno
 *
 * The followings are the available model relations:
 * @property Resoluciones $resoluciones
 */
class EpCanjeRetencion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EpCanjeRetencion the static model class
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
		return 'ep_canje_retencion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('valor, resoluciones_id, mes, agno', 'required'),
			array('valor, resoluciones_id, mes, agno', 'numerical', 'integerOnly'=>true),
			array('comentarios', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, valor, comentarios, resoluciones_id, mes, agno', 'safe', 'on'=>'search'),
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
			'resoluciones' => array(self::BELONGS_TO, 'Resoluciones', 'resoluciones_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'valor' => 'Valor',
			'comentarios' => 'Comentarios',
			'resoluciones_id' => 'Resoluciones',
			'mes' => 'Mes',
			'agno' => 'Agno',
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
		$criteria->compare('valor',$this->valor);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('resoluciones_id',$this->resoluciones_id);
		$criteria->compare('mes',$this->mes);
		$criteria->compare('agno',$this->agno);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}