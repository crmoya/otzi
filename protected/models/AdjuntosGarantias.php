<?php

/**
 * This is the model class for table "adjuntos_garantias".
 *
 * The followings are the available columns in table 'adjuntos_garantias':
 * @property integer $id
 * @property string $nombre_archivo
 * @property string $fecha
 * @property integer $subidor_id
 * @property integer $garantias_id
 *
 * The followings are the available model relations:
 * @property Usuarios $subidor
 * @property Garantias $garantias
 */
class AdjuntosGarantias extends CActiveRecord
{
	public $file;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AdjuntosGarantias the static model class
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
		return 'adjuntos_garantias';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre_archivo, fecha, subidor_id, garantias_id', 'required'),
			array('subidor_id, garantias_id', 'numerical', 'integerOnly'=>true),
			array('nombre_archivo', 'length', 'max'=>200),
			array('file', 'file', 'types'=>Tools::getTiposArchivos()),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre_archivo, fecha, subidor_id, garantias_id', 'safe', 'on'=>'search'),
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
			'subidor' => array(self::BELONGS_TO, 'Usuarios', 'subidor_id'),
			'garantias' => array(self::BELONGS_TO, 'Garantias', 'garantias_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre_archivo' => 'Nombre Archivo',
			'fecha' => 'Fecha  Adjunto',
			'subidor_id' => 'Subidor',
			'garantias_id' => 'Garantias',
			'garantias'=>'N° Garantía',
		);
	}
	
	protected function gridDataColumn($data,$row)
	{
		return Tools::backFecha($data->fecha);
	}
	
	public function searchGarantia($contrato_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
		$contrato = Contratos::model()->findByPk($contrato_id);
		$g_contrato = $contrato->getGarantias();
		$garantias = array();
		$i =0;
		foreach($g_contrato as $garantia){
			$garantias[$i]=$garantia->id;
			$i++;
		}
		$criteria->addInCondition('garantias_id',$garantias);
	
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
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
		$criteria->compare('nombre_archivo',$this->nombre_archivo,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('subidor_id',$this->subidor_id);
		$criteria->compare('garantias_id',$this->garantias_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}