<?php
class CPCustomButton extends CButtonColumn
{
    protected function renderButton($id, $button, $row, $data)
    {
        $button['imageUrl'] = RCamionPropio::getImagenValidado($data->registro->id);
        parent::renderButton($id, $button, $row, $data);
    }
}