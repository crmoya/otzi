<?php

/**
 * This is the model class for table "equipoPropio".
 *
 * The followings are the available columns in table 'equipoPropio':
 * @property integer $id
 * @property string $nombre
 * @property string $codigo
 */
class EquipoPropio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EquipoPropio the static model class
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
		return 'equipoPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, codigo,precioUnitario,valorHora,consumoEsperado', 'required'),
			array('nombre', 'length', 'max'=>100),
			array('codigo', 'length', 'max'=>45),
			array('consumoEsperado,coeficienteDeTrato', 'length', 'max'=>10),
			array('precioUnitario,horasMin', 'length', 'max'=>9),
			array('precioUnitario,valorHora', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre, codigo,precioUnitario,horasMin,valorHora,vigente', 'safe', 'on'=>'search'),
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
			'nombre' => 'Nombre',
			'codigo' => 'Codigo',
			'horasMin' => 'Horas Mínimas Diarias',
			'precioUnitario' => 'Precio Unitario',
			'valorHora'=>'Valor Unitario Trato Operador',
			'consumoEsperado'=>'Consumo Esperado',
			'coeficienteDeTrato'=>'Coeficiente de Castigo (% sobre consumo de combustible)'
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
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('horasMin',$this->horasMin,true);
		$criteria->compare('valorHora',$this->valorHora,true);
		$criteria->compare('precioUnitario',$this->precioUnitario,true);
		$criteria->compare('consumoEsperado',$this->consumoEsperado,true);
		$criteria->compare('coeficienteDeTrato',$this->coeficienteDeTrato,true);
		$criteria->compare('vigente',$this->vigente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
	public function list($selected_id){
		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre,codigo
			from		equipoPropio
			where		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un equipo propio",'id'=>'');
		$i=1;
		$selected_exists = false;
		foreach($rows as $row){
			$data[$i]=array('id'=>$row['id'],'nombre'=>$row['codigo']." / ".$row['nombre']);
			$i++;
			if($row['id'] == $selected_id){
				$selected_exists = true;
			}
		}
		if(!$selected_exists && (int)$selected_id > 0){
			$equipo = EquipoPropio::model()->findByPk($selected_id);
			$data[] = ['id'=>$selected_id,'nombre'=>$equipo->codigo . " / " . $equipo->nombre . " (NO VIGENTE)"];
		}
		return $data;
	}
	
	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre
			from		equipoPropio
			where		vigente = 'SÍ'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un equipo",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=$row;
			$i++;
		}
		return $data;
	}
}