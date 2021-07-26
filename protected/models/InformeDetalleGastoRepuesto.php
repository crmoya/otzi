<?php

/**
 * This is the model class for table "informeDetalleGastoRepuesto".
 *
 * The followings are the available columns in table 'informeDetalleGastoRepuesto':
 * @property integer $id
 * @property string $fecha
 * @property integer $reporte
 * @property string $operario
 * @property string $maquina
 * @property string $repuesto
 * @property integer $montoNeto
 * @property string $guia
 * @property string $factura
 * @property string $cantidad
 */
class InformeDetalleGastoRepuesto extends CActiveRecord
{

	public $fechaInicio;
	public $fechaFin;
	/**
	 * Returns the static model of the specified AR class.
	 * @return InformeDetalleGastoRepuesto the static model class
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
		return 'informeDetalleGastoRepuesto';
	}

	protected function gridDataColumn($data,$row)
	{
		return Tools::backFecha($data->fecha);
	}


	public function generarInforme($id){


		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
			
		$informe = InformeGastoRepuesto::model()->findByPk($id);

		if($this->fechaInicio == null || $this->fechaInicio == ""){
			$this->fechaInicio = $informe->fInicio;
		}
		if($this->fechaFin == null || $this->fechaFin == ""){
			$this->fechaFin = $informe->fFin;
		}


		$maquina = $informe->maquina_id;
		$operador = $informe->operador_id;
		$centro = $informe->centroGestion_id;
		$maquinaStr = $informe->maquina;
		$operadorStr = $informe->operador;
		$centroStr = $informe->centroGestion;
		$tipo = $informe->tipo;
		$tipoMaquina = $informe->tipo_maquina;
                

		$insertSql = "
		insert into informeDetalleGastoRepuesto
			(fecha,reporte,operario,maquina,repuesto,montoNeto,guia,factura,cantidad,numero,nombre,fechaRendicion)
		";		

		$filtroFecha = "";
		if(isset($this->fechaInicio) && isset($this->fechaFin)){
			if($this->fechaInicio != "" && $this->fechaFin != ""){
				$filtroFecha = "
					and		fecha >= :fechaInicio 
					and		fecha <= :fechaFin
				";
			}
		}

		$filtroMaquina = "";
		if(isset($maquinaStr)){
			if(trim($maquinaStr) != ""){
				$filtroMaquina = "
					and		m.id = :maquina
				";
			}
		}

		$filtroOperador = "";
		if(isset($operadorStr)){
			if(trim($operadorStr) != ""){
				$filtroOperador = "
					and		o.id = :operador
				";
			}
		}
		
		$filtroCentro = "";
		if(isset($centroStr)){
			if(trim($centroStr) != ""){
				$filtroCentro = "
					and		cg.id = :centro
				";
			}
		}

		$sql = "select * from camionPropio";

		if($tipo == "CP"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro";
		}
		if($tipo == "CA"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro";
		}

		if($tipo == "CT"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
			
			union all
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
			";
			
			if($maquinaStr != ''){
				if($tipoMaquina == 'CP'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoCamionPropio as c,
							rCamionPropio as r,
							camionPropio as m,
							chofer as o,
							faena as cg
					where	r.id = c.rCamionPropio_id and
							m.id = r.camionPropio_id and
							o.id = r.chofer_id and
							cg.id = c.faena_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
				if($tipoMaquina == 'CA'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoCamionArrendado as c,
							rCamionArrendado as r,
							camionArrendado as m,
							chofer as o,
							faena as cg
					where	r.id = c.rCamionArrendado_id and
							m.id = r.camionArrendado_id and
							o.id = r.chofer_id and
							cg.id = c.faena_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
			}
		}
		if($tipo == "MP"){
                    
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro";
		}
		if($tipo == "MA"){
			$sql = "
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
			";
		}
		if($tipo == "MT"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
					
			union all
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro";
			
			if($maquinaStr != ''){
				if($tipoMaquina == 'MP'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoEquipoPropio as c,
							rEquipoPropio as r,
							equipoPropio as m,
							operador as o,
							faena as cg
					where	r.id = c.rEquipoPropio_id and
							m.id = r.equipoPropio_id and
							o.id = r.operador_id and
							cg.id = c.faena_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
				if($tipoMaquina == 'MA'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoEquipoArrendado as c,
							rEquipoArrendado as r,
							equipoArrendado as m,
							operador as o,
							faena as cg
					where	r.id = c.rEquipoArrendado_id and
							m.id = r.equipoArrendado_id and
							o.id = r.operador_id and
							cg.id = c.faena_id 
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
			}
		}
		if($tipo == "TT"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
					
			union all
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as cg
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					cg.id = c.faena_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
					
			union all
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro
			
			union all
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.repuesto as repuesto,
					c.montoNeto as montoNeto,
					c.guia as guia,
					c.factura as factura,
					concat(c.cantidad,' ',c.unidad) as cantidad,
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	compraRepuestoCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as cg
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					cg.id = c.faena_id
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroCentro";
			
			if($maquinaStr != ''){
				if($tipoMaquina == 'MP'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoEquipoPropio as c,
							rEquipoPropio as r,
							equipoPropio as m,
							operador as o,
							faena as cg
					where	r.id = c.rEquipoPropio_id and
							m.id = r.equipoPropio_id and
							o.id = r.operador_id and
							cg.id = c.faena_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
				if($tipoMaquina == 'MA'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoEquipoArrendado as c,
							rEquipoArrendado as r,
							equipoArrendado as m,
							operador as o,
							faena as cg
					where	r.id = c.rEquipoArrendado_id and
							m.id = r.equipoArrendado_id and
							o.id = r.operador_id and
							cg.id = c.faena_id 
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
				if($tipoMaquina == 'CP'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoCamionPropio as c,
							rCamionPropio as r,
							camionPropio as m,
							chofer as o,
							faena as cg
					where	r.id = c.rCamionPropio_id and
							m.id = r.camionPropio_id and
							o.id = r.chofer_id and
							cg.id = c.faena_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
				if($tipoMaquina == 'CA'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.repuesto as repuesto,
							c.montoNeto as montoNeto,
							c.guia as guia,
							c.factura as factura,
							concat(c.cantidad,' ',c.unidad) as cantidad,
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	compraRepuestoCamionArrendado as c,
							rCamionArrendado as r,
							camionArrendado as m,
							chofer as o,
							faena as cg
					where	r.id = c.rCamionArrendado_id and
							m.id = r.camionArrendado_id and
							o.id = r.chofer_id and
							cg.id = c.faena_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroCentro
					";
				}
			}
		}
		$command=$connection->createCommand($insertSql.$sql);

		if($filtroFecha!=""){
			$command->bindParam(":fechaInicio",$this->fechaInicio,PDO::PARAM_STR);
			$command->bindParam(":fechaFin",$this->fechaFin,PDO::PARAM_STR);
		}
		$maquina = trim($maquina);
		/*$opRut = trim($opRut);
		$opNombre = trim($opNombre);*/

