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
class InformeDetalleGastoCombustible extends CActiveRecord
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
		return 'informeDetalleGastoCombustible';
	}
	
	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fecha);   
	} 
	

	public function generarInforme($id){
		
		$this->limpiar();
		$connection=Yii::app()->db;
		$connection->active=true;
			
		$informe = InformeGastoCombustible::model()->findByPk($id);

		if($this->fechaInicio == null || $this->fechaInicio == ""){
			$this->fechaInicio = $informe->fInicio;
		}
		if($this->fechaFin == null || $this->fechaFin == ""){
			$this->fechaFin = $informe->fFin;
		}
		$filtroTipoComb = "";
		
		$maquina = $informe->maquina_id;
		$operador = $informe->operador_id;
		$faena = $informe->centroGestion_id;
		$maquinaStr = $informe->maquina;
		$operadorStr = $informe->operador;
		$faenaStr = $informe->centroGestion;
		$tipo = $informe->tipo;
		$tipoMaquina = $informe->tipo_maquina;
		
			
		
		$insertSql = "
		insert into informeDetalleGastoCombustible
			(fecha,reporte,operario,maquina,petroleoLts,kmCarguio,guia,factura,precioUnitario,valorTotal,faena,tipoCombustible,supervisorCombustible,numero,nombre,fechaRendicion)
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
		
		
		$filtroFaena = "";
		if(isset($faenaStr)){
			if(trim($faenaStr) != ""){
				$filtroFaena = "	
					and		f.id = :faena
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
		
	
		if($informe->tipo_comb != 0){
			$filtroTipoComb = " 
					and t.id = :tipoComb";
		}
		
		if($tipo == "CP"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.petroleoLts,
					c.kmCarguio,				
					c.guia as guia,
					c.factura as factura,
					c.precioUnitario,
					c.valorTotal,
					f.nombre,
					t.nombre,
					concat(s.rut,' / ',s.nombre),
					c.numero as num,
					c.nombre,
					c.fechaRendicion
			from 	cargaCombCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as f,
					supervisorCombustible as s,
					tipoCombustible as t
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					f.id = c.faena_id and
					s.id = c.supervisorCombustible_id and
					t.id = c.tipoCombustible_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroFaena
					$filtroTipoComb";
		}
		if($tipo == "CA"){
		
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.petroleoLts,
					c.kmCarguio,				
					c.guia as guia,
					c.factura as factura,
					c.precioUnitario,
					c.valorTotal,
					f.nombre,
					t.nombre,
					concat(s.rut,' / ',s.nombre),
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	cargaCombCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as f,
					supervisorCombustible as s,
					tipoCombustible as t
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					f.id = c.faena_id and
					s.id = c.supervisorCombustible_id and
					t.id = c.tipoCombustible_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroFaena
					$filtroTipoComb";
				
		}
		if($tipo == "CT"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					concat(m.codigo,' / ',m.nombre),
					c.petroleoLts,
					c.kmCarguio,				
					c.guia as guia,
					c.factura as factura,
					c.precioUnitario,
					c.valorTotal,
					f.nombre,
					t.nombre,
					concat(s.rut,' / ',s.nombre),
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	cargaCombCamionPropio as c,
					rCamionPropio as r,
					camionPropio as m,
					chofer as o,
					faena as f,
					supervisorCombustible as s,
					tipoCombustible as t
			where	r.id = c.rCamionPropio_id and
					m.id = r.camionPropio_id and
					o.id = r.chofer_id and
					f.id = c.faena_id and
					s.id = c.supervisorCombustible_id and
					t.id = c.tipoCombustible_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroFaena
					$filtroTipoComb
			
			union all
			
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.petroleoLts,
					c.kmCarguio,				
					c.guia as guia,
					c.factura as factura,
					c.precioUnitario,
					c.valorTotal,
					f.nombre,
					t.nombre,
					concat(s.rut,' / ',s.nombre),
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	cargaCombCamionArrendado as c,
					rCamionArrendado as r,
					camionArrendado as m,
					chofer as o,
					faena as f,
					supervisorCombustible as s,
					tipoCombustible as t
			where	r.id = c.rCamionArrendado_id and
					m.id = r.camionArrendado_id and
					o.id = r.chofer_id and
					f.id = c.faena_id and
					s.id = c.supervisorCombustible_id and
					t.id = c.tipoCombustible_id 
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroFaena
					$filtroTipoComb";
			
			
			if($maquinaStr != ''){
				//quiere decir que pinchó un camión específico
				
				if($tipoMaquina == 'CP'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.petroleoLts,
							c.kmCarguio,				
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombCamionPropio as c,
							rCamionPropio as r,
							camionPropio as m,
							chofer as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rCamionPropio_id and
							m.id = r.camionPropio_id and
							o.id = r.chofer_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id 
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
					";
				}
				if($tipoMaquina == 'CA'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.petroleoLts,
							c.kmCarguio,				
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombCamionArrendado as c,
							rCamionArrendado as r,
							camionArrendado as m,
							chofer as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rCamionArrendado_id and
							m.id = r.camionArrendado_id and
							o.id = r.chofer_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id 
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
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
					c.petroleoLts,
					c.hCarguio,				
					c.guia as guia,
					c.factura as factura,
					c.precioUnitario,
					c.valorTotal,
					f.nombre,
					t.nombre,
					concat(s.rut,' / ',s.nombre),
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	cargaCombEquipoPropio as c,
					rEquipoPropio as r,
					equipoPropio as m,
					operador as o,
					faena as f,
					supervisorCombustible as s,
					tipoCombustible as t
			where	r.id = c.rEquipoPropio_id and
					m.id = r.equipoPropio_id and
					o.id = r.operador_id and
					f.id = c.faena_id and
					s.id = c.supervisorCombustible_id and
					t.id = c.tipoCombustible_id  
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroFaena
					$filtroTipoComb";
		}
		
		if($tipo == "MA"){
			$sql = "
			select 	r.fecha as fecha,
					r.reporte,
					concat(o.rut,' / ',o.nombre),
					m.nombre,
					c.petroleoLts,
					c.hCarguio,				
					c.guia as guia,
					c.factura as factura,
					c.precioUnitario,
					c.valorTotal,
					f.nombre,
					t.nombre,
					concat(s.rut,' / ',s.nombre),
					c.numero,
					c.nombre,
					c.fechaRendicion
			from 	cargaCombEquipoArrendado as c,
					rEquipoArrendado as r,
					equipoArrendado as m,
					operador as o,
					faena as f,
					supervisorCombustible as s,
					tipoCombustible as t
			where	r.id = c.rEquipoArrendado_id and
					m.id = r.equipoArrendado_id and
					o.id = r.operador_id and
					f.id = c.faena_id and
					s.id = c.supervisorCombustible_id and
					t.id = c.tipoCombustible_id  
					$filtroFecha
					$filtroMaquina
					$filtroOperador
					$filtroFaena
					$filtroTipoComb
			";
		}
		if($tipo == "MT"){
			$sql = "
				select 	r.fecha as fecha,
						r.reporte,
						concat(o.rut,' / ',o.nombre),
						concat(m.codigo,' / ',m.nombre),
						c.petroleoLts,
						c.hCarguio,				
						c.guia as guia,
						c.factura as factura,
						c.precioUnitario,
						c.valorTotal,
						f.nombre,
						t.nombre,
						concat(s.rut,' / ',s.nombre),
						c.numero,
						c.nombre,
						c.fechaRendicion
				from 	cargaCombEquipoPropio as c,
						rEquipoPropio as r,
						equipoPropio as m,
						operador as o,
						faena as f,
						supervisorCombustible as s,
						tipoCombustible as t
				where	r.id = c.rEquipoPropio_id and
						m.id = r.equipoPropio_id and
						o.id = r.operador_id and
						f.id = c.faena_id and
						s.id = c.supervisorCombustible_id and
						t.id = c.tipoCombustible_id  
						$filtroFecha
						$filtroMaquina
						$filtroOperador
						$filtroFaena
						$filtroTipoComb
						
				union all
				
				select 	r.fecha as fecha,
						r.reporte,
						concat(o.rut,' / ',o.nombre),
						m.nombre,
						c.petroleoLts,
						c.hCarguio,				
						c.guia as guia,
						c.factura as factura,
						c.precioUnitario,
						c.valorTotal,
						f.nombre,
						t.nombre,
						concat(s.rut,' / ',s.nombre),
						c.numero,
						c.nombre,
						c.fechaRendicion
				from 	cargaCombEquipoArrendado as c,
						rEquipoArrendado as r,
						equipoArrendado as m,
						operador as o,
						faena as f,
						supervisorCombustible as s,
						tipoCombustible as t
				where	r.id = c.rEquipoArrendado_id and
						m.id = r.equipoArrendado_id and
						o.id = r.operador_id and
						f.id = c.faena_id and
						s.id = c.supervisorCombustible_id and
						t.id = c.tipoCombustible_id  
						$filtroFecha
						$filtroMaquina
						$filtroOperador
						$filtroFaena
						$filtroTipoComb";
			
			if($maquinaStr != ''){
				//quiere decir que pinchó un camión específico
				if($tipoMaquina == 'MP'){
					$sql = "
					
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.petroleoLts,
							c.hCarguio,				
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombEquipoPropio as c,
							rEquipoPropio as r,
							equipoPropio as m,
							operador as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rEquipoPropio_id and
							m.id = r.equipoPropio_id and
							o.id = r.operador_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id  
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
					
					";
				}
				if($tipoMaquina == 'MA'){
					$sql = "
						
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.petroleoLts,
							c.hCarguio,				
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombEquipoArrendado as c,
							rEquipoArrendado as r,
							equipoArrendado as m,
							operador as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rEquipoArrendado_id and
							m.id = r.equipoArrendado_id and
							o.id = r.operador_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id  
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
						
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
						c.petroleoLts,
						c.hCarguio,				
						c.guia as guia,
						c.factura as factura,
						c.precioUnitario,
						c.valorTotal,
						f.nombre,
						t.nombre,
						concat(s.rut,' / ',s.nombre),
						c.numero,
						c.nombre,
						c.fechaRendicion
				from 	cargaCombEquipoPropio as c,
						rEquipoPropio as r,
						equipoPropio as m,
						operador as o,
						faena as f,
						supervisorCombustible as s,
						tipoCombustible as t
				where	r.id = c.rEquipoPropio_id and
						m.id = r.equipoPropio_id and
						o.id = r.operador_id and
						f.id = c.faena_id and
						s.id = c.supervisorCombustible_id and
						t.id = c.tipoCombustible_id  
						$filtroFecha
						$filtroMaquina
						$filtroOperador
						$filtroFaena
						$filtroTipoComb
						
				union all
				
				select 	r.fecha as fecha,
						r.reporte,
						concat(o.rut,' / ',o.nombre),
						m.nombre,
						c.petroleoLts,
						c.hCarguio,				
						c.guia as guia,
						c.factura as factura,
						c.precioUnitario,
						c.valorTotal,
						f.nombre,
						t.nombre,
						concat(s.rut,' / ',s.nombre),
						c.numero,
						c.nombre,
						c.fechaRendicion
				from 	cargaCombEquipoArrendado as c,
						rEquipoArrendado as r,
						equipoArrendado as m,
						operador as o,
						faena as f,
						supervisorCombustible as s,
						tipoCombustible as t
				where	r.id = c.rEquipoArrendado_id and
						m.id = r.equipoArrendado_id and
						o.id = r.operador_id and
						f.id = c.faena_id and
						s.id = c.supervisorCombustible_id and
						t.id = c.tipoCombustible_id  
						$filtroFecha
						$filtroMaquina
						$filtroOperador
						$filtroFaena
						$filtroTipoComb
							
				union all
				
				select 	r.fecha as fecha,
						r.reporte,
						concat(o.rut,' / ',o.nombre),
						concat(m.codigo,' / ',m.nombre),
						c.petroleoLts,
						c.kmCarguio,				
						c.guia as guia,
						c.factura as factura,
						c.precioUnitario,
						c.valorTotal,
						f.nombre,
						t.nombre,
						concat(s.rut,' / ',s.nombre),
						c.numero,
						c.nombre,
						c.fechaRendicion
				from 	cargaCombCamionPropio as c,
						rCamionPropio as r,
						camionPropio as m,
						chofer as o,
						faena as f,
						supervisorCombustible as s,
						tipoCombustible as t
				where	r.id = c.rCamionPropio_id and
						m.id = r.camionPropio_id and
						o.id = r.chofer_id and
						f.id = c.faena_id and
						s.id = c.supervisorCombustible_id and
						t.id = c.tipoCombustible_id 
						$filtroFecha
						$filtroMaquina
						$filtroOperador
						$filtroFaena
						$filtroTipoComb
				
				union all
				
				select 	r.fecha as fecha,
						r.reporte,
						concat(o.rut,' / ',o.nombre),
						m.nombre,
						c.petroleoLts,
						c.kmCarguio,				
						c.guia as guia,
						c.factura as factura,
						c.precioUnitario,
						c.valorTotal,
						f.nombre,
						t.nombre,
						concat(s.rut,' / ',s.nombre),
						c.numero,
						c.nombre,
						c.fechaRendicion
				from 	cargaCombCamionArrendado as c,
						rCamionArrendado as r,
						camionArrendado as m,
						chofer as o,
						faena as f,
						supervisorCombustible as s,
						tipoCombustible as t
				where	r.id = c.rCamionArrendado_id and
						m.id = r.camionArrendado_id and
						o.id = r.chofer_id and
						f.id = c.faena_id and
						s.id = c.supervisorCombustible_id and
						t.id = c.tipoCombustible_id 
						$filtroFecha
						$filtroMaquina
						$filtroOperador
						$filtroFaena
						$filtroTipoComb";
			
			
			if($maquinaStr != ''){
				//quiere decir que pinchó un equipo específico
				if($tipoMaquina == 'MP'){
					$sql = "
						
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.petroleoLts,
							c.hCarguio,
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombEquipoPropio as c,
							rEquipoPropio as r,
							equipoPropio as m,
							operador as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rEquipoPropio_id and
							m.id = r.equipoPropio_id and
							o.id = r.operador_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
						
					";
				}
				if($tipoMaquina == 'MA'){
					$sql = "
			
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.petroleoLts,
							c.hCarguio,
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombEquipoArrendado as c,
							rEquipoArrendado as r,
							equipoArrendado as m,
							operador as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rEquipoArrendado_id and
							m.id = r.equipoArrendado_id and
							o.id = r.operador_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
			
					";
				}
				
				if($tipoMaquina == 'CP'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							concat(m.codigo,' / ',m.nombre),
							c.petroleoLts,
							c.kmCarguio,
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombCamionPropio as c,
							rCamionPropio as r,
							camionPropio as m,
							chofer as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rCamionPropio_id and
							m.id = r.camionPropio_id and
							o.id = r.chofer_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
					";
					
				}
				if($tipoMaquina == 'CA'){
					$sql = "
					select 	r.fecha as fecha,
							r.reporte,
							concat(o.rut,' / ',o.nombre),
							m.nombre,
							c.petroleoLts,
							c.kmCarguio,
							c.guia as guia,
							c.factura as factura,
							c.precioUnitario,
							c.valorTotal,
							f.nombre,
							t.nombre,
							concat(s.rut,' / ',s.nombre),
							c.numero,
							c.nombre,
							c.fechaRendicion
					from 	cargaCombCamionArrendado as c,
							rCamionArrendado as r,
							camionArrendado as m,
							chofer as o,
							faena as f,
							supervisorCombustible as s,
							tipoCombustible as t
					where	r.id = c.rCamionArrendado_id and
							m.id = r.camionArrendado_id and
							o.id = r.chofer_id and
							f.id = c.faena_id and
							s.id = c.supervisorCombustible_id and
							t.id = c.tipoCombustible_id
							$filtroFecha
							$filtroMaquina
							$filtroOperador
							$filtroFaena
							$filtroTipoComb
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
		if($filtroFaena!=""){
			$command->bindParam(":faena",$faena,PDO::PARAM_INT);
		}
		if($filtroTipoComb!=""){
			$command->bindParam(":tipoComb",$informe->tipo_comb,PDO::PARAM_INT);
		}
		$command->execute();	
		
		
		$connection->active=false;
		$command = null;
		
	}
	
	public function limpiar(){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			truncate informeDetalleGastoCombustible;
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
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id', 'safe', 'on'=>'search'),
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
			'kmCarguio' => 'Carguío',
			'petroleoLts' => 'Combustible Lts',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}