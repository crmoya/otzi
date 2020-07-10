<?php

/**
 * This is the model class for table "adjuntos_resoluciones".
 *
 * The followings are the available columns in table 'adjuntos_resoluciones':
 * @property integer $id
 * @property string $nombre_archivo
 * @property integer $subidor_id
 * @property integer $resoluciones_id
 * @property string $fecha
 *
 * The followings are the available model relations:
 * @property Usuarios $subidor
 * @property Resoluciones $resoluciones
 */
class AdjuntosResoluciones extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AdjuntosResoluciones the static model class
	 */
	
	public $file;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'adjuntos_resoluciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre_archivo, subidor_id, resoluciones_id, fecha', 'required'),
			array('subidor_id, resoluciones_id', 'numerical', 'integerOnly'=>true),
			array('nombre_archivo', 'length', 'max'=>200),
			array('file', 'file', 'types'=>Tools::getTiposArchivos()),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre_archivo, subidor_id, resoluciones_id, fecha', 'safe', 'on'=>'search'),
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
			'nombre_archivo' => 'Nombre Archivo',
			'subidor_id' => 'Subidor',
			'resoluciones_id' => 'Resoluciones',
			'fecha' => 'Fecha Adjunto',
			'subidor'=>'Usuario que adjuntó',
			'resoluciones'=>'N° Resolución',
		);
	}

	protected function gridDataColumn($data,$row)
	{
		return Tools::backFecha($data->fecha);
	}
	
	public function searchResolucion($contrato_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
		$contrato = Contratos::model()->findByPk($contrato_id);
		$res_contrato = $contrato->getResoluciones();
		$resoluciones = array();
		$i =0;
		foreach($res_contrato as $resolucion){
			$resoluciones[$i]=$resolucion->id;
			$i++;
		}
		$criteria->addInCondition('resoluciones_id',$resoluciones);
		
	
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
		$criteria->compare('subidor_id',$this->subidor_id);
		$criteria->compare('resoluciones_id',$this->resoluciones_id);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}