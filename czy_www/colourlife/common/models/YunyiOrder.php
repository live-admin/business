<?php

/**
 * This is the model class for table "yunyi_order".
 *
 * The followings are the available columns in table 'yunyi_order':
 * @property integer $id
 * @property string $JyOrder
 * @property integer $OrderStatus
 * @property integer $UserId
 * @property integer $CategoryId
 * @property integer $VenderOrderDate
 * @property string $InitPrice
 * @property string $ProductName
 * @property string $Amount
 */
class YunyiOrder extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'yunyi_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('OrderStatus, UserId, CategoryId, VenderOrderDate', 'numerical', 'integerOnly'=>true),
			array('JyOrder, ProductName', 'length', 'max'=>255),
			array('InitPrice, Amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, JyOrder, OrderStatus, UserId, CategoryId, VenderOrderDate, InitPrice, ProductName, Amount', 'safe', 'on'=>'search'),
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
			'JyOrder' => 'Jy Order',
			'OrderStatus' => 'Order Status',
			'UserId' => 'User',
			'CategoryId' => 'Category',
			'VenderOrderDate' => 'Vender Order Date',
			'InitPrice' => 'Init Price',
			'ProductName' => 'Product Name',
			'Amount' => 'Amount',
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
		$criteria->compare('JyOrder',$this->JyOrder,true);
		$criteria->compare('OrderStatus',$this->OrderStatus);
		$criteria->compare('UserId',$this->UserId);
		$criteria->compare('CategoryId',$this->CategoryId);
		$criteria->compare('VenderOrderDate',$this->VenderOrderDate);
		$criteria->compare('InitPrice',$this->InitPrice,true);
		$criteria->compare('ProductName',$this->ProductName,true);
		$criteria->compare('Amount',$this->Amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/*检测云易达金额是否用完*/
	public function checkOver()
	{
		$model = Customer::model()->findByPk(2211273);//
		$amount=$model->balance;      //预存放总金额
//		$sum = Yii::app()->db->createCommand("SELECT SUM(sum) FROM red_packet WHERE from_type=69")->queryAll();
//		$sum = intval($sum[0]['SUM(sum)']);
		if($amount>0){
			return true;
		}else{
			return false;
		}
	}


	/*发放饭票给用户并更改状态*/
	public function sendMoney($receiverID , $amount  , $JyOrder ,$OrderStatus)
	{
		$type=1;
		$cmobile = 20000000001;
		$customerID = 2211273;
		$redPacked = new RedPacketCarry();
		$note = '解忧回收订单号'.$JyOrder.'饭票交易'.$amount.'元';

		$UpdateTime = time();
		$resultYun = Yii::app()->db->createCommand("update yunyi_order set OrderStatus='".$OrderStatus."' , Amount='".$amount."' , UpdateTime = '".$UpdateTime."' where JyOrder='".$JyOrder."'")->execute();
		if(!$resultYun){
			Yii::log("云易达发放饭票订单修改失败，JyOrder:{$JyOrder}，彩之云id：" . Yii::app()->user->id . "，地址：" ,
				CLogger::LEVEL_INFO, 'colourlife.core.Yunyida.Update');
		}
		$rebateResult = $redPacked->customerTransferAccounts($customerID,$receiverID,$amount,$type,$cmobile,$note,$isLimit=false);
		if($rebateResult['status']==1){
			return $this->notify($JyOrder,$OrderStatus=4);   //通知发放成功
		}else{
			return  $this->notify($JyOrder,$OrderStatus=2);   //通知发放失败
		}

	}

	/*发放失败或成功后通知云易达*/
	public function notify($JyOrder, $OrderStatus){
		$key =time().'colourLife';
		$url = "http://jdhs.jieyoushequ.com/index.php?r=csh/final-check&key=".$key;
		$param = array(
			'JyOrder' => $JyOrder,
			'OrderStatus' => $OrderStatus, //正常4，失败2
		);
		$result = Yii::app()->curl->post($url,$param);
		$result = json_decode($result);
		$UpdateTime = time();
		$status = $OrderStatus == 2?2:5;
		if($result->res == 'ok'){
			$resultYun = Yii::app()->db->createCommand("update yunyi_order set OrderStatus='".$status."' , UpdateTime = '".$UpdateTime."' where JyOrder='".$JyOrder."'")->execute();
			Yii::log("云易达发放饭票通知成功，JyOrder:{$JyOrder}，彩之云id：" . Yii::app()->user->id . "，地址：" . $url,
				CLogger::LEVEL_INFO, 'colourlife.core.Yunyida.Notify');
			return true;
		}else{
			$resultYun = Yii::app()->db->createCommand("update yunyi_order set OrderStatus='".$status."' , UpdateTime = '".$UpdateTime."' where JyOrder='".$JyOrder."'")->execute();
			Yii::log("云易达发放饭票通知失败，JyOrder:{$JyOrder}，彩之云id：" . Yii::app()->user->id . "，地址：" . $url,
				CLogger::LEVEL_INFO, 'colourlife.core.Yunyida.Notify');
			return false;
		}
	}


	/*判断该订单是否发过饭票*/
	public function isSend($JyOrder, $amount)
	{
		$note = '解忧回收订单号'.$JyOrder.'饭票交易'.$amount.'元';
		$redPacketCarry = RedPacketCarry::model();
		$count = $redPacketCarry->findByAttributes(array('note' => $note));
		if($count){
			return false;
		}else{
			return true;
		}
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YunyiOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
