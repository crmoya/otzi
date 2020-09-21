<?php

/**
 * This is the model class for table "unidadfaena".
 *
 * The followings are the available columns in table 'unidadfaena':
 * @property integer $id
 * @property integer $unidad
 * @property string $pu
 * @property integer $cantidad
 * @property integer $faena_id
 *
 * The followings are the available model relations:
 * @property Faena $faena
 */
class Unidadfaena extends CActiveRecord
{

	public static function listar(){
		return [
			['id' =>1, 'nombre'=>'MINUTOS'], 
			['id' =>2, 'nombre'=>'HORAS'], 
			['id' =>3, 'nombre'=>'DÍAS'], 
			['id' =>4, 'nombre'=>'SEMANAS'], 
			['id' =>5, 'nombre'=>'MESES'], 
		];
	}

	public static function getUnidad($unidad){
		$unidades = [
			1=>'MINUTOS', 
			2=>'HORAS', 
			3=>'DÍAS', 
			4=>'SEMANAS', 
			5=>'MESES', 
		];
		if(isset($unidades[$unidad])){
			return $unidades[$unidad];
		}
		return "";
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unidadfaena';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('unidad, pu, cantidad, faena_id', 'required'),
			array('unidad, cantidad, faena_id', 'numerical', 'integerOnly'=>true),
			array('pu', 'length', 'max'=>12),
			array('pu', 'length', 'max'=>10),
			array('pu','esDecimal'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, unidad, pu, cantidad, faena_id', 'safe', 'on'=>'search'),
		);
	}

	
	public function esDecimal($attribute,$params)
	{
		if($this->$attribute=="")return;
		$numero = str_replace(",", ".", $this->$attribute);
		if(!is_numeric($numero))
			$this->addError($attribute, 'Debe ser número');
		if(strlen($numero."") > 11 )
			$this->addError($attribute, 'Número muy largo');
		if($numero<0){
			$this->addError($attribute, 'Debe ser mayor que 0');
		}
		if(strpos($numero."",".")<strlen($numero."")-3 && strpos($numero."",".")>0)
			$this->addError($attribute, 'No debe tener más de 2 decimales');
		
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'faena' => array(self::BELONGS_TO, 'Faena', 'faena_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'unidad' => 'Unidad',
			'pu' => 'Pu',
			'cantidad' => 'Cantidad',
			'faena_id' => 'Faena',
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
		$criteria->compare('unidad',$this->unidad);
		$criteria->compare('pu',$this->pu,true);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('faena_id',$this->faena_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Unidadfaena the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
