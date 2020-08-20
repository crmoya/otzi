<?php

/**
 * This is the model class for table "gasto".
 *
 * The followings are the available columns in table 'gasto':
 * @property integer $id
 * @property string $supplier
 * @property string $issue_date
 * @property integer $net
 * @property integer $total
 * @property string $category
 * @property string $category_group
 * @property string $note
 *
 * The followings are the available model relations:
 * @property ExtraGasto[] $extraGastos
 * @property GastoImagen[] $gastoImagens
* @property InformeGasto $informeGasto
 */
class Gasto extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gasto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('net, total', 'numerical', 'integerOnly'=>true),
			array('supplier, issue_date, category, category_group, note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplier, issue_date, net, total, category, category_group, note, retenido, cantidad,centro_costo_faena,departamento,faena,impuesto_especifico,iva,km_carguio,litros_combustible,monto_neto,nombre_quien_rinde,nro_documento,periodo_planilla,rut_proveedor,supervisor_combustible,tipo_documento,unidad,vehiculo_equipo,vehiculo_oficina_central', 'safe', 'on'=>'search'),
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
			'extraGastos' => array(self::HAS_MANY, 'ExtraGasto', 'gasto_id'),
			'gastoImagens' => array(self::HAS_MANY, 'GastoImagen', 'gasto_id'),
			'informeGasto' => array(self::BELONGS_TO, 'InformeGasto', 'report_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supplier' => 'Supplier',
			'issue_date' => 'Issue Date',
			'net' => 'Net',
			'total' => 'Total',
			'category' => 'Category',
			'category_group' => 'Category Group',
			'note' => 'Note',
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
		$criteria->compare('supplier',$this->supplier,true);
		$criteria->compare('issue_date',$this->issue_date,true);
		$criteria->compare('net',$this->net);
		$criteria->compare('total',$this->total);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('category_group',$this->category_group,true);
		$criteria->compare('note',$this->note,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getImagen(){
		if(isset($this->gastoImagens)){
			if(count($this->gastoImagens)>0){
				if(isset($this->gastoImagens[0]->original)){
					return $this->gastoImagens[0]->original;
				}
				else if(isset($this->gastoImagens[0]->large)){
					return $this->gastoImagens[0]->large;
				}
				else if(isset($this->gastoImagens[0]->medium)){
					return $this->gastoImagens[0]->medium;
				}
				else if(isset($this->gastoImagens[0]->small)){
					return $this->gastoImagens[0]->small;
				}
			}
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gasto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
