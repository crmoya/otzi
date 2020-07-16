<?php

/**
 * This is the model class for table "observaciones".
 *
 * The followings are the available columns in table 'observaciones':
 * @property integer $id
 * @property integer $maquina_id
 * @property string $propio_arrendado
 * @property string $maquina_camion
 * @property string $obra_maquina
 * @property string $observaciones
 * @property string $fecha
 * @property integer $chofer_id
 * @property integer $faena_id
 * @property string $maquina
 * @property string $chofer
 * @property string $faena
 */
class Observaciones extends CActiveRecord
{
	public $fecha_inicio;
	public $fecha_fin;
        public $obra_maquina;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Observaciones the static model class
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
		return 'observaciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, maquina_id, propio_arrendado, maquina_camion, fecha, chofer_id, faena_id, maquina, chofer, faena', 'required'),
			array('id, maquina_id, chofer_id, faena_id', 'numerical', 'integerOnly'=>true),
			array('propio_arrendado, maquina_camion', 'length', 'max'=>1),
			array('maquina', 'length', 'max'=>100),
			array('chofer, faena', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, maquina_id, propio_arrendado, obra_maquina,maquina_camion, observaciones,observaciones_obra, fecha, chofer_id, faena_id, maquina, chofer, faena', 'safe', 'on'=>'search'),
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
			'maquina_id' => 'Id Maquina',
			'propio_arrendado' => 'Propios o Arrendados',
			'maquina_camion' => 'Máquinas o Vehículos',
			'observaciones' => 'Observaciones Máquina',
			'fecha' => 'Fecha',
			'chofer_id' => 'Chofer u Operador',
			'faena_id' => 'Faena',
			'maquina' => 'Máquina o Vehículo',
			'chofer' => 'Chofer u Operador',
			'faena' => 'Centro de Gestión',
                        'obra_maquina' => 'Mostrar de Obra y/o Máquina'
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
		$criteria->compare('maquina_id',$this->maquina_id);
		$criteria->compare('propio_arrendado',$this->propio_arrendado,true);
		$criteria->compare('maquina_camion',$this->maquina_camion,true);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('chofer_id',$this->chofer_id);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('maquina',$this->maquina,true);
		$criteria->compare('chofer',$this->chofer,true);
		$criteria->compare('faena',$this->faena,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listarMaquinas(){
		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select 		id,nombre
			from	(
				select		concat(id,'MP') as id,nombre
				from		equipoPropio
				where		vigente = 'SÍ'
				
				union
				
				select		concat(id,'MA') as id,nombre
				from		equipoArrendado
				where		vigente = 'SÍ'
				
				union
				
				select		concat(id,'CP') as id,nombre
				from		camionPropio
				where		vigente = 'SÍ'
				
				union
				
				select		concat(id,'CA') as id,nombre
				from		camionArrendado
				where		vigente = 'SÍ'
				
			) as t
			order by nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un Vehículo o Máquina",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=$row;
			$i++;
		}
		return $data;
	}
	
	public function listarChoferes(){
		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select 		id,nombre
			from	(
				select		concat(id,'O') as id,concat(nombre,', ',rut) as nombre
				from		operador
				where		vigente = 'SÍ'
				
				union
				
				select		concat(id,'CH') as id,concat(nombre,', ',rut) as nombre
				from		chofer
				where		vigente = 'SÍ'
				
			) as t
			order by nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un Chofer u Operador",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=$row;
			$i++;
		}
		return $data;
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate observaciones;
			"
		);
		$command->execute();
		$connection->active=false;
		$command = null;
	}
	
	public function generarInforme(){
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
				
		$insertSql = "
		insert into observaciones
			(maquina_id,propio_arrendado,maquina_camion,observaciones,observaciones_obra,fecha,chofer_id,faena_id,maquina,chofer,faena)
		";	

				
		$condiciones = "";
		if(!empty($this->propio_arrendado))
        {
	        $condiciones = $condiciones." propio_arrendado = '".$this->propio_arrendado."' and ";
        }
        
        /*AGREGAR LA CONDICIÓN DE OBRA O MÁQUINA*/
                if(!empty($this->obra_maquina))
                {
                    if($this->obra_maquina == 'M'){
                        $condiciones = $condiciones." observaciones <> '' and ";
                    }
                    if($this->obra_maquina == 'O'){
                        $condiciones = $condiciones." observaciones_obra <> '' and ";
                    }

                }
        /*END AGREGAR LA CONDICIÓN DE OBRA O MÁQUINA*/
		if(!empty($this->maquina_camion))
        {
        	$condiciones = $condiciones." maquina_camion = '".$this->maquina_camion."' and ";
        }
        
		if(!empty($this->fecha_inicio) && empty($this->fecha_fin))
        {
        	$condiciones = $condiciones." fecha >= '".Tools::fixFecha($this->fecha_inicio)."' and ";
        }
        elseif(!empty($this->fecha_fin) && empty($this->fecha_inicio))
        {
            $condiciones = $condiciones." fecha <= '".Tools::fixFecha($this->fecha_fin)."' and ";
        }
        elseif(!empty($this->fecha_fin) && !empty($this->fecha_inicio))
        {
            $condiciones = $condiciones." fecha  >= '".Tools::fixFecha($this->fecha_inicio)."' and fecha <= '".Tools::fixFecha($this->fecha_fin)."' and ";
        }
		
		if(!empty($this->chofer_id))
        {
        	$condiciones = $condiciones." chofer_id = '".$this->chofer_id."' and ";
        }
		if(!empty($this->maquina_id))
        {
        	$condiciones = $condiciones." maquina_id = '".$this->maquina_id."' and ";
        }
		if(!empty($this->faena_id) && $this->faena_id != '-1')
        {
        	$condiciones = $condiciones." faena_id = '".$this->faena_id."' and ";
        }
	        
        $condiciones = $condiciones." 1=1 ";
        
		$sql = "select 	maquina_id,propio_arrendado,maquina_camion,observaciones,observaciones_obra,fecha,chofer_id,faena_id,maquina,chofer,faena
				from 	vObservaciones
				where	$condiciones";
		
		$command=$connection->createCommand($insertSql.$sql);
		$command->execute();
		
		$connection->active=false;
		$command = null;
		
		
	}
	
	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fecha);   
	}
}