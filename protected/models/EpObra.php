<?php

/**
 * This is the model class for table "ep_obra".
 *
 * The followings are the available columns in table 'ep_obra':
 * @property integer $id
 * @property integer $produccion
 * @property integer $costo
 * @property integer $reajuste
 * @property integer $retencion
 * @property integer $descuento
 * @property integer $mes
 * @property integer $agno
 * @property string $comentarios
 * @property integer $resoluciones_id
 *
 * The followings are the available model relations:
 * @property Resoluciones $resoluciones
 */
class EpObra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EpObra the static model class
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
		return 'ep_obra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('produccion, mes, agno, resoluciones_id', 'required'),
			array('produccion, costo, reajuste, retencion, descuento, mes, agno, resoluciones_id', 'numerical', 'integerOnly'=>true),
			array('comentarios', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, produccion, costo, reajuste, retencion, descuento, mes, agno, comentarios, resoluciones_id', 'safe', 'on'=>'search'),
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
			'produccion' => 'Produccion',
			'costo' => 'Costo',
			'reajuste' => 'Reajuste',
			'retencion' => 'Retencion',
			'descuento' => 'Descuento',
			'mes' => 'Mes',
			'agno' => 'Agno',
			'comentarios' => 'Comentarios',
			'resoluciones_id' => 'Resoluciones',
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
		$criteria->compare('produccion',$this->produccion);
		$criteria->compare('costo',$this->costo);
		$criteria->compare('reajuste',$this->reajuste);
		$criteria->compare('retencion',$this->retencion);
		$criteria->compare('descuento',$this->descuento);
		$criteria->compare('mes',$this->mes);
		$criteria->compare('agno',$this->agno);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('resoluciones_id',$this->resoluciones_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}