<?php

/**
 * This is the model class for table "rCamionArrendado".
 *
 * The followings are the available columns in table 'rCamionArrendado':
 * @property integer $id
 * @property string $fecha
 * @property integer $reporte
 * @property string $observaciones
 * @property string $ordenCompra
 * @property integer $camionArrendado_id
 * @property integer $chofer_id
 * @property integer $validado
 * @property integer $validador_id
 */
class RCamionArrendado extends CActiveRecord
{
	public $kms;
	public $administrador_1;
	public $administrador_2;
	public $clave_admin_1;
	public $clave_admin_2;

	public $camion;
	public $usuario;
	public $coeficiente;
	public $validador_nm;

	public $horas;

	/**
	 * Returns the static model of the specified AR class.
	 * @return RCamionArrendado the static model class
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
		return 'rCamionArrendado';
	}

	public static function getImagenValidado($id)
	{
		$report = RCamionArrendado::model()->findByPk($id);
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, reporte, ordenCompra, camionArrendado_id,chofer_id', 'required'),
			array('camionArrendado_id,chofer_id', 'numerical', 'integerOnly' => true),
			array('kmInicial,kmFinal,kmGps', 'length', 'max' => 10),
			array('reporte', 'length', 'max' => 12),
			array('ordenCompra', 'length', 'max' => 45),
			array('observaciones', 'safe'),
			array('reporte', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('camion, validador_nm,usuario,fecha, reporte, observaciones, observaciones_obra,validado', 'safe', 'on' => 'search'),
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
			'usuarios' => array(self::BELONGS_TO, 'Usuario', 'usuario_id'),
			'validador' => array(self::BELONGS_TO, 'Usuario', 'validador_id'),
			'camiones' => array(self::BELONGS_TO, 'CamionArrendado', 'camionArrendado_id'),
		);
	}

	protected function gridDataColumn($data, $row)
	{
		return Tools::backFecha($data->fecha);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$enMillas = "";
		$kmsOMillas = "KMs recorridos";
		$distanciaGps = "KMs GPS";
		if(isset($this->camiones)){
			if($this->camiones->odometro_en_millas == 1){
				$enMillas = " (en Millas)";
				$kmsOMillas = "Millas recorridas";
				$distanciaGps = "Millas GPS";
			}
		}
		return array(
			'id' => 'ID',
			'fecha' => 'Fecha',
			'reporte' => 'Reporte',
			'observaciones' => 'Observaciones Camión',
			'ordenCompra' => 'Orden Compra o Contrato N°',
			'camionArrendado_id' => 'camión, camioneta, auto Arrendado',
			'chofer_id' => 'Chofer',
			'kmInicial' => 'Odómetro Inicial'.$enMillas,
			'kmFinal' => 'Odómetro Final'.$enMillas,
			'kmGps' => $distanciaGps,
			'total' => 'Total en $',
			'validador_nm' => 'Validado Por',
			'kms' => $kmsOMillas,
			'horometro_inicial' => 'Horómetro Inicial',
			'horometro_final' => 'Horómetro Final',
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

		$criteria->compare('fecha', Tools::fixFecha($this->fecha), true);
		$criteria->compare('reporte', $this->reporte, true);
		$criteria->compare('observaciones', $this->observaciones, true);
		$criteria->compare('observaciones_obra', $this->observaciones_obra, true);
		if ($this->camion != 'Seleccione un camión, camioneta, auto') $criteria->compare('camiones.nombre', $this->camion, true);
		$criteria->with = array('validador' => array('select' => 'validador.nombre'), 'camiones' => array('select' => 'camiones.nombre'), 'usuarios' => array('select' => 'usuarios.nombre'),);
		$criteria->compare('validador.nombre', $this->validador_nm, true);
		$criteria->compare('usuarios.nombre', $this->usuario, true);
		$criteria->compare('camiones.nombre', $this->camion, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id DESC',
				'attributes' => array(
					'camion' => array(
						'asc' => 'camiones.nombre',
						'desc' => 'camiones.nombre DESC',
					),
					'usuario' => array(
						'asc' => 'usuarios.nombre',
						'desc' => 'usuarios.nombre DESC',
					),
					'validador_nm' => array(
						'asc' => 'validador.nombre',
						'desc' => 'validador.nombre DESC',
					),
					'*',
				),
			),
		));
	}
}
