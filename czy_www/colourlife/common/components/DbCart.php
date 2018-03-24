<?php
/**
 * 购物车基本功能
 * 1) 将物品加入购物车
 * 2) 从购物车中删除物品
 * 3) 更新购物车物品信息 【+1/-1】
 * 4) 对购物车物品进行统计
 *    1. 总项目
2. 总数量
3. 总金额
 * 5) 对购物单项物品的数量及金额进行统计
 * 6) 清空购物车
 *
 */
class DbCart{
    //物品id及名称规则,调试信息控制
    private $debug = TRUE;
    //购物车
    private $_cart_contents = array();

    /**
     * 构造函数
     * @param array
     */
    public function __construct() {
        $cartList = CustomerGoodsCart::model()->findAllByAttributes(array('customer_id'=>Yii::app()->user->id));
        //是否第一次使用?
        if(!empty($cartList)) {
            $this->_cart_contents = $cartList;
        } else {
            $this->_cart_contents['cart_total'] = 0;
            $this->_cart_contents['total_items'] = 0;
        }
    }

    /**
     * 将物品加入购物车
     *
     * @access 	public
     * @param	array	一维或多维数组,必须包含键值名:
    good_id -> 物品ID标识,
    number -> 数量(quantity),
    good_price -> 单价(good_price),
    good_name -> 物品姓名
     * @return 	bool
     */
    public function insert($items = array(),$customer_id=null) {
        $customer_id = ($customer_id==null?Yii::app()->user->id:$customer_id);
        //输入物品参数异常
        if( ! is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage:cart_no_items_insert by insert \r\n\n");
            }
            return FALSE;
        }
        //物品参数处理
        $save_cart = FALSE;
        if(isset($items['good_id'])) {
            $cgCart = CustomerGoodsCart::model()->findByAttributes(
                array('customer_id'=>$customer_id,'good_id'=>$items['good_id'],'community_id'=>$items['community_id']));
            //如果商品不存在。那么插入购物车，否则数目增加传入数目
            if(empty($cgCart)){
                $items['customer_id'] = empty($items['customer_id'])?$customer_id:$items['customer_id'];
                if($this->_insert($items) === TRUE) {
                    $save_cart = TRUE;
                }
            }else{
                $updateItems = array('id'=>$cgCart['id'],
                    'number'=>($cgCart['number']+$items['number']),
                    'integral'=>0,//再次添加同一商品,积分抵扣需再次手动设置
                    'integral_price'=>0,//再次添加同一商品,积分抵扣的价格需再次手动设置
                );
                if($this->update($updateItems) === TRUE) {
                    $save_cart = TRUE;
                }
            }
        } else {
            foreach($items as $val) {
                if(is_array($val) AND isset($val['good_id'])) {
                    $cgCart = CustomerGoodsCart::model()->findByAttributes(
                        array('customer_id'=>$customer_id,'good_id'=>$val['good_id'],'community_id'=>$val['community_id']));
                    //如果商品不存在。那么插入购物车，否则数目增加传入数目
                    if(empty($cgCart)){
                        $val['customer_id'] = empty($val['customer_id'])?$customer_id:$val['customer_id'];
                        if($this->_insert($val) === TRUE) {
                            $save_cart = TRUE;
                        }
                    }else{
                        $updateItems = array('id'=>$cgCart['id'],
                            'number'=>($cgCart['number']+$val['number']),
                            'integral'=>0,//再次添加同一商品,积分抵扣需再次手动设置
                            'integral_price'=>0,//再次添加同一商品,积分抵扣的价格需再次手动设置
                        );
                        if($this->update($updateItems) === TRUE) {
                            $save_cart = TRUE;
                        }
                    }
                }
            }
        }

