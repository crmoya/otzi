<?php

/**
 * This is the model class for table "informeRegExpEquipoPropio".
 *
 * The followings are the available columns in table 'informeRegExpEquipoPropio':
 * @property integer $id
 * @property string $fecha
 * @property string $reporte
 * @property string $observaciones
 * @property string $horasReales
 * @property string $combustible
 * @property integer $repuesto
 * @property string $horasPanne
 */
class InformeRegExpEquipoArrendado extends CActiveRecord
{

	public $validador_nm;
	public $fechaInicio;
	public $fechaFin;
	public $equipo_id;


	public static function getImagenValidado($id)
	{
		$report = REquipoArrendado::model()->findByPk($id);
		if ($report != null) {
			if ($report->validado == 1)
				return Yii::app()->request->baseUrl . '/images/ok.png';
			else
				return Yii::app()->request->baseUrl . '/images/eliminar.png';
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return InformeRegExpEquipoPropio the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'informeRegExpEquipoArrendado';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, reporte, observaciones,observaciones_obra, horasReales, combustible, repuesto, horasPanne', 'required'),
			array('repuesto', 'numerical', 'integerOnly' => true),
			array('reporte, horasReales, combustible, horasPanne', 'length', 'max' => 12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('fechaInicio,fechaFin,equipo_id,reporte', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'registro' => array(self::BELONGS_TO, 'REquipoArrendado', 'id_reg'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fecha' => 'Fecha',
			'reporte' => 'Reporte',
			'observaciones' => 'Observaciones',
			'horasReales' => 'Horas Reales',
			'combustible' => 'Combustible (Lt)',
			'repuesto' => 'Repuesto ($)',
			'horasPanne' => 'Horas Panne',
			'validador_nm' => 'Validado Por',
			'equipo_id' => 'Equipo'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$thisF = Tools::fixFecha($this->fecha);
		$criteria->compare('id', $this->id);
		$criteria->compare('fecha', $thisF, true);
		$criteria->compare('reporte', $this->reporte, true);
		$criteria->compare('observaciones', $this->observaciones, true);
		$criteria->compare('horasReales', $this->horasReales, true);
		$criteria->compare('combustible', $this->combustible, true);
		$criteria->compare('repuesto', $this->repuesto);
		$criteria->compare('equipo', $this->equipo, true);
		$criteria->compare('horasPanne', $this->horasPanne, true);
		$criteria->compare('panne', $this->panne, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id DESC',
				'attributes' => array(
					'*',
				),
			),
		));
	}


	public function generarInforme()
	{
		$this->limpiar();
		$connection = Yii::app()->db;
		$connection->active = true;

		$insertSql = "
		insert into informeRegExpEquipoArrendado
			(fecha,reporte,observaciones,observaciones_obra,equipo,horasReales,combustible,repuesto,horasPanne,horasGps,panne,id_reg)
		";

		$filtroFecha = "";
		if (isset($this->fechaInicio) && isset($this->fechaFin)) {
			if ($this->fechaInicio != "" && $this->fechaFin != "") {
				$filtroFecha = "	
					and		fecha >= :fechaInicio 
					and		fecha <= :fechaFin
				";
			}
		}

		$filtroReporte = "";
		$filtroEquipo = "";
		if (isset($this->equipo_id)) {
			if ($this->equipo_id != "") {
				$filtroEquipo = "	
					and		e.id = :equipo_id 
				";
			}
		}

		$sql = "
			select 	fecha,
					reporte,
					tc.observaciones,
					tc.observaciones_obra,
					nombreM,
					horas,
					combustible,
					IFNULL(sum(cr.montoNeto),0) as repuesto,
					horasPanne,
					horasGps,
					panne,
					tc.id
			from	
					(		
					select 	fecha,
							reporte,
							tr.observaciones,
							tr.observaciones_obra,
							nombreM,
							horas,
							IFNULL(sum(c.petroleoLts),0) as combustible,
							horasPanne,
							horasGps,
							panne,
							tr.id as id
					from
							(
							select 	r.fecha,
									r.reporte,
									r.observaciones,
									r.observaciones_obra,
									e.nombre as nombreM,
									r.horas,
									r.minPanne/60 as horasPanne,
									r.horasGps,
									IF(r.panne=1,'SÍ','NO') as panne,
									r.id
							from 	rEquipoArrendado as r,
									equipoArrendado as e
							where 	r.equipoArrendado_id = e.id
									$filtroEquipo 
									$filtroFecha
									$filtroReporte
							) as tr
					left join 
							cargaCombEquipoArrendado as c
					on		c.rEquipoArrendado_id = tr.id
					group by tr.id
					) as tc	
			left join 
					compraRepuestoEquipoArrendado as cr
			on		cr.rEquipoArrendado_id = tc.id
			group by tc.id
	
		";

		$command = $connection->createCommand($insertSql . $sql);

		$fechaI = Tools::fixFecha($this->fechaInicio);
		$fechaF = Tools::fixFecha($this->fechaFin);
		if ($filtroFecha != "") {
			$command->bindParam(":fechaInicio", $fechaI, PDO::PARAM_STR);
			$command->bindParam(":fechaFin", $fechaF, PDO::PARAM_STR);
		}

		if ($filtroReporte != "") {
			$command->bindParam(":reporte", $this->reporte, PDO::PARAM_STR);
		}

		if ($filtroEquipo != "") {
			$command->bindParam(":equipo_id", $this->equipo_id, PDO::PARAM_STR);
		}

		$command->execute();

		$connection->active = false;
		$command = null;
	}
	public function getReg($id)
	{
		$connection = Yii::app()->db;
		$connection->active = true;
		$command = $connection->createCommand("
			select		id_reg
			from		informeRegExpEquipoArrendado
			where 		id = :id
			");
		$command->bindParam(":id", $id, PDO::PARAM_INT);
		$dataReader = $command->query();
		$rows = $dataReader->readAll();
		$connection->active = false;
		$command = null;
		foreach ($rows as $row) {
			return $row['id_reg'];
		}
	}

	public function limpiar()
	{
		$connection = Yii::app()->db;
		$connection->active = true;
		$command = $connection->createCommand("
			truncate informeRegExpEquipoArrendado;
			");

		$command->execute();
		$connection->active = false;
		$command = null;
	}

	protected function gridDataColumn($data, $row)
	{
		return Tools::backFecha($data->fecha);
	}
}
