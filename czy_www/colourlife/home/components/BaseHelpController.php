<?php

class BaseHelpController extends Controller
{
    public $layout = 'helpmain';

    public $HelpItems = array(
        'Help' => '19',
        'About' => '16',
        'Jobs' => '17',
        'Advertising' => '18'
    );
    //用于点亮菜单
    public $Items;


    //获取文章分类
    protected function findCategory($parent_id=0)
    {
        $criteria = new CDbCriteria;
        if (empty($parent_id) || $parent_id == 0) {
            $criteria->compare('parent_id', $this->HelpItems[$this->modelName]);
        }
        else{
            $criteria->compare('parent_id',$parent_id);
        }

        return EssayCategory::model()->enabled()->findAll($criteria);
    }
    //查询文章列表
    protected function findEssay($category_id)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('category_id',$category_id) ;

        return Essay::model()->findAll($criteria);
        // return new CActiveDataProvider(Essay::model(), array(
        //   'criteria'=>$criteria,
        //));
    }

    //查询文章列表
    protected function essayList($category_id,$pagesize=10)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('category_id',$category_id) ;
        $criteria->compare('is_deleted',0);
        //分页

        $count = Essay::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = $pagesize;
        $pager->applyLimit($criteria);


        return  array(
            'list'=>Essay::model()->findAll($criteria),
            'pager'=>$pager
        ) ;
    }
    //查询详情
    protected function views($id)
    {
        $model = Essay::model()->findByPK($id);
        if (empty($model))
            throw new CHttpException(404, '非常抱歉，您访问的页面不存在！');
        return $model;
    }

    //创建右边菜单
    public  function createRightMenu($data,$type)
    {
        $reult='';
        switch($type)
        {
            case '1':
                $reult= $this->createOne($data);
                break;
            default:
                $reult=$this->createMany($data,1);
                break;
        }
        return $reult;
    }
       //创建一级菜单
    private function   createOne($data)
    {
        $reult = " <div class='hcConBox_conBox'>
        <!-- hcSideBar start -->
        <div class='hcSideBar'>";
        $ac=lcfirst($this->modelName);
        foreach ($data as $a) {
            $reult .= " <div class='itemBox'>   <span class='linkBox'>   ";
            $reult .= "  <a href='".$this->createUrl($ac.'/index',array('category_id'=>$a->id))."'  class='subLink'> " . $a->name;
            $reult .= "  </a>  </span>  </div>";

        }
        $reult .= "  </div>   </div>";
        return $reult;

    }
        //创建多级菜单
    private  function   createMany($data,$type)
    {
        $ac=lcfirst($this->modelName);
        $i=1;
        foreach ($data as $a) {
            $child = $this->findCategory($a->id);
            if (count($child) > 0) {
                echo "<div class='itemBox'>";
                echo "  <div class='subTit'>  <i class='icon'></i> ";
                echo "  <span class='title'>".$a->name."</span> </div> <div class='itemBox'>    ";

                $this->createThree($child,$i);

                echo " </div> </div>";
            }
            else
            {
                $this->Items[]=array(
                    'id'=>$a->id,
                    'num'=>$i
                );
                echo "    <span class='linkBox'>  <a href='".$this->createUrl($ac.'/index',array('category_id'=>$a->id))."'" ;
                echo "  class='subLink'>".$a->name."</a>  </span> ";
            }
            $i++;
        }
    }

    private  function   createThree($data,$arr)
    {
        $ac=lcfirst($this->modelName);
        $i=1;
        foreach ($data as $a) {
            $child = $this->findCategory($a->id);
            if (count($child) > 0) {
                echo "  <div class='subTit'>  <i class='icon'></i> ";
                echo "  <span class='title'>".$a->name."</span> </div> <div class='itemBox'>    ";
                $this->createThree($child,($arr.','.$i));

                echo "</div>";
            }
            else
            {
                $this->Items[]=array(
                    'id'=>$a->id,
                    'num'=>$arr.','.$i
                );
                echo "    <span class='linkBox'>  <a href='".$this->createUrl($ac.'/index',array('category_id'=>$a->id))."'" ;
                echo "  class='subLink'>".$a->name."</a>  </span> ";
            }
            $i++;
        }
    }

    //创建数据显示列表
    public  function createfrontView($data)
    {
        $result=" ";
        $ac=lcfirst($this->modelName);
        if(count($data)>0)
        {
            foreach($data as $a)
            {
                $result.="<a class='link' href='".$this->createUrl($ac.'/view',array('id'=>$a->id))."' >  <span class='date'>".Yii::app()->format->formatLocaleDate($a->create_time)."</span> ";
                $result.=" <span class='jobName'>".$a->title."</span>";
                //$result.="  <span class='jobName'>".$a->brief."</span>";
                $result.="  </a>";
            }
        }

        return $result;

    }
    ///用于点亮右菜单
    public  function getNum($data,$category_id)
    {
        $result=0;
        for($i=0;$i<count($data);$i++)
        {
            if($data[$i]->id==$category_id)
            {
                $result=$i;
                break;
            }
        }

        return $result+1;
    }

}