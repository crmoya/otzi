<?php

class ExpedicionesEquipoPropio extends CActiveRecord
{
	public $chkHrsReales;
	public $chkHrsGPS;
	public $chkHrsMin;
	public $chkProduccionReal;
	public $chkProduccionMinima;
	public $chkCombLts;
	public $chkRepuestos;
	public $chkRemuneraciones;
	public $chkHrsPanne;
	public $chkPanne;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, equipo_id, faena_id, reporte, operador_id', 'safe', 'on'=>'search'),
			array('chkHrsReales, chkHrsGPS, chkHrsMin, chkProduccionReal, chkProduccionMinima, chkCombLts, chkRepuestos,
				chkRemuneraciones, chkHrsPanne, chkPanne', 'safe'),
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
		
		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $reporte;
	public $equipo_id;
	public $faena_id;

	public function tableName()
	{
		return 'vreportmaquinarias';
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
			'faena_id' => 'Faena',
			'chkHrsReales' => 'Hrs. Reales',
			'chkHrsGPS' => 'Hrs. GPS',
			'chkHrsMin' => 'Hrs. Mínimas',
			'chkProduccionReal' => 'Prod. Real',
			'chkProduccionMinima' => 'Prod. Mínima',
			'chkCombLts' => 'Comb. Lts.',
			'chkRepuestos' => 'Repuestos',
			'chkRemuneraciones' => 'Remuneraciones',
			'chkHrsPanne' => 'Hrs. Panne',
			'chkPanne' => 'Panne',
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
