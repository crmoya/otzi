<?php

/**
 * This is the model class for table "expediciones".
 *
 * The followings are the available columns in table 'expediciones':
 * @property string $id
 * @property integer $nVueltas
 * @property string $totalTransportado
 * @property string $total
 * @property string $kmRecorridos
 * @property string $fecha
 * @property string $vehiculo
 * @property string $chofer
 * @property string $propio_arrendado
 */
class Expediciones extends CActiveRecord
{
	public $agrupar_por;
	public $propio_arrendado;
	public $fecha_inicio;
	public $fecha_fin;
	public $chofer;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Expediciones the static model class
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
		return 'expediciones';
	}

	
	public function primaryKey()
	{
		return 'id';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nVueltas, totalTransportado, total, fecha, vehiculo, chofer', 'required'),
			array('nVueltas', 'numerical', 'integerOnly'=>true),
			array('id, totalTransportado, total, kmRecorridos', 'length', 'max'=>12),
			array('vehiculo', 'length', 'max'=>100),
			array('chofer', 'length', 'max'=>200),
			array('propio_arrendado', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, origen_destino,nVueltas, totalTransportado, total, kmRecorridos, fecha, vehiculo, chofer, propio_arrendado', 'safe', 'on'=>'search'),
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
			'nVueltas' => 'N° Vueltas',
			'totalTransportado' => 'Total Transportado',
			'total' => 'Total',
			'kmRecorridos' => 'Km Recorridos',
			'fecha' => 'Fecha',
			'vehiculo' => 'Camión Camioneta o Auto',
			'chofer' => 'Chofer',
			'propio_arrendado' => 'Propio o Arrendado',
			'pu'=>'Valor del Flete',
			'origen_destino_nombre'=>'Origen Destino',
		);
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate expediciones;
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
		insert into expediciones
			(fecha,vehiculo,chofer,nVueltas,totalTransportado,total,kmRecorridos,pu,origen_destino_nombre,origen_destino,faena,faena_id,propio_arrendado)
		";	

		$campos = "fecha,vehiculo,chofer,nVueltas,totalTransportado,total,kmRecorridos,pu,origen_destino_nombre,origen_destino,faena,faena_id,propio_arrendado";
		$group = "";
		if($this->agrupar_por == 'fecha')
		{
			$campos = '" " as id,fecha," " as vehiculo," " as chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu) as pu," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by fecha';
		}
		if($this->agrupar_por == 'vehiculo')
		{
			$campos = '" " as fecha,vehiculo," " as chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu)," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by vehiculo';
		}
		if($this->agrupar_por == 'chofer')
		{
			$campos = '" " as fecha," " as vehiculo,chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu)," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by chofer';
		}
		if($this->agrupar_por == 'fecha_vehiculo')
		{
			$campos = 'fecha,vehiculo," " as chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu)," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by fecha,vehiculo';
		}
		if($this->agrupar_por == 'fecha_chofer')
		{
			$campos = 'fecha," " as vehiculo,chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu)," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by fecha,chofer';
		}
		if($this->agrupar_por == 'vehiculo_chofer')
		{
			$campos = '" " as fecha,vehiculo,chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu)," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by vehiculo,chofer';
		}
		if($this->agrupar_por == 'fecha_vehiculo_chofer')
		{
			$campos = 'fecha,vehiculo,chofer,sum(nVueltas) as nVueltas,sum(totalTransportado) as totalTransportado,sum(total) as total,sum(kmRecorridos) as kmRecorridos,sum(pu)," " as origen_destino_nombre," " as origen_destino," " as faena," " as faena_id," " as propio_arrendado';
			$group = 'group by fecha,vehiculo,chofer';
		}
		
		$condiciones = "";
		if(!empty($this->propio_arrendado))
        {
	        $condiciones = $condiciones." propio_arrendado = '".$this->propio_arrendado."' and ";
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
		
		if(!empty($this->chofer))
        {
        	$ch = Chofer::model()->findByPk($this->chofer);
        	if($this->chofer != 'Seleccione un chofer'){
        		$condiciones = $condiciones." chofer = '".$this->chofer."' and ";
        	}
        }
		if(!empty($this->faena_id) && $this->faena_id != '-1')
        {
        	$condiciones = $condiciones." faena_id = '".$this->faena_id."' and ";
        }
		if(!empty($this->origen_destino) && $this->origen_destino != '-1')
        {
        	$condiciones = $condiciones." origen_destino = '".$this->origen_destino."' and ";
        }
        
        $condiciones = $condiciones." 1=1 ";
        
		$sql = "select 	".$campos.
			"	from 	vExpediciones
				where	$condiciones
				$group";
		
		$command=$connection->createCommand($insertSql.$sql);
		$command->execute();
		
		$connection->active=false;
		$command = null;
		
		
		
		
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{		

		$criteria=new CDbCriteria;
	        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fecha);   
	}
	protected function propioArrendado($data,$row)
    {
     	if($data->propio_arrendado == 'P') return 'PROPIO';
     	else if($data->propio_arrendado == 'A') return 'ARRENDADO';
     	else return " ";   
	}
}