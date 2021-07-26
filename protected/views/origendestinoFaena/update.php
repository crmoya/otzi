<?php
$this->breadcrumbs=array(
	'Origendestino Faenas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OrigendestinoFaena', 'url'=>array('index')),
	array('label'=>'Create OrigendestinoFaena', 'url'=>array('create')),
	array('label'=>'View OrigendestinoFaena', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OrigendestinoFaena', 'url'=>array('admin')),
);
?>

<h1>Update OrigendestinoFaena <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>