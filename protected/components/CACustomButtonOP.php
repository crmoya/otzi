<?php
class CACustomButtonOP extends CButtonColumn
{
    protected function renderButton($id, $button, $row, $data)
    { 
        $button['imageUrl'] = ($data->validado != 1)?Yii::app()->baseUrl."/images/update.png":Yii::app()->baseUrl."/images/nopicture.png";
        parent::renderButton($id, $button, $row, $data);
    }
}