        //当插入成功后更新项目条数和总价
        if($save_cart) {
            $this->_save_cart();
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
    public function update($items = array()) {
        //输入物品参数异常
        if( !is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_no_items_insert by update \r\n\n");
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

        //当更新成功后更新项目条数和总价
        if($save_cart) {
            $this->_save_cart();
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 获取购物车物品总金额
     *
     * @return	int
     */
    public function total($community_id) {
        $this->_cart_contents['cart_total'] = 0;
        foreach($this->_cart_contents as $cart){
            if($cart['community_id']==$community_id){
                $this->_cart_contents['cart_total'] += ($cart['good_price'] * $cart['number'])-$cart['integral_price'];
            }
        }
        return $this->_cart_contents['cart_total'];
    }

    /**
     * @version 获取购物车商品种类数量
     * @return	int
     */
    public function total_items($community_id) {
        $this->_cart_contents['total_items'] = 0;
        foreach($this->_cart_contents as $cart){
            if($cart['community_id']==$community_id){
                $this->_cart_contents['total_items'] += 1;
            }
        }
        return $this->_cart_contents['total_items'];
    }
    
    /**
     * @version 获取购物车商品总数量
     * @return	int
     */
    public function total_cart_items($community_id) {
        if(empty($community_id)){
            return false;
        }
        $sql="select sum(number) as total from customer_goods_cart where community_id=".$community_id. " and customer_id=".Yii::app()->user->id." and shop_id=".Item::JD_SELL_ID;
        $queryArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $queryArr[0]['total'];
    }

    public function subtotal($id){
        $cgCart = CustomerGoodsCart::model()->findByPk($id);
        return sprintf('%.2f',$cgCart->good_price*$cgCart->number-$cgCart->integral_price);//只保留两位小数
    }

    /**
     * 获取购物车
     * @return	array
     */
    public function contents($community_id) {
        $cart_contents = array();
//        $cgCart = CustomerGoodsCart::model()->findAllByAttributes(
//            array('customer_id'=>Yii::app()->user->id,'community_id'=>$community_id));
        $cgCart = CustomerGoodsCart::model()->findAllByAttributes(
            array(),'customer_id=:customer_id and community_id=:community_id and shop_id!=:shop_id',array(':customer_id'=>Yii::app()->user->id,':community_id'=>$community_id,':shop_id'=>Item::JD_SELL_ID));
        foreach($cgCart as $cart){
            $cart_contents[$cart->shop_id][$cart->id] = $cart->attributes;
            $cart_contents[$cart->shop_id][$cart->id]['subtotal'] = ($cart->good_price * $cart->number-$cart->integral_price);
        }
        return $cart_contents;
    }
    /**
     * 获取拼团购物车
     * @return	array
     */
    public function pincontents($community_id) {
        $cart_contents = array();
//        $cgCart = CustomerGoodsCart::model()->findAllByAttributes(
//            array('customer_id'=>Yii::app()->user->id,'community_id'=>$community_id));
        $cgCart = CustomerGoodsCart::model()->findAllByAttributes(
            array(),'customer_id=:customer_id and community_id=:community_id and shop_id=:shop_id',array(':customer_id'=>Yii::app()->user->id,':community_id'=>$community_id,':shop_id'=>Item::PINTUAN));
        foreach($cgCart as $cart){
            $cart_contents[$cart->shop_id][$cart->id] = $cart->attributes;
            $cart_contents[$cart->shop_id][$cart->id]['subtotal'] = ($cart->good_price * $cart->number-$cart->integral_price);
        }
        return $cart_contents;
    }
    /**
     * 获取司庆的购物车
     * @return	array
     */
    public function siqingcontents($community_id) {
        $cart_contents = array();
        $cgCart = CustomerGoodsCart::model()->findAllByAttributes(
            array(),'customer_id=:customer_id and community_id=:community_id and shop_id=:shop_id',array(':customer_id'=>Yii::app()->user->id,':community_id'=>$community_id,':shop_id'=>Item::SIQING));
        foreach($cgCart as $cart){
            $cart_contents[$cart->shop_id][$cart->id] = $cart->attributes;
            $cart_contents[$cart->shop_id][$cart->id]['subtotal'] = ($cart->good_price * $cart->number-$cart->integral_price);
        }
        return $cart_contents;
    }
    /*
     * @version 获取京东购物车 
     * @param int $community_id 小区id
     * @return	array
     */
    public function jdcontents($community_id) {
        $cgCartOther = CustomerGoodsCart::model()->findAllByAttributes(
            array('customer_id'=>Yii::app()->user->id,'community_id'=>$community_id,'shop_id'=>Item::JD_SELL_ID));
        
        foreach($cgCartOther as $cartOther){
                JdApi::model()->updateCartProductPrice($cartOther->good_id);
        }

        $cart_contents = array();
        $cgCart = CustomerGoodsCart::model()->findAllByAttributes(
            array('customer_id'=>Yii::app()->user->id,'community_id'=>$community_id,'shop_id'=>Item::JD_SELL_ID));
        foreach($cgCart as $cart){
            $cart_contents[$cart->shop_id][$cart->id] = $cart->attributes;
            $cart_contents[$cart->shop_id][$cart->id]['subtotal'] = ($cart->good_price * $cart->number-$cart->integral_price);
        }
        return $cart_contents;
    }

    
    
    
    
    
    /**
     * 获取购物车物品options
     *
     * @param 	string
     * @return	array
     */
    /*public function options($id = '') {
        if($this->has_options($id)) {
            return $this->_cart_contents[$id]['options'];
        } else {
            return array();
        }
    }*/

    /**
     * 清空购物车
     *
     */
    public function destroy() {
        unset($this->_cart_contents);

        $this->_cart_contents['cart_total'] = 0;
        $this->_cart_contents['total_items'] = 0;

        //unset(Yii::app()->session['cart_contents']);
        CustomerGoodsCart::model()->deleteAllByAttributes(array('customer_id'=>Yii::app()->user->id));
    }

    /**
     * 清空购物车
     *
     */
    public function destroyByCommunity($community_id) {
        unset($this->_cart_contents);

        $this->_cart_contents['cart_total'] = 0;
        $this->_cart_contents['total_items'] = 0;

        //unset(Yii::app()->session['cart_contents']);
        //CustomerGoodsCart::model()->deleteAllByAttributes(array('customer_id'=>Yii::app()->user->id,'community_id'=>$community_id));
        CustomerGoodsCart::model()->deleteAllByAttributes(array(),'customer_id=:customer_id and community_id=:community_id and shop_id!=:shop_id',array(':customer_id'=>Yii::app()->user->id,':community_id'=>$community_id,':shop_id'=>Item::JD_SELL_ID));
    }
    /*
     * @version 根据条件清空购物车
     * @param int $community_id 小区id
     * @param array $good_id_arr 产品数组
     */
    public function destroyByCommunityAndGoods($community_id,$good_id_arr){
        
        if(empty($community_id) || empty($good_id_arr)){
            return false;
        }
        if(is_array($good_id_arr)){
            $good_id_str=implode(',', $good_id_arr);
        }else{
            $good_id_str=$good_id_arr;
        }
        
        $cgCart=CustomerGoodsCart::model()->deleteAllByAttributes(
            array('customer_id' => Yii::app()->user->id,'community_id'=>$community_id),  
             "good_id in (".$good_id_str.")"
        );
    }
    
    
    
    
    
    
    
    

    /**
     * 判断购物车物品是否有options选项
     *
     * @param	string
     * @return	bool
     */
    /*private function has_options($id = '') {
        if( ! isset($this->_cart_contents[$id]['options']) OR count($this->_cart_contents[$id]['options']) === 0) {
            return FALSE;
        }

        return TRUE;
    }*/

    /**
     * 插入数据
     *
     * @access	private
     * @param	array
     * @return	bool
     */
    private function _insert($items = array()) {
        //输入物品参数异常
        if( ! is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_no_data_insert by _insert \r\n\n");
            }
            return FALSE;
        }
        //ID自增长,不能赋值
        if(isset($items['id'])){
            unset($items['id']);
        }

        //用户不能为空,设置为当前用户
        if(empty($items['customer_id'])){
            $items['customer_id'] = Yii::app()->user->id;
        }

        //如果物品参数无效（无good_id/number/good_price/good_name）
        if( ! isset($items['number']) OR ! isset($items['good_price']) ) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data_invalid by _insert \r\n\n");
            }
            return FALSE;
        }

        //去除物品数量左零及非数字字符
        $items['number'] = trim(preg_replace('/([^0-9])/i', '', $items['number']));
        $items['number'] = trim(preg_replace('/^([0]+)/i', '', $items['number']));

        //如果物品数量为0，或非数字，则我们对购物车不做任何处理!
        if( ! is_numeric($items['number']) OR $items['number'] == 0) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data(number)_invalid by _insert \r\n\n");
            }
            return FALSE;
        }

