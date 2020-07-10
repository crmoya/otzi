<?php

$series = array();
$i=0;
foreach ($serie as $data) {
	$series[$i++] = array('name' => $data[0], 'data' => array_slice($data, 1));
}
$this->Widget('ext.highcharts.HighchartsWidget', array(
   'options'=>array(
   	'chart' => array('type' => $type),
      'title' => array('text' => $title),
      'xAxis' => array(
         'categories' => $categories
      ),
      'yAxis' => array(
         'title' => array('text' => $title_y)
      ),
      'series' => $series
   )
));?>