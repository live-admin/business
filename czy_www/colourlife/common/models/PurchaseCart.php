<?php

/**
 * This is the model class for table "purchase_cart".
 *
 * The followings are the available columns in table 'purchase_cart':
 * @property integer $id
 * @property integer $good_id
 * @property integer $number
 * @property string $good_name
 * @property string $good_price
 * @property integer $community_id
 * @property integer $shop_name
 * @property integer $buyer_id
 * @property integer $city_id
 * @property integer $create_time
 */
class PurchaseCart extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '内部采购商品购物车';
    public $debug = true;
    public $purchaseSwitch;
    public $purchaseRate;
    public $switch;

    public function init()
    {
        $this->purchaseSwitch = F::getConfig('integralSwitch', 'purchaseSwitch');//采购积分开关
        $this->purchaseRate = F::getConfig('integralSwitch', 'purchaseRate');//采购积分兑换比例
        $this->switch = $this->purchaseSwitch && $this->purchaseRate ? true : false;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PurchaseCart the static model class
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
        return 'purchase_cart';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('good_id, number, good_name, good_price, shop_id, buyer_id', 'required'),
            array('good_id, number, city_id, buyer_id, shop_id, create_time,integral', 'numerical', 'integerOnly'=>true),
            array('good_name', 'length', 'max'=>200),
            array('good_price,integral_price', 'length', 'max'=>10),
            array('id, good_id, city_id,integral,integral_price,number, good_name, good_price, shop_id, buyer_id, create_time', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'goodInfo'=>array(self::BELONGS_TO, 'PurchaseGoods', 'good_id'),
            'buyerInfo'=>array(self::BELONGS_TO,'Employee','buyer_id'),
            'shopInfo'=>array(self::BELONGS_TO,'Shop','shop_id'),
            'city'=>array(self::BELONGS_TO,'Region','city_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'good_id' => '商品ID',
            'number' => '商品数量',
            'good_name' => '商品名称',
            'good_price' => '商品单价',
            'shop_id' => '商家名称',
            'buyer_id' => '购买人',
            'create_time' => '添加时间',
            'integral' => '抵扣积分',
            'integral_price' => '积分抵扣的金额',
            'city_id' => '城市',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('good_id',$this->good_id);
        $criteria->compare('number',$this->number);
        $criteria->compare('good_name',$this->good_name,true);
        $criteria->compare('good_price',$this->good_price,true);
        $criteria->compare('shop_id',$this->shop_id);
        $criteria->compare('buyer_id',$this->buyer_id);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('city_id',$this->city_id);
        $criteria->compare('integral',$this->integral);
        $criteria->compare('integral_price',$this->integral_price);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    /**
     * 加入购物车功能
     * @param	array(goods_id,number,good_price,good_name,buyer_id,integral,integral_price)	一维或多维数组,必须包含键值名:
     * @return 	bool
     */
    public function addCart($items = array()) {
        $buyer_id = (isset($items['buyer_id'])?$$items['buyer_id']:Yii::app()->user->id);
        if( ! is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("加入购物车失败!ErrorMessage:输入商品参数异常".json_encode($items));
            }
            return FALSE;
        }
        //物品参数处理
        $save_cart = FALSE;
        if(isset($items['good_id'])) {
            $cgCart = PurchaseCart::model()->findByAttributes(
                array('buyer_id'=>$buyer_id,'good_id'=>$items['good_id'],'city_id'=>$items['city_id']));
            //如果商品不存在。那么插入购物车，否则数目增加传入数目
            if(empty($cgCart)){
                $items['buyer_id'] = empty($items['buyer_id'])?$buyer_id:$items['buyer_id'];
                if($this->_insert($items) === TRUE) {
                    $save_cart = TRUE;
                }
            }else{
                $updateItems = array('id'=>$cgCart['id'],
                    'number'=>($cgCart['number']+$items['number']),
                    'integral'=>($cgCart['integral']+$items['integral']),
                    'integral_price'=>($cgCart['integral_price']+$items['integral_price'])
                );
                if($this->updateCart($updateItems) === TRUE) {
                    $save_cart = TRUE;
                }
            }
        } else {
            foreach($items as $val) {
                if(is_array($val) AND isset($val['good_id'])) {
                    $cgCart = PurchaseCart::model()->findByAttributes(
                        array('buyer_id'=>$buyer_id,'good_id'=>$val['good_id'],'city_id'=>$val['city_id']));
                    //如果商品不存在。那么插入购物车，否则数目增加传入数目
                    if(empty($cgCart)){
                        $val['buyer_id'] = empty($val['buyer_id'])?$buyer_id:$val['buyer_id'];
                        if($this->_insert($val) === TRUE) {
                            $save_cart = TRUE;
                        }
                    }else{
                        /*$updateItems = array('id'=>$cgCart['id'],
                            'number'=>($cgCart['number']+$val['number']),
                        );*/
                        $val['id'] = $cgCart['id'];
                        $val['number'] += (int)$cgCart['number'];
                        if($this->updateCart($val) === TRUE) {
                            $save_cart = TRUE;
                        }
                    }
                }
            }
        }

        if($save_cart) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 更新购物车物品信息
     *
     * @access	public
     * @param	array
     * @return	bool
     */
    public function updateCart($items = array()) {
        //输入物品参数异常
        if( !is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("更新购物车失败!ErrorMessage:输入商品参数异常".json_encode($items));
            }
            return FALSE;
        }
        //物品参数处理
        $save_cart = FALSE;
        if(isset($items['id']) AND isset($items['number'])) {
            if($this->_update($items) === TRUE) {
                $save_cart = TRUE;
            }
        } else {
            foreach($items as $val) {
                if(is_array($val) AND isset($val['id']) AND isset($val['number'])) {
                    if($this->_update($val) === TRUE) {
                        $save_cart = TRUE;
                    }
                }
            }
        }

        if($save_cart) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 获取买家购物车物品总金额
     * @return	float
     */
    public function total() {
        $cart_contents = PurchaseCart::model()->findAllByAttributes(array('buyer_id'=>Yii::app()->user->id));
        $cart_total = 0;
        foreach($cart_contents as $cart){
            //判断是否开启开关   且使用了积分
            $total = true === $this->switch ? $cart['good_price'] * $cart['number'] - sprintf('%.2f', $cart['integral'] / $this->purchaseRate) : $cart['good_price'] * $cart['number'];
            $cart_total += $total;
        }

        return sprintf('%.2f',$cart_total);
    }

    /**
     * 获取买家购物车物品总金额(包含积分抵扣的价格)
     * @return	float
     */
    public function getTotalMoney() {
        $cart_contents = PurchaseCart::model()->findAllByAttributes(array('buyer_id'=>Yii::app()->user->id));
        $cart_total = 0;
        foreach($cart_contents as $cart){
            $total =$cart['good_price'] * $cart['number'];
            $cart_total += $total;
        }
        return sprintf('%.2f',$cart_total);
    }

    /**
     * 获取买家购物车物品种类数目
     * @return	int
     */
    public function total_items() {
        $total_items = self::model()->countByAttributes(array('buyer_id'=>Yii::app()->user->id));
        return $total_items;
    }

    //获得买家购物车内单个商品的小计金额
    public function subtotal($id){
        $cgCart = self::model()->findByPk($id);
        return sprintf('%.2f',$cgCart->good_price*$cgCart->number);//只保留两位小数
    }

    /**
     * 获取买家购物车对象
     * @return	array
     */
    public function contents() {
        $cart_contents = array();
        $cgCart = PurchaseCart::model()->findAllByAttributes(
            array('buyer_id'=>Yii::app()->user->id));
        foreach($cgCart as $cart){
            $cart_contents[$cart->shop_id][$cart->id] = $cart->attributes;
            $cart_contents[$cart->shop_id][$cart->id]['subtotal'] = sprintf('%.2f',($cart->good_price * $cart->number));
        }
        return $cart_contents;
    }

    /**
     * 获取买家没有城市购物车对象
     * @return	array
     */
    public function noCityContents() {
        $cart_contents = array();
        $sql = 'SELECT *, SUM(number) AS newNumber ,SUM(integral) as newIntegral
               FROM purchase_cart WHERE buyer_id="'.Yii::app()->user->id.'" GROUP BY good_id';
        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($rows as $cart){
            $cart_contents[$cart['shop_id']][$cart['good_id']] = $cart;
            $cart_contents[$cart['shop_id']][$cart['good_id']]['number'] = $cart['newNumber'];
            $cart_contents[$cart['shop_id']][$cart['good_id']]['integral'] = $cart['newIntegral'];
            $cart_contents[$cart['shop_id']][$cart['good_id']]['subtotal'] = sprintf('%.2f',($cart['good_price'] * $cart['newNumber']));
        }
        return $cart_contents;
    }

    /**
     * 清空购物车
     */
    public function destroy() {
        self::model()->deleteAllByAttributes(array('buyer_id'=>Yii::app()->user->id));
    }

    /**
     * 插入数据
     *
     * @access	private
     * @param	array
     * @return	bool
     */
    private function _insert($items = array()) {
        if( ! is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("加入购物车失败!ErrorMessage:输入商品参数异常".json_encode($items));
            }
            return FALSE;
        }
        //ID自增长,不能赋值
        if(isset($items['id'])){
            unset($items['id']);
        }

        //用户不能为空,设置为当前用户
        if(empty($items['buyer_id'])){
            $items['buyer_id'] = Yii::app()->user->id;
        }

        //如果物品参数无效（无good_id/number/good_price/good_name）
        if( ! isset($items['number']) OR ! isset($items['good_price']) ) {
            if($this->debug === TRUE) {
                $this->_log("加入购物车失败!商品参数异常:商品数目/价格必填！");
            }
            return FALSE;
        }

        //去除物品数量左零及非数字字符
        $items['number'] = trim(preg_replace('/([^0-9])/i', '', $items['number']));
        $items['number'] = trim(preg_replace('/^([0]+)/i', '', $items['number']));

        //如果物品数量为0，或非数字，则我们对购物车不做任何处理!
        if( ! is_numeric($items['number']) OR $items['number'] == 0) {
            if($this->debug === TRUE) {
                $this->_log("加入购物车失败!商品参数异常:商品数目必须大于零！");
            }
            return FALSE;
        }

        //去除物品单价左零及非数字（带小数点）字符
        $items['good_price'] = trim(preg_replace('/([^0-9\.])/i', '', $items['good_price']));
        $items['good_price'] = trim(preg_replace('/^([0]+)/i', '', $items['good_price']));

        //如果物品单价非数字
        if( ! is_numeric($items['good_price'])) {
            if($this->debug === TRUE) {
                $this->_log("加入购物车失败!商品参数异常:商品价格必须为数字！");
            }
            return FALSE;
        }

        //将item保存的属性
        $sgCart = new self();
        $sgCart->attributes = $items;

        //加入物品到购物车
        if(!$sgCart->save()){
            if($this->debug === TRUE) {
                $this->_log("加入购物车失败!保存数据未成功！".json_encode($sgCart->getErrors()));
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 更新购物车物品信息（私有）
     * @access 	private
     * @param	array
     * @return 	bool
     */
    private function _update($items = array()) {
        //输入物品参数异常
        if( ! isset($items['id']) OR ! isset($items['number']) ) {
            if($this->debug == TRUE) {
                $this->_log("更新购物车失败！传入参数异常.".json_encode($items));
            }
            return FALSE;
        }

        $cgCart = self::model()->findByPk($items['id']);
        if(empty($cgCart)){
            if($this->debug == TRUE) {
                $this->_log("更新购物车失败！要更新的购物车ID({$items['id']})不存在！");
            }
            return FALSE;
        }

        //去除物品数量左零及非数字字符
        $items['number'] = preg_replace('/([^0-9])/i', '', $items['number']);
        $items['number'] = preg_replace('/^([0]+)/i', '', $items['number']);

        //如果物品数量非数字，对购物车不做任何处理!
        if($items['number']!=0 && ! is_numeric($items['number'])) {
            if($this->debug === TRUE) {
                $this->_log("更新购物车失败！商品数目必须为数字！");
            }
            return FALSE;
        }

        //如果购物车物品数量与需要更新的物品数量一致，则不需要更新
        if($cgCart['number'] == $items['number'] &&
            $cgCart['integral'] == $items['integral'] &&
            $cgCart['integral_price'] == $items['integral_price']) {
            if($this->debug === TRUE) {
                $this->_log("更新购物车失败！购物车内容未改变！");
            }
            return true;
        }

        //如果需要更新的物品数量等于0，表示不需要这件物品，从购物车种清除
        //否则修改购物车物品数量等于输入的物品数量
        if($items['number'] == 0) {
            $return = $cgCart->deleteByPk($items['id']);
        } else {
            $return = $cgCart->updateByPk($items['id'],array('number'=>$items['number'],
                'integral'=>$items['integral'],'integral_price'=>$items['integral_price']));
        }
        if($return){
            return TRUE;
        }else{
            if($this->debug === TRUE) {
                $this->_log("更新购物车失败！保存数据未成功。".json_encode($cgCart->getErrors()));
            }
            return FALSE;
        }
    }

    /**
     * 日志记录
     * @access	private
     * @param	string
     * @return	bool
     */
    private function _log($msg) {
        //echo $msg;exit;
        Yii::log($msg,CLogger::LEVEL_INFO, 'colourlife.shopBackend.PurchaseCart.log');
    }

    /**
     * @return array 返回订单及商品的集合,及错误信息
     * 根据商家拆分购物车
     */
    public function SplitCartByShop(){
        $return = array('result'=>array(),'error'=>"");
        //获得买家的购物车
        $cartList = self::model()->noCityContents();
        if(empty($cartList) or !is_array($cartList)){
            $return['error'] = "购物车为空，无法结算";
            return $return;
        }
        //合并商品结束
        $buyer_id = Yii::app()->user->id;
        $buyer = Employee::model()->findByPk($buyer_id);
        if(empty($buyer)){
            $return['error'] = "未知的购买用户！";
            return $return;
        }

        foreach($cartList as $shop_id=>$cartArr){
            if(empty($shop_id)){
                $return['error'] = "购物车存在异常商家，无法结算！";
                return $return;
            };

            $shop_subtotal = 0;//订单实付金额
            $shop_amount = 0;//订单总金额
            $ogrArr = array();//用来保存订单-商品关联表的数据
            foreach($cartArr as $cartInfo){
                if(empty($cartInfo['good_id'])){
                    $return['error'] = "购物车存在异常商品，无法结算！";
                    return $return;
                };

                $goodInfo = PurchaseGoods::model()->findByPk($cartInfo['good_id']);
                if(empty($goodInfo)){
                    $return['error'] = "购物车存在异常商品(未知的商品)，无法结算！";
                    return $return;
                }

                //商品总金额=商品单价*商品数目
                $integral = 0;
                if($cartInfo['integral_price'] > 0){
                    $integral = $cartInfo['integral'] / $cartInfo['integral_price'];
                    $integral=round($integral,2);
                }
                $good_subtotal =$cartInfo['good_price'] * $cartInfo['number'] - $integral;
                $good_subtotal=round($good_subtotal,2);
                $good_amount = $cartInfo['good_price'] * $cartInfo['number'];
                $shop_amount += $good_amount;
                $ogrArr[] = array(//构建订单商品关联表需要保存的数据
                    'goods_id'=>$cartInfo['good_id'],//商品ID
                    'name'=>$cartInfo['good_name'],//商品名称
                    'price'=>$cartInfo['good_price'],//商品单价
                    'count'=>$cartInfo['number'],//商品数目
                    'integral' => $cartInfo['integral'],//用来抵扣的积分
                    'integral_price' => $cartInfo['integral_price'],//积分抵扣金额
                    'bank_pay' => $good_subtotal,//实际应付金额
                    'amount'=>$good_amount,//总金额
                );
                $shop_subtotal += $good_subtotal;//商家(订单)总金额
            }

            $shopInfo = Shop::model()->findByPk($shop_id);
            $orderArr = array(
                'shop_id'=>$shop_id,
                'seller_id'=>$shop_id,//销售商ID=shop_id，刘总确认的，拆单按加盟商拆单
                'supplier_id'=>0,//商品的所有者(供应商).不展示
                'buyer_model'=>'Shop',//买家模型
                'seller_contact'=> $shopInfo->contact,//卖家联系人
                'seller_tel'=> $shopInfo->mobile,//卖家电话
                'red_packet_pay'=>0,//订单使用红包抵扣金额
                'amount'=>$shop_amount,//订单总金额(包括红包金额,但扣除了商品积分抵扣金额)
                'bank_pay'=>$shop_subtotal,//实际应该支付金额
            );

            $return['result'][] = array('param'=>$orderArr,'relation'=>$ogrArr);
        }
        return $return;
    }

    public function cleanCartIntegral()
    {
        $cart = $this->contents();
        if($cart){
            foreach($cart as $key => $model)
            {
                foreach($model as $attribute)
                {
                    $val = array(
                        'id' => $attribute['id'],
                        'integral' => 0,
                        'integral_price' => 0,
                        'number' => $attribute['number']
                    );
                    $this->_update($val);
                }
            }
        }
    }

    //购物车数量
    public function getCartNum()
    {
        return $this->count('buyer_id=:buyer_id',array(':buyer_id'=>Yii::app()->user->id));
    }

    //获取内部采购商品的图片
    public function getImgUrl(){
        if(isset($this->goodInfo)){
            return Yii::app()->ajaxUploadImage->getUrl($this->goodInfo->good_image);
        }
    }
}