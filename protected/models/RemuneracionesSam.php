<?php

/**
 * This is the model class for table "remuneraciones_sam".
 *
 * The followings are the available columns in table 'remuneraciones_sam':
 * @property integer $id
 * @property string $tipo_equipo_camion
 * @property string $descripcion
 * @property integer $neto
 * @property string $guia
 * @property string $documento
 * @property double $cantidad
 * @property string $unidad
 * @property integer $faena_id
 * @property string $numero
 * @property string $nombre
 * @property string $fecha_rendicion
 * @property string $rut_rinde
 * @property string $cuenta
 * @property string $nombre_proveedor
 * @property string $rut_proveedor
 * @property string $observaciones
 * @property string $tipo_documento
 * @property integer $rindegastos
 * @property integer $equipoPropio_id
 * @property integer $equipoArrendado_id
 * @property integer $camionPropio_id
 * @property integer $camionArrendado_id
 * @property integer $chofer_id
 * @property integer $operador_id
 * @property integer $gasto_id
 *
 * The followings are the available model relations:
 * @property Camionarrendado $camionArrendado
 * @property Camionpropio $camionPropio
 * @property Chofer $chofer
 * @property Equipoarrendado $equipoArrendado
 * @property Equipopropio $equipoPropio
 * @property Faena $faena
 * @property Gasto $gasto
 * @property Operador $operador
 */
class RemuneracionesSam extends CActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'remuneraciones_sam';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipo_equipo_camion, descripcion, neto, rut_rinde, cuenta, nombre_proveedor, rut_proveedor, observaciones, tipo_documento, gasto_id', 'required'),
			array('neto, faena_id, rindegastos, equipoPropio_id, equipoArrendado_id, camionPropio_id, camionArrendado_id, chofer_id, operador_id, gasto_id', 'numerical', 'integerOnly' => true),
			array('cantidad', 'numerical'),
			array('tipo_equipo_camion, unidad', 'length', 'max' => 2),
			array('descripcion', 'length', 'max' => 200),
			array('guia, documento', 'length', 'max' => 45),
			array('numero, fecha_rendicion', 'length', 'max' => 20),
			array('nombre, nombre_proveedor', 'length', 'max' => 100),
			array('rut_rinde, rut_proveedor', 'length', 'max' => 15),
			array('tipo_documento', 'length', 'max' => 40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tipo_equipo_camion, descripcion, neto, guia, documento, cantidad, unidad, faena_id, numero, nombre, fecha_rendicion, rut_rinde, cuenta, nombre_proveedor, rut_proveedor, observaciones, tipo_documento, rindegastos, equipoPropio_id, equipoArrendado_id, camionPropio_id, camionArrendado_id, chofer_id, operador_id, gasto_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'camionArrendado' => array(self::BELONGS_TO, 'Camionarrendado', 'camionArrendado_id'),
			'camionPropio' => array(self::BELONGS_TO, 'Camionpropio', 'camionPropio_id'),
			'chofer' => array(self::BELONGS_TO, 'Chofer', 'chofer_id'),
			'equipoArrendado' => array(self::BELONGS_TO, 'Equipoarrendado', 'equipoArrendado_id'),
			'equipoPropio' => array(self::BELONGS_TO, 'Equipopropio', 'equipoPropio_id'),
			'faena' => array(self::BELONGS_TO, 'Faena', 'faena_id'),
			'gasto' => array(self::BELONGS_TO, 'Gasto', 'gasto_id'),
			'operador' => array(self::BELONGS_TO, 'Operador', 'operador_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'tipo_equipo_camion' => 'Tipo Equipo Camion',
			'descripcion' => 'Descripcion',
			'neto' => 'Neto',
			'guia' => 'Guia',
			'documento' => 'Documento',
			'cantidad' => 'Cantidad',
			'unidad' => 'Unidad',
			'faena_id' => 'Faena',
			'numero' => 'Numero',
			'nombre' => 'Nombre',
			'fecha_rendicion' => 'Fecha Rendicion',
			'rut_rinde' => 'Rut Rinde',
			'cuenta' => 'Cuenta',
			'nombre_proveedor' => 'Nombre Proveedor',
			'rut_proveedor' => 'Rut Proveedor',
			'observaciones' => 'Observaciones',
			'tipo_documento' => 'Tipo Documento',
			'rindegastos' => 'Rindegastos',
			'equipoPropio_id' => 'Equipo Propio',
			'equipoArrendado_id' => 'Equipo Arrendado',
			'camionPropio_id' => 'Camion Propio',
			'camionArrendado_id' => 'Camion Arrendado',
			'chofer_id' => 'Chofer',
			'operador_id' => 'Operador',
			'gasto_id' => 'Gasto',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('tipo_equipo_camion', $this->tipo_equipo_camion, true);
		$criteria->compare('descripcion', $this->descripcion, true);
		$criteria->compare('neto', $this->neto);
		$criteria->compare('guia', $this->guia, true);
		$criteria->compare('documento', $this->documento, true);
		$criteria->compare('cantidad', $this->cantidad);
		$criteria->compare('unidad', $this->unidad, true);
		$criteria->compare('faena_id', $this->faena_id);
		$criteria->compare('numero', $this->numero, true);
		$criteria->compare('nombre', $this->nombre, true);
		$criteria->compare('fecha_rendicion', $this->fecha_rendicion, true);
		$criteria->compare('rut_rinde', $this->rut_rinde, true);
		$criteria->compare('cuenta', $this->cuenta, true);
		$criteria->compare('nombre_proveedor', $this->nombre_proveedor, true);
		$criteria->compare('rut_proveedor', $this->rut_proveedor, true);
		$criteria->compare('observaciones', $this->observaciones, true);
		$criteria->compare('tipo_documento', $this->tipo_documento, true);
		$criteria->compare('rindegastos', $this->rindegastos);
		$criteria->compare('equipoPropio_id', $this->equipoPropio_id);
		$criteria->compare('equipoArrendado_id', $this->equipoArrendado_id);
		$criteria->compare('camionPropio_id', $this->camionPropio_id);
		$criteria->compare('camionArrendado_id', $this->camionArrendado_id);
		$criteria->compare('chofer_id', $this->chofer_id);
		$criteria->compare('operador_id', $this->operador_id);
		$criteria->compare('gasto_id', $this->gasto_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RemuneracionesSam the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
}
