<?php

/**
 * This is the model class for table "informecombustible".
 *
 * The followings are the available columns in table 'informecombustible':
 * @property string $petroleoLts
 * @property string $carguio
 * @property string $guia
 * @property string $factura
 * @property integer $precioUnitario
 * @property string $valorTotal
 * @property string $faena
 * @property string $tipoCombustible
 * @property string $supervisorCombustible
 * @property string $numero
 * @property string $nombre
 * @property string $fechaRendicion
 * @property string $camion
 * @property integer $faena_id
 * @property integer $tipoCombustible_id
 * @property integer $supervisorCombustible_id
 * @property integer $camion_id
 * @property string $tipo
 */
class Informecombustible extends CActiveRecord
{
	
	public $agrupar_por;
	public $propio_arrendado;
	public $fecha_inicio;
	public $fecha_fin;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Informecombustible the static model class
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
		return 'informecombustible';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('precioUnitario, faena_id, tipoCombustible_id, supervisorCombustible_id, camion_id', 'numerical', 'integerOnly'=>true),
			array('petroleoLts, carguio, valorTotal', 'length', 'max'=>12),
			array('guia, factura, tipoCombustible', 'length', 'max'=>45),
			array('faena', 'length', 'max'=>200),
			array('supervisorCombustible', 'length', 'max'=>63),
			array('numero, fechaRendicion', 'length', 'max'=>20),
			array('nombre', 'length', 'max'=>100),
			array('camion', 'length', 'max'=>148),
			array('tipo', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('petroleoLts, carguio, guia, factura, precioUnitario, valorTotal, faena, tipoCombustible, supervisorCombustible, numero, nombre, fechaRendicion, camion, faena_id, tipoCombustible_id, supervisorCombustible_id, camion_id, tipo', 'safe', 'on'=>'search'),
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
			'petroleoLts' => 'Combustible (Lts)',
			'carguio' => 'Km. Carguío',
			'guia' => 'Guia',
			'factura' => 'Factura',
			'precioUnitario' => 'Precio Unitario',
			'valorTotal' => 'Valor Total',
			'faena' => 'Faena',
			'tipoCombustible' => 'Tipo Combustible',
			'supervisorCombustible' => 'Supervisor Combustible',
			'numero' => 'N° Rendición',
			'nombre' => 'Nombre quien Rinde',
			'fechaRendicion' => 'Fecha Rendición',
			'camion' => 'Máquina o Vehículo',
			'faena_id' => 'Faena',
			'tipoCombustible_id' => 'Tipo Combustible',
			'supervisorCombustible_id' => 'Supervisor Combustible',
			'camion_id' => 'Camion',
			'tipo' => 'Tipo',
			'reporte'=>'N°Report',
			'fecha'=>'Fecha Report',
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

		$criteria->compare('petroleoLts',$this->petroleoLts,true);
		$criteria->compare('carguio',$this->carguio,true);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('precioUnitario',$this->precioUnitario);
		$criteria->compare('valorTotal',$this->valorTotal,true);
		$criteria->compare('faena',$this->faena,true);
		$criteria->compare('tipoCombustible',$this->tipoCombustible,true);
		$criteria->compare('supervisorCombustible',$this->supervisorCombustible,true);
		$criteria->compare('numero',$this->numero,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fechaRendicion',$this->fechaRendicion,true);
		$criteria->compare('camion',$this->camion,true);
		$criteria->compare('faena_id',$this->faena_id);
		$criteria->compare('tipoCombustible_id',$this->tipoCombustible_id);
		$criteria->compare('supervisorCombustible_id',$this->supervisorCombustible_id);
		$criteria->compare('camion_id',$this->camion_id);
		$criteria->compare('tipo',$this->tipo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informecombustible;
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
		insert into informecombustible
			(tipo_documento,observaciones,rut_proveedor,nombre_proveedor,petroleoLts,carguio,valorTotal,faena,tipoCombustible,supervisorCombustible,numero,nombre,fechaRendicion,camion,faena_id,tipoCombustible_id,camion_id,tipo,guia,factura,precioUnitario,supervisorCombustible_id,reporte,fecha)
		";	

		
		$campos = "tipo_documento,observaciones,rut_proveedor,nombre_proveedor,petroleoLts,carguio,valorTotal,faena,tipoCombustible,supervisorCombustible,numero,nombre,fechaRendicion,camion,faena_id,tipoCombustible_id,camion_id,tipo,guia,factura,precioUnitario,supervisorCombustible_id,reporte,fecha";
		$group = "";
		if($this->agrupar_por == 'fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,sum(petroleoLts) as petroleoLts,"" as carguio,sum(valorTotal) as valorTotal,"" as faena,"" as tipoCombustible,"" as supervisorCombustible,"" as numero,"" as nombre,fechaRendicion,"" as camion,"" as faena_id,"" as tipoCombustible_id,"" as camion_id,"" as tipo,"" as guia,"" as factura,"" as precioUnitario,"" as supervisorCombustible_id,"" as reporte,"" as fecha';
			$group = 'group by fechaRendicion';
		}
		if($this->agrupar_por == 'numero')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,sum(petroleoLts) as petroleoLts,"" as carguio,sum(valorTotal) as valorTotal,"" as faena,"" as tipoCombustible,"" as supervisorCombustible,numero,"" as nombre,"" as fechaRendicion,"" as camion,"" as faena_id,"" as tipoCombustible_id,"" as camion_id,"" as tipo,"" as guia,"" as factura,"" as precioUnitario,"" as supervisorCombustible_id,"" as reporte,"" as fecha';
			$group = 'group by numero';
		}
		if($this->agrupar_por == 'nombre')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,sum(petroleoLts) as petroleoLts,"" as carguio,sum(valorTotal) as valorTotal,"" as faena,"" as tipoCombustible,"" as supervisorCombustible,"" as numero,nombre,"" as fechaRendicion,"" as camion,"" as faena_id,"" as tipoCombustible_id,"" as camion_id,"" as tipo,"" as guia,"" as factura,"" as precioUnitario,"" as supervisorCombustible_id,"" as reporte,"" as fecha';
			$group = 'group by nombre';
		}
		if($this->agrupar_por == 'nombre_fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,sum(petroleoLts) as petroleoLts,"" as carguio,sum(valorTotal) as valorTotal,"" as faena,"" as tipoCombustible,"" as supervisorCombustible,"" as numero,nombre,fechaRendicion,"" as camion,"" as faena_id,"" as tipoCombustible_id,"" as camion_id,"" as tipo,"" as guia,"" as factura,"" as precioUnitario,"" as supervisorCombustible_id,"" as reporte,"" as fecha';
			$group = 'group by nombre,fechaRendicion';
		}
		if($this->agrupar_por == 'numero_fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,sum(petroleoLts) as petroleoLts,"" as carguio,sum(valorTotal) as valorTotal,"" as faena,"" as tipoCombustible,"" as supervisorCombustible,numero,"" as nombre,fechaRendicion,"" as camion,"" as faena_id,"" as tipoCombustible_id,"" as camion_id,"" as tipo,"" as guia,"" as factura,"" as precioUnitario,"" as supervisorCombustible_id,"" as reporte,"" as fecha';
			$group = 'group by numero,fechaRendicion';
		}
		if($this->agrupar_por == 'nombre_numero_fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,sum(petroleoLts) as petroleoLts,"" as carguio,sum(valorTotal) as valorTotal,"" as faena,"" as tipoCombustible,"" as supervisorCombustible,numero,nombre,fechaRendicion,"" as camion,"" as faena_id,"" as tipoCombustible_id,"" as camion_id,"" as tipo,"" as guia,"" as factura,"" as precioUnitario,"" as supervisorCombustible_id,"" as reporte,"" as fecha';
			$group = 'group by nombre,numero,fechaRendicion';
		}
		
		$condiciones = "";
		if($this->propio_arrendado == 'P'){
        	$condiciones = $condiciones." (tipo = 'CP' or tipo = 'EP') and ";
        }
        if($this->propio_arrendado == 'A'){
        	$condiciones = $condiciones." (tipo = 'CA' or tipo = 'EA') and ";
        }
        
        if(!empty($this->fecha_inicio) && empty($this->fecha_fin))
        {
        	$condiciones = $condiciones." fechaRendicion >= '".Tools::fixFecha($this->fecha_inicio)."' and ";
        }
        elseif(!empty($this->fecha_fin) && empty($this->fecha_inicio))
        {
            $condiciones = $condiciones." fechaRendicion <= '".Tools::fixFecha($this->fecha_fin)."' and ";
        }
        elseif(!empty($this->fecha_fin) && !empty($this->fecha_inicio))
        {
            $condiciones = $condiciones." fechaRendicion  >= '".Tools::fixFecha($this->fecha_inicio)."' and fechaRendicion <= '".Tools::fixFecha($this->fecha_fin)."' and ";
        }
        
        if(!empty($this->numero))
        {
        	$condiciones = $condiciones." numero ='".$this->numero."' and ";
        }
       	if(!empty($this->nombre))
        {
        	$condiciones = $condiciones." nombre like '%".$this->nombre."%' and ";
        }
        
        
        $condiciones = $condiciones." 1=1 ";
        
		$sql = "select 	".$campos.
			"	from 	vinformecombustible
				where	$condiciones
				$group";
		
                
		$command=$connection->createCommand($insertSql.$sql);
		$command->execute();
		
		$connection->active=false;
		$command = null;
		
		
		
		
	}
	
	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fechaRendicion);   
	}
	protected function propioArrendado($data,$row)
    {
     	if($data->propio_arrendado == 'P') return 'PROPIO';
     	else if($data->propio_arrendado == 'A') return 'ARRENDADO';
     	else return "";   
	}
}