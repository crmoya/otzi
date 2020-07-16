<?php
class EACustomButton extends CButtonColumn
{
    protected function renderButton($id, $button, $row, $data)
    {
        $button['imageUrl'] = REquipoArrendado::getImagenValidado($data->registro->id);
        parent::renderButton($id, $button, $row, $data);
    }
}