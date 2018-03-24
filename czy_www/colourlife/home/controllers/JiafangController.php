<?php
function p($arr)
{
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

class BranchUtil
{
    /**
     * 获取组织结构的到根的层级链.
     * 注意，此使用递归查询数据库.如果页面存在大量调用，性能低下。
     * @param int $branch_id
     * @return array( array ( <br>
     *      "id" =>  ,        //branch_id  <br>
     *      "parent_id" =>  , //父ID <br>
     *      "level" =>        //层级 <br>
     * ))
     */
    public static function getBranchLevelChain($branch_id)
    {
    	$branch_id=intval($branch_id);
        $branch = Branch::model()->findByPk($branch_id);
        if($branch === null)
        {
            return array();
        }
        
        $result = array();
        $r = array(
                'id' => $branch->id,
                'parent_id' => 0,
                'level' => 0
        );
        
        if($branch->parent_id <= 0 )
        {
            $result[0] = $r;
        }
        else
        {
            $parentBranchChain = self::getBranchLevelChain($branch->parent_id);            
            foreach ($parentBranchChain as $pBranch)
            {
                if($pBranch['id'] == $branch->parent_id )
                {
                    $r['parent_id'] = $pBranch['id'];
                    $r['level'] = $pBranch['level'] + 1;
                    break;
                }
            }            
            $parentBranchChain[] = $r;            
            $result = $parentBranchChain;
        }
        
        return $result;        
    }
}

class JiafangController extends SsoController
{
	public $layout="//";
	
	protected $title = "彩管家E家访";
	
	private $titleDict = null;

	private $employeBindObj = null;
	
	private $pageSize = 10; //页大小
	
	
	protected function isCompatibleMode()
	{
		return true;
	}
	
	protected function getSsoConfig()
	{
		return array(
				'app_id' => "ICEEJF00-ECBD-481C-95A9-4788C734A7D1",
				'token' => "IGoxZC1wKeSW0yzVmBiU",
		);
	}
	
	public function init()
	{
		$this->checkLogin();
	}
	
	public function actionCommentDetail($id)
	{
		$id = intval($id);
		
		$comment = ProprietorComment::model()->with("attachment", "proprietor")->findByPk($id);
		
		$data = array(
				'comment' => $comment
		);
		
		$this->render("commentdetail", $data);
	}
	
	public function actionTagHandle()
	{
		$id = intval(Yii::app()->request->getParam("id", 0));
		$action = Yii::app()->request->getParam("action", "");
		$content = Yii::app()->request->getParam("content", "");
		
		
		if($action != "tag" && $action != "untag")
		{
			$this->jsonReturn(array(
					'code' => 1,
					'msg' => "非法提交"
			));
		}
		if($this->getBindedCustomer() == null)
		{
			$this->jsonReturn(array(
					'code' => 2,
					'msg' => "非法提交"
			));
		}
		$comment = ProprietorComment::model()
			->find("id=:id and manager_id=:manager_id",
				array(':id' => $id, ":manager_id" => $this->getBindedCustomer()->id ));
		if($comment == null)
		{
			$this->jsonReturn(array(
					'code' => 3,
					'msg' => "非法提交"
			));
		}
		if($action == "tag")
		{
			$comment->manager_tag = 1;
			$comment->manager_tag_content = $content;
		}
		else
		{
			$comment->manager_tag = 0;
			$comment->manager_tag_content = $content;
		}
		if($comment->save())
		{
			$this->jsonReturn(array(
					'code' => 0,
					'msg' => "标记成功"
			));
		}
		else
		{
			$this->jsonReturn(array(
					'code' => 10,
					'msg' => "标记失败"
			));
		}
		
		
	}
	
	public function actionCommentList($action)
	{
	    
	    if(!$this->currentEmployeeIsBindedCustomer())
	    {
	        throw new CHttpException(400, "非法访问");
	    }
	    $dateRange = null;
	    if($action == "tag")
	    {
	        $dateRange = $this->getOneMonthRange(time());

	        $data = array(
	                'comments' => $this->getComments(null, null, $dateRange, null, 1),
	                'tag' => $action,
	                'statistics' => $this->getCommentsStatistics(null, null, $dateRange, 1)
	        );
	    }
	    else if($action == "all")
	    {
	        $dateRange = $this->getOneMonthRange(time());
	       
	        $data = array(
	                'comments' => $this->getComments(null, null, $dateRange),
	                'tag' => $action,
	                'statistics' => $this->getCommentsStatistics(null, null, $dateRange)
	        );
	    }
	    else 
	    {
	        throw new CHttpException(404, "访问页面不存在");
	    }		
		
	    
	    $communitys = $this->getEmployeeCommunitys();
	    $data['communitys'] = $communitys;
	    $dateArr = $this->generateMonth("2015-01-01");
	    rsort($dateArr);
	    $data['dateList'] = $dateArr;
	    $data['isEndPage'] = count($data['comments']) < $this->pageSize ? "true" : "false";
	    

		$this->render("commentlist", $data);
	}
	
	/**
	 * 
	 * @param string $from
	 * @param string $to
	 */
	private function generateMonth($from, $to = "")
	{
	    if($to=="")
	    {
	        $to = date("Y-m-d",time());
	    }
	    
	    $from = date("Y-m-01", strtotime($from));
	    
	    $to = date("Y-m-01", strtotime($to));
	    
	    $result = array();
	    while( strtotime($from) <= strtotime($to) )
	    {
	        $result[] = date("Y-m", strtotime($from));
	        $from = $from . " +1 month";
	        $from = date("Y-m-01", strtotime($from));
	    }
	    return $result;
	}
	
	public function actionGetCommentList()
	{

	    $searchContent = Yii::app()->request->getQuery("searchContent", "");
	    $community = Yii::app()->request->getQuery("community", "");
	    $dateRange = Yii::app()->request->getQuery("dateRange", "curr");
	    $level = Yii::app()->request->getQuery("level", "-1");
	    $page = Yii::app()->request->getQuery("page", "0");
	    $tag = Yii::app()->request->getQuery("tag", "");
	    $getStatistics = Yii::app()->request->getQuery("getStatistics", "0");
	    
	    
	    
	    /*小区*/
	    if($community === "all" || $community === "")
	    {
	        $community = null;
	    }
	    else
	    {
	        $community = array(intval($community));
	    }
	    
	    /*日期*/
	    if($dateRange == "all")
	    {
	        $dateRange = null;
	    }	    
	    else if($dateRange == "curr")
	    {
	        $dateRange = $this->getOneMonthRange(time());
	    }
	    else
	    {
	        $dateRange = $this->getOneMonthRange(strtotime($dateRange));
	    }
	    
	    /*评价等级*/
	    if($level == "" || $level == "-1")
	    {
	        $level = null;
	    }
	    else 
	    {
	        $level = intval($level );
	        $level = $level < 0 ? 0 : $level % 4;
	        $level = array($level);
	    }
	    
	    /*标记*/
	    if($tag == "tag")
	    {
	        $tag = 1;
	    }
	    else if($tag == "untag")
	    {
	        $tag = 0;
	    }
	    else 
	    {
	        $tag = null;
	    }
	    
	    $page = intval($page);
	    $page = $page <= 0 ? 1 : $page;
	    
	    $comments = $this->getComments(
	            $searchContent, 
	            $community,
	            $dateRange,
	            $level,
	            $tag,
	    		$page
	            );
	    $this->processCommentsForAjaxRequest($comments);
	    
	    if($getStatistics == "1")
	    {
	    	$statistics = $this->getCommentsStatistics($searchContent, $community, $dateRange, $tag);
	    }
	    else 
	    {
	    	$statistics = array();
	    }
	    
	    $this->jsonReturn(array(
	            "code" => 0,
	            "comments" => $comments,
	            "isEndPage" => count($comments) < $this->pageSize,
	            "statistics" => $statistics
	    ));
	}
	
	/**
	 * 获取评论列表
	 * @param string $searchContent 查找内容，null 或"" 表示全部， 可以是名字或者手机号码
	 * @param array $communitys 小区id数组。null表示全部
	 * @param array $daterange  日期范围.null表示全部.array[0]开始时间array[1]结束时间. 时间戳形式
	 * @param array $commentLevel 评价等级数组，null表示全部.如array(0,2)表示显示0和2等级的评论
	 * @param int $tag null--全部, 0--未标记， 1--已标记
	 * @param int $page 返回第几页的内容
	 */
	private function getComments(
			$searchContent = null, 
			$communitys = null , 
			$daterange = null, 
			$commentLevel = null, 
			$tag = null,
			$page = 1)
	{
		$customer = $this->getBindedCustomer();
		
		$sqlCmd = Yii::app()->db->createCommand();
		$sqlCmd->select("
    cmt.id id, 
    cmt.create_time,
    cmt.level,
    cmt.content, 
    cmt.manager_id,
    cmt.proprietor_id,
    cmt.manager_tag AS tag,
    cmt.manager_tag_content AS tag_content,
    cmt.community_id,
    cmt.address,
    c.name,
    c.mobile")
		->from("jf_proprietor_comment cmt")
		->join("customer c", "cmt.proprietor_id = c.id")
		->where( "manager_id = :customer_id", array(":customer_id" => $customer->id) );


		if(!($searchContent===null || $searchContent === ""))
		{
			if($this->isMobileNumber($searchContent))
			{
				$sqlCmd->andWhere("c.mobile = :mobile", array(':mobile' => $searchContent));
			}
			else
			{
				$sqlCmd->andWhere(array("like", "c.name", "$searchContent%"));
			}			
		}
		if($communitys != null && is_array($communitys) && count($communitys) > 0)
		{
			$sqlCmd->andWhere(array("in", "cmt.community_id", $communitys));
		}
		
		if(is_array($daterange) && count($daterange) == 2)
		{
			$sqlCmd->andWhere("cmt.create_time BETWEEN :beginTime AND :endTime", 
					array(
							":beginTime" => $daterange[0],
							":endTime" => $daterange[1]
					));
		}
		
		if(is_array($commentLevel) && count($commentLevel) > 0)
		{
			$sqlCmd->andWhere(array("in", "cmt.level", $commentLevel));
		}
		
		if($tag === 0 || $tag === 1)
		{
			$sqlCmd->andWhere("cmt.manager_tag = :tag", array(":tag" => $tag));
		}
		$sqlCmd->order("cmt.create_time DESC");
		$sqlCmd->limit($this->pageSize, ($page - 1) * $this->pageSize);
		return $sqlCmd->queryAll();
	}
	
	/**
	 * 获取评论统计信息。
	 * @param string $searchContent 查找内容，null 或"" 表示全部， 可以是名字或者手机号码
	 * @param array $communitys 小区id数组。null表示全部
	 * @param array $daterange  日期范围.null表示全部.array[0]开始时间array[1]结束时间. 时间戳形式
	 * @param int $tag null--全部, 0--未标记， 1--已标记
	 * @return array( <br>
	 * 	'total' => //评论数 <br>
	 *  0 =>  <br>
	 *  1 => <br>
	 *  2 => <br>
	 *  3 => <br>
	 * )
	 */
	private function getCommentsStatistics(
			$searchContent = null, 
			$communitys = null ,
			$daterange = null ,
			$tag = null)
	{
		$customer = $this->getBindedCustomer();
		
		$sqlCmd = Yii::app()->db->createCommand();
		$sqlCmd->select("
        COUNT(*) AS total,
	    COUNT( CASE cmt.`level`
	            WHEN 0 THEN cmt.`level`
	            ELSE NULL
	            END
	    ) AS level0,
	    
	    COUNT( CASE cmt.`level`
	            WHEN 1 THEN cmt.`level`
	            ELSE NULL
	            END
	    ) AS level1,
	    
	    COUNT( CASE cmt.`level`
	            WHEN 2 THEN cmt.`level`
	            ELSE NULL
	            END
	    ) AS level2,
	    
	    COUNT( CASE cmt.`level`
	            WHEN 3 THEN cmt.`level`
	            ELSE NULL
	            END
	    ) AS level3"
		        )
		    ->from("jf_proprietor_comment cmt")
		    ->join("customer c", "cmt.proprietor_id = c.id")
		    ->where( "manager_id = :customer_id", array(":customer_id" => $customer->id) );
		
		
		if(!($searchContent===null || $searchContent === ""))
		{
			if($this->isMobileNumber($searchContent))
			{
				$sqlCmd->andWhere("c.mobile = :mobile", array(':mobile' => $searchContent));
			}
			else
			{
				$sqlCmd->andWhere(array("like", "c.name", "$searchContent%"));
			}
		}
		if($communitys != null && is_array($communitys) && count($communitys) > 0)
		{
			$sqlCmd->andWhere(array("in", "cmt.community_id", $communitys));
		}
		
		if(is_array($daterange) && count($daterange) == 2)
		{
			$sqlCmd->andWhere("cmt.create_time BETWEEN :beginTime AND :endTime",
					array(
							":beginTime" => $daterange[0],
							":endTime" => $daterange[1]
					));
		}
				
		if($tag === 0 || $tag === 1)
		{
			$sqlCmd->andWhere("cmt.manager_tag = :tag", array(":tag" => $tag));
		}
		return $sqlCmd->queryRow();
		
		
	}
	
	public function actionTop()
	{
	    if(!$this->currentEmployeeIsBindedCustomer())
	    {
	        $this->redirect(array("Index"));
	    }
	    
		$dateRange = $this->getOneMonthRange(time());
		
		$sql = $this->buildTopSql($dateRange[0], $dateRange[1], 20, 1);
		
		$db = Yii::app()->db;
		$cmd = $db->createCommand($sql);
		$topList = $cmd->queryAll();
		
		$score = $this->getMyScore(array(1,2,3));
		
		$data = array(
				'topList' => $topList,
				'myRank' => $this->getMyRanking(),
				'upNextLevelScore' => $this->upgradeToNextLevelScore($score['count']),
		        'myScore' => $score['count']
		);
		
		$this->render("top", $data);
	}
	
	/**
	 * 获取上级branch名称
	 * @param int $branch_id 组织机构名称
	 * @param int $level 显示第几层的。顶层为0
	 */
	protected function getParentBranchName($branch_id, $level)
	{
	    $branchChain = BranchUtil::getBranchLevelChain($branch_id);
	    
	    $maxLevel = 0;
	    foreach ($branchChain as $b )
	    {
	        $maxLevel = $b['level'] > $maxLevel ? $b['level'] : $maxLevel;
	    }
	    
	    if($level < 0 || $level > $maxLevel)
	    {
	        $level = $maxLevel;
	    }
	    
	    $level_branch_id = $branch_id;
	    foreach($branchChain as $b )
	    {
	        if($b['level'] == $level)
	        {
	            $level_branch_id = $b['id'];         
	        }
	    }	    

	    $branch = Branch::model()->findByPk($level_branch_id);
	    return $branch === null ? "" : $branch->name;
	}
	
	private function buildTopSql($beginTime, $endTime, $topCount, $minScore)
	{
		$sql = "
SELECT
    mgr.`id`,
    mgr.`name` mgr_name,
    community.`branch_id`,
    top.score
FROM customer mgr
  JOIN (
        SELECT
            manager_id AS id,
            COUNT(*) score
        FROM jf_proprietor_comment
		WHERE level > 0
		  AND create_time between $beginTime and $endTime
        GROUP BY manager_id
        HAVING COUNT(*) >= (
                SELECT
                    MIN(score)
                FROM (SELECT COUNT(*) score
                      FROM jf_proprietor_comment
                      WHERE create_time between $beginTime and $endTime
		                AND level > 0
                      GROUP BY manager_id
                      ORDER BY score DESC
                      LIMIT $topCount
                      ) t_top
             )
             AND COUNT(LEVEL) >= $minScore
        ) top
    ON mgr.`id` = top.id
  LEFT JOIN community
    ON mgr.`community_id` = community.`id`
ORDER BY top.score DESC
		        
		        ";
		
		return $sql;
	}
	
	private function upgradeToNextLevelScore($score)
	{
		$prev = null;
		$titleDict = $this->getTitleDict();
		foreach ($titleDict as $key_score => $title)
		{			
			if( $score >= $key_score )
			{
				if($prev === null)
				{
					return 0;
				}
				else
				{
					return $prev - $score;
				}
			} 
			$prev = $key_score;
		}
		return end ($titleDict) - $score;
	}

	/**
	 * 获取的的成绩
	 * @return array ('score' => 评价分数, 'count' => 评价数量)
	 */
	private function getMyScore($levels = array())
	{
		$bindObj = $this->getEmployeeBindCustomerObj();
		$sql = "
SELECT
COUNT(*) AS comment_count,
SUM(`level`) AS comment_score
FROM jf_proprietor_comment
WHERE manager_id = %d
		";
		
		if(is_array($levels) && count($levels) > 0)
		{
		    $sql .= " AND `level` in (". implode(",", $levels) . ")";
		}
		
		$sql = sprintf($sql, $bindObj->customer_id );
		$cmd = Yii::app()->db->createCommand($sql);
		$result = $cmd->queryAll();
		return array(
				'score' => $result[0]['comment_score'],
				'count' => $result[0]['comment_count']
		);
	}
	
	/**
	 * 获取我的名次。统计评价数名次，评价数
	 * 不包含差评（level = 0）
	 */
	private function getMyRanking()
	{
		$sql = "
SELECT 
    COUNT(*) + 1 AS rank
FROM (  SELECT 
            cmt.manager_id,
            COUNT(`level`) score
        FROM jf_proprietor_comment cmt
        WHERE level > 0
		GROUP BY cmt.manager_id
		
      ) t_rank
WHERE t_rank.score > (    
        SELECT
            COUNT(`level`)
        FROM jf_proprietor_comment
        WHERE manager_id = %d AND level > 0
      )

";
		$bindObj = $this->getEmployeeBindCustomerObj();
		
		$sql = sprintf($sql, $bindObj->customer_id );
		$cmd = Yii::app()->db->createCommand($sql);
		$result = $cmd->queryAll();
		return $result[0]['rank'];
	}
	
	public function actionIndex()
	{
		if($this->currentEmployeeIsBindedCustomer() )
		{
			$this->redirect(array("Top"));
		}
		
		$data=array(
				'customer' => $this->getCustomerByMobile()
		);
		$this->render("index", $data);
	}
	
	public function actionUploadPortrait()
	{
		$customer = $this->getCustomerByMobile();
		
		if($customer == null)
		{
			$this->jsonReturn(array(
					'code' => 4,
					'msg' => "不存在对应的彩之云账户"
			));
		}
		
		if($this->hasPortrait($customer))
		{
			$this->jsonReturn(array(
					'code' => 3,
					'msg' => "已经存在头像"
			));
		}
		
		if(!isset($_FILES['file']))
		{
			$this->jsonReturn(array(
					'code' => 1,
					'msg' => "上传失败"
			));
		}
		
		
		$customer->save();
		
		$picStaticPath = Yii::app()->imageFile->getFilename($customer->portrait);
		
		$conversion = new ImageConversion($picStaticPath);
		$conversion->conversion($picStaticPath, array(
				'w' => 160,   // 结果图的宽
				'h' => 160,   // 结果图的高
				't' => 'resize ,clip', // 转换类型
		));
		
		if(Yii::app()->imageFile->exists($customer->portrait))
		{
			$this->jsonReturn(array(
					'code' => 0,
					'msg' => "ok",
					'pic_url' => $customer->getPortraitUrl()
			));
		}
		else 
		{
			$this->jsonReturn(array(
					'code' => 2,
					'msg' => "上传头像失败",
			));
		}
	}

	public function actionDoBinding()
	{
		if($this->currentEmployeeIsBindedCustomer())
		{
			$this->jsonReturn(array(
					"code" => 3,
					"msg"  => "当前用户已经绑定，不能再绑定"
			));
		}
		
		$customer = $this->getCustomerByMobile();
		if( $customer == null )
		{
			$this->jsonReturn(array(
					"code" => 1,
					"msg"  => "不存在彩之云账户，不能绑定"
			));
		}
		
		if(! $this->hasPortrait($customer))
		{
			$this->jsonReturn(array(
					'code' => 2,
					'msg' => "彩之云账号不存在头像，不能绑定"
			));
		}
		
		EmployeeBindCustomer::model()->deleteAll("employee_id=:employee_id or customer_id=:customer_id", 
                array(
                        ":employee_id" => $this->employee_id,
                        ':customer_id' => $customer->id
                ));
		$bindObj = new EmployeeBindCustomer();	
		$bindObj->attributes = array(
				'employee_id' => $this->employee_id,
				'customer_id' => $customer->id,
				'bind_time' => time(),
				'state' => 1
		);
		if($bindObj->save())
		{
			$this->jsonReturn(array(
					'code' => 0,
					'msg' => "ok",
					'redirect' => $this->createUrl('Top')
			));
		}
		else
		{
			$this->jsonReturn(array(
					'code' => 4,
					'msg' => "绑定失败"
			));
		}
	}
	
	/**
	 * 将评价分数转化为头衔
	 */
	protected function levelScoreToTitle($score)
	{
		$titleDic = $this->getTitleDict();
		foreach ($titleDic as $key_score => $title)
		{
			if($score >= $key_score)
			{
				return $title;
			}
		}
		return end($titleDic);
	}
	
	private function getTitleDict()
	{
		if($this->titleDict == null)
		{
			$cmd = Yii::app()->db->createCommand("select * from jf_dict_score_title order by score desc");
			$result = $cmd->queryAll();
				
			$this->titleDict = array();
			foreach ($result as $row)
			{
				$this->titleDict[$row['score']] = $row['title'];
			}
		}
		return $this->titleDict;
	}
	
	/**
	 * @return EmployeeBindCustomer
	 */
	private function getEmployeeBindCustomerObj()
	{
		if($this->employeBindObj === null)
		{
			$this->employeBindObj = EmployeeBindCustomer::model()
				->with("customer", "employee")
				->find(
					"employee_id=:employeeid",
					array(":employeeid"=>$this->employee_id));
		}
		return $this->employeBindObj;
		
	}
	
	private function currentEmployeeIsBindedCustomer()
	{
		$bindObj = $this->getEmployeeBindCustomerObj();
		return $bindObj != null && $bindObj->customer != null && $bindObj->state == 1;
	}

	/**
	 * 
	 * @return Customer
	 */
	private function getCustomerByMobile()
	{
		$employee = Employee::model()->findByPk($this->employee_id);
		if(is_null($employee->mobile) || $employee->mobile === "")
		{
			return null;
		}
		$customer = Customer::model()->find("mobile=:mobile", array(":mobile" => $employee->mobile));
		return $customer;
	}
	
	/**
	 * 获取绑定的彩之云账户
	 * @return Customer
	 */
	private function getBindedCustomer()
	{
		return $this->getEmployeeBindCustomerObj()->customer;
	}
	
	protected function hasPortrait($customer)
	{
		return $customer == null ? false : Yii::app()->imageFile->exists($customer->portrait);
	} 
	
	
	protected function jsonReturn($arr)
    {
    	exit(json_encode($arr));
    }
	
	private function isMobileNumber($mobile)
	{
		return preg_match("/^1[3-9]\d{9}$/", $mobile);
	}
	
	protected function levelCodeToText($level)
	{
		$level = $level < 0 ? 0 : $level % 4;
		$arr = array(
				0 => "不满意",
				1 => "一般",
				2 => "满意",
				3 => "非常满意"
		);
		return $arr[$level];
	}
	
	protected function getAttachmentUrl($fileName)
	{
	    $fileUploader = new FileUploader("jiafang");
		return $fileUploader->getFileUrl($fileName);
	}
	
	/**
	 * 获得一个月的月初到月末的时间范围。
	 * @param DateTime $time
	 * @return array  array[0] 月初时间戳， array[1] 月末时间戳
	 */
	private function getOneMonthRange($time)
	{
	    $begintime = strtotime(date("Y-m-01", $time));
	    $endtime = strtotime(date("Y-m-01", $time)." +1 month -1 second");
	    return array($begintime, $endtime);
	}
	
	private function processCommentsForAjaxRequest(&$comments)
	{
	    for($i = 0; $i < count($comments); $i++)
	    {
	        $comments[$i]['create_time'] = date('Y-m-d H:i:s', $comments[$i]['create_time']) ;
	        $comments[$i]['detail_url'] = $this->createUrl("CommentDetail", array('id'=>$comments[$i]['id'])) ;
	        $comments[$i]['level_text'] = $this->levelCodeToText($comments[$i]['level']);
	    }
	}
	
	/**
	 * 返回当前职员关联的小区，
	 * @return array(
	 *     'id' => , //小区ID
	 *     'name' => , //小区名称
	 * )
	 */
	private function getEmployeeCommunitys()
	{
	    $sql = "
    	    SELECT
        	    c.id,
        	    c.name
    	    FROM employee_branch_relation ebr
    	      JOIN branch b
    	        ON ebr.`branch_id` = b.id
    	      JOIN community c
    	        ON c.branch_id = b.id
    	    WHERE ebr.`employee_id` = :employee_id
	    ";
	    $cmd = Yii::app()->db->createCommand($sql);
	    return $cmd->queryAll(true, array(":employee_id" => $this->employee_id));
	    
	}
}
