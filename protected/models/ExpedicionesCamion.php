<?php

class ExpedicionesCamion extends CActiveRecord
{


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('chofer_id,fecha_inicio, fecha_fin, agruparPor,tipoCombustible_id,propiosOArrendados,decimales', 'safe', 'on'=>'search'),
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
			if($this->propiosOArrendados == "CA"){
				$criteria->addCondition('tipo = :tipo');
				$criteria->params[':tipo'] = 'camiones_arrendados';
			}
			if($this->propiosOArrendados == "CP"){
				$criteria->addCondition('tipo = :tipo');
				$criteria->params[':tipo'] = 'camiones_propios';
			}
		}

		if(isset($this->chofer_id))
		{
			$criteria->addCondition('chofer_id = :chofer');
			$criteria->params[':chofer'] = $this->chofer_id;
		}

		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $reporte;
	public $camion_id;
	public $faena_id;
	public $decimales;
	public $propiosOArrendados;
	public $agruparPor;
	public $tipoCombustible_id;
	public $chofer_id;


	public function tableName()
	{
		return 'vreportcamiones';
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
			'tipoCombustible_id' => 'Combustible',
			'propiosOArrendados' => 'Propios / Arrendados',
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
