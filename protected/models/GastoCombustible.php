<?php

/**
 * This is the model class for table "gasto_completa".
 *
 * The followings are the available columns in table 'gasto_completa':
 * @property integer $id
 * @property string $retenido
 * @property string $cantidad
 * @property string $centro_costo_faena
 * @property string $departamento
 * @property string $faena
 * @property string $impuesto_especifico
 * @property string $iva
 * @property string $km_carguio
 * @property string $litros_combustible
 * @property string $monto_neto
 * @property string $nombre_quien_rinde
 * @property string $nro_documento
 * @property string $periodo_planilla
 * @property string $rut_proveedor
 * @property string $supervisor_combustible
 * @property string $tipo_documento
 * @property string $unidad
 * @property string $vehiculo_equipo
 * @property string $vehiculo_oficina_central
 * @property integer $gasto_id
 *
 * The followings are the available model relations:
 * @property Gasto $gasto
 */
class GastoCombustible extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha_inicio, fecha_fin, agruparPor,tipoCombustible_id,propiosOArrendados', 'safe', 'on'=>'search'),
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

		if(isset($this->tipoCombustible_id) && $this->tipoCombustible_id != ""){
			$criteria->addCondition('tipo_combustible = :combustible');
			$criteria->params[':combustible'] = $this->tipoCombustible_id;
		}

		if(isset($this->propiosOArrendados) && $this->propiosOArrendados != "TODOS"){
			if($this->propiosOArrendados == "CA" || $this->propiosOArrendados == "CP" || $this->propiosOArrendados == "EA" || $this->propiosOArrendados == "EP"){
				$criteria->addCondition('tipo_maquina = :tipo_maquina');
				$criteria->params[':tipo_maquina'] = $this->propiosOArrendados;
			}
			else if($this->propiosOArrendados == "C"){
				$criteria->addCondition("(tipo_maquina = 'CA' or tipo_maquina = 'CP')");
			}
			else if($this->propiosOArrendados == "E"){
				$criteria->addCondition("(tipo_maquina = 'EA' or tipo_maquina = 'EP')");
			}
		}


		$inicioAgrupacion = "	maquina,
								operador,
								centro_gestion,
								tipo_combustible,
								tipocombustible,
								sum(litros) as litros,
								sum(total) as total";
		$finAgrupacion = "		operador,maquina,centro_gestion,tipo_combustible,tipocombustible";

		if(isset($this->agruparPor) && $this->agruparPor != "NINGUNO"){
			if($this->agruparPor == "MAQUINA"){
				$inicioAgrupacion = "
				maquina,
				'' as operador,
				'' as centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "maquina,tipocombustible,tipo_combustible";
			}
			if($this->agruparPor == "OPERADOR"){
				$inicioAgrupacion = "
				'' as maquina,
				operador,
				'' as centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "operador,tipocombustible,tipo_combustible";
			}
			if($this->agruparPor == "CENTROGESTION"){
				$inicioAgrupacion = "
				'' as maquina,
				'' as operador,
				centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "centro_gestion,tipocombustible,tipo_combustible";
			}
			if($this->agruparPor == "CENTROMAQUINA"){
				$inicioAgrupacion = "
				maquina,
				'' as operador,
				centro_gestion,
				sum(litros) as litros,
				sum(total) as total,
				'CENTROMAQUINA' as id
				";
				$finAgrupacion = "maquina,centro_gestion,tipocombustible,tipo_combustible";
			}
			if($this->agruparPor == "CENTROOPERADOR"){
				$inicioAgrupacion = "
				'' as maquina,
				operador,
				centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "centro_gestion,operador,tipocombustible,tipo_combustible";
			}
			if($this->agruparPor == "OPERADORMAQUINA"){
				$inicioAgrupacion = "
				maquina,
				operador,
				'' as centro_gestion,
				sum(litros) as litros,
				sum(total) as total
				";
				$finAgrupacion = "operador,maquina,tipocombustible,tipo_combustible";
			}
		}

		$criteria->select = $inicioAgrupacion;
		$criteria->group = $finAgrupacion;

		return $criteria;
	}

	public $fecha_inicio;
	public $fecha_fin;
	public $propiosOArrendados;
	public $agruparPor;
	public $tipoCombustible_id;


	public function tableName()
	{
		return 'vgastocombustible';
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tipoCombustible' => array(self::BELONGS_TO, 'TipoCombustible', 'tipo_combustible'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'propiosOArrendados' => 'Propios / Arrendados',
			'tipoCombustible_id' => 'Combustible',
		);
	}

	public function getGastoCompleta(){
		$partes = explode("-",$this->id);;
		$id = (int)$partes[0];
		$tipo = $partes[1];
		$tipo_maquina = $partes[2];
		if($tipo == "RG"){
			return GastoCompleta::model()->findByAttributes(['id'=>$id]);
		}
		if($tipo == "R"){
			$report = null;
			$carga = null;
			$combustibleRG = null;
			if($tipo_maquina == "CP"){
				$report = RCamionPropio::model()->findByPk($id);
				if(isset($report)){
					$carga = CargaCombCamionPropio::model()->findByAttributes(['rCamionPropio_id'=>$report->id, 'rindegastos'=>1]);
					if(isset($carga)){
						$combustibleRG = CombustibleRindegasto::model()->findByAttributes(['carga_id'=>$carga->id, 'camionpropio_id'=>$report->camionPropio_id,'status'=>1]);
						if(isset($combustibleRG)){
							return GastoCompleta::model()->findByPk($combustibleRG->gasto_completa_id);
						}
					}
				}				
			}
			if($tipo_maquina == "CA"){
				$report = RCamionArrendado::model()->findByPk($id);
				if(isset($report)){
					$carga = CargaCombCamionArrendado::model()->findByAttributes(['rCamionArrendado_id'=>$report->id, 'rindegastos'=>1]);
					if(isset($carga)){
						$combustibleRG = CombustibleRindegasto::model()->findByAttributes(['carga_id'=>$carga->id, 'camionarrendado_id'=>$report->camionArrendado_id,'status'=>1]);
						if(isset($combustibleRG)){
							return GastoCompleta::model()->findByPk($combustibleRG->gasto_completa_id);
						}
					}
				}
			}
			if($tipo_maquina == "EP"){
				$report = REquipoPropio::model()->findByPk($id);
				if(isset($report)){
					$carga = CargaCombEquipoPropio::model()->findByAttributes(['rEquipoPropio_id'=>$report->id, 'rindegastos'=>1]);
					if(isset($carga)){
						$combustibleRG = CombustibleRindegasto::model()->findByAttributes(['carga_id'=>$carga->id, 'equipopropio_id'=>$report->equipoPropio_id,'status'=>1]);
						if(isset($combustibleRG)){
							return GastoCompleta::model()->findByPk($combustibleRG->gasto_completa_id);
						}
					}
				}
			}
			if($tipo_maquina == "EA"){
				$report = REquipoArrendado::model()->findByPk($id);
				if(isset($report)){
					$carga = CargaCombEquipoArrendado::model()->findByAttributes(['rEquipoArrendado_id'=>$report->id, 'rindegastos'=>1]);
					if(isset($carga)){
						$combustibleRG = CombustibleRindegasto::model()->findByAttributes(['carga_id'=>$carga->id, 'equipoarrendado_id'=>$report->equipoArrendado_id,'status'=>1]);
						if(isset($combustibleRG)){
							return GastoCompleta::model()->findByPk($combustibleRG->gasto_completa_id);
						}
					}
				}
			}
			
		}
		return null;
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
