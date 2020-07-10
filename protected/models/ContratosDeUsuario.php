<?php

/**
 * This is the model class for table "contratos_de_usuario".
 *
 * The followings are the available columns in table 'contratos_de_usuario':
 * @property integer $contratos_id
 * @property integer $id
 * @property string $nombre
 * @property string $fecha_inicio
 * @property string $observacion
 * @property integer $estados_contratos_id
 * @property string $estados_contratos_nombre
 * @property string $rut_mandante
 * @property string $nombre_mandante
 * @property integer $usuarios_id
 * 
 */
class ContratosDeUsuario extends CActiveRecord
{    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContratosDeUsuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contratos_de_usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, fecha_inicio, estados_contratos_nombre, usuarios_id', 'required'),
			array('contratos_id, id, estados_contratos_id, usuarios_id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>200),
			array('estados_contratos_nombre', 'length', 'max'=>100),
			array('observacion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('contratos_id,rut_mandante,nombre_mandante, id, nombre, fecha_inicio, observacion, estados_contratos_id, estados_contratos_nombre, usuarios_id', 'safe', 'on'=>'search'),
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
                    //'contratos' => array(self::HAS_ONE, 'Contratos', 'contratos_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'contratos_id' => 'Contratos',
			'id' => 'ID',
			'nombre' => 'Proyecto',
			'fecha_inicio' => 'Fecha de Oferta Técnica o Cotización',
			'observacion' => 'Observacion',
			'estados_contratos_id' => 'Estados Contratos',
			'estados_contratos_nombre' => 'Estado Contrato',
			'usuarios_id' => 'Usuarios',
                        'presupuesto_oficial'=>'Presupuesto Oficial con IVA o Monto Cotización con IVA',
                        'codigo_safi'=>'Código SAFI o N° de Cotización',
		);
	}

	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fecha_inicio);   
	}  
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	
	
	public function searchCerrados()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('estados_contratos_id',Contratos::ESTADO_CERRADO);
		$criteria->compare('estados_contratos_nombre',$this->estados_contratos_nombre,true);
		$criteria->compare('rut_mandante',$this->rut_mandante,true);
                $criteria->compare('nombre_mandante',$this->nombre_mandante,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'fecha_inicio DESC',
			),
		));
	}
	
	public function searchNoCerradosUsuarioActual()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('estados_contratos_id', Contratos::ESTADO_NUEVO, true, 'OR');
		$criteria->compare('estados_contratos_id', Contratos::ESTADO_ADJUDICADO, true, 'OR');
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('estados_contratos_id',$this->estados_contratos_id);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('usuarios_id',Yii::app()->user->id);
		$criteria->compare('estados_contratos_nombre',$this->estados_contratos_nombre,true);
		$criteria->compare('rut_mandante',$this->rut_mandante,true);
                $criteria->compare('nombre_mandante',$this->nombre_mandante,true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'fecha_inicio DESC',
			),
		));
	}	
	
	public function searchNuevosUsuarioActual()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('estados_contratos_id',$this->estados_contratos_id);
		$criteria->compare('estados_contratos_id',Contratos::ESTADO_NUEVO);
		$criteria->compare('estados_contratos_nombre',$this->estados_contratos_nombre,true);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('usuarios_id',Yii::app()->user->id);
		$criteria->compare('rut_mandante',$this->rut_mandante,true);
                $criteria->compare('nombre_mandante',$this->nombre_mandante,true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'fecha_inicio DESC',
			),
		));
	}
	
	public function searchAdjudicadosUsuarioActual()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('estados_contratos_id',$this->estados_contratos_id);
		$criteria->compare('estados_contratos_id',Contratos::ESTADO_ADJUDICADO);
		$criteria->compare('estados_contratos_nombre',$this->estados_contratos_nombre,true);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('usuarios_id',Yii::app()->user->id);
		$criteria->compare('rut_mandante',$this->rut_mandante,true);
                $criteria->compare('nombre_mandante',$this->nombre_mandante,true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'fecha_inicio DESC',
			),
		));
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('contratos_id',$this->contratos_id);
		$criteria->compare('id',$this->id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('estados_contratos_id',$this->estados_contratos_id);
		$criteria->compare('estados_contratos_nombre',$this->estados_contratos_nombre,true);
		$criteria->compare('usuarios_id',$this->usuarios_id);
                $criteria->compare('rut_mandante',$this->rut_mandante,true);
                $criteria->compare('nombre_mandante',$this->nombre_mandante,true);
		
                return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
					'defaultOrder'=>'fecha_inicio DESC',
			),
		));
	}
}