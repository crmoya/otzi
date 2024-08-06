<?php

/**
 * This is the model class for table "rCamionPropio".
 *
 * The followings are the available columns in table 'rCamionPropio':
 * @property integer $id
 * @property string $fecha
 * @property integer $reporte
 * @property string $observaciones
 * @property double $horas_panne
 * @property integer $camionPropio_id
 * @property integer $chofer_id
 * @property string $horometro_inicial
 * @property string $horometro_final
 * @property string $iniPanne
 * @property string $finPanne
 * @property integer $validado
 * @property integer $validador_id
 */
class RCamionPropio extends CActiveRecord
{

	public $kms;
	
	public $administrador_1;
	public $administrador_2;
	public $clave_admin_1;
	public $clave_admin_2;
	public $horas_panne;

	public $camion;
	public $usuario;
	public $codigo;
	public $coeficiente;
	public $validador_nm;

	public $horas;

	/**
	 * Returns the static model of the specified AR class.
	 * @return RCamionPropio the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getImagenValidado($id)
	{
		$report = RCamionPropio::model()->findByPk($id);
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
		return 'rCamionPropio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, reporte, camionPropio_id,chofer_id', 'required'),
			array('camionPropio_id,chofer_id', 'numerical', 'integerOnly' => true),
			array('horas_panne', 'numerical', 'integerOnly' => false),
			array('camionPropio_id,kmInicial,kmFinal,kmGps', 'length', 'max' => 10),
			array('reporte', 'length', 'max' => 12),
			array('reporte','unique'),
			array('observaciones, iniPanne, finPanne', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('camion,validador_nm,usuario,codigo,fecha, reporte, observaciones_obra,observaciones,validado,horometro_inicial,horometro_final', 'safe', 'on' => 'search'),
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
			'camiones' => array(self::BELONGS_TO, 'CamionPropio', 'camionPropio_id'),
			'camiones2' => array(self::BELONGS_TO, 'CamionPropio', 'camionPropio_id'),
			'validador' => array(self::BELONGS_TO, 'Usuario', 'validador_id'),
		);
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
			}
		}
		return array(
			'id' => 'ID',
			'fecha' => 'Fecha',
			'reporte' => 'Reporte N°',
			'observaciones' => 'Observaciones Camión',
			'camionPropio_id' => 'camión, camioneta, auto Propio',
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

	protected function gridDataColumn($data, $row)
	{
		return Tools::backFecha($data->fecha);
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
		if ($this->camion != 'Seleccione un camión, camioneta, auto') $criteria->compare('camiones.nombre', $this->camion, true);
		$criteria->with = array('validador' => array('select' => 'validador.nombre'), 'camiones' => array('select' => 'camiones.nombre'), 'usuarios' => array('select' => 'usuarios.nombre'));
		$criteria->compare('usuarios.nombre', $this->usuario, true);
		$criteria->compare('validador.nombre', $this->validador_nm, true);
		$criteria->compare('camiones.codigo', $this->codigo, true);


		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id DESC',
				'attributes' => array(
					'camion' => array(
						'asc' => 'camiones.nombre',
						'desc' => 'camiones.nombre DESC',
					),
					'codigo' => array(
						'asc' => 'camiones.codigo',
						'desc' => 'camiones.codigo DESC',
					),
					'*',
					'usuario' => array(
						'asc' => 'usuarios.nombre',
						'desc' => 'usuarios.nombre DESC',
					),
					'validador_nm' => array(
						'asc' => 'validador.nombre',
						'desc' => 'validador.nombre DESC',
					),
				),
			),
		));
	}
}
