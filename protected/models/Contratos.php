<?php

/**
 * This is the model class for table "contratos".
 *
 * The followings are the available columns in table 'contratos':
 * @property integer $id
 * @property string $nombre
 * @property string $fecha_inicio
 * @property integer $plazo
 * @property integer $monto_incial
 * @property integer $modificaciones_monto
 * @property integer $monto_actualizado
 * @property integer $creador_id
 * @property integer $modificador_id
 * @property string $observacion
 * @property integer $estados_contratos_id
 * @property string $codigo_safi
 * @property string $nombre_mandante
 * @property string $rut_mandante
 *
 * The followings are the available model relations:
 * @property EstadosContratos $estadosContratos
 * @property Usuarios $creador
 * @property Usuarios $modificador
 * @property FlujosProgramados[] $flujosProgramadoses
 * @property FlujosReales[] $flujosReales
 * @property Resoluciones[] $resoluciones
 */
class Contratos extends CActiveRecord
{
	
	const ESTADO_NUEVO = 1;
	const ESTADO_ADJUDICADO = 2;
	const ESTADO_CERRADO = 3;
	
	public $estado_contrato;
        public $tipo_reajuste;
        public $tipo_contrato;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contratos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function gridDataColumn($data,$row)
    {
     	return Tools::backFecha($data->fecha_inicio);   
	}  
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contratos';
	}

	public function getLibrosAdjuntos(){
		$adjuntos = AdjuntosLibros::model()->findAllByAttributes(array('contratos_id'=>$this->id));
		return $adjuntos;
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('nombre,rut_mandante,nombre_mandante,presupuesto_oficial,fecha_inicio, creador_id, modificador_id, estados_contratos_id', 'required'),
                    array('nombre','unique'),	
                    array('plazo, monto_inicial, modificaciones_monto, monto_actualizado, creador_id, modificador_id, estados_contratos_id', 'numerical', 'integerOnly'=>true),
                    array('nombre,nombre_mandante', 'length', 'max'=>200),
                    array('rut_mandante', 'length', 'max'=>12),
                    array('rut_mandante','es_rut'),	
                    array('observacion', 'safe'),
                    array('codigo_safi','length','max'=>30),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
                    array('id, nombre, fecha_inicio, plazo, monto_inicial, modificaciones_monto, monto_actualizado, creador_id, modificador_id, observacion,estado_contrato', 'safe', 'on'=>'search'),
            );
	}
	
        public function es_rut($attribute,$params){
            $s=1;
            $rut = str_replace('.','',$this->$attribute);
            $arr = split('-',$rut);
            if(count($arr) == 2){
                if(is_numeric($arr[0])){
                    $r = (int)$arr[0];
                    for($m=0;$r!=0;$r/=10)
                        $s=($s+$r%10*(9-$m++%6))%11;
                    if(chr($s?$s+47:75) != $arr[1]){
                        $this->addError($attribute, 'El dígito verificador no corresponde con el RUT.');
                    }
                }
                else{
                    $this->addError($attribute, 'El RUT no es válido. Debe ingresarlo con guión.'.$arr[0]);
                }
            }
            else{
                $this->addError($attribute, 'El RUT no es válido. Debe ingresar el RUT con guión y dígito verificador.');
            }
        }
        
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'garantiasases' => array(self::HAS_MANY, 'Garantias', 'contratos_id'),
			'estadosContratos' => array(self::BELONGS_TO, 'EstadosContratos', 'estados_contratos_id'),
                        'tiposReajustes' => array(self::BELONGS_TO, 'TiposReajustes', 'tipos_reajustes_id'),
                        'tiposContratos' => array(self::BELONGS_TO, 'TiposContratos', 'tipos_contratos_id'),
			'creador' => array(self::BELONGS_TO, 'Usuarios', 'creador_id'),
			'modificador' => array(self::BELONGS_TO, 'Usuarios', 'modificador_id'),
			'resoluciones' => array(self::HAS_MANY, 'Resoluciones', 'contratos_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Proyecto',
			'fecha_inicio' => 'Fecha Oferta Técnica o Cotización',
			'plazo' => 'Plazo',
			'monto_inicial' => 'Monto Inicial',
			'modificaciones_monto' => 'Modificaciones Monto',
			'monto_actualizado' => 'Monto Actualizado',
			'creador_id' => 'Creador',
			'modificador_id' => 'Modificador',
			'observacion' => 'Observación',
			'estados_contratos_id' => 'Estados Contratos',
			'file'=>'Archivo',
			'codigo_safi'=>'Código SAFI o N° de Cotización',
			'tipos_contratos_id'=>'Tipo de Contrato',
			'tipos_reajustes_id'=>'Tipo de Reajuste',
			'presupuesto_oficial'=>'Presupuesto Oficial con IVA o Monto Cotización con IVA',
			'valor_neto'=>'Valor Neto del Contrato',
			'estados_contratos_id'=>'Estado',
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

		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('t.nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('plazo',$this->plazo);
		$criteria->compare('monto_inicial',$this->monto_inicial);
		$criteria->compare('modificaciones_monto',$this->modificaciones_monto);
		$criteria->compare('monto_actualizado',$this->monto_actualizado);
		$criteria->compare('creador_id',$this->creador_id);
		$criteria->compare('modificador_id',$this->modificador_id);
		$criteria->compare('observacion',$this->observacion,true);
		
		$criteria->with = array('estadosContratos',);
		$criteria->compare('estadosContratos.nombre', $this->estado_contrato, true );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
		        'attributes'=>array(
		            'estado_contrato'=>array(
		                'asc'=>'estadosContratos.nombre',
		                'desc'=>'estadosContratos.nombre DESC',
		            ),
		            '*',
		        ),
				'defaultOrder'=>'fecha_inicio DESC',
		    ),
		));
	}
	
        public function searchAdmin()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
                
		$criteria->compare('id',$this->id);
                $criteriaEstado = new CDbCriteria();
                $criteriaEstado->compare('estados_contratos_id',Contratos::ESTADO_NUEVO,true);
                $criteriaEstado->compare('estados_contratos_id',Contratos::ESTADO_ADJUDICADO,true,'OR');
                $criteria->mergeWith($criteriaEstado,'AND');
                
		$criteria->compare('id',$this->id);
		$criteria->compare('t.nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('plazo',$this->plazo);
		$criteria->compare('monto_inicial',$this->monto_inicial);
		$criteria->compare('modificaciones_monto',$this->modificaciones_monto);
		$criteria->compare('monto_actualizado',$this->monto_actualizado);
		$criteria->compare('creador_id',$this->creador_id);
		$criteria->compare('modificador_id',$this->modificador_id);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->with = array('estadosContratos',);
		$criteria->compare('estadosContratos.nombre', $this->estado_contrato, true );

                //$criteria->condition = 'estados_contratos_id = 1 OR estados_contratos_id = 2';
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
		        'attributes'=>array(
		            'estado_contrato'=>array(
		                'asc'=>'estadosContratos.nombre',
		                'desc'=>'estadosContratos.nombre DESC',
		            ),
		            '*',
		        ),
				'defaultOrder'=>'fecha_inicio DESC',
		    ),
		));
	}
        
	public function searchAdjudicados()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id',$this->id);
		$criteria->compare('t.nombre',$this->nombre,true);
		$criteria->compare('fecha_inicio',Tools::fixFecha($this->fecha_inicio),true);
		$criteria->compare('plazo',$this->plazo);
		$criteria->compare('monto_inicial',$this->monto_inicial);
		$criteria->compare('modificaciones_monto',$this->modificaciones_monto);
		$criteria->compare('monto_actualizado',$this->monto_actualizado);
		$criteria->compare('creador_id',$this->creador_id);
		$criteria->compare('modificador_id',$this->modificador_id);
		$criteria->compare('observacion',$this->observacion,true);
	
		$criteria->with = array('estadosContratos',);
		$criteria->compare('estadosContratos.nombre', $this->estado_contrato, true );
	
		$criteria->compare('estados_contratos_id',Contratos::ESTADO_ADJUDICADO);
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'sort'=>array(
						'attributes'=>array(
								'estado_contrato'=>array(
										'asc'=>'estadosContratos.nombre',
										'desc'=>'estadosContratos.nombre DESC',
								),
								'*',
						),
						'defaultOrder'=>'fecha_inicio DESC',
				),
		));
	}
	
		
	public function calculaPlazo(){
		$resoluciones = Resoluciones::model()->findAllByAttributes(array('contratos_id'=>$this->id));
		$plazo = 0;
		foreach($resoluciones as $resolucion){
			$plazo += $resolucion->plazo;
		}
		return $plazo;
	}
	
	public function calculaMonto(){
		$resoluciones = Resoluciones::model()->findAllByAttributes(array('contratos_id'=>$this->id));
		$monto = 0;
		foreach($resoluciones as $resolucion){
			$monto_i = $resolucion->monto;
			$monto+=$monto_i;
		}
		return $monto;
	}
	
	public function listaContratosAdjudicados($estado){
		$contratos = Contratos::model()->findAllByAttributes(array('estados_contratos_id'=>$estado));
		return $contratos;
	}
	
	public function getResoluciones(){
		$resoluciones = Resoluciones::model()->findAllByAttributes(array('contratos_id'=>$this->id));
		return $resoluciones;
	}
	
	public function getAdjuntos(){
		$adjuntos = AdjuntosContratos::model()->findAllByAttributes(array('contratos_id'=>$this->id));
		return $adjuntos;
	}	
	
	public function getUltimaResolucion(){
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$sql = "
			select 	id
			from	resoluciones as r
			where	contratos_id = :contrato_id
			order by id DESC
			limit 1
		";		
		
		$command=$connection->createCommand($sql);
		$contrato_id = $this->id;
		$command->bindParam(":contrato_id",$contrato_id,PDO::PARAM_INT);
		$results=$command->queryAll(); 
		$id = -1;
		foreach($results AS $result){
		   $id = $result['id'];
		   break;
		}
		
		$connection->active=false;
		$command = null;
		$resolucion = Resoluciones::model()->findByPk($id);
		return $resolucion;
	}
	
	/** 
	 * Retrieves the list of Garantías associated to the current contract id
	 */
	public function getGarantias(){
		//echo "Searching with $contrato_id";
		$contract_id = $this->id;
		$garantias = Garantias::model()->findAllByAttributes(array('contratos_id'=>$contract_id));
		return $garantias;
	}
	
	public function getFlujosProgramados(){
		$contract_id = $this->id;
		$conditions = array('contratos_id'=>$contract_id);
		$criteria = new CDbCriteria(array('order'=>'id'));
		$resoluciones = Resoluciones::model()->findAllByAttributes($conditions, $criteria);
		$flujos = array();
		$i=0;
		foreach($resoluciones as $res){
			$flujosP = $res->getFlujosProgramados();
			foreach($flujosP as $fp){
				$flujos[$i]=$fp;
				$i++;
			}
		}
		return $flujos;
	}
	
	public function getFlujoProgramado($mes,$agno){
		$resoluciones = $this->getResoluciones();
		foreach($resoluciones as $resolucion){
			$flujo = FlujosProgramados::model()->findByAttributes(
				array('resoluciones_id'=>$resolucion->id,'mes'=>$mes,'agno'=>$agno)
			);
			if($flujo != null){
				return $flujo;
			}
		}	
		return null;
	}
	
}