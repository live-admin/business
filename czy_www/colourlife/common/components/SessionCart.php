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
class SessionCart{
    //物品id及名称规则,调试信息控制
    private $product_id_rule = '\.a-z0-9-_';  	//小写字母 | 数字 | ._-
    private $product_good_name_rule = '\.\:a-z0-9-_';//小写字母 | 数字 | ._-:
    private $debug = TRUE;

    //购物车
    private $_cart_contents = array();

    /**
     * 构造函数
     *
     * @param array
     */
    public function __construct() {
        //是否第一次使用?
        if(isset(Yii::app()->session['cart_contents'])) {
            $this->_cart_contents = Yii::app()->session['cart_contents'];
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
    public function insert($items = array()) {
        //输入物品参数异常
        if( ! is_array($items) OR count($items) == 0) {
            if($this->debug === TRUE) {
                $this->_log("SessionCart   ErrorMessage:Insert fails!Incoming parameter is incorrect! \r\n\n");
            }
            return FALSE;
        }

        //物品参数处理
        $save_cart = FALSE;
        if(isset($items['good_id'])) {
            $cart_id = $this->hasCategory($items);
            //如果商品不存在。那么插入购物车，否则数目增加传入数目
            if(empty($cart_id)){
                if($this->_insert($items) === TRUE) {
                    $save_cart = TRUE;
                }
            }else{
                $updateItems = array(
                    'id'=>$cart_id,
                    'number'=>($this->_cart_contents[$cart_id]['number']+$items['number']),
                    'integral'=>0,//再次添加同一商品,积分抵扣需再次手动设置
                    'integral_price'=>0,//再次添加同一商品,积分抵扣的价格需再次手动设置
                );
                if($this->update($updateItems) === TRUE) {
                    $save_cart = TRUE;
                }
            }
        } else {
            foreach($items as $val) {
                $cart_id = $this->hasCategory($val);;
                //如果商品不存在。那么插入购物车，否则数目增加传入数目
                if(empty($cart_id)){
                    if($this->_insert($val) === TRUE) {
                        $save_cart = TRUE;
                    }
                }else{
                    $updateItems = array(
                        'id'=>$cart_id,
                        'number'=>($this->_cart_contents[$cart_id]['number']+$val['number']),
                        'integral'=>0,//同上
                        'integral_price'=>0,
                    );
                    if($this->update($updateItems) === TRUE) {
                        $save_cart = TRUE;
                    }
                }
            }
        }
        //当插入成功后保存数据到session
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
                $this->_log("SessionCart.php  ErrorMessage:Update fails!Incoming parameter is incorrect! \r\n\n");
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

        //当更新成功后保存数据到session
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
                $this->_cart_contents['cart_total'] += $cart['subtotal'];
            }
        }
        return $this->_cart_contents['cart_total'];
    }

    /**
     * 获取购物车物品种类
     *
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

    public function subtotal($id){
        return $this->_cart_contents[$id]['subtotal'];
    }

    /**
     * 获取购物车
     *
     * @return	array
     */
    public function contents($community_id) {
        $list = array();
        foreach($this->_cart_contents as $cart){
            if($cart['community_id']==$community_id){
                $list[$cart['shop_id']][$cart['id']] = $cart;
            }
        }
        return $list;
    }

    /**
     * 获取所有小区的购物车数据
     * @return	array
     */
    public function contentsAll(){
        return $this->_cart_contents;
    }

    /**
     * 获取购物车物品options
     *
     * @param 	string
     * @return	array
     */
    public function options($id = '') {
        if($this->has_options($id)) {
            return $this->_cart_contents[$id]['options'];
        } else {
            return array();
        }
    }

    /**
     * 清空购物车
     *
     */
    public function destroy() {
        unset($this->_cart_contents);

        $this->_cart_contents['cart_total'] = 0;
        $this->_cart_contents['total_items'] = 0;

        unset(Yii::app()->session['cart_contents']);
    }

