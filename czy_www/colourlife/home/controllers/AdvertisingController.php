<?php
header('Access-Control-Allow-Origin:*');
class AdvertisingController extends BaseHelpController
{

    public  $modelName = 'Advertising';
    private $_id; //文章ID
    private $_cate_id;



    public function actionIndex($category_id=0)
    {
        throw new CHttpException(404,"页面不存在");
    	$this->pageTitle = "广告机会-彩之云";
        $category = $this->findCategory();
        if (!empty($category_id) && $category_id != 0) {
        	$category_id=intval($category_id);
            $model = $this->essayList($category_id);
        } else {
            if (!empty($category)) {
                $model = $this->essayList($category[0]->id);
            }
        }
        $this->render('index', array(
            'right' => $category,
            'model' =>empty($model)?'':$model['list'],
            'num'=>$this->getNum($category,$category_id),
            'pages'=>empty($model)?null:$model['pager'],
        ));
    }

    public  function actionView($id)
    {   throw new CHttpException(404,"页面不存在");
    	$this->pageTitle = "广告机会-彩之云";
        //$this->_id = intval($id);
    	$id=intval($id);
    	$this->_id = $id;
        $category=$this->findCategory();
        $model=$this->views($id);
        $this->_cate_id=$model->category_id;
        $this->render('view',array(
            'right'=>$category,
            'model'=>$model,
            'num'=>$this->getNum($category,$model->category_id),
        ));
    }

    //下一篇
    public function nextJob()
    {
        throw new CHttpException(404,"页面不存在");
        return Essay::model()->find(array('condition' => 'id>:id and category_id=:category_id', 'params' => array(':id' => $this->_id,
            ':category_id'=>$this->_cate_id), 'order' => 'id ASC'));
    }

    //上一篇
    public function prevJob()
    {
        throw new CHttpException(404,"页面不存在");
        return Essay::model()->find(array('condition' => 'id<:id and category_id=:category_id', 'params' => array(':id' => $this->_id,
            ':category_id'=>$this->_cate_id), 'order' => 'id DESC'));
    }

