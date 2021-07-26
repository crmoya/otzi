<?php
class CACustomButton extends CButtonColumn
{
    protected function renderButton($id, $button, $row, $data)
    {
        $button['imageUrl'] = RCamionArrendado::getImagenValidado($data->registro->id);
        parent::renderButton($id, $button, $row, $data);
    }
}