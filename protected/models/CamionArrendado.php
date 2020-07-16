<?php

/**
 * This is the model class for table "camionArrendado".
 *
 * The followings are the available columns in table 'camionArrendado':
 * @property integer $id
 * @property string $nombre
 * @property string $capacidad
 * @property string $pesoOVolumen
 */
class CamionArrendado extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CamionArrendado the static model class
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
		return 'camionArrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('nombre,consumoPromedio, capacidad, pesoOVolumen', 'required'),
		array('nombre', 'length', 'max'=>100),
		array('horasMin,capacidad,consumoPromedio,produccionMinima,coeficienteDeTrato', 'length', 'max'=>10),
		array('pesoOVolumen', 'length', 'max'=>1),
		array('capacidad,consumoPromedio','esDecimal'),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, nombre, capacidad, pesoOVolumen,consumoPromedio,vigente', 'safe', 'on'=>'search'),
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

	protected function gridDataColumn($data,$row)
    {
     	if($data->pesoOVolumen == "P")
    		return "Kgs";
    	if($data->pesoOVolumen == "V")
    		return "M3";   
    	if($data->pesoOVolumen == "L")
    		return "Lt"; 
	}  
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'registros'=>array(self::HAS_MANY, 'RegCamionArrendado', 'camionArrendado_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
			'capacidad' => 'Capacidad',
			'pesoOVolumen' => 'Capacidad medida en',
			'consumoPromedio' => 'Consumo Promedio [Km/Lts]',
			'produccionMinima' => 'Producción Mínima Diaria [$]',
			'horasMin' => 'Horas Mínimas Pactadas',	
			'coeficienteDeTrato' => 'Coeficiente de Trato (% sobre consumo de combustible)',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('capacidad',$this->capacidad,true);
		$criteria->compare('consumoPromedio',$this->consumoPromedio,true);
		$criteria->compare('pesoOVolumen',$this->pesoOVolumen,true);
		$criteria->compare('vigente',$this->vigente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre
			from		camionArrendado
			where		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un camión, camioneta, auto",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=$row;
			$i++;
		}
		return $data;
	}
}