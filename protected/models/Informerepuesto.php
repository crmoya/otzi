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
class Informerepuesto extends CActiveRecord
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
		return 'informerepuesto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('repuesto, faena', 'length', 'max'=>200),
            array('montoNeto, cantidad, faena_id', 'length', 'max'=>11),
            array('guia, factura', 'length', 'max'=>45),
            array('unidad', 'length', 'max'=>1),
            array('numero', 'length', 'max'=>20),
            array('nombre, camion', 'length', 'max'=>100),
            array('fechaRendicion', 'length', 'max'=>10),
            array('tipo', 'length', 'max'=>2),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, repuesto, montoNeto, guia, factura, cantidad, unidad, faena_id, faena, numero, nombre, fechaRendicion, camion, tipo', 'safe', 'on'=>'search'),
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
			'camion'=>'Máquina o Vehículo',
			'fechaRendicion'=>'Fecha Rendición',
			'numero'=>'N° Rendición',
			'nombre'=>'Nombre quien Rinde',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informerepuesto;
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
		insert into informerepuesto
			(tipo_documento,observaciones,rut_proveedor,nombre_proveedor,cuenta,repuesto,montoNeto,guia,factura,cantidad,unidad,faena_id,faena,numero,nombre,fechaRendicion,camion,reporte,fecha)
		";	

		
		$campos = "tipo_documento,observaciones,rut_proveedor,nombre_proveedor,cuenta,repuesto,montoNeto,guia,factura,cantidad,unidad,faena_id,faena,numero,nombre,fechaRendicion,camion,reporte,fecha";
		$group = "";
		if($this->agrupar_por == 'fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,"" as cuenta,"" as repuesto,sum(montoNeto) as montoNeto,"" as guia,"" as factura,"" as cantidad,"" as unidad,"" as faena_id,"" as faena,"" as numero,"" as nombre,fechaRendicion,"" as camion,"" as reporte,"" as fecha';
			$group = 'group by fechaRendicion';
		}
		if($this->agrupar_por == 'numero')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,"" as cuenta,"" as repuesto,sum(montoNeto) as montoNeto,"" as guia,"" as factura,"" as cantidad,"" as unidad,"" as faena_id,"" as faena,numero,"" as nombre,"" as fechaRendicion,"" as camion,"" as reporte,"" as fecha';
			$group = 'group by numero';
		}
		if($this->agrupar_por == 'nombre')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,"" as cuenta,"" as repuesto,sum(montoNeto) as montoNeto,"" as guia,"" as factura,"" as cantidad,"" as unidad,"" as faena_id,"" as faena,"" as numero,nombre,"" as fechaRendicion,"" as camion,"" as reporte,"" as fecha';
			$group = 'group by nombre';
		}
		if($this->agrupar_por == 'nombre_fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,"" as cuenta,"" as repuesto,sum(montoNeto) as montoNeto,"" as guia,"" as factura,"" as cantidad,"" as unidad,"" as faena_id,"" as faena,"" as numero,nombre,fechaRendicion,"" as camion,"" as reporte,"" as fecha';
			$group = 'group by nombre,fechaRendicion';
		}
		if($this->agrupar_por == 'numero_fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,"" as cuenta,"" as repuesto,sum(montoNeto) as montoNeto,"" as guia,"" as factura,"" as cantidad,"" as unidad,"" as faena_id,"" as faena,numero,"" as nombre,fechaRendicion,"" as camion,"" as reporte,"" as fecha';
			$group = 'group by numero,fechaRendicion';
		}
		if($this->agrupar_por == 'nombre_numero_fechaRendicion')
		{
			$campos = '"" as tipo_documento,"" as observaciones,"" as rut_proveedor,"" as nombre_proveedor,"" as cuenta,"" as repuesto,sum(montoNeto) as montoNeto,"" as guia,"" as factura,"" as cantidad,"" as unidad,"" as faena_id,"" as faena,numero,nombre,fechaRendicion,"" as camion,"" as reporte,"" as fecha';
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
			"	from 	vinformerepuestos
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
	protected function unidad($data,$row)
    {
     	if($data->unidad == 'U') return 'unidades';
     	else if($data->unidad == 'L') return 'litros';
     	else if($data->unidad == 'K') return 'kilos';
     	else return "";   
	}
}