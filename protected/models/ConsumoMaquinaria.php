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
			array('fecha_inicio, fecha_fin, agruparPor,tipoCombustible_id,propiosOArrendados', 'safe', 'on'=>'search'),
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
			$criteria->addCondition('tipo_combustible = :combustible');
			$criteria->params[':combustible'] = $this->tipoCombustible_id;
		}

		if(isset($this->propiosOArrendados) && $this->propiosOArrendados != "TODOS"){
			if($this->propiosOArrendados == "EA" || $this->propiosOArrendados == "EP"){
				$criteria->addCondition('tipo_maquina = :tipo_maquina');
				$criteria->params[':tipo_maquina'] = $this->propiosOArrendados;
			}
			else if($this->propiosOArrendados == "E"){
				$criteria->addCondition("(tipo_maquina = 'EA' or tipo_maquina = 'EP')");
			}
		}


		$inicioAgrupacion = "	maquina,
								operador,
								centro_gestion,
								sum(litros) as litros,
								sum(total) as total";
		$finAgrupacion = "		operador,maquina,centro_gestion";

		if(isset($this->agruparPor) && $this->agruparPor != "NINGUNO"){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacion = "
				maquina,
				'' as operador,
				'' as centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "maquina";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacion = "
				'' as maquina,
				operador,
				'' as centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "operador";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacion = "
				'' as maquina,
				'' as operador,
				centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "centro_gestion";
			}
			if($this->agruparPor == "CENTROMAQUINA"){
				$inicioAgrupacion = "
				maquina,
				'' as operador,
				centro_gestion,
				sum(litros) as litros,
				sum(total) as total,
				'CENTROMAQUINA' as id
				";
				$finAgrupacion = "maquina,centro_gestion";
			}
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacion = "
				'' as maquina,
				operador,
				centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "centro_gestion,operador";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacion = "
				maquina,
				operador,
				'' as centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "operador,maquina";
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


	public function tableName()
	{
		return 'vgastocombustible';
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
