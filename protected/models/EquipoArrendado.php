<?php

/**
 * This is the model class for table "equipoArrendado".
 *
 * The followings are the available columns in table 'equipoArrendado':
 * @property integer $id
 * @property string $nombre
 * @property integer $propietario_id
 */
class EquipoArrendado extends CActiveRecord
{
	
	public $propietario;
	/**
	 * Returns the static model of the specified AR class.
	 * @return EquipoArrendado the static model class
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
		return 'equipoArrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, propietario_id,valorHora', 'required'),
			array('propietario_id,valorHora', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>100),
			array('consumoEsperado', 'length', 'max'=>10),
			array('coeficienteDeTrato', 'length', 'max'=>10),
			array('precioUnitario,horasMin,valorHora', 'length', 'max'=>9),
			array('precioUnitario', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre, vigente,propietario,horasMin,precioUnitario,valorHora', 'safe', 'on'=>'search'),
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
			'registros'=>array(self::HAS_MANY, 'RegEquipoArrendado', 'equipoArrendado_id'),
			'propietarios'=>array(self::BELONGS_TO, 'Propietario', 'propietario_id'),
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
			'propietario_id' => 'Propietario',
			'horasMin' => 'Horas Mínimas Diarias',
			'precioUnitario' => 'Precio Unitario',
			'valorHora'=> 'Valor Unitario Trato Operador',
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
		$criteria->with=array('propietarios');	
		$criteria->compare('t.nombre',$this->nombre,true);
		$criteria->compare('t.id',$this->id);
		$criteria->compare('valorHora',$this->valorHora,true);
		$criteria->compare('horasMin',$this->horasMin,true);
		$criteria->compare('precioUnitario',$this->precioUnitario,true);
		$criteria->compare('consumoEsperado',$this->consumoEsperado,true);
		$criteria->compare('propietario_id',$this->propietario_id);
		$criteria->compare('propietarios.nombre', $this->propietario, true );
		$criteria->compare('coeficienteDeTrato',$this->coeficienteDeTrato,true);
		$criteria->compare('t.vigente',$this->vigente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
		        'attributes'=>array(
                            'nombre'=>array(
                                'asc' => 't.nombre',
                                'desc' => 't.nombre DESC',
                            ),
                            'id'=>array(
                                'asc' => 't.id',
                                'desc' => 't.id DESC',
                            ),
		            'propietario'=>array(
		                'asc'=>'propietarios.nombre',
		                'desc'=>'propietarios.nombre DESC',
		            ),
		            '*',
		        ),
		    ),
		));
	}
	
	public function getPropietario(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		p.nombre as nombre
			from		propietario as p,
						equipoArrendado as e
			where		p.id = e.propietario_id and
						e.id = $this->id
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['nombre'];
		}
	}
	
	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre
			from		equipoArrendado
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