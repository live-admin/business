<?php
class CommunityController extends Controller
{

    //
    function actionIndex()
    {
        $this->layout = false;
        $searchValue = isset($_GET["name"]) ? $_GET["name"] : "";
        $model = new ShopCommunityGoodsSell();
        $criteria = new CDbCriteria;
        //$criteria->addCondition("name like '%" . $searchValue . "%'");
        $searchValue=htmlspecialchars($searchValue);
        $criteria->compare('name', $searchValue, true);
        
        //$criteria->join = 'JOIN shop_goods_community_relation as sgcr ON sgcr.community_id= t.id ';
        //$criteria->addCondition("sgcr.is_on_sale =1");
        //$criteria->addCondition("g.cheap_category_id = 1");
        // $criteria->addCondition("g.audit_cheap = 1");
        //$criteria->addCondition("t.name like '%".$searchValue."%'");

        $count = Community::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 5;

        $pager->applyLimit($criteria);
        $communityList = Community::model()->findAll($criteria);

        //  $model->getSaleGoods
        //var_dump($goods);

        $this->render("index", array('model' => $model, "searchValue" => $searchValue,
            'pages' => $pager,
            'communityList' => $communityList,
            'name' => $searchValue,));
    }
}

?>