        //去除物品单价左零及非数字（带小数点）字符
        $items['good_price'] = trim(preg_replace('/([^0-9\.])/i', '', $items['good_price']));
        $items['good_price'] = trim(preg_replace('/^([0]+)/i', '', $items['good_price']));

        //如果物品单价非数字
        if( ! is_numeric($items['good_price'])) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data(good_price)_invalid by _insert \r\n\n");
            }
            return FALSE;
        }

        //将item保存的属性
        $sgCart = new CustomerGoodsCart();
        $sgCart->attributes = $items;

        //加入物品到购物车
        if(!$sgCart->save()){
            if($this->debug === TRUE) {
                $this->_log("DbCart  ErrorMessage: 插入失败！ by _insert \r\n\n");
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 更新购物车物品信息（私有）
     *
     * @access 	private
     * @param	array
     * @return 	bool
     */
    private function _update($items = array()) {
        //输入物品参数异常
        if( ! isset($items['id']) OR ! isset($items['number']) ) {
            if($this->debug == TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data_invalid by _update \r\n\n");
            }
            return FALSE;
        }

        $cgCart = CustomerGoodsCart::model()->findByPk($items['id']);
        if(empty($cgCart)){
            if($this->debug == TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data_invalid by _update \r\n\n");
            }
            return FALSE;
        }

        //去除物品数量左零及非数字字符
        $items['number'] = preg_replace('/([^0-9])/i', '', $items['number']);
        $items['number'] = preg_replace('/^([0]+)/i', '', $items['number']);

        //如果物品数量非数字，对购物车不做任何处理!
        if($items['number']!=0 && ! is_numeric($items['number'])) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data(number)_invalid by _update \r\n\n");
            }
            return FALSE;
        }

        //如果购物车物品数量与需要更新的物品数量一致，则不需要更新
        if($cgCart['number'] == $items['number'] &&
            $cgCart['integral'] == $items['integral'] &&
            $cgCart['integral_price'] == $items['integral_price']) {
            if($this->debug === TRUE) {
                $this->_log("DbCart   ErrorMessage: cart_items_data(number)_equal by _update \r\n\n");
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
                $this->_log("DbCart   ErrorMessage: update_cart_items_data(number)_failure by _update \r\n\n");
            }
            return FALSE;
        }
    }

    /**
     * 保存购物车数据到session
     *
     * @access	private
     * @return	bool
     */
    private function _save_cart() {
        //首先清除购物车总物品种类及总金额
        unset($this->_cart_contents['total_items']);
        unset($this->_cart_contents['cart_total']);

        //然后遍历数组统计物品种类及总金额
        $total = 0;
        $cartList = CustomerGoodsCart::model()->findAllByAttributes(array('customer_id'=>Yii::app()->user->id));
        $this->_cart_contents = $cartList;
        foreach($cartList as $key => $val) {
            if( ! is_array($val) OR ! isset($val['good_price']) OR ! isset($val['number'])) {
                continue;
            }
            //商品小计=商品单价*数目-积分抵扣价格
            $amount = ($val['good_price'] * $val['number'])-$val['integral_price'];
            $total += $amount;

            //每种物品的总金额
            $this->_cart_contents[$key]['subtotal'] = $amount;
        }
        //设置购物车总物品种类及总金额
        $this->_cart_contents['total_items'] = count($this->_cart_contents);
        $this->_cart_contents['cart_total'] = $total;

        //如果购物车的元素个数少于等于2，说明购物车为空
        if(count($this->_cart_contents) <= 2) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 日志记录
     *
     * @access	private
     * @param	string
     * @return	bool
     */
    private function _log($msg) {
        return @file_put_contents('cart_err.log', $msg, FILE_APPEND);
    }
    
    /*
     * @version 根据商品购物车商品id删除商品
     * @param int id 购物车商品id
     */
    public function deleteProductById($id){
        
        $flag=CustomerGoodsCart::model()->deleteAllByAttributes(array('id'=>$id));
        if($flag){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * @version 获取直接购买物品信息
     * $param int $community_id 小区id
     * $param int $good_id 产品id
     */
    public function dancontent($community_id,$good_id_arr) {
        if(empty($community_id) || empty($good_id_arr)){
            return false;
        }
        $cart_contents = array();
        if(is_array($good_id_arr)){
            $good_id_str=implode(',', $good_id_arr);
        }else{
            $good_id_str=$good_id_arr;
        }
        
        $cgCart=CustomerGoodsCart::model()->findAllByAttributes(
            array('customer_id' => Yii::app()->user->id,'community_id'=>$community_id),  
             "good_id in (".$good_id_str.")"
        );
        foreach($cgCart as $cart){
            $cart_contents[$cart->shop_id][$cart->id] = $cart->attributes;
            $cart_contents[$cart->shop_id][$cart->id]['subtotal'] = ($cart->good_price * $cart->number-$cart->integral_price);
        }
        return $cart_contents;
    }
    /*
     * @version 拼装直接购买数组
     * @param  array $goodInfo 购物车数组
     */
    public function shopPingArr($goodInfo){
        if(empty($goodInfo)){
            return false;
        }
        $cart_contents = array();
        $cart_contents[$goodInfo['shop_id']][999999]['good_id']=$goodInfo['good_id'];
        $cart_contents[$goodInfo['shop_id']][999999]['number']=$goodInfo['number'];
        $cart_contents[$goodInfo['shop_id']][999999]['good_price']=$goodInfo['good_price'];
        $cart_contents[$goodInfo['shop_id']][999999]['good_name']=$goodInfo['good_name'];
        $cart_contents[$goodInfo['shop_id']][999999]['community_id']=$goodInfo['community_id'];
        $cart_contents[$goodInfo['shop_id']][999999]['shop_id']=$goodInfo['shop_id'];
        $cart_contents[$goodInfo['shop_id']][999999]['integral']=$goodInfo['integral'];
        $cart_contents[$goodInfo['shop_id']][999999]['integral_price']=$goodInfo['integral_price'];
        $cart_contents[$goodInfo['shop_id']][999999]['integral']=$goodInfo['integral'];
        $cart_contents[$goodInfo['shop_id']][999999]['subtotal'] = ($goodInfo['good_price'] * $goodInfo['number']-$goodInfo['integral_price']);
        return $cart_contents;
    }



}