<?php
/**
 * 前台页面的页头和页脚数据
 * 请修改具体的url
 */

class FootItems
{
    ///获取页脚显示数据
    public static function getItems()
    {
        return array(array(
            'title' => '购物指南',
            'items' => array(
                array('id' => '1',
                    'name' => '购物流程',
                    'url' => '/help?category_id=24'
                ),
                array('id' => '1',
                    'name' => '订单状态说明',
                    'url' => '/help?category_id=36'
                ),
                array('id' => '1',
                    'name' => '优惠券说明',
                    'url' => '/help?category_id=37'
                ),
                // array('id' => '1',
                // 'name' => '积分说明',
                // 'url' => '/jobs'
                // ),
            ),
        ),
            array(
                'title' => '支付帮助',
                'items' => array(
                    array('id' => '1',
                        'name' => '在线支付',
                        'url' => '/help?category_id=25'
                    ),
                    // array('id' => '1',
                    // 'name' => '银行转账',
                    // 'url' => '#'
                    // ),
                    // array('id' => '1',
                    // 'name' => '发票说明',
                    // 'url' => '#'
                    // ),
                ),
            ),
            array(
                'title' => '物流配送',
                'items' => array(
                    array('id' => '1',
                        'name' => '商家配送',
                        'url' => '/help?category_id=26'
                    ),
                    // array('id' => '1',
                    // 'name' => '快递配送',
                    // 'url' => '#'
                    // ),
                ),
            ),
            array(
                'title' => '服务条款',
                'items' => array(
                    array('id' => '1',
                        'name' => '退换货说明',
                        'url' => '/help?category_id=27'
                    ),
                    array('id' => '1',
                        'name' => '联系客服',
                        'url' => '/help?category_id=28'
                    ),
                ),
            ),
            array(
                'title' => '关于彩之云',
                'items' => array(
                    array('id' => '1',
                        'name' => '彩之云简介',
                        'url' => '/about/8'
                    ),
                    array('id' => '1',
                        'name' => '投资者关系',
                        'url' => 'http://www.colourlife.hk'
                    ),
                    array('id' => '1',
                        'name' => '招贤纳士',
                        'url' => '/jobs'
                    ),
                    array('id' => '1',
                        'name' => '供应商直通车',
                        'url' => '/help?category_id=29'
                    ),
                    array('id' => '1',
                        'name' => '加盟商直通车',
                        'url' => '/help?category_id=38'
                    ),
                ),
            ),
        );
    }

    //获取页面底部
    public static function getBottom()
    {
        return array(
            array(
                'title' => '彩生活',
                'url' => '/'
            ),
            array(
                'title' => '公司介绍',
                'url' => '/about/20',
            ),
            array(
                'title' => '服务协议',
                'url' => '/help?category_id=30'
            ),
            array(
                'title' => '隐私权保护',
                'url' => '/help?category_id=31'
            ),
            /*array(
                'title' => '商务洽谈',
                'url' => '/help?category_id=39'
            ),*/
            array(
                'title' => '广告服务',
                'url' => '/advertising/16'
            ),
            array(
                'title' => '客服中心',
                'url' => '/help?category_id=28'
            ),
            array(
                'title' => '加入我们',
                'url' => '/jobs',
            ),
            array(
                'title' => '帮助',
                'url' => '/help',
            ),
            // array(
            // 'title' => '友情连接',
            // 'url' => '/help?category_id=17',
            // ),
            // array(
            // 'title' => '网站导航',
            // 'url' => '/help?category_id=17',
            // ),
        );
    }

    ///获取页头帮助的选项
    public static function getHelpItems()
    {
        return array(
            array(
                'title' => '常见问题',
                'url' => '/help?category_id=40',
            ),
            array(
                'title' => '售后服务',
                'url' => '/help?category_id=27',
            ),
            array(
                'title' => '客服中心',
                'url' => '/help?category_id=28',
            ),
            // array(
            // 'title' => '客服邮箱',
            // 'url' => '',
            // ),
            // array(
            // 'title' => '在线客服',
            // 'url' => '',
            // ),
        );
    }

    public static function getHomeItems()
    {
        return array(
            array(
                'title' => '关于我们',
                'url' => '/about/8',
            ),
            array(
                'title' => '投资者关系',
                'url' => 'http://www.colourlife.hk'
            ),
            array(
                'title' => '服务协议',
                'url' => '/help?category_id=30',
            ),
            array(
                'title' => '帮助',
                'url' => '/help',
            ),

        );
    }

    public static function getWeiboLink()
    {
        return 'http://e.weibo.com/3170349347/profile';
    }

    public static function getTqqLink()
    {
        return 'http://e.t.qq.com/caishenghuoshequ?preview';
    }

    public static function getAppLink()
    {
        return 'http://mapp.colourlife.com/';
    }

}