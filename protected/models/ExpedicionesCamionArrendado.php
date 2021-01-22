<?php

class ExpedicionesCamionArrendado extends CActiveRecord
{


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, camion_id, faena_id, reporte', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria();
		
		if($this->fecha_inicio != "" && $this->fecha_fin == ""){
			$criteria->addCondition('fecha >= :fecha_inicio');
			$criteria->params[':fecha_inicio'] = $this->fecha_inicio;
		}
		if($this->fecha_inicio == "" && $this->fecha_fin != ""){
			$criteria->addCondition('fecha <= :fecha_fin');
			$criteria->params = [':fecha_fin'=>$this->fecha_fin];
		}
		if($this->fecha_inicio != "" && $this->fecha_fin != ""){
			$criteria->addCondition('fecha >= :fecha_inicio and fecha <= :fecha_fin');
			$criteria->params[':fecha_inicio'] = $this->fecha_inicio;
			$criteria->params[':fecha_fin'] = $this->fecha_fin;
		}

		$criteria->compare('reporte',$this->reporte,true);
		$criteria->compare('camion_id',$this->camion_id);
		$criteria->compare('faena_id',$this->faena_id);
		
		$criteria->addCondition("tipo = 'camiones_arrendados'");
		
		$criteria->select = 'tipo,id,fecha,reporte,observaciones,observaciones_obra,camion,camion_id,faena_id,faena,camion_codigo,sum(km_recorridos) as km_recorridos,sum(km_gps) as km_gps,sum(horas) as horas,sum(produccion) as produccion,sum(combustible) as combustible, sum(repuestos) as repuestos, sum(horas_panne) as horas_panne, panne,validado,validador';
		$criteria->group = 'tipo,id,fecha,reporte,observaciones,observaciones_obra,camion,camion_id,faena_id,faena,camion_codigo,panne,validado,validador';

		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $reporte;
	public $camion_id;
	public $faena_id;


	public function tableName()
	{
		return 'vexpedicionescamion';
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'gasto' => array(self::BELONGS_TO, 'Gasto', 'gasto_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'camion_id' => 'Camión',
			'faena_id' => 'Faena',
		];
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GastoCompleta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
