<?php

class ConsumoMaquinaria extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, agruparPor,tipoCombustible_id,propiosOArrendados,decimales', 'safe', 'on'=>'search'),
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

		if(isset($this->tipoCombustible_id) && $this->tipoCombustible_id != ""){
			$criteria->addCondition('(tipo_combustible = :combustible or tipo_combustible = -1)');
			$criteria->params[':combustible'] = $this->tipoCombustible_id;
		}

		if(isset($this->propiosOArrendados) && $this->propiosOArrendados != "TODOS"){
			if($this->propiosOArrendados == "EA" || $this->propiosOArrendados == "EP"){
				$criteria->addCondition('tipo_maquina = :tipo_maquina');
				$criteria->params[':tipo_maquina'] = $this->propiosOArrendados;
			}
		}
		

		$inicioAgrupacion = "	maquina,
								operador,
								sum(litros) as litros,
								sum(horas) as horas,
								sum(horas_gps) as horas_gps,
								avg(consumo_esperado) as consumo_esperado,
								sum(litros)/sum(horas) as litros_hora,
								sum(litros)/sum(horas_gps) as litros_hora_gps    ";

		$finAgrupacion = "		operador,maquina";

		if(isset($this->agruparPor) && $this->agruparPor != "NINGUNO"){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacion = "
				maquina,
				'' as operador,
				sum(litros) as litros,
				sum(horas) as horas,
				sum(horas_gps) as horas_gps,
				avg(consumo_esperado) as consumo_esperado,
				sum(litros)/sum(horas) as litros_hora,
				sum(litros)/sum(horas_gps) as litros_hora_gps   
				";
				$finAgrupacion = "maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacion = "
				'' as maquina,
				operador,
				sum(litros) as litros,
				sum(horas) as horas,
				sum(horas_gps) as horas_gps,
				avg(consumo_esperado) as consumo_esperado,
				sum(litros)/sum(horas) as litros_hora,
				sum(litros)/sum(horas_gps) as litros_hora_gps   
				";
				$finAgrupacion = "operador";
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
	public $tipoCombustible_id;
	public $decimales;


	public function tableName()
	{
		return 'vconsumomaquinaria';
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
			'tipoCombustible_id' => 'Combustible',
			'decimales' => 'Cantidad decimales',
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
