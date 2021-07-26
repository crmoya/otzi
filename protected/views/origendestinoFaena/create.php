<?php
$this->breadcrumbs=array(
	'Origendestino Faenas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrigendestinoFaena', 'url'=>array('index')),
	array('label'=>'Manage OrigendestinoFaena', 'url'=>array('admin')),
);
?>

<h1>Create OrigendestinoFaena</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>