    public function actionColourHouse(){
        $customer_id = Yii::app()->request->getParam('cust_id');
        $sign = Yii::app()->request->getParam('sign');
        $ts = Yii::app()->request->getParam('ts');


        $queryData = [
            'ts' => $ts,
            'cust_id' => $customer_id,
            'sign' => $sign
        ];

//        $data = json_decode(
//            Yii::app()->curl->get(
//                'http://www.colourlife.com/Advertising/ColourHouse',
//                $queryData
//            ),
//            true
//        );
//        echo json_encode($data);exit();





        if(time()-$ts > 1200){
            $arr = [
                'code'=>0,
                'message'=>'已超时',
            ];
            echo json_encode($arr);exit();
        }
        if(empty($sign) || empty($ts)){
            $arr = [
                'code'=>0,
                'message'=>'必填参数为空',
            ];
            echo json_encode($arr);exit();
        }

        $sign_mine = md5($customer_id.$ts.'colourlife123456');
        $customer_id = ($customer_id - 1778) / 778;


        if($sign == $sign_mine){
            $model = new HomeConfigResource();
            $rent = $model->getResourceByKeyOrId('daytuan',1,$customer_id);
            if($rent){
                $url =$rent->completeURL;
            }else{
                $url = '';
            }
            $cheap = CheapLog::model();
            $url_goods = $cheap->findByAttributes(array('goods_id'=>42603 , 'status'=>0 , 'is_deleted'=>0));
            $pid1 = $url_goods->id;
            $url_goods = $cheap->findByAttributes(array('goods_id'=>41094 , 'status'=>0 , 'is_deleted'=>0));
            $pid2 = $url_goods->id;
            $url_goods = $cheap->findByAttributes(array('goods_id'=>38766 , 'status'=>0 , 'is_deleted'=>0));
            $pid3 = $url_goods->id;
            $house_arr = [
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136889/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/xuzhou_guotai.jpg'),
                    'label' => '徐州',
                    'name' => '国泰菁华逸景',
                    'address' => '江苏省徐州市翡翠路与姚庄路交汇处',
//                    'info' => '徐房售许字 (2015) 第58号',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136888/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/huanggang_yingdei.jpg'),
                    'label' => '黄冈',
                    'name' => '盈德花满庭',
                    'address' => '湖北省黄冈市浠水县洪山大道与发展大道',
//                    'info' => '预售许可号：2016003',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136887/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/yuzhou_yiheyuan.jpg'),
                    'label' => '禹州',
                    'name' => '懿合苑',
                    'address' => '河南省禹州市府东路阳翟大道东南角',
//                    'info' => '禹建商品房预售字 (2015) 第018号',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136884/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/yidu_jingxiu.jpg'),
                    'label' => '宜都',
                    'name' => '宜都锦绣江南',
                    'address' => '湖北省宜都市西扩南进的核心地段',
//                    'info' => '鄂都房售 (2009) 10号',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136882/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/xuchang_huirun.jpg'),
                    'label' => '许昌',
                    'name' => '汇润映像',
                    'address' => '河南省许昌魏都区五一路与新兴路交汇处东',
//                    'info' => '许房预售字第2016037号',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136881/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/changdei_lizhou.jpg'),
                    'label' => '常德',
                    'name' => '灃州印象',
                    'address' => '湖南省常德市灃县运大路与翊武路交汇处',
//                    'info' => '灃房售许字 (20160034) 号',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136880/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/changdei_jinshi.jpg'),
                    'label' => '常德',
                    'name' => '津市新天地',
                    'address' => '湖南常德市市建设路与刘公桥路交汇处',
//                    'info' => '津房售201515号',
                ],
                [
                    'url' => 'http://m.leju.com/touch/house/sz/136875/' ,
                    'img' => F::getStaticsUrl('/activity/v2017/colourHouseVip/images/huanggang_binhe.jpg'),
                    'label' => '黄冈',
                    'name' => '滨河国际城',
                    'address' => '湖北省黄冈市黄梅县新政府斜对面',
//                    'info' => '鄂黄房售许字 (14) 0098号',
                ],

            ];
            $shop_arr = [
                [
                    'url'=>$url.'&pid='.$pid1 ,
                    'img'=> F::getStaticsUrl('/activity/v2017/colourHouseVip/images/recommend01.jpg'),
                    'introduce' => '每一顿饭都是家乡的味道'
                ],
                [
                    'url'=>$url.'&pid='.$pid2 ,
                    'img'=> F::getStaticsUrl('/activity/v2017/colourHouseVip/images/recommend02.jpg'),
                    'introduce' => '海味大礼包礼盒'
                ]
                ,
                [
                    'url'=>$url.'&pid='.$pid3 ,
                    'img'=> F::getStaticsUrl('/activity/v2017/colourHouseVip/images/recommend03.jpg'),
                    'introduce' => '醇香 亚麻籽油 低温冷榨无添加剂'
                ],
            ];
            $ticket_arr = [
                ['itemName'=>'湖南津市·新天地' ,'startTime'=>'2017.03.23' ,'awardStander'=>'1000饭票'],
                ['itemName'=>'湖北黄梅·滨河国际' ,'startTime'=>'2017.03.23' ,'awardStander'=>'1000饭票'],
                ['itemName'=>'湖北浠水·盈德花满庭' ,'startTime'=>'2017.03.23' ,'awardStander'=>'1000饭票'],
                ['itemName'=>'湖北鄂州·武汉东国际家居建材' ,'startTime'=>'2017.03.23' ,'awardStander'=>'1000饭票'],
                ['itemName'=>'江苏徐州·菁华逸景' ,'startTime'=>'2017.03.23' ,'awardStander'=>'1000饭票'],
                ['itemName'=>'福建漳平·万成100家园' ,'startTime'=>'2017.03.23' ,'awardStander'=>'2000饭票'],
                ['itemName'=>'河南禹州·懿合苑' ,'startTime'=>'2017.03.23' ,'awardStander'=>'5000饭票'],
                ['itemName'=>'河南许昌·汇润映象' ,'startTime'=>'2017.03.23' ,'awardStander'=>'2000饭票'],
            ];
            $arr = [
                'code'=>1,
                'message'=>'请求成功',
                'data' => [
                    'house_arr'=>$house_arr,
                    'goods_arr'=>$shop_arr,
                    'ticket_arr' =>$ticket_arr
                    ]
               ];
        }else{
            $arr = [
                'code'=>0,
                'message'=>'签名验证失败',
            ];
        }
        echo json_encode($arr);exit();
    }

}