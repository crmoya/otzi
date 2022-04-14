<?php

class Duplicados
{
    public function gastos($ids)
    {
        $eliminados = 0;
        foreach($ids as $id)
        {
            $gastoCompleta = GastoCompleta::model()->findByAttributes(["id"=>$id]);
            $gasto = Gasto::model()->findByAttributes(["id"=>$gastoCompleta->gasto_id]);
            $gastosCompletas = GastoCompleta::model()->findAllByAttributes([
                'rut_proveedor' => $gastoCompleta->rut_proveedor,
                'monto_neto' => $gastoCompleta->monto_neto,
                'nro_documento' => $gastoCompleta->nro_documento,
                'vehiculo_equipo' => $gastoCompleta->vehiculo_equipo,
            ]);
            foreach($gastosCompletas as $gastoDelete)
            {
                $gastoDel = Gasto::model()->findByAttributes(["id"=>$gastoDelete->gasto_id]);
                if($gastoDelete->id != $id && $gastoDel->issue_date == $gasto->issue_date)
                {
                    $gastoDelete->delete();
                    $gastoDel->delete();
                    $eliminados++;
                }
            }
        }
        return $eliminados;
    }

    public function compras($ids)
    {
        $eliminados = 0;
        foreach($ids as $id)
        {
            $idarr = explode('-',$id);
            $tiporep = $idarr[0];
            $cid = $idarr[1];
            if($tiporep == "CP")
            {
                $compraorig = CompraRepuestoCamionPropio::model()->findByAttributes(['id'=>$cid]);
                $compras = CompraRepuestoCamionPropio::model()->findAllByAttributes([
                    'repuesto'=>$compraorig->repuesto,
                    'montoNeto'=>$compraorig->montoNeto,
                    'factura'=>$compraorig->factura,
                    'rCamionPropio_id'=>$compraorig->rCamionPropio_id,
                    'tipo_documento'=>$compraorig->tipo_documento,
                    'rut_proveedor'=>$compraorig->rut_proveedor,
                    'cuenta'=>$compraorig->cuenta,
                    'nombre_proveedor'=>$compraorig->nombre_proveedor,
                    'faena_id'=>$compraorig->faena_id,
                    'cantidad'=>$compraorig->cantidad,
                    'unidad'=>$compraorig->unidad,
                    'fechaRendicion'=>$compraorig->fechaRendicion,
                    'observaciones'=>$compraorig->observaciones,
                ]);
                foreach($compras as $compra)
                {
                    if($compraorig->id != $compra->id)
                    {
                        $compra->delete();
                        $eliminados++;
                    }
                }
            }
            if($tiporep == "CA")
            {
                $compraorig = CompraRepuestoCamionArrendado::model()->findByAttributes(['id'=>$cid]);
                $compras = CompraRepuestoCamionArrendado::model()->findAllByAttributes([
                    'repuesto'=>$compraorig->repuesto,
                    'montoNeto'=>$compraorig->montoNeto,
                    'factura'=>$compraorig->factura,
                    'rCamionArrendado_id'=>$compraorig->rCamionArrendado_id,
                    'tipo_documento'=>$compraorig->tipo_documento,
                    'rut_proveedor'=>$compraorig->rut_proveedor,
                    'cuenta'=>$compraorig->cuenta,
                    'nombre_proveedor'=>$compraorig->nombre_proveedor,
                    'faena_id'=>$compraorig->faena_id,
                    'cantidad'=>$compraorig->cantidad,
                    'unidad'=>$compraorig->unidad,
                    'fechaRendicion'=>$compraorig->fechaRendicion,
                    'observaciones'=>$compraorig->observaciones,
                ]);
                foreach($compras as $compra)
                {
                    if($compraorig->id != $compra->id)
                    {
                        $compra->delete();
                        $eliminados++;
                    }
                }
            }
            if($tiporep == "EP")
            {
                $compraorig = CompraRepuestoEquipoPropio::model()->findByAttributes(['id'=>$cid]);
                $compras = CompraRepuestoEquipoPropio::model()->findAllByAttributes([
                    'repuesto'=>$compraorig->repuesto,
                    'montoNeto'=>$compraorig->montoNeto,
                    'factura'=>$compraorig->factura,
                    'rEquipoPropio_id'=>$compraorig->rEquipoPropio_id,
                    'tipo_documento'=>$compraorig->tipo_documento,
                    'rut_proveedor'=>$compraorig->rut_proveedor,
                    'cuenta'=>$compraorig->cuenta,
                    'nombre_proveedor'=>$compraorig->nombre_proveedor,
                    'faena_id'=>$compraorig->faena_id,
                    'cantidad'=>$compraorig->cantidad,
                    'unidad'=>$compraorig->unidad,
                    'fechaRendicion'=>$compraorig->fechaRendicion,
                    'observaciones'=>$compraorig->observaciones,
                ]);
                foreach($compras as $compra)
                {
                    if($compraorig->id != $compra->id)
                    {
                        $compra->delete();
                        $eliminados++;
                    }
                }
            }
            if($tiporep == "EA")
            {
                $compraorig = CompraRepuestoEquipoArrendado::model()->findByAttributes(['id'=>$cid]);
                $compras = CompraRepuestoEquipoArrendado::model()->findAllByAttributes([
                    'repuesto'=>$compraorig->repuesto,
                    'montoNeto'=>$compraorig->montoNeto,
                    'factura'=>$compraorig->factura,
                    'rEquipoArrendado_id'=>$compraorig->rEquipoArrendado_id,
                    'tipo_documento'=>$compraorig->tipo_documento,
                    'rut_proveedor'=>$compraorig->rut_proveedor,
                    'cuenta'=>$compraorig->cuenta,
                    'nombre_proveedor'=>$compraorig->nombre_proveedor,
                    'faena_id'=>$compraorig->faena_id,
                    'cantidad'=>$compraorig->cantidad,
                    'unidad'=>$compraorig->unidad,
                    'fechaRendicion'=>$compraorig->fechaRendicion,
                    'observaciones'=>$compraorig->observaciones,
                ]);
                foreach($compras as $compra)
                {
                    if($compraorig->id != $compra->id)
                    {
                        $compra->delete();
                        $eliminados++;
                    }
                }
            }
        }
        return $eliminados;
    }

