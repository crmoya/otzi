<?php

/**
 * This is the model class for table "flujos_reales".
 *
 * The followings are the available columns in table 'flujos_reales':
 * @property integer $id
 * @property string $produccion
 * @property string $anticipo
 * @property string $costo
 * @property string $reajuste
 * @property string $retencion
 * @property string $descuento
 * @property integer $mes
 * @property integer $agno
 * @property string $comentarios
 * @property integer $resoluciones_id
 * @property string $tipo
 */
class FlujosReales extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'flujos_reales';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, mes, agno, resoluciones_id', 'numerical', 'integerOnly'=>true),
			array('produccion, costo, reajuste, retencion, descuento', 'length', 'max'=>20),
			array('anticipo', 'length', 'max'=>11),
			array('tipo', 'length', 'max'=>15),
			array('comentarios', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, produccion, anticipo, costo, reajuste, retencion, descuento, mes, agno, comentarios, resoluciones_id, tipo', 'safe', 'on'=>'search'),
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
			'produccion' => 'Produccion',
			'anticipo' => 'Anticipo',
			'costo' => 'Costo',
			'reajuste' => 'Reajuste',
			'retencion' => 'Retencion',
			'descuento' => 'Descuento',
			'mes' => 'Mes',
			'agno' => 'Agno',
			'comentarios' => 'Comentarios',
			'resoluciones_id' => 'Resoluciones',
			'tipo' => 'Tipo',
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
		$criteria->compare('produccion',$this->produccion,true);
		$criteria->compare('anticipo',$this->anticipo,true);
		$criteria->compare('costo',$this->costo,true);
		$criteria->compare('reajuste',$this->reajuste,true);
		$criteria->compare('retencion',$this->retencion,true);
		$criteria->compare('descuento',$this->descuento,true);
		$criteria->compare('mes',$this->mes);
		$criteria->compare('agno',$this->agno);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('resoluciones_id',$this->resoluciones_id);
		$criteria->compare('tipo',$this->tipo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FlujosReales the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
