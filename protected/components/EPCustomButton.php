<?php
class EPCustomButton extends CButtonColumn
{
    protected function renderButton($id, $button, $row, $data)
    {
        $button['imageUrl'] = REquipoPropio::getImagenValidado($data->registro->id);
        parent::renderButton($id, $button, $row, $data);
    }
}