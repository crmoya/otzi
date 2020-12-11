<?php

class ProduccionCamiones extends CActiveRecord
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
			if($this->propiosOArrendados == "CA" || $this->propiosOArrendados == "CP"){
				$criteria->addCondition('tipo_camion = :tipo_camion');
				$criteria->params[':tipo_camion'] = $this->propiosOArrendados;
			}
		}

		$inicioAgrupacion = "	camion,
								chofer,
								centro_gestion,
								sum(total_transportado) as total_transportado,
								sum(produccion_contratada) as produccion_contratada,
								sum(produccion_real) as produccion_real,
								GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";

		$finAgrupacion = "		camion,chofer,centro_gestion";

		if(isset($this->agruparPor) && $this->agruparPor != "NINGUNO"){
			if($this->agruparPor == "CAMION"){
				$inicioAgrupacion = "	camion,
										'' as chofer,
										'' as centro_gestion,
										sum(total_transportado) as total_transportado,
										sum(produccion_contratada) as produccion_contratada,
										sum(produccion_real) as produccion_real,
										GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";
				$finAgrupacion = "		camion";
			}
			if($this->agruparPor == "CHOFER"){
				$inicioAgrupacion = "	'' as camion,
										chofer,
										'' as centro_gestion,
										sum(total_transportado) as total_transportado,
										sum(produccion_contratada) as produccion_contratada,
										sum(produccion_real) as produccion_real,
										GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";
				$finAgrupacion = "		chofer";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacion = "	'' as camion,
										'' as chofer,
										centro_gestion,
										sum(total_transportado) as total_transportado,
										sum(produccion_contratada) as produccion_contratada,
										sum(produccion_real) as produccion_real,
										GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";
				$finAgrupacion = "		centro_gestion";
			}
			if($this->agruparPor == "CENTROCAMION"){
				$inicioAgrupacion = "	camion,
										'' as chofer,
										centro_gestion,
										sum(total_transportado) as total_transportado,
										sum(produccion_contratada) as produccion_contratada,
										sum(produccion_real) as produccion_real,
										GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";
				$finAgrupacion = "		camion,centro_gestion";
			}
			if($this->agruparPor == "CENTROCHOFER"){
				$inicioAgrupacion = "	'' as camion,
										chofer,
										centro_gestion,
										sum(total_transportado) as total_transportado,
										sum(produccion_contratada) as produccion_contratada,
										sum(produccion_real) as produccion_real,
										GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";
				$finAgrupacion = "		chofer,centro_gestion";
			}
			if($this->agruparPor == "CHOFERCAMION"){
				$inicioAgrupacion = "	camion,
										chofer,
										'' as centro_gestion,
										sum(total_transportado) as total_transportado,
										sum(produccion_contratada) as produccion_contratada,
										sum(produccion_real) as produccion_real,
										GREATEST(sum(produccion_contratada) - sum(produccion_real),0) as produccion_diferencia";
				$finAgrupacion = "		camion,chofer";
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
		return 'vproduccioncamiones';
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
