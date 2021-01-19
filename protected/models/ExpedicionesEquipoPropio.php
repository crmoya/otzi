<?php

class ExpedicionesEquipoPropio extends CActiveRecord
{


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, equipo_id, reporte', 'safe', 'on'=>'search'),
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
		$criteria->compare('equipo_id',$this->equipo_id);
		
		$criteria->addCondition("tipo = 'equipos_propios'");
		
		$criteria->select = 'tipo,id,fecha,reporte,observaciones,observaciones_obra,equipo,equipo_id,sum(horas_reales) as horas_reales,sum(horas_gps) as horas_gps,sum(produccion) as produccion,sum(combustible) as combustible, sum(repuestos) as repuestos, sum(horas_panne) as horas_panne, panne,validado,validador';
		$criteria->group = 'tipo,id,fecha,reporte,observaciones,observaciones_obra,equipo,equipo_id,panne,validado,validador';

		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $reporte;
	public $equipo_id;


	public function tableName()
	{
		return 'vexpedicionesequipo';
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
			'equipo_id' => 'Equipo',
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
