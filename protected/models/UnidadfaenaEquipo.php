<?php

/**
 * This is the model class for table "unidadfaena_equipo".
 *
 * The followings are the available columns in table 'unidadfaena_equipo':
 * @property integer $id
 * @property integer $unidad
 * @property string $pu
 * @property integer $faena_id
 * @property integer $equipoarrendado_id
 * @property integer $equipopropio_id
 *
 * The followings are the available model relations:
 * @property Equipoarrendado $equipoarrendado
 * @property Equipopropio $equipopropio
 * @property Faena $faena
 */
class UnidadfaenaEquipo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unidadfaena_equipo';
	}

	public $tipo_equipo;

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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('unidad, pu, faena_id', 'required'),
			array('unidad, faena_id, equipoarrendado_id, equipopropio_id', 'numerical', 'integerOnly'=>true),
			array('pu', 'length', 'max'=>12),
			array('pu','esDecimal'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, unidad, pu, faena_id, equipoarrendado_id, equipopropio_id,observaciones', 'safe', 'on'=>'search'),
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
			'equipoarrendado' => array(self::BELONGS_TO, 'Equipoarrendado', 'equipoarrendado_id'),
			'equipopropio' => array(self::BELONGS_TO, 'Equipopropio', 'equipopropio_id'),
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
			'faena_id' => 'Faena',
			'equipoarrendado_id' => 'Equipoarrendado',
			'equipopropio_id' => 'Equipopropio',
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
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('equipoarrendado_id',$this->equipoarrendado_id);
		$criteria->compare('equipopropio_id',$this->equipopropio_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnidadfaenaEquipo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
