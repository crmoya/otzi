<?php

class ProduccionMaquinaria extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, agruparPor,propiosOArrendados', 'safe', 'on'=>'search'),
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

		if(isset($this->propiosOArrendados) && $this->propiosOArrendados != "TODOS"){
			if($this->propiosOArrendados == "EA" || $this->propiosOArrendados == "EP"){
				$criteria->addCondition('tipo_maquina = :tipo_maquina');
				$criteria->params[':tipo_maquina'] = $this->propiosOArrendados;
			}
		}

		$inicioAgrupacion = "	maquina,
								operador,
								centro_gestion,
								CASE
									WHEN min(pu) = max(pu) THEN min(pu)
									WHEN min(pu) <> max(pu) THEN ''
								END as pu,
								sum(horas_fisicas) as horas_fisicas,
								sum(horas_contratadas) as horas_contratadas,
								sum(produccion_fisica) as produccion_fisica,
								sum(produccion_contratada) as produccion_contratada";

		$finAgrupacion = "		maquina,operador,centro_gestion";

		if(isset($this->agruparPor) && $this->agruparPor != "NINGUNO"){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacion = "	maquina,
										'' as operador,
										'' as centro_gestion,
										CASE
											WHEN min(pu) = max(pu) THEN min(pu)
											WHEN min(pu) <> max(pu) THEN ''
										END as pu,
										sum(horas_fisicas) as horas_fisicas,
										sum(horas_contratadas) as horas_contratadas,
										sum(produccion_fisica) as produccion_fisica,
										sum(produccion_contratada) as produccion_contratada";
				$finAgrupacion = "		maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacion = "	'' as maquina,
										operador,
										'' as centro_gestion,
										CASE
											WHEN min(pu) = max(pu) THEN min(pu)
											WHEN min(pu) <> max(pu) THEN ''
										END as pu,
										sum(horas_fisicas) as horas_fisicas,
										sum(horas_contratadas) as horas_contratadas,
										sum(produccion_fisica) as produccion_fisica,
										sum(produccion_contratada) as produccion_contratada";
				$finAgrupacion = "		operador";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacion = "	'' as maquina,
										'' as operador,
										centro_gestion,
										CASE
											WHEN min(pu) = max(pu) THEN min(pu)
											WHEN min(pu) <> max(pu) THEN ''
										END as pu,
										sum(horas_fisicas) as horas_fisicas,
										sum(horas_contratadas) as horas_contratadas,
										sum(produccion_fisica) as produccion_fisica,
										sum(produccion_contratada) as produccion_contratada";
				$finAgrupacion = "		centro_gestion";
			}
			if($this->agruparPor == "CENTROMAQUINA"){
				$inicioAgrupacion = "	maquina,
										'' as operador,
										centro_gestion,
										CASE
											WHEN min(pu) = max(pu) THEN min(pu)
											WHEN min(pu) <> max(pu) THEN ''
										END as pu,
										sum(horas_fisicas) as horas_fisicas,
										sum(horas_contratadas) as horas_contratadas,
										sum(produccion_fisica) as produccion_fisica,
										sum(produccion_contratada) as produccion_contratada";
				$finAgrupacion = "		maquina,centro_gestion";
			}
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacion = "	'' as maquina,
										operador,
										centro_gestion,
										CASE
											WHEN min(pu) = max(pu) THEN min(pu)
											WHEN min(pu) <> max(pu) THEN ''
										END as pu,
										sum(horas_fisicas) as horas_fisicas,
										sum(horas_contratadas) as horas_contratadas,
										sum(produccion_fisica) as produccion_fisica,
										sum(produccion_contratada) as produccion_contratada";
				$finAgrupacion = "		operador,centro_gestion";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacion = "	maquina,
										operador,
										'' as centro_gestion,
										CASE
											WHEN min(pu) = max(pu) THEN min(pu)
											WHEN min(pu) <> max(pu) THEN ''
										END as pu,
										sum(horas_fisicas) as horas_fisicas,
										sum(horas_contratadas) as horas_contratadas,
										sum(produccion_fisica) as produccion_fisica,
										sum(produccion_contratada) as produccion_contratada";
				$finAgrupacion = "		maquina,operador";
			}
		}

		$criteria->select = $inicioAgrupacion;
		$criteria->group = $finAgrupacion;

		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $propiosOArrendados;
	public $agruparPor;


	public function tableName()
	{
		return 'vproduccionmaquinaria';
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
		return array(
			'propiosOArrendados' => 'Propios / Arrendados',
		);
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
