<?php

/**
 * This is the model class for table "usuario".
 *
 * The followings are the available columns in table 'usuario':
 * @property integer $id
 * @property string $email
 * @property string $nombre
 * @property string $clave
 * @property string $rol
 * @property string $user
 */
class Usuario extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Usuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function findAllAdmin(){
            return $this->findAllByAttributes(array('rol'=>'administrador'));
        }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, clave, rol, user', 'required'),
			array('email, rol, user', 'length', 'max'=>50),
			array('email','email'),
			array('user','unique'),			
			array('nombre', 'length', 'max'=>200),
			array('clave', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, nombre, clave, rol, user', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'nombre' => 'Nombre',
			'clave' => 'Clave',
			'rol' => 'Rol',
			'user' => 'Nombre de Usuario',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('clave',$this->clave,true);
		$criteria->compare('rol',$this->rol,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('vigente',1);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}