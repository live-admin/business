<?php

/**
 * @property integer $user_red_packet
 * Class BaseOrder
 */
class BaseOrder extends Order
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '订单';
	public $goods_id;
	public $count;
	public $sellerName;

	public $payment_name;
	public $c_name;
	public $branch_id;
	public $username;
	public $buyer_mobile;
	public $pay_sn;
	public $commounity;

	public $communityIds; //小区
	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sn,seller_id, supplier_id, buyer_model, buyer_id, payment_id, buyer_name, buyer_address,red_packet_pay,bank_pay,buyer_tel, buyer_postcode, seller_contact, seller_tel, income_pay_time, amount, status, rate', 'required', 'on' => 'create,update'),
			array('cwy_profit, am_profit', 'required', 'on' => 'profit'),
			array('seller_id, supplier_id, buyer_model, buyer_id, buyer_name, buyer_address,
                     buyer_tel,seller_contact, seller_tel, amount', 'required', 'on' => 'apiCreate'),
			array('seller_id, supplier_id, buyer_id, payment_id, create_time, income_pay_time, status', 'numerical', 'integerOnly' => true),
			array('buyer_model, buyer_tel, buyer_postcode, seller_tel', 'length', 'max' => 100),
			array('buyer_name,sn, seller_contact', 'length', 'max' => 255),
			array('create_ip', 'length', 'max' => 50),
			array('amount', 'length', 'max' => 10),
			array('amount,red_packet_pay,bank_pay', 'safe'),
//			ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,branch_id, seller_id, supplier_id, buyer_model, buyer_id, payment_id, buyer_name, buyer_address,
                     buyer_tel, buyer_postcode, comment, seller_contact, seller_tel, note, create_time, create_ip,
                     income_pay_time, amount, status,sellerName,region,buyer_mobile,customer_buyer_name,categoryByOrder,username,community,community_id,payment_name,category,c_name,goodsName,start_time,end_time,_supplierName,user_red_packet', 'safe'),
			array('id,branch_id, seller_id, supplier_id, buyer_model, buyer_id, payment_id, buyer_name, buyer_address,
                     buyer_tel, buyer_postcode, comment, seller_contact, seller_tel, note, create_time, create_ip, 
                     one_yuan_code,income_pay_time, amount, status,sellerName,region,buyer_mobile,categoryByOrder,customer_buyer_name,username,community,community_id,payment_name,category,c_name,goodsName,start_time,end_time,_supplierName,user_red_packet,pay_sn', 'safe', 'on' => 'search'),

			//取消订单前判断订单是否能取消
			//array('status', 'checkCanCancel', 'on' => 'cancel,disposal'),
			//快递公司和单号必填。。 在处理订单的时候
			array('delivery_express_name,delivery_express_sn', 'required', 'on' => 'disposal'),

			array('sn', 'required', 'on' => 'search_customer_order_by_supplier'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('seller_id', $this->seller_id);
		$criteria->compare('supplier_id', $this->supplier_id);
		$criteria->compare('buyer_model', $this->buyer_model, true);
		$criteria->compare('buyer_id', $this->buyer_id);
		$criteria->compare('payment_id', $this->payment_id);
		$criteria->compare('expense_pay_id', $this->expense_pay_id);
		$criteria->compare('buyer_name', $this->buyer_name, true);
		$criteria->compare('buyer_address', $this->buyer_address, true);
		$criteria->compare('buyer_tel', $this->buyer_tel, true);
		$criteria->compare('buyer_postcode', $this->buyer_postcode, true);
		$criteria->compare('comment', $this->comment, true);
		$criteria->compare('seller_contact', $this->seller_contact, true);
		$criteria->compare('seller_tel', $this->seller_tel, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('create_ip', $this->create_ip, true);
		$criteria->compare('income_pay_time', $this->income_pay_time);
		$criteria->compare('amount', $this->amount, true);
		$criteria->compare('status', $this->status);


		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time desc',
			)
		));
	}

	public function search_seller()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('buyer_model', 'shop', true);
		$criteria->compare('buyer_model', 'customer', true);
		$criteria->compare('status', $this->status);
		$criteria->compare('seller_id', Yii::app()->user->id);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time desc',
			)
		));
	}

	public function search_customer_order()
	{
		$criteria = new CDbCriteria;
		$criteria->with[] = 'customer_buyer';
		$criteria->compare("customer_buyer.name", $this->buyer_id, true);
		$criteria->compare("customer_buyer.mobile", $this->buyer_mobile, true);
		// $criteria->with[]="payment";
		$criteria->compare("`t`.payment_id", $this->payment_name);
		$criteria->compare('`t`.buyer_model', 'customer', true);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.seller_id', Yii::app()->user->id);
		$criteria->compare("`t`.sn", $this->sn, true);
		$criteria->compare("`t`.buyer_name", $this->buyer_name, true);
		$criteria->compare("`t`.buyer_tel", $this->buyer_tel, true);
		$criteria->compare("`t`.buyer_address", $this->buyer_address, true);

		if ($this->start_time != "") {
			$criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}

		if (!empty($this->categoryByOrder)) {
			$goodList = OrderGoodsRelation::model()->findAll();
			$goodsIds = array();
			foreach ($goodList as $gModel) {
				if ($gModel->good) {
					$categoryIds = GoodsCategory::model()->findByPk($this->categoryByOrder)->getChildrenIdsAndSelf();
					if (in_array($gModel->good->category_id, $categoryIds)) {
						$goodsIds[] = $gModel->good->id;
					}
				}
			}
			$criteria->addInCondition('t.id', $goodsIds);
		}

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time desc',
			)
		));
	}

	public function search_customer_order_by_supplier()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('buyer_model', 'customer', true);
		$criteria->compare('sn', empty($this->sn) ? 'null' : $this->sn);
		$criteria->compare('supplier_id', Yii::app()->user->id);
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time desc',
			)
		));
	}

	public function search_backend_customer_order()
	{
		$criteria = new CDbCriteria;

//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;
//        //选择的组织架构ID
//        if ($this->branch_id != '')
//            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//        else if (!empty($this->communityIds)) //如果有小区
//            $community_ids = $this->communityIds;
//        else if ($this->region != '') //如果有地区
//            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
//        else {
//            $community_ids = array();
//            foreach ($branchIds as $branchId) {
//                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
//                $community_ids = array_unique(array_merge($community_ids, $data));
//            }
//        }

		if (Yii::app()->user->getId() != 1) {
			//	    ICE接入
			//选择的组织架构ID
			if ($this->branch_id != '') {
				$community_ids = ICEBranch::model()->findByPk($this->branch_id)->ICEGetBranchAllCommunity();
			} else if (!empty($this->communityIds)) {
				$community_ids = $this->communityIds;
			} else if ($this->province_id) {
				if ($this->district_id) {
					$regionId = $this->district_id;
				} else if ($this->city_id) {
					$regionId = $this->city_id;
				} else if ($this->province_id) {
					$regionId = $this->province_id;
				} else {
					$regionId = 0;
				}
				$community_ids = ICERegion::model()->getRegionCommunity(
					$regionId,
					'id'
				);

//			$community_ids = ICERegion::model()->getRegionCommunity(
//				$this->province_id, $this->city_id, $this->district_id,
//				'id'
//			);

			} else {

				$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
				$community_ids = $employee->ICEGetOrgCommunity();
			}

			$criteria->addInCondition('`t`.community_id', $community_ids);
		}

		$criteria->with[] = "customer_buyer";
		$criteria->compare("customer_buyer.name", $this->customer_buyer_name, true);
		$criteria->compare('user_red_packet', $this->user_red_packet);
		if ($this->one_yuan_code) {
			$criteria->addCondition('one_yuan_code <>""');
		}
		/*if(1 == $this->user_red_packet){
			$criteria->addCondition('red_packet_pay > 0');
		}*/
		// $criteria->with[] = 'seller';
		// $criteria->compare("seller.name", $this->sellerName, true);
		//  var_dump($this->seller_id);exit;
		$criteria->with[] = "seller";//供应商
		$criteria->compare("seller.name", $this->seller_id, true);
		$criteria->compare('`t`.buyer_name', $this->buyer_name, true);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.buyer_model', 'customer', true);
		$criteria->compare('`t`.sn', $this->sn, true);

		$criteria->join = "left join `order_goods_relation` ogr on ogr.order_id=`t`.id";
		$criteria->compare("ogr.name", $this->goodsName, true);

		if ($this->start_time != "") {
			$criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}
		if (!empty($this->categoryByOrder)) {
			$goodList = OrderGoodsRelation::model()->findAll();
			$goodsIds = array();
			foreach ($goodList as $gModel) {
				if ($gModel->good) {
					$categoryIds = GoodsCategory::model()->findByPk($this->categoryByOrder)->getChildrenIdsAndSelf();
					if (in_array($gModel->good->category_id, $categoryIds)) {
						$goodsIds[] = $gModel->good->id;
					}
				}
			}
			$criteria->addInCondition('t.id', $goodsIds);
		}
		if ($this->pay_sn != "") {
			$pay = Pay::model()->getModel($this->pay_sn);
			if (!empty($pay)) {
				$pay_id = $pay->id;
				$criteria->compare("`pay_id`", $pay_id);
			} else {
				$criteria->compare("`pay_id`", "-1");
			}

		}
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time desc',
			)
		));
	}

	/**
	 * 物业后台
	 **/
	public function search_backend_seller_order()
	{
		$criteria = new CDbCriteria;
//		ICE 用下面的静态方法这个注释掉
//		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		//选择的组织架构ID
		$criteria->with[] = 'seller';

//		//选择的组织架构ID
//		if ($this->branch_id != '')
//			$criteria->addInCondition('seller.branch_id', Branch::model()->findByPk($this->branch_id)->getChildrenIdsAndSelf());
//		else //自己的组织架构的ID
//			$criteria->addInCondition('seller.branch_id', $employee->getBranchIds());

		//选择的组织架构ID
		if ($this->branch_id != '')
			$criteria->addInCondition('seller.branch_id', Branch::model()->findByPk($this->branch_id)->getChildrenIdsAndSelf());
		else //自己的组织架构的ID
//			$criteria->addInCondition('seller.branch_id', $employee->getBranchIds());
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
			$criteria->addInCondition('seller.branch_id', Employee::ICEgetOldBranchIds());


		$criteria->compare("seller.name", $this->sellerName, true);
		$criteria->compare('`t`.buyer_name', $this->buyer_name, true);

		//$criteria->with[] = "supplier";
		// var_dump($this->_supplierName);exit;
		if (!empty($this->_supplierName))
			$criteria->addInCondition("t.seller_id", $this->getSellerShopIds());

		$criteria->with[] = "community";


		if ($this->start_time != "") {
			$criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}

		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.buyer_model', 'shop', true);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare("`community`.name", $this->c_name, true);
		$criteria->join = "left join `order_goods_relation` ogr on ogr.order_id=`t`.id";
		// $criteria->join .= "left join   `goods` as gds on ogr.goods_id = gds.id ";
		$criteria->compare("ogr.name", $this->goodsName, true);

		// $criteria->compare("gds.name",)

		if (!empty($this->categoryByOrder)) {
			$goodList = OrderGoodsRelation::model()->findAll();
			$goodsIds = array();
			foreach ($goodList as $gModel) {
				if ($gModel->good) {
					$categoryIds = GoodsCategory::model()->findByPk($this->categoryByOrder)->getChildrenIdsAndSelf();
					if (in_array($gModel->good->category_id, $categoryIds)) {
						$goodsIds[] = $gModel->good->id;
					}
				}
			}
			$criteria->addInCondition('t.id', $goodsIds);
		}
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time desc',
			)
		));
	}

	public function search_supplier()
	{
		$criteria = new CDbCriteria;
		$criteria->with[] = 'customer_buyer';
		$criteria->compare("seller_buyer.name", $this->buyer_id, true);
		$criteria->compare("seller_buyer.mobile", $this->buyer_mobile, true);
		$criteria->compare("`t`.payment_id", $this->payment_name);
		$criteria->compare('`t`.buyer_model', 'Shop');
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.seller_id', Yii::app()->user->id);
		$criteria->compare("`t`.sn", $this->sn, true);
		$criteria->compare("`t`.buyer_name", $this->buyer_name, true);
		$criteria->compare("`t`.buyer_tel", $this->buyer_tel, true);
		$criteria->compare("`t`.buyer_address", $this->buyer_address, true);

		if ($this->start_time != "") {
			$criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}

		if (!empty($this->categoryByOrder)) {
			$goodList = OrderGoodsRelation::model()->findAll();
			$goodsIds = array();
			foreach ($goodList as $gModel) {
				if ($gModel->good) {
					$categoryIds = GoodsCategory::model()->findByPk($this->categoryByOrder)->getChildrenIdsAndSelf();
					if (in_array($gModel->good->category_id, $categoryIds)) {
						$goodsIds[] = $gModel->good->id;
					}
				}
			}
			$criteria->addInCondition('t.id', $goodsIds);
		}
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time desc',
			)
		));
	}

	/**
	 * 截取字符并hover显示全部字符串
	 * $str string  截取的字符串
	 * $len int 截取的长度
	 */
	public function getFullString($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}

	public function search_buyer()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('buyer_id', Yii::app()->user->id, true);
		$criteria->compare('status', $this->status);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time desc',
			)
		));
	}


	public function checkCanCancel($attribute, $params)
	{
		//只有处于已下单，未付款的状态才能取消订单
		if ($this->status != Item::ORDER_AWAITING_PAYMENT) {
			$this->addError('status', '订单当前状态不能取消,无法继续进行操作.');
			return false;
		}
	}

	public function search_backend_customer_order_export($currentPage, $pageSize)
	{
		$criteria = new CDbCriteria;

		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$branchIds = $employee->mergeBranch;
		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->region != '') //如果有地区
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
		else {
			$community_ids = array();
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}
		}

		$criteria->addInCondition('`t`.community_id', $community_ids);

		$criteria->with[] = "customer_buyer";
		$criteria->compare("customer_buyer.name", $this->customer_buyer_name, true);
		// $criteria->with[] = 'seller';
		// $criteria->compare("seller.name", $this->sellerName, true);
		$criteria->with[] = "supplier";
		$criteria->compare("supplier.name", $this->seller_id, true);
		$criteria->compare('`t`.buyer_name', $this->buyer_name, true);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.buyer_model', 'customer', true);
		$criteria->compare('`t`.sn', $this->sn, true);

		$criteria->join = "left join `order_goods_relation` ogr on ogr.order_id=`t`.id";
		$criteria->compare("ogr.name", $this->goodsName, true);
		if ($this->start_time != "") {
			$criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}
		if (!empty($this->categoryByOrder)) {
			$goodList = OrderGoodsRelation::model()->findAll();
			$goodsIds = array();
			foreach ($goodList as $gModel) {
				if ($gModel->good) {
					$categoryIds = GoodsCategory::model()->findByPk($this->categoryByOrder)->getChildrenIdsAndSelf();
					if (in_array($gModel->good->category_id, $categoryIds)) {
						$goodsIds[] = $gModel->good->id;
					}
				}
			}
			$criteria->addInCondition('t.id', $goodsIds);
		}

		$criteria->limit = $pageSize;
		$criteria->offset = ($currentPage - 1) * $pageSize;
		$criteria->order = "t.create_time desc";

		return $this->findAll($criteria);
	}

	public function search_backend_seller_order_export($currentPage, $pageSize)
	{
		$criteria = new CDbCriteria;
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		//选择的组织架构ID
		$criteria->with[] = 'seller';

		//选择的组织架构ID
		if ($this->branch_id != '')
			$criteria->addInCondition('seller.branch_id', Branch::model()->findByPk($this->branch_id)->getChildrenIdsAndSelf());
		else //自己的组织架构的ID
			$criteria->addInCondition('seller.branch_id', $employee->getBranchIds());

		$criteria->compare("seller.name", $this->sellerName, true);
		$criteria->compare('`t`.buyer_name', $this->buyer_name, true);
		$criteria->with[] = "supplier";
		$criteria->compare("supplier.name", $this->_supplierName, true);
		$criteria->with[] = "community";


		if ($this->start_time != "") {
			$criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.buyer_model', 'shop', true);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare("`community`.name", $this->c_name, true);
		$criteria->join = "left join `order_goods_relation` ogr on ogr.order_id=`t`.id";
		$criteria->compare("ogr.name", $this->goodsName, true);

		if (!empty($this->categoryByOrder)) {
			$goodList = OrderGoodsRelation::model()->findAll();
			$goodsIds = array();
			foreach ($goodList as $gModel) {
				if ($gModel->good) {
					$categoryIds = GoodsCategory::model()->findByPk($this->categoryByOrder)->getChildrenIdsAndSelf();
					if (in_array($gModel->good->category_id, $categoryIds)) {
						$goodsIds[] = $gModel->good->id;
					}
				}
			}
			$criteria->addInCondition('t.id', $goodsIds);
		}

		$criteria->limit = $pageSize;
		$criteria->offset = ($currentPage - 1) * $pageSize;
		$criteria->order = "t.create_time desc";

		return $this->findAll($criteria);
	}

	public function getSellerShopIds()
	{
		$shopArr = array();
		$cr = new CDbCriteria;
		$cr->select = 'id';
		$cr->compare('is_goods_supplier', '1');
		$cr->compare('state', Item::STATE_ON);
		$cr->compare('is_deleted', '0');
		$cr->compare('name', $this->_supplierName, true);
		$supllierShop = Shop::model()->findAll($cr);
		if (!empty($supllierShop))
			$shopArr = Chtml::listData($supllierShop, 'id', 'id');
		return $shopArr;
	}

	protected function ICEGetSearchRegionData($search = array())
	{
		return array(
			'province_id' => isset($search['province_id']) && $search['province_id']
				? $search['province_id'] : '',
			'city_id' => isset($search['city_id']) && $search['city_id']
				? $search['city_id'] : '',
			'district_id' => isset($search['district_id']) && $search['district_id']
				? $search['district_id'] : '',
		);
	}

	protected function ICEGetLinkageRegionDefaultValueForUpdate()
	{
		return array();
	}

	public function ICEGetLinkageRegionDefaultValueForSearch()
	{
		$searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
			? $_GET[__CLASS__] : array());

		$defaultValue = array();

		if ($searchRegion['province_id']) {
			$defaultValue[] = $searchRegion['province_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['city_id']) {
			$defaultValue[] = $searchRegion['city_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['district_id']) {
			$defaultValue[] = $searchRegion['district_id'];
		} else {
			return $defaultValue;
		}

		return $defaultValue;
	}

	public function ICEGetLinkageRegionDefaultValue()
	{
		$updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
		return $updateDefaults
			? $updateDefaults
			: $this->ICEGetLinkageRegionDefaultValueForSearch();
	}
}
