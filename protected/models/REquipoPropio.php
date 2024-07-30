<?php

/**
 * This is the model class for table "rEquipoPropio".
 *
 * The followings are the available columns in table 'rEquipoPropio':
 * @property integer $id
 * @property string $fecha
 * @property integer $reporte
 * @property string $observaciones
 * @property integer $equipoPropio_id
 * @property string $hInicial
 * @property string $hFinal
 * @property string $horas
 * @property double $horas_panne
 * @property integer $operador_id
 * @property integer $validado
 * @property integer $validador_id
 */
class REquipoPropio extends CActiveRecord
{

	public $administrador_1;
	public $administrador_2;
	public $clave_admin_1;
	public $clave_admin_2;
	public $validador_nm;
	public $horas_panne;

	public $equipo;
	public $codigo;
	public $usuario;

	/**
	 * Returns the static model of the specified AR class.
	 * @return REquipoPropio the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getImagenValidado($id)
	{
		$report = REquipoPropio::model()->findByPk($id);
		if ($report != null) {
			if ($report->validado == 1)
				return Yii::app()->request->baseUrl . '/images/check.png';
			else if ($report->validado == 2)
				return Yii::app()->request->baseUrl . '/images/check2.png';
			else
				return Yii::app()->request->baseUrl . '/images/eliminar.png';
		}
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rEquipoPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, reporte, equipoPropio_id, horas, operador_id', 'required'),
			array('equipoPropio_id, operador_id', 'numerical', 'integerOnly' => true),
			array('horas_panne', 'numerical', 'integerOnly' => false),
			array('hInicial, hFinal, horas,horasGps,reporte', 'length', 'max' => 12),
			array('observaciones', 'safe'),
			array('reporte', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('validador_nm,equipo,usuario, codigo,fecha, reporte, observaciones, observaciones_obra,validado', 'safe', 'on' => 'search'),
		);
	}

	protected function gridDataColumn($data, $row)
	{
		return Tools::backFecha($data->fecha);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'equipos' => array(self::BELONGS_TO, 'EquipoPropio', 'equipoPropio_id'),
			'usuarios' => array(self::BELONGS_TO, 'Usuario', 'usuario_id'),
			'equipos2' => array(self::BELONGS_TO, 'EquipoPropio', 'equipoPropio_id'),
			'validador' => array(self::BELONGS_TO, 'Usuario', 'validador_id'),
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
			'observaciones' => 'Observaciones Maquinaria',
			'equipoPropio_id' => 'Equipo Propio',
			'hInicial' => 'Inicial',
			'hFinal' => 'Final',
			'horas' => 'Diario',
			'horasGps' => 'Horas GPS',
			'operador_id' => 'Operador',
			'validador_nm' => 'Validado Por',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('fecha', Tools::fixFecha($this->fecha), true);
		$criteria->compare('reporte', $this->reporte, true);
		$criteria->compare('observaciones', $this->observaciones, true);
		$criteria->compare('observaciones_obra', $this->observaciones_obra, true);
		if ($this->equipo != 'Seleccione un equipo') $criteria->compare('equipos.nombre', $this->equipo, true);
		$criteria->with = array('validador' => array('select' => 'validador.nombre'), 'equipos' => array('select' => 'equipos.nombre'), 'usuarios' => array('select' => 'usuarios.nombre'));
		$criteria->compare('equipos.codigo', $this->codigo, true);
		$criteria->compare('usuarios.nombre', $this->usuario, true);
		$criteria->compare('validador.nombre', $this->validador_nm, true);


		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id DESC',
				'attributes' => array(
					'equipo' => array(
						'asc' => 'equipos.nombre',
						'desc' => 'equipos.nombre DESC',
					),
					'usuario' => array(
						'asc' => 'usuarios.nombre',
						'desc' => 'usuarios.nombre DESC',
					),
					'validador_nm' => array(
						'asc' => 'validador.nombre',
						'desc' => 'validador.nombre DESC',
					),
					'codigo' => array(
						'asc' => 'equipos.codigo',
						'desc' => 'equipos.codigo DESC',
					),
					'*',
				),
			),
		));
	}
}