		if($filtroMaquina!=""){
			$command->bindParam(":maquina",$maquina,PDO::PARAM_INT);
		}
		if($filtroOperador!=""){
			$command->bindParam(":operador",$operador,PDO::PARAM_INT);
		}
		if($filtroCentro!=""){
			$command->bindParam(":centro",$centro,PDO::PARAM_INT);
		}
		$command->execute();


		$connection->active=false;
		$command = null;

	}

	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informeDetalleGastoRepuesto;
			"
			);

			$command->execute();
			$connection->active=false;
			$command = null;
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('montoNeto', 'numerical', 'integerOnly'=>true),
		array('operario, maquina', 'length', 'max'=>220),
		array('repuesto', 'length', 'max'=>200),
		array('guia, factura', 'length', 'max'=>45),
		array('cantidad', 'length', 'max'=>20),
		array('fecha', 'safe'),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, fecha, reporte, operario, maquina, repuesto, montoNeto, guia, factura, cantidad', 'safe', 'on'=>'search'),
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
			'fecha' => 'Fecha',
			'reporte' => 'Reporte',
			'operario' => 'Operario',
			'maquina' => 'Maquina',
			'repuesto' => 'Repuesto',
			'montoNeto' => 'Monto Neto',
			'guia' => 'Guia',
			'factura' => 'Factura',
			'cantidad' => 'Cantidad',
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
		$criteria->compare('fecha',Tools::fixFecha($this->fecha),true);
		$criteria->compare('reporte',$this->reporte);
		$criteria->compare('operario',$this->operario,true);
		$criteria->compare('maquina',$this->maquina,true);
		$criteria->compare('repuesto',$this->repuesto,true);
		$criteria->compare('montoNeto',$this->montoNeto);
		$criteria->compare('guia',$this->guia,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('cantidad',$this->cantidad,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}