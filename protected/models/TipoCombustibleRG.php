<?php

/**
 * This is the model class for table "origen".
 *
 * The followings are the available columns in table 'vehiculo_rindegasto':
 * @property integer $id
 * @property integer $tipoCombustible_id
 * @property string $tipocombustible
 *
 * The followings are the available model relations:
 */
class TipoCombustibleRG extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Origen the static model class
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
		return 'tipocombustible_rindegasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipocombustible', 'required'),
			array('tipocombustible','ext.MyValidators.NoBlanco'),
			array('tipoCombustible_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tipocombustible', 'safe', 'on'=>'search'),
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
			'tc' => array(self::BELONGS_TO, 'TipoCombustible', 'tipoCombustible_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tipocombustible' => 'Tipo de Combustible Rindegastos',
			'tipoCombustible_id' => 'Tipo de Combustible SAM',
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
		$criteria->compare('tipocombustible',$this->tipocombustible,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listarNoVinculados(){
		$novinculados = [];
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT category_code';
		$criteria->condition = "not exists (select * from tipocombustible_rindegasto where tipocombustible = t.category_code) and category_code != ''";
		$tipos = Gasto::model()->findAll($criteria);
		foreach($tipos as $tipo){
			$novinculados[] = ['tipocombustible'=>$tipo['category_code']];
		}
		asort($novinculados);
		return $novinculados;
	}

	public function listar(){
		$novinculados = [];
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT category_code';
		$tipos = Gasto::model()->findAll($criteria);
		foreach($tipos as $tipo){
			$novinculados[] = ['tipocombustible'=>$tipo['category_code']];
		}
		asort($novinculados);
		return $novinculados;
	}

}