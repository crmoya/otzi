<?php

/**
 * This is the model class for table "informe_gasto".
 *
 * The followings are the available columns in table 'informe_gasto':
 * @property integer $id
 * @property string $titulo
 * @property integer $numero
 * @property string $fecha_envio
 * @property string $fecha_cierre
 * @property string $nombre_empleado
 * @property string $rut_empleado
 * @property string $aprobado_por
 * @property integer $politica_id
 * @property string $politica
 * @property integer $estado
 * @property integer $total
 * @property integer $total_aprobado
 * @property integer $nro_gastos
 * @property integer $nro_gastos_aprobados
 * @property integer $nro_gastos_rechazados
 */
class InformeGasto extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'informe_gasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, numero, politica_id, estado, total, total_aprobado, nro_gastos, nro_gastos_aprobados, nro_gastos_rechazados', 'numerical', 'integerOnly'=>true),
			array('nombre_empleado, aprobado_por', 'length', 'max'=>300),
			array('rut_empleado', 'length', 'max'=>20),
			array('politica', 'length', 'max'=>200),
			array('titulo, fecha_envio, fecha_cierre', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, titulo, numero, fecha_envio, fecha_cierre, nombre_empleado, rut_empleado, aprobado_por, politica_id, politica, estado, total, total_aprobado, nro_gastos, nro_gastos_aprobados, nro_gastos_rechazados', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'titulo' => 'Titulo',
			'numero' => 'Numero',
			'fecha_envio' => 'Fecha Envio',
			'fecha_cierre' => 'Fecha Cierre',
			'nombre_empleado' => 'Nombre Empleado',
			'rut_empleado' => 'Rut Empleado',
			'aprobado_por' => 'Aprobado Por',
			'politica_id' => 'Politica',
			'politica' => 'Politica',
			'estado' => 'Estado',
			'total' => 'Total',
			'total_aprobado' => 'Total Aprobado',
			'nro_gastos' => 'Nro Gastos',
			'nro_gastos_aprobados' => 'Nro Gastos Aprobados',
			'nro_gastos_rechazados' => 'Nro Gastos Rechazados',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('titulo',$this->titulo,true);
		$criteria->compare('numero',$this->numero);
		$criteria->compare('fecha_envio',$this->fecha_envio,true);
		$criteria->compare('fecha_cierre',$this->fecha_cierre,true);
		$criteria->compare('nombre_empleado',$this->nombre_empleado,true);
		$criteria->compare('rut_empleado',$this->rut_empleado,true);
		$criteria->compare('aprobado_por',$this->aprobado_por,true);
		$criteria->compare('politica_id',$this->politica_id);
		$criteria->compare('politica',$this->politica,true);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('total',$this->total);
		$criteria->compare('total_aprobado',$this->total_aprobado);
		$criteria->compare('nro_gastos',$this->nro_gastos);
		$criteria->compare('nro_gastos_aprobados',$this->nro_gastos_aprobados);
		$criteria->compare('nro_gastos_rechazados',$this->nro_gastos_rechazados);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InformeGasto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
