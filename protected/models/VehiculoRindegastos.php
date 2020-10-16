<?php

/**
 * This is the model class for table "origen".
 *
 * The followings are the available columns in table 'vehiculo_rindegasto':
 * @property integer $id
 * @property integer $camionpropio_id
 * @property integer $camionarrendado_id
 * @property integer $equipopropio_id
 * @property integer $equipoarrendado_id
 *
 * The followings are the available model relations:
 */
class VehiculoRindegastos extends CActiveRecord
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
		return 'vehiculo_rindegasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vehiculo', 'required'),
			array('vehiculo','ext.MyValidators.NoBlanco'),
			array('id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, vehiculo', 'safe', 'on'=>'search'),
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
			'camionPropio' => array(self::BELONGS_TO, 'CamionPropio', 'camionpropio_id'),
			'camionArrendado' => array(self::BELONGS_TO, 'CamionArrendado', 'camionarrendado_id'),
			'equipoPropio' => array(self::BELONGS_TO, 'EquipoPropio', 'equipopropio_id'),
			'equipoArrendado' => array(self::BELONGS_TO, 'EquipoArrendado', 'equipoarrendado_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'vehiculo' => 'Vehículo Rindegastos',
			'vehiculosam' => 'Camion / Equipo - Propio / Arrendado'
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
		$criteria->compare('vehiculo',$this->vehiculo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listarNoVinculados(){
		$novinculados = [];
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT vehiculo_equipo';
		$criteria->condition = "not exists (select * from vehiculo_rindegasto where vehiculo = t.vehiculo_equipo) and vehiculo_equipo != ''";
		$vehiculos = GastoCompleta::model()->findAll($criteria);
		foreach($vehiculos as $vehiculo){
			$novinculados[] = ['vehiculo'=>$vehiculo['vehiculo_equipo']];
		}
		asort($novinculados);
		return $novinculados;
	}

	public function listarSam(){
		$novinculados = [];
		$criteria = new CDbCriteria;
		$criteria->select = 'id,nombre';
		//$criteria->condition = "not exists (select * from vehiculo_rindegasto where camionpropio_id = t.id)";
		$camionesPropios = CamionPropio::model()->findAll($criteria);
		foreach($camionesPropios as $camionPropio){
			$novinculados[] = ['id'=>$camionPropio['id']."-cp",'nombre'=>$camionPropio['nombre']];
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'id,nombre';
		//$criteria->condition = "not exists (select * from vehiculo_rindegasto where camionarrendado_id = t.id)";
		$camionesArrendados = CamionArrendado::model()->findAll($criteria);
		foreach($camionesArrendados as $camionArrendado){
			$novinculados[] = ['id'=>$camionArrendado['id']."-ca",'nombre'=>$camionArrendado['nombre']];
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'id,nombre';
		//$criteria->condition = "not exists (select * from vehiculo_rindegasto where equipopropio_id = t.id)";
		$equiposPropios = EquipoPropio::model()->findAll($criteria);
		foreach($equiposPropios as $equipoPropio){
			$novinculados[] = ['id'=>$equipoPropio['id']."-ep",'nombre'=>$equipoPropio['nombre']];
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'id,nombre';
		//$criteria->condition = "not exists (select * from vehiculo_rindegasto where equipoarrendado_id = t.id)";
		$equiposArrendados = EquipoArrendado::model()->findAll($criteria);
		foreach($equiposArrendados as $equipoArrendado){
			$novinculados[] = ['id'=>$equipoArrendado['id']."-ea",'nombre'=>$equipoArrendado['nombre']];
		}
		$novinculados = $this->bubblesort($novinculados);
		return $novinculados;
	}

	public function bubblesort($array){
		for($i = 0; $i < count($array); $i++){
			for($j = 0; $j < count($array) - 1 - $i; $j++){
				if($array[$j]['nombre'] > $array[$j+1]['nombre']){
					$temp = $array[$j];
					$array[$j] = $array[$j+1];
					$array[$j+1] = $temp;
				}
			}
		}
		return $array;
	}

	public static function autoVincular(){
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT vehiculo_equipo';
		$vehiculos = GastoCompleta::model()->findAll($criteria);
		foreach($vehiculos as $vRow){
			$vehiculo = trim($vRow['vehiculo_equipo']);
			$camionPropio = CamionPropio::model()->findByAttributes(['nombre'=>$vehiculo]);
			if(isset($camionPropio)){
				$vehiculoRindegastos = new VehiculoRindegastos();
				$vehiculoRindegastos->vehiculo = $vehiculo;
				$vehiculoRindegastos->camionpropio_id = $camionPropio->id;
				$vehiculoRindegastos->save();
				continue;
			}
			$camionArrendado = CamionArrendado::model()->findByAttributes(['nombre'=>$vehiculo]);
			if(isset($camionArrendado)){
				$vehiculoRindegastos = new VehiculoRindegastos();
				$vehiculoRindegastos->vehiculo = $vehiculo;
				$vehiculoRindegastos->camionarrendado_id = $camionArrendado->id;
				$vehiculoRindegastos->save();
				continue;
			}
			$equipoPropio = EquipoPropio::model()->findByAttributes(['nombre'=>$vehiculo]);
			if(isset($equipoPropio)){
				$vehiculoRindegastos = new VehiculoRindegastos();
				$vehiculoRindegastos->vehiculo = $vehiculo;
				$vehiculoRindegastos->equipopropio_id = $equipoPropio->id;
				$vehiculoRindegastos->save();
				continue;
			}
			$equipoArrendado = EquipoArrendado::model()->findByAttributes(['nombre'=>$vehiculo]);
			if(isset($equipoArrendado)){
				$vehiculoRindegastos = new VehiculoRindegastos();
				$vehiculoRindegastos->vehiculo = $vehiculo;
				$vehiculoRindegastos->equipoarrendado_id = $equipoArrendado->id;
				$vehiculoRindegastos->save();
				continue;
			}
		}

	}
}