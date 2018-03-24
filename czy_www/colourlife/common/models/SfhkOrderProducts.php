<?php
/**
 * This is the model class for table "topic".
 *
 * The followings are the available columns in table 'topic':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $user_id
 * @property integer $community_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $cate_id
 * @property integer $favour_num
 * @property integer $comment_num
 * @property integer $status
 */
class SfhkOrderProducts extends CActiveRecord {

    public $modelName = '顺风订单-产品';
    public $startTime;
    public $endTime;
    public $fullname;
    public $mobile;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sfhk_order_products';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('order_start_time,order_id,product_name', 'length', 'max'=>100),
            array('id,order_id,product_name,big_priture,total,price,note,uuid,startTime,endTime,fullname,mobile', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            "sfhkOrder" => array(self::BELONGS_TO, "SfhkOrder", array('order_id' => 'order_id')),
                // "community"=>array(self::BELONGS_TO,"Community","community_id"),
                // "cate"=>array(self::BELONGS_TO,"TopicCategory","cate_id"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'order_id' => '订单号',
            'product_name' => '产品名称',
            'big_picture' => '图片地址',
            'total' => '数量',
            'price' => '单价',
            'startTime' => '开始时间',
            'fullname' => '名称',
            'endTime' => '结束时间',
            'note' => '描述',
            'uuid' => 'uuid',
            'mobile' => '收货人手机号',
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
    public function getSfhkOrder() {
        return $this->hasOne(SfhkOrder::className(), array('order_id' => 'order_id'));
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.order_id', $this->order_id, true);
        $criteria->compare('t.product_name', $this->product_name, true);
        $criteria->compare('t.big_picture', $this->big_picture);
        $criteria->compare('t.total', $this->total);
        $criteria->compare('t.price', $this->price, true);
        $criteria->compare('t.note', $this->note);
        // 指明关联表
        $criteria->with = array('sfhkOrder');
        $criteria->together = TRUE;
        if ($this->startTime != '') {
            $criteria->compare("SfhkOrder.order_start_time", ">=" . strtotime($this->startTime . ' 00:00:00'));
        }

        if ($this->endTime != '') {
            $criteria->compare("SfhkOrder.order_start_time", "< " . strtotime($this->endTime . ' 23:59:59'));
        }

        if ($this->fullname != '') {
            $criteria->addSearchCondition("SfhkOrder.fullname", $this->fullname);
        }
        //手机号搜
        if ($this->mobile != '') {
            $criteria->addSearchCondition("SfhkOrder.mobile", $this->mobile);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.order_id desc',
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Topic the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //图片地f址
    public function getImage() {
        $re = new PublicFunV23();
        return $re->setAbleUploadImg($this->big_picture);
    }

    public function optData() {
        //一个月范围
        /* $date = date('Y-m') . '-01';
          $hour = date('H:i:s');
          $month = 1;
          $end_date =  date('Y-m-d', strtotime($date."+{$month} month-1 day")); */
        $date = date('Y-m-d');
        $hour = date('H:i:s');
        //$start_date = '2015-03-01 00:00:00';
        //$end_date = '2015-03-30 00:00:00';
        $sql = "SELECT update_time FROM sfhk_order ORDER BY id DESC limit 1";
        $re = Yii::app()->db->createCommand($sql)->queryRow();
        if ($re) {
            $start_date = date('Y-m-d H:i:s', $re['update_time']);
        } else {
            $start_date = $date . ' 00:00:00';
        }
        //结束时间
        //$end_date = $date . ' 23:59:59';
        $end_date = $date . ' ' . $hour;
        $re = new PublicFunV23();
        $server_url = 'http://hkapi.st.sf-express.com:2002/app/third/index';
        $post = array(
            'method' => 'sfhk.csh.order.search',
            'access_token' => 'a2254fee9b1e34e34b53c635d2867157',
            'timestamp' => $date . ' ' . $hour,
            'page' => 1,
            'page_size' => 10,
            'start_date' => $start_date,
            'end_date' => $end_date
        );
        $re->typeConnect = 'curl';
        $re = $re->contentMethod($server_url, $post);
        $json = json_decode($re);
        $order = $order_pros = '';
        if (!empty($json->sfhk_csh_order_search_response)) {
            $array = $json->sfhk_csh_order_search_response;
            $timestamp = strtotime($array->timestamp);
            if (!empty($array->order_list)) {
                $list = $array->order_list;
                foreach ($list as $k => $v) {
                    $order_start_time = strtotime($v->order_start_time);
                    $order .= "('','{$v->order_id}','{$order_start_time}', '{$timestamp}'%s%s,'{$v->user_info->fullname}','{$v->user_info->phone}','{$v->user_info->mobile}','{$v->user_info->address}'),";
                    $order_total = 0;
                    $order_price = 0.00;
                    if (!empty($v->product_info_list)) {
                        //产品数量
                        $pro_list = $v->product_info_list;
                        foreach ($pro_list as $k1 => $v1) {
                            $total = empty($v1->total) ? 1 : $v1->total;
                            $price = empty($v1->price) ? '0.00' : $v1->price;
                            $order_pros .= "('','{$v->order_id}','{$v1->product_name}','{$v1->big_picture}','{$total}', '{$price}','{$v1->note}','{$v1->uuid}'),";
                            $order_total += $total;
                            $order_price += $price;
                        }
                    }
                    $order = sprintf($order, ',' . round($order_price, 2), ',' . (int) $order_total);
                }
            }
        }
        //增加订单表
        if ($order && $order_pros) {
            $order = trim($order, ',');
            $sql = "INSERT INTO sfhk_order VALUES  {$order}";
            //}
            //增加订单对y应的产品表
            //if ($order_pros){
            $order_pros = trim($order_pros, ',');
            if (Yii::app()->db->createCommand($sql)->execute()) {
                $sql = "INSERT INTO sfhk_order_products VALUES {$order_pros}";
                Yii::app()->db->createCommand($sql)->execute();
            }
        }
        echo 'ok';
    }

    public function behaviors() {//return array();
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    /*  public function beforeDelete()
      {
      //删除所有的小区的对应的帖子
      Topic::model()->deleteAllByAttributes(array('parent_id' => $this->id));
      return parent::beforeDelete();
      }
     */

    public function afterDelete() {
        //删除关联关系
        $data = SfhkOrderProducts::model()->findAllByAttributes(array('order_id' => $this->order_id));
        if (count($data) <= 0) {
            SfhkOrder::model()->deleteAllByAttributes(array('order_id' => $this->order_id));
        }

        return parent::afterDelete();
    }

}
