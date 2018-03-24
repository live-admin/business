<?php

/**
 * This is the model class for table "proimg".
 *
 * The followings are the available columns in table 'proimg':
 * @property integer $id
 * @property integer $pid
 * @property string $url
 * @property integer $createtime
 */
class product extends CActiveRecord
{	

	public $modelName = '试试';
	public $BookImg;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'proimg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, createtime', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>255),
			array('BookImg', 'file','allowEmpty'=>true,  
'types'=>'jpg, gif, png',  'maxSize'=>1024*1024*1,'tooLarge'=>'The file was larger than 1MB. Please upload a smaller file.',), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, url, createtime', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'url' => 'Url',
			'createtime' => 'Createtime',
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
		$criteria->compare('pid',$this->pid);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('createtime',$this->createtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	//图片函数
    public function setImageInformation($image)
    {
        foreach($image as $file){
            $name=$file['name'];
            $arr=explode('.',$name);
            $ext=$arr[count($arr)-1];
            $root = "..".Yii::app()->request->baseUrl;//echo dirname(__FILE__)
            $path="/img/proimg/".date("YmdHis").mt_rand(1,9999).".".$ext;
            move_uploaded_file($file['tmp_name'],$root.$path);
			//写入数据库 按自己的需求更改
            $db = Yii::app()->db;
            $db->createCommand()->insert('{{proimg}}', array(
                'pid' => $this->id,
                'url'=>$path,
                //'createtime'=>date('Y-m-d H:i:s'),
                'createtime'=>time(),
            ));
        }
    }



}
