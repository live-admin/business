<?php

/**
 * 序列号生成类
 *
 * The followings are the available columns in table 'comm_serial_number':
 * @property string $name 序列号名称，唯一主键
 * @property string $last_serial_number 最后一次生成的序列号
 * @property string $last_generat_time 最后一次生成序列号的时间
 */
class SerialNumber extends CActiveRecord
{
    
    /**
     * 产生下一个序列号
     * @param string $sn_name 序列号的名称，唯一主键
     * @param string $resetType 序列号重置方式. <br>
     *  'year' 表示序列号每年重新从 1 开始。 <br>
     *  'month' 表示序列号在每月重新从 1 开始 <br>
     *  'day' 表示序列号在每天重新从 1 开始 <br>
     *  'no'或者null 表示不重置序列号。序列号将无限递增
     * @return int 获取生成的序列号
     */
    public static function generateNextSerialNumber($sn_name, $resetType = 'no')
    {
        $conn = Yii::app()->db;
        $isInTrans = ($conn->currentTransaction == null ? false : true);
        $trans = null;
        if(!$isInTrans)
        {
            $trans = $conn->beginTransaction();
        }
        $model = SerialNumber::model()->findByPk($sn_name);        
        if($model == null)
        {
            $model = new SerialNumber();
            $model->name = $sn_name;
            $model->last_serial_number = 1;
            $model->last_generat_time = time();
        }
        else 
        {
            $time = time();            
            if(self::needReset($model->last_generat_time, $time, $resetType))
            {
                $model->last_serial_number = 1;           
            }
            else 
            {
                $model->last_serial_number += 1;
            }            
            $model->last_generat_time = $time;
        }        
        $model->save();        
        if(!$isInTrans)
        {
            $trans->commit();
        }
        return $model->last_serial_number;
    }
    
    /**
     * 格式化输出序列号
     * @param array $format 格式化序列号的参数数组 <br>
     * array( <br>
     *      array('sn', array("序列号名称", "序列号重置类型(year|month|day|no)" , 序列号站位宽度 )), //序列号 <br>
     *      array('date', "YmdHis"),         //格式化日期 <br>
     *      array('rnd' , array(min, max, width)),  //产生随机数 <br>
     *      array('str', ""),    //固定字符串
     * )
     * @return string 格式化输出的序列号字符串
     */
    public static function buildFormatSerialNumber($format)
    {
        $sn = "";
        foreach ($format as $v)
        {
            if(!is_array($v)) continue;
            switch (strtolower($v[0]))
            {
                case "str" : 
                    $sn .= $v[1];
                    break;
                case "sn" :
                    $sn_number = self::generateNextSerialNumber($v[1][0], $v[1][1]);
                    $sn .= str_pad($sn_number, $v[1][2], '0', STR_PAD_LEFT);                    
                    break;
                case "rnd":
                    $rnd = rand($v[1][0], $v[1][1]);
                    $sn .= str_pad($rnd, $v[1][2], '0', STR_PAD_LEFT);
                    break;
                case "date":
                    $sn .= date($v[1], time());
                    break;                    
            }
        }
        return $sn;
    }
    
    private static function needReset($last_time, $time, $resetType)
    {
        $l_year = date("Y", $last_time);
        $l_month = date("m", $last_time);
        $l_day = date("d", $last_time);
        
        $year = date("Y", $time);
        $month = date("m", $time);
        $day = date("d", $time);        
        
        switch ( strtolower($resetType) )
        {
            case "year" :
                return $l_year != $year;                    
            case "month" :
                return $l_month != $month || $l_year != $year;
            case "day" :
                return $l_day != $day || $l_month != $month || $l_year != $year;
            default :
                return false;
        }
    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'comm_serial_number';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>128),
			array('last_serial_number', 'length', 'max'=>20),
			array('last_generat_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, last_serial_number, last_generat_time', 'safe', 'on'=>'search'),
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
			'name' => '序列号键名',
			'next_serial_number' => '最后一次生成的序列号',
			'last_generat_time' => '最后一次生成序列号的时间',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('next_serial_number',$this->next_serial_number,true);
		$criteria->compare('last_generat_time',$this->last_generat_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SerialNumber the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