    public function cargas($ids)
    {
        foreach($ids as $id)
        {
            $idarr = explode('-',$id);
            $tiporep = $idarr[0];
            $cid = $idarr[1];
            if($tiporep == "CP")
            {
                $cargaorig = CargaCombCamionPropio::model()->findByAttributes(['id'=>$cid]);
                $cargas = CargaCombCamionPropio::model()->findAllByAttributes([
                    'petroleoLts' => $cargaorig->petroleoLts,
                    'kmCarguio' => $cargaorig->kmCarguio,
                    'guia' => $cargaorig->guia,
                    'factura' => $cargaorig->factura,
                    'precioUnitario'=> $cargaorig->precioUnitario,
                    'valorTotal'=> $cargaorig->valorTotal,
                    'faena_id'=> $cargaorig->faena_id,
                    'numero' => $cargaorig->numero,
                    'nombre' => $cargaorig->nombre,
                    'fechaRendicion'=> $cargaorig->fechaRendicion,
                    'rut_rinde'=> $cargaorig->rut_rinde,
                    'cuenta'=> $cargaorig->cuenta,
                    'nombre_proveedor'=> $cargaorig->nombre_proveedor,
                    'rut_proveedor'=> $cargaorig->rut_proveedor,
                    'observaciones'=> $cargaorig->observaciones,
                    'tipo_documento' => $cargaorig->tipo_documento,
                    'tipoCombustible_id'=> $cargaorig->tipoCombustible_id,
                    'supervisorCombustible_id' => $cargaorig->supervisorCombustible_id,
                    'rCamionPropio_id' => $cargaorig->rCamionPropio_id,
                ]);
                foreach($cargas as $carga)
                {
                    if($cargaorig->id != $carga->id)
                    {
                        $carga->delete();
                        $eliminados++;
                    }
                }
            }
            if($tiporep == "CA")
            {
                $cargaorig = CargaCombCamionArrendado::model()->findByAttributes(['id'=>$cid]);
                $cargas = CargaCombCamionArrendado::model()->findAllByAttributes([
                    'petroleoLts' => $cargaorig->petroleoLts,
                    'kmCarguio' => $cargaorig->kmCarguio,
                    'guia' => $cargaorig->guia,
                    'factura' => $cargaorig->factura,
                    'precioUnitario'=> $cargaorig->precioUnitario,
                    'valorTotal'=> $cargaorig->valorTotal,
                    'faena_id'=> $cargaorig->faena_id,
                    'numero' => $cargaorig->numero,
                    'nombre' => $cargaorig->nombre,
                    'fechaRendicion'=> $cargaorig->fechaRendicion,
                    'rut_rinde'=> $cargaorig->rut_rinde,
                    'cuenta'=> $cargaorig->cuenta,
                    'nombre_proveedor'=> $cargaorig->nombre_proveedor,
                    'rut_proveedor'=> $cargaorig->rut_proveedor,
                    'observaciones'=> $cargaorig->observaciones,
                    'tipo_documento' => $cargaorig->tipo_documento,
                    'tipoCombustible_id'=> $cargaorig->tipoCombustible_id,
                    'supervisorCombustible_id' => $cargaorig->supervisorCombustible_id,
                    'rCamionArrendado_id' => $cargaorig->rCamionArrendado_id,
                ]);
                foreach($cargas as $carga)
                {
                    if($cargaorig->id != $carga->id)
                    {
                        $carga->delete();
                        $eliminados++;
                    }
                }
            }
            if($tiporep == "EP")
            {
                $cargaorig = CargaCombEquipoPropio::model()->findByAttributes(['id'=>$cid]);
                $cargas = CargaCombEquipoPropio::model()->findAllByAttributes([
                    'petroleoLts' => $cargaorig->petroleoLts,
                    'hCarguio' => $cargaorig->hCarguio,
                    'guia' => $cargaorig->guia,
                    'factura' => $cargaorig->factura,
                    'precioUnitario'=> $cargaorig->precioUnitario,
                    'valorTotal'=> $cargaorig->valorTotal,
                    'faena_id'=> $cargaorig->faena_id,
                    'numero' => $cargaorig->numero,
                    'nombre' => $cargaorig->nombre,
                    'fechaRendicion'=> $cargaorig->fechaRendicion,
                    'rut_rinde'=> $cargaorig->rut_rinde,
                    'cuenta'=> $cargaorig->cuenta,
                    'nombre_proveedor'=> $cargaorig->nombre_proveedor,
                    'rut_proveedor'=> $cargaorig->rut_proveedor,
                    'observaciones'=> $cargaorig->observaciones,
                    'tipo_documento' => $cargaorig->tipo_documento,
                    'tipoCombustible_id'=> $cargaorig->tipoCombustible_id,
                    'supervisorCombustible_id' => $cargaorig->supervisorCombustible_id,
                    'rEquipoPropio_id' => $cargaorig->rEquipoPropio_id,
                ]);
                foreach($cargas as $carga)
                {
                    if($cargaorig->id != $carga->id)
                    {
                        $carga->delete();
                        $eliminados++;
                    }
                }
            }
            if($tiporep == "EA")
            {
                $cargaorig = CargaCombEquipoArrendado::model()->findByAttributes(['id'=>$cid]);
                $cargas = CargaCombEquipoArrendado::model()->findAllByAttributes([
                    'petroleoLts' => $cargaorig->petroleoLts,
                    'hCarguio' => $cargaorig->hCarguio,
                    'guia' => $cargaorig->guia,
                    'factura' => $cargaorig->factura,
                    'precioUnitario'=> $cargaorig->precioUnitario,
                    'valorTotal'=> $cargaorig->valorTotal,
                    'faena_id'=> $cargaorig->faena_id,
                    'numero' => $cargaorig->numero,
                    'nombre' => $cargaorig->nombre,
                    'fechaRendicion'=> $cargaorig->fechaRendicion,
                    'rut_rinde'=> $cargaorig->rut_rinde,
                    'cuenta'=> $cargaorig->cuenta,
                    'nombre_proveedor'=> $cargaorig->nombre_proveedor,
                    'rut_proveedor'=> $cargaorig->rut_proveedor,
                    'observaciones'=> $cargaorig->observaciones,
                    'tipo_documento' => $cargaorig->tipo_documento,
                    'tipoCombustible_id'=> $cargaorig->tipoCombustible_id,
                    'supervisorCombustible_id' => $cargaorig->supervisorCombustible_id,
                    'rEquipoArrendado_id' => $cargaorig->rEquipoArrendado_id,
                ]);
                foreach($cargas as $carga)
                {
                    if($cargaorig->id != $carga->id)
                    {
                        $carga->delete();
                        $eliminados++;
                    }
                }
            }
        }
    }
}