    /**
     * 判断购物车物品是否有options选项
     *
     * @param	string
     * @return	bool
     */
    private function has_options($id = '') {
        if( ! isset($this->_cart_contents[$id]['options']) OR count($this->_cart_contents[$id]['options']) === 0) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 根据商品ID与小区ID判断是否已记录在Session库中
     * @param array $item
     * @return bool
     */
    private function hasCategory(array $item)
    {
        foreach($this->_cart_contents as $cart)
        {
            if(isset($cart['good_id'],$cart['community_id']) && ($cart['good_id'] == $item['good_id'] && $cart['community_id'] == $item['community_id'])){
                return $cart['id'];
            }
        }
        return null;
    }

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
                $this->_log("SessionCart.php  ErrorMessage:cart no data by _insert \r\n\n");
            }
            return FALSE;
        }

        //如果物品参数无效（无good_id/number/good_price/good_name）
        if( ! isset($items['good_id']) OR ! isset($items['number']) OR ! isset($items['good_price']) OR ! isset($items['good_name'])) {
            if($this->debug === TRUE) {
                $this->_log("SessionCart.php  ErrorMessage:cart items data invalid by _insert \r\n\n");
            }
            return FALSE;
        }

        //去除物品数量左零及非数字字符
        $items['number'] = trim(preg_replace('/([^0-9])/i', '', $items['number']));
        $items['number'] = trim(preg_replace('/^([0]+)/i', '', $items['number']));

        //如果物品数量为0，或非数字，则我们对购物车不做任何处理!
        if( ! is_numeric($items['number']) OR $items['number'] == 0) {
            if($this->debug === TRUE) {
                $this->_log("SessionCart.php  ErrorMessage:cart items data(number) invalid bu _insert \r\n\n");
            }
            return FALSE;
        }

        //物品ID正则判断
        /*if( ! preg_match('/^['.$this->product_id_rule.']+$/i', $items['good_id'])) {
            if($this->debug === TRUE) {
                $this->_log("cart_items_data(good_id)_invalid");
            }
            return FALSE;
        }*/

        //物品名称正则判断
        /*if( ! preg_match('/^['.$this->product_good_name_rule.']+$/i', $items['good_name'])) {
            if($this->debug === TRUE) {
                $this->_log("cart_items_data(good_name)_invalid");
            }
            return FALSE;
        }*/

        //去除物品单价左零及非数字（带小数点）字符
        $items['good_price'] = trim(preg_replace('/([^0-9\.])/i', '', $items['good_price']));
        $items['good_price'] = trim(preg_replace('/^([0]+)/i', '', $items['good_price']));

        //如果物品单价非数字
        if( ! is_numeric($items['good_price'])) {
            if($this->debug === TRUE) {
                $this->_log("SessionCart.php  ErrorMessage:cart items data(good_price) invalid by _insert \r\n\n");
            }
            return FALSE;
        }

        //生成物品的唯一id
        if(isset($items['options']) AND isset($items['community_id']) AND count($items['options']) >0) {
            $id = md5($items['good_id'].$items['community_id'].implode('', $items['options']));
        } else {
            $id = md5($items['good_id'].$items['community_id']);
        }

        //加入物品到购物车
        unset($this->_cart_contents[$id]);
        $this->_cart_contents[$id]['id'] = $id;
        foreach($items as $key => $val) {
            $this->_cart_contents[$id][$key] = $val;
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
        if( ! isset($items['id']) OR ! isset($items['number']) OR ! isset($this->_cart_contents[$items['id']])) {
            if($this->debug == TRUE) {
                $this->_log("SessionCart.php  ErrorMessage: cart_items_data_invalid by _update \r\n\n");
            }
            return FALSE;
        }

        //去除物品数量左零及非数字字符
        $items['number'] = preg_replace('/([^0-9])/i', '', $items['number']);
        $items['number'] = preg_replace('/^([0]+)/i', '', $items['number']);

        //如果物品数量非数字，对购物车不做任何处理!
        if($items['number']!=0 && !is_numeric($items['number'])) {
            if($this->debug === TRUE) {
                $this->_log("cart_items_data(number)_invalid");
            }
            return FALSE;
        }

        //如果购物车物品数量与需要更新的物品数量一致，则不需要更新
        if($this->_cart_contents[$items['id']]['number'] == $items['number'] &&
            $this->_cart_contents[$items['id']]['integral'] == $items['integral'] &&
            $this->_cart_contents[$items['id']]['integral_price'] == $items['integral_price']) {
            if($this->debug === TRUE) {
                $this->_log("SessionCart.php  ErrorMessage:cart_items_data(number)_equal by _update \r\n\n");
            }
            return true;
        }

        //如果需要更新的物品数量等于0，表示不需要这件物品，从购物车种清除
        //否则修改购物车物品数量等于输入的物品数量
        if($items['number'] == 0) {
            unset($this->_cart_contents[$items['id']]);
        } else {
            $this->_cart_contents[$items['id']]['number'] = $items['number'];
            $this->_cart_contents[$items['id']]['integral'] = $items['integral'];//跟新使用的积分
            $this->_cart_contents[$items['id']]['integral_price'] = $items['integral_price'];//跟新抵扣金额
        }

        return TRUE;
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
        foreach($this->_cart_contents as $key => $val) {
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
            unset(Yii::app()->session['cart_contents']);
            return FALSE;
        }

        //保存购物车数据到session
        Yii::app()->session['cart_contents'] = $this->_cart_contents;
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
}

/*End of file cart.php*/
/*Location /htdocs/cart.php*/