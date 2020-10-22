<?php

/**
 * This is the model class for table "origen".
 *
 * The followings are the available columns in table 'vehiculo_rindegasto':
 * @property integer $id
 * @property integer $faena_id
 * @property integer $faena
 *
 * The followings are the available model relations:
 */
class FaenaRindegasto extends CActiveRecord
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
		return 'faena_rindegasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('faena', 'required'),
			array('faena','ext.MyValidators.NoBlanco'),
			array('id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, faena', 'safe', 'on'=>'search'),
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
			'cg' => array(self::BELONGS_TO, 'Faena', 'faena_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'faena' => 'Faena Rindegastos',
			'faena_id' => 'Faena SAM',
			'vehiculosam' => 'Centro de Gestión SAM',
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
		$criteria->compare('faena',$this->faena,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listarNoVinculados(){
		$novinculados = [];
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT centro_costo_faena';
		$criteria->condition = "not exists (select * from faena_rindegasto where faena = t.centro_costo_faena) and centro_costo_faena != ''";
		$faenas = GastoCompleta::model()->findAll($criteria);
		foreach($faenas as $faena){
			$novinculados[] = ['faena'=>$faena['centro_costo_faena']];
		}
		asort($novinculados);
		return $novinculados;
	}


	public static function autoVincular(){
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT centro_costo_faena';
		$faenas = GastoCompleta::model()->findAll($criteria);
		foreach($faenas as $vFaena){
			$faena = trim($vFaena['centro_costo_faena']);
			$faenaSAM = Faena::model()->findByAttributes(['nombre'=>$faena]);
			if(isset($faenaSAM)){
				$faenaRindeGasto = new FaenaRindegasto();
				$faenaRindeGasto->faena = $faena;
				$faenaRindeGasto->faena_id = $faenaSAM->id;
				$faenaRindeGasto->save();
				continue;
			}
		}

	}
}