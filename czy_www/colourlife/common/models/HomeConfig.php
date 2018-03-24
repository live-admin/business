<?php

/**
 * 3.0标签 //TODO:kakatool
 */
class HomeConfig extends CActiveRecord
{

	public $modelName = '首页第一栏功能';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	protected function getResourceUrl($url, $resourceID = 0, $verifyLogin = true)
	{
		if ($verifyLogin && Yii::app()->user->isGuest)
			return  $url = '';

		$resource = HomeConfigResource::model()->getResourceByKeyOrId($resourceID);
		if ($resource) {
			return $resource->completeURL;
		} else {
			return $url;
		}

		/*$resource = HomeConfigResource::model()->getAvailableResourceByPk($resourceID);
		if ($resource) {
			$customer = self::getCustomer();
			if (!$customer) {
				return $url;
			}

			$userNameEncode = urlencode($customer->username);
			$secret = "DJKC#$%CD%des$";
			$resouceParams = CJSON::decode($resource->parameter);
			$arguments = '';
			$argumentStr = '';

			if (is_array($resouceParams)) {
				foreach ($resouceParams as $_k => $_v) {
					$arguments .= $_k . '=' . $_v . '&';
					$argumentStr .= $_k . $_v;
				}
			}

			$password = md5($customer->id);
			$arguments .= sprintf(
				'userid=%s&username=%s&mobile=%s&password=%s&cid=%s',
				$customer->id,
				$userNameEncode,
				$customer->mobile,
				$password,
				$customer->community_id
			);
			$argumentStr .= sprintf(
				'userid=%susername=%smobile=%spassword=%scid=%s',
				$customer->id,
				$userNameEncode,
				$customer->mobile,
				$password,
				$customer->community_id
			);


			switch ($resource->sl_key) {
				case '':
					break;
			}

			return $resource->completeUrl;
		} else {
			return $url;
		}*/
	}

	protected function getProtocolLink($type = '',
	                                   $schema = 'colourlife', $action = 'proto')
	{
		return sprintf(
			'%s://%s?type=%s',
			$schema,
			$action,
			$type
		);
	}

	protected function getFunc($sql = '', $params = array())
	{
		$command = Yii::app()->db->createCommand($sql);
		foreach ($params as $param) {
			$command->bindParam($param['key'], $param['value'], $param['type']);
		}

		$reader = $command->query();

		$id = 0;
		$img = '';
		$name = '';
		$act = '';
		$url = '';
		$native = '';
		$resourceID = 0;
		$isNew = 0;
		$labelIcon = 0;
		$categoryID = 0;
		$state = 0;

		$reader->bindColumn('id', $id);
		$reader->bindColumn('img', $img);
		$reader->bindColumn('name', $name);
		$reader->bindColumn('act', $act);
		$reader->bindColumn('url', $url);
		$reader->bindColumn('native', $native);
		$reader->bindColumn('resource_id', $resourceID);
		$reader->bindColumn('is_new', $isNew);
		$reader->bindColumn('label_icon', $labelIcon);
		$reader->bindColumn('category_id', $categoryID);
		$reader->bindColumn('state', $state);


		$mainFuncs = array();

		while ($reader->read() !== false) {
			if ($state == 0) {
				if ($act == 'proto') {
					$link = $this->getProtocolLink($native);
				} else {
					$link = $this->getResourceUrl($url, $resourceID);
				}
			} else {
				$link = '';
			}

			if ($img) {
				$img = $this->getResourceImage($img);
			}
			if ($labelIcon) {
				$labelIcon = $this->getResourceImage($labelIcon);
			}

			$mainFuncs[] = array(
				'id' => (int)$id,
				'img' => $img,
				'name' => $name,
				'url' => $link,
				'isnew' => (int)$isNew,
				'label_icon' => $labelIcon,
				'category_id' => (int)$categoryID,
			);
		}

		return $mainFuncs;
	}


	public function getResourceImage($img = '', $nopic = '/common/images/nopic.png')
	{
		$res = new PublicFunV30();
		return $res->setAbleUploadImg($img);
//		$img = trim($img, '/ ');
//		if ($img) {
//			if (strstr($img, 'v30')) {
//				$url = F::getStaticsUrl('/common/' . $img);
//			} else {
//				$url = Yii::app()->ajaxUploadImage->getUrl($img);
//			}
//		} else {
//			$url = F::getStaticsUrl('' . $nopic);
//		}
//
//		return $url;
	}

	/**
	 * 获取第一栏功能栏
	 * @param int $customerID
	 * @return array
	 */
	public function getTopFunc()
	{
		$topFuncs = array();
		$criteria = new CDbCriteria();
		$criteria->select = 'resource_id, name, img, url, native, act';
		$criteria->condition = 'state = 0';
		$criteria->order = 'sort';

		$findFuncs = HomeConfigTopfunc::model()->findAll($criteria);
		if (!$findFuncs) {
			return $topFuncs;
		}

		foreach ($findFuncs as $func) {
			if ($func->act == 'proto') {
				$link = $this->getProtocolLink($func->native);
			} else {
				$link = $this->getResourceUrl($func->url, $func->resource_id);
			}

			if ($func->img) {
				$img = $this->getResourceImage($func->img);
			}

			$topFuncs[] = array(
				'img' => $img,
				'name' => $func->name,
				'url' => $link,
			);
		}


		return $topFuncs;
	}

	public function getCustomerMainFunc($customerID = 0)
	{
		return $this->getFunc(
			'SELECT
				*
			FROM(
			(SELECT
                f.id,
                f.img,
                f.`name`,
                f.act,
                f.url,
                f.native,
                f.resource_id,
                f.is_new,
                f.label_icon,
                f.category_id,
                f.state,
                f.sort
            FROM
                home_config_morefunc AS f
            WHERE
                f.id = 19
            )
            UNION    
            (SELECT
                f.id,
                f.img,
                f.`name`,
                f.act,
                f.url,
                f.native,
                f.resource_id,
                f.is_new,
                f.label_icon,
                f.category_id,
                f.state,
                f.sort
            FROM
                home_config_morefunc AS f
            LEFT JOIN home_config_mainfunc_tag_relation AS t ON (f.id = t.more_function_id)
            LEFT JOIN home_config_customer_tag_relation AS c ON (t.tag_id = c.tagid)
            WHERE
                c.customer_id = :customer_id
            )
            UNION
            (SELECT
                f.id,
                f.img,
                f.`name`,
                f.act,
                f.url,
                f.native,
                f.resource_id,
                f.is_new,
                f.label_icon,
                f.category_id,
                f.state,
                f.sort
            FROM
                home_config_morefunc AS f
            WHERE
                f.id = 496
            )
            ) AS u
            ORDER BY
			u.sort',
			array(
				array('key' => ':customer_id', 'value' => (int)$customerID, 'type' => PDO::PARAM_INT)
			)
		);
	}

	public function getCommunityMainFunc($communityID = 0)
	{
		return $this->getFunc(
			'SELECT
            DISTINCT f.id,
            f.img,
            f.`name`,
            f.act,
            f.url,
            f.native,
            f.resource_id,
            f.is_new,
            f.label_icon,
            f.category_id,
            f.state
        FROM
            home_config_morefunc AS f
        LEFT JOIN home_config_mainfunc_more_relation AS r ON (f.id = r.more_function_id)
        WHERE
            r.community_id in (0, :community_id)
        ORDER BY
            f.sort',
			array(
				array('key' => ':community_id', 'value' => (int)$communityID, 'type' => PDO::PARAM_INT)
			)
		);
	}

	/**
	 * 获取第二栏功能栏
	 * @param int $communityID
	 * @return array
	 */
	public function getMainFunc($communityID = 0, $customerID = 0)
	{
		$secondFunc = $this->getCustomerMainFunc($customerID);
		if (!$secondFunc || count($secondFunc) != 8) {
			$secondFunc = $this->getCommunityMainFunc($communityID);
			if (!$secondFunc) {
				$secondFunc = $this->getCommunityMainFunc();
			}
		}

		//只保留8个，最后一个是更多
		if ($secondFunc && count($secondFunc) > 8) {

			$tempFunc = array();
			$total = count($secondFunc);
			$index = 0;
			foreach ($secondFunc as $item) {

				if ($index < 7 || $index == $total - 1) {
					$tempFunc[] = $item;
				}
				$index++;
			}

			$secondFunc = $tempFunc;
		}

//		var_dump($secondFunc);

		return $secondFunc;
	}


	protected function getAd($sql = '', $params = array())
	{
		$command = Yii::app()->db->createCommand($sql);
		foreach ($params as $param) {
			$command->bindParam($param['key'], $param['value'], $param['type']);
		}

		$reader = $command->query();

		$id = 0;
		$resourceID = 0;
		$title = '';
		$img = '';
		$act = '';
		$url = '';
		$native = '';
		$startTime = '';
		$stopTime = '';


		$reader->bindColumn(1, $id);
		$reader->bindColumn(2, $resourceID);
		$reader->bindColumn(3, $title);
		$reader->bindColumn(4, $img);
		$reader->bindColumn(5, $act);
		$reader->bindColumn(6, $url);
		$reader->bindColumn(7, $native);
		$reader->bindColumn(8, $startTime);
		$reader->bindColumn(9, $stopTime);

		$firstFunc = array();

		while ($reader->read() !== false) {

			if ($act == 'proto') {
				$link = $this->getProtocolLink($native);
			} else {
				$link = $this->getResourceUrl($url, $resourceID);
			}

			if ($img) {
				$img = $this->getResourceImage($img);
			}

			$firstFunc[] = array(
				'id' => (int)$id,
				'img' => $img,
				'name' => $title,
				'url' => $link,
				'start_time' => $startTime,
				'stop_time' => $stopTime,
			);
		}

		return $firstFunc;
	}

	private function checkAdCommunity($adID = 0, $cid = 0)
	{
		$relation = HomeConfigAdCommunityRelation::model()->findAll(
			'ad_id = :ad_id',
			array(':ad_id' => $adID)
		);
		if ($relation) {
			//判断该业主所在的小区是否显示该应用
			foreach ($relation as $k => $v) {
				if ($v->community_id == $cid) return true;
			}
			return false;
		}
		return true;
	}

	public function getCommunityRelatedAd($communityID = 0)
	{
		return $this->getAd(
			'SELECT
				DISTINCT a.id,
				a.resource_id,
				a.title,
				a.img,
				a.act,
				a.url,
				a.native,
				a.start_time,
				a.stop_time,
				a.sort
			FROM
				home_config_ad AS a
			LEFT JOIN home_config_ad_community_relation AS r ON (a.id = r.ad_id)
			WHERE
				r.community_id in(0, :community_id)
			AND a.state = 0
			AND IF (a.start_time = 0, TRUE, a.start_time <= :timeline)
			AND IF (a.stop_time = 0, TRUE, a.stop_time >= :timeline)
			ORDER BY
				a.sort',
			array(
				array('key' => ':community_id', 'value' => $communityID, 'type' => PDO::PARAM_INT),
				array('key' => ':timeline', 'value' => time(), 'type' => PDO::PARAM_INT),
			)
		);
	}

	/**
	 * 获取广告
	 * @param int $communityID
	 * @return array
	 */
	public function getCommunityAd($communityID = 0)
	{
		$communityID = (int)$communityID;
		$ads = $this->getCommunityRelatedAd($communityID);
		if (!$ads) {
			$ads = $this->getCommunityRelatedAd();
		}

		$filterAds = array();
		if ($ads) {
			foreach ($ads as $ad) {
				switch ($ad['id']) {
					case 6:
					case 7:
						if ($ad['start_time'] < strtotime('2015-03-31 21:59:00')
							|| $ad['stop_time'] > strtotime('2015-05-31 23:59:59')
						) {
							continue 2;
						}
						break;
					case 11:
						if ($ad['start_time'] < strtotime('2015-04-23 00:00:00')
							|| $ad['stop_time'] > strtotime('2015-05-24 23:59:59')
						) {
							continue 2;
						}
						break;
					case 22:
						if ($ad['start_time'] < strtotime('2015-06-08 14:00:00')
							|| $ad['stop_time'] > strtotime('2015-06-14 22:00:00')
						) {
							continue 2;
						}
						break;
					case 31:
						if ($ad['start_time'] < strtotime('2015-07-21 00:00:00')
							|| $ad['stop_time'] > strtotime('2015-08-21 23:59:59')
						) {
							continue 2;
						}
						break;
					case 24:
						if ($ad['start_time'] < strtotime('2015-06-15 10:00:00')
							|| $ad['stop_time'] > strtotime('2015-06-25 17:00:00')
						) {
							continue 2;
						}
						break;
					case 37:
						if ($ad['start_time'] < strtotime('2015-08-05 00:00:00')
							|| $ad['stop_time'] > strtotime('2015-10-31 23:59:59')
						) {
							continue 2;
						}
						break;
					case 38:
						if ($ad['start_time'] < strtotime('2015-08-15 00:00:00')
							|| $ad['stop_time'] > strtotime('2015-10-31 23:59:59')
						) {
							continue 2;
						}
						break;
					case 25:
						if ($ad['start_time'] > strtotime('2015-06-30 23:59:59')) {
							$ad['name'] = '京东特供';
							$ad['img'] = $this->getResourceImage('v23/ad/adjd.png');
							$ad['url'] = str_replace('/?', '/jd/?', $ad['url']);
						}
						break;
				}

				/*if ($this->checkAdCommunity($ad['id'], $communityID) == false) {
					continue;
				}*/

				$filterAds[] = array(
					'name' => $ad['name'],
					'img' => $ad['img'],
					'url' => $ad['url']
				);
			}
		}

		return $filterAds;
	}


	protected function getActivity($sql = '', $params = array())
	{
		$command = Yii::app()->db->createCommand($sql);
		foreach ($params as $param) {
			$command->bindParam($param['key'], $param['value'], $param['type']);
		}

		$reader = $command->query();

		$id = 0;
		$resourceID = 0;
		$categoryID = 0;
		$title = '';
		$img = '';
		$act = '';
		$url = '';
		$native = '';
		$startTime = '';
		$stopTime = '';
		$state = '0';


		$reader->bindColumn(1, $id);
		$reader->bindColumn(2, $resourceID);
		$reader->bindColumn(3, $categoryID);
		$reader->bindColumn(4, $title);
		$reader->bindColumn(5, $img);
		$reader->bindColumn(6, $act);
		$reader->bindColumn(7, $url);
		$reader->bindColumn(8, $native);
		$reader->bindColumn(9, $startTime);
		$reader->bindColumn(10, $stopTime);
		$reader->bindColumn(11, $state);

		$activities = array();
		$datetime = strtotime('today');
		$datetimeEnd = strtotime('tomorrow');
		while ($reader->read() !== false) {
			if (!isset($activities[$categoryID])) {
				$category = HomeConfigActivityCategory::model()->findByPk($categoryID);
				if ($category) {
					$style = $category->style;
					$name = $category->name;
				} else {
					$name = '';
					$style = '1';
				}
				$activities[$categoryID] = array(
					//'id' => $categoryID,
					'title' => $name,
					'style' => $style,
					'attr' => array(),
				);
			}

			if ($act == 'proto') {
				$link = $this->getProtocolLink($native);
			} else {
				$link = $this->getResourceUrl($url, $resourceID);
			}

			if ($state == 1
				|| ($stopTime && $stopTime < $datetime)
				|| ($startTime && $startTime > $datetimeEnd)
			) {
				$link = '';
			}

			if ($img) {
				$img = $this->getResourceImage($img);
			}

			$activities[$categoryID]['attr'][] = array(
				'id' => (int)$id,
				'img' => $img,
				'name' => $title,
				'url' => $link,
				//'start_time' => $startTime,
				//'stop_time' => $stopTime,
			);
		}

		return $activities ? array_values($activities) : array();
	}

	public function getCommunityRelatedActivity($communityID = 0)
	{
		return $this->getActivity(
			'SELECT
				DISTINCT a.id,
				a.resource_id,
				a.activity_category_id,
				a.name,
				a.img,
				a.act,
				a.url,
				a.native,
				a.start_time,
				a.stop_time,
				a.state
			FROM
				home_config_activity AS a
			LEFT JOIN home_config_activity_community_relation AS r ON (a.activity_category_id = r.activity_category_id)
			WHERE
				r.community_id = :community_id
			ORDER BY
				a.activity_category_id, a.sort',
			array(
				array('key' => ':community_id', 'value' => $communityID, 'type' => PDO::PARAM_INT)
			)
		);
	}

	public function getCommunityDefaultActivity()
	{
		return $this->getActivity(
			'SELECT
				DISTINCT a.id,
				a.resource_id,
				a.activity_category_id,
				a.name,
				a.img,
				a.act,
				a.url,
				a.native,
				a.start_time,
				a.stop_time,
				a.state
			FROM
				home_config_activity AS a
			LEFT JOIN home_config_activity_community_relation AS r ON (a.activity_category_id = r.activity_category_id)
			WHERE
				r.community_id = :community_id
			ORDER BY
				a.activity_category_id, a.sort',
			array(
				array('key' => ':community_id', 'value' => 0, 'type' => PDO::PARAM_INT)
			)
		);
	}

	protected function getCommunityCategory($communityID = 0)
	{
		$communityID = (int)$communityID;
		$criteria = new CDbCriteria();
        $criteria->select = 'c.id, c.`name`, c.style';
        $criteria->alias = 'c';
        $criteria->join = 'LEFT JOIN home_config_activity_community_relation AS r ON (r.activity_category_id = c.id)';
		$criteria->condition = 'r.community_id in(:community_id, 0) AND c.state = 0 AND IF (c.start_time = 0, TRUE, c.start_time <= :timeline) AND IF (c.stop_time = 0, TRUE, c.stop_time >= :timeline)';
		$criteria->params = array(':community_id' => $communityID, ':timeline' => time());
        $criteria->order = 'r.community_id desc, c.id asc';
        $criteria->limit = 1;
		$communityCategory = HomeConfigActivityCategory::model()->find($criteria);
        /*
		$communityCategory = HomeConfigActivityCommunityRelation::model()->find(
			'community_id in(:community_id, 0) AND state = 0',
			array(
				':community_id' => (int) $communityID
			)
		);
         */
		if (!$communityCategory) {
			return false;
		}

        return $communityCategory;

		$category = HomeConfigActivityCategory::model()->find(
			'id = :activity_category_id AND state = 0 AND IF (start_time = 0, TRUE, start_time <= :timeline) AND IF (stop_time = 0, TRUE, stop_time >= :timeline)',
			array(
				':activity_category_id' => $communityCategory->activity_category_id,
				':timeline' => time(),
			)
		);
		if (!$category
		) {
			return false;
		}

		return $category;
	}

	/**
	 * 获取限时活动
	 * @param int $communityID
	 * @return array
	 */
	public function getCommunityActivity($communityID = 0)
	{
		/*$communityID = (int)$communityID;
		$activities = $this->getCommunityRelatedActivity($communityID);
		if (!$activities) {
			$activities = $this->getCommunityDefaultActivity();
		}

		return $activities;*/

		$activities = array();


		$category = $this->getCommunityCategory($communityID);
		if (!$category) {
			$category = $this->getCommunityCategory(0);
			if (!$category) {
				return $activities;
			}
		}

		$activities = $this->getActivityByCategoryId($category->id);

		return $activities ? array(
			'title' => $category->name,
			'style' => (int)$category->style,
			'attr' => $activities
		) : array();
	}

	/**
	 * 根据板块类别获取限时活动
	 * @param int $activityCategoryId
	 * @return array
	 */
	public function getActivityByCategoryId($activityCategoryId)
	{
		$activities = array();
		$datetime = strtotime('today');
		$datetimeEnd = strtotime('tomorrow');

		$criteria = new CDbCriteria();
		$criteria->condition = 'activity_category_id = :activity_category_id AND state = 0 AND IF (start_time = 0, TRUE, start_time <= :timeline) AND IF (stop_time = 0, TRUE, stop_time >= :timeline)';
		$criteria->params = array(':activity_category_id' => $activityCategoryId, ':timeline' => time());
		$criteria->order = 'sort';

		$categoryActivites = HomeConfigActivity::model()->findAll($criteria);
		if (!$categoryActivites) {
			return $activities;
		}

		foreach ($categoryActivites as $activity) {
			if ($activity->state == 1
				|| ($activity->stop_time && $activity->stop_time < $datetime)
				|| ($activity->start_time && $activity->start_time > $datetimeEnd)
			) {
				$link = '';
			} else {
				if ($activity->act == 'proto') {
					$link = $this->getProtocolLink($activity->native);
				} else {
					$link = $this->getResourceUrl($activity->url, $activity->resource_id, false);
				}
			}

			$activities[] = array(
				'img' => $this->getResourceImage($activity->img),
				'name' => $activity->name,
				'url' => $link,
			);
		}

		return $activities;
	}

	protected function getCls($sql = '', $params = array())
	{
		$command = Yii::app()->db->createCommand($sql);
		foreach ($params as $param) {
			$command->bindParam($param['key'], $param['value'], $param['type']);
		}

		$reader = $command->query();

		$id = 0;
		$img = '';
		$name = '';
		$act = '';
		$url = '';
		$native = '';
		$resourceID = 0;
		$isNew = 0;
		$labelIcon = 0;
		$categoryID = 0;

		$reader->bindColumn('id', $id);
		$reader->bindColumn('img', $img);
		$reader->bindColumn('name', $name);
		$reader->bindColumn('act', $act);
		$reader->bindColumn('url', $url);
		$reader->bindColumn('native', $native);
		$reader->bindColumn('resource_id', $resourceID);
		$reader->bindColumn('is_new', $isNew);
		$reader->bindColumn('label_icon', $labelIcon);
		$reader->bindColumn('category_id', $categoryID);

		$moreFunc = array();

		while ($reader->read() !== false) {
			if (!isset($moreFunc[$categoryID])) {
				$moreFunc[$categoryID] = array(
					'id' => (int)$categoryID,
					'name' => HomeConfigMoreCategory::GetCategoryName($categoryID),
					'attr' => array(),
				);
			}

			if ($act == 'proto') {
				$link = $this->getProtocolLink($native);
			} else {
				$link = $this->getResourceUrl($url, $resourceID);
			}

			if ($img) {
				$img = $this->getResourceImage($img);
			}

			$moreFunc[$categoryID]['attr'][] = array(
				'id' => (int)$id,
				'img' => $img,
				'name' => $name,
				'url' => $link,
				'isnew' => (int)$isNew,
				'label_icon' => $labelIcon ? $this->getResourceImage($labelIcon) : '',
				'category_id' => (int)$categoryID,
			);
		}

		return $moreFunc;
	}

	protected function getSectionCls($sql = '', $params = array(), $isCategory=true)
	{
		$command = Yii::app()->db->createCommand($sql);
		foreach ($params as $param) {
			$command->bindParam($param['key'], $param['value'], $param['type']);
		}

		$reader = $command->query();

		$id = 0;
		$img = '';
		$name = '';
		$act = '';
		$url = '';
		$native = '';
		$resourceID = 0;
		$isNew = 0;
		$labelIcon = 0;
		$categoryID = 0;

		$reader->bindColumn('id', $id);
		$reader->bindColumn('img', $img);
		$reader->bindColumn('name', $name);
		$reader->bindColumn('act', $act);
		$reader->bindColumn('url', $url);
		$reader->bindColumn('native', $native);
		$reader->bindColumn('resource_id', $resourceID);
		$reader->bindColumn('is_new', $isNew);
		$reader->bindColumn('label_icon', $labelIcon);
		$reader->bindColumn('category_id', $categoryID);

		$moreFunc = array();
		$list = array();

		while ($reader->read() !== false) {
			if (!isset($moreFunc[$categoryID])) {
				$moreFunc[$categoryID] = array(
						'id' => (int)$categoryID,
						'name' => HomeConfigSectionCategory::GetCategoryName($categoryID),
						'attr' => array(),
				);
			}

			if ($act == 'proto') {
				$link = $this->getProtocolLink($native);
			} else {
				$link = $this->getResourceUrl($url, $resourceID);
			}

			if ($img) {
				$img = $this->getResourceImage($img);
			}

			$moreFunc[$categoryID]['attr'][] = array(
					'id' => (int)$id,
					'img' => $img,
					'name' => $name,
					'url' => $link,
					'isnew' => (int)$isNew,
					'label_icon' => $labelIcon ? $this->getResourceImage($labelIcon) : '',
					'category_id' => (int)$categoryID,
			);

			$list[] = array(
					'id' => (int)$id,
					'img' => $img,
					'name' => $name,
					'url' => $link,
					'isnew' => (int)$isNew,
					'label_icon' => $labelIcon ? $this->getResourceImage($labelIcon) : '',
					'category_id' => (int)$categoryID,
			);
		}


		return $isCategory ? $moreFunc : $list;
	}

	public function getCommunityMoreCls($communityID = 0)
	{
		return $this->getCls(
			'SELECT
            DISTINCT m.id,
            m.category_id,
            m.resource_id,
            m.`name`,
            m.img,
            m.act,
            m.url,
            m.native,
            m.is_new,
            m.label_icon,
            m.sort
        FROM
            home_config_morefunc AS m
        LEFT JOIN home_config_morefunc_community_relation AS c ON (m.id = c.function_id)
        WHERE
            c.community_id in(0, :community_id)
        AND m.state = 0
        ORDER BY
            m.category_id,
            m.sort',
			array(
				array('key' => ':community_id', 'value' => $communityID, 'type' => PDO::PARAM_INT)
			)
		);
		/*return $this->getCls(
			'SELECT
			m.id,
			m.category_id,
			m.resource_id,
			m.`name`,
			m.img,
			m.act,
			m.url,
			m.native,
			m.is_new,
			m.label_icon
		FROM
			home_config_morefunc AS m
		LEFT JOIN home_config_morefunc_community_relation AS c ON (m.id = c.function_id)
		LEFT JOIN home_config_mainfunc_more_relation AS r ON (
			m.id = r.more_function_id
			AND c.community_id = r.community_id
		)
		WHERE
			c.community_id = :community_id
		AND m.state = 0
		AND ISNULL(r.id) = 1
		ORDER BY
			m.category_id,
			m.sort',
			array(
				array('key' => ':community_id', 'value' => $communityID, 'type' => PDO::PARAM_INT)
			)
		);*/
	}

	public function getSectionMoreCls($categoryId, $communityID = 0)
	{
		return $this->getSectionCls(
				'SELECT
            DISTINCT m.id,
            m.category_id,
            m.resource_id,
            m.`name`,
            m.img,
            m.act,
            m.url,
            m.native,
            m.is_new,
            m.label_icon,
            m.sort
        FROM
            home_config_sectionfunc AS m
        LEFT JOIN home_config_section_community_relation AS c ON (m.id = c.function_id)
        WHERE
            c.community_id IN(0, :community_id)
        AND m.category_id = :category_id
        AND m.state = 0
        ORDER BY
            m.category_id,
            m.sort',
				array(
						array('key' => ':community_id', 'value' => $communityID, 'type' => PDO::PARAM_INT),
						array('key' => ':category_id', 'value' => $categoryId, 'type' => PDO::PARAM_INT),
				)
		);
	}

	/**
	 * 获取更多
	 * @param int $communityID
	 * @return array
	 */
	public function getMoreCls($communityID = 0)
	{
		$moreCls = $this->getCommunityMoreCls($communityID);
		if (!$moreCls && $communityID) {
			$moreCls = $this->getCommunityMoreCls();
		}

		return $moreCls ? array_values($moreCls) : array();
	}

	/**
	 * 获取板块栏
	 * @param $sectionCode
	 * @param $communityID
	 * @return array
	 */
	public function getSectionFunc($sectionCode, $communityID=0)
	{
		$result = array();

		$criteria = new CDbCriteria();
		$criteria->select = 'id, name';
		$criteria->condition = 'section_code = :section_code';
		$criteria->params = array(':section_code' => $sectionCode);

		$list = HomeConfigSectionCategory::model()->findAll($criteria);
		if (!$list)
			return $result;

		foreach ($list as $row) {
			$result[] = array(
				'name' => $row->name,
				'list' => $this->getSectionFuncByCategoryId($row->id, $communityID)
			);
		}

		return $result;
	}

	/**
	 * 获取板块下分类
	 * @param $sectionCode
	 * @param $customerID
	 * @return array
	 */
	public function getSectionCategory($sectionCode, $customerID=0)
	{
		$result = array();

		$criteria = new CDbCriteria();
		$criteria->select = 'id, name, section_code';
		$criteria->condition = 'section_code = :section_code';
		$criteria->params = array(':section_code' => $sectionCode);
		$criteria->order = 'sort ASC';

		$list = HomeConfigSectionCategory::model()->findAll($criteria);
		if (!$list)
			return $result;

		$connection = Yii::app()->db;

		foreach ($list as $row) {
			$categoryId = $row->id;
			$line = array(
				'id' => $categoryId,
				'section_code' => $row->section_code,
				'name' => $row->name,
				'is_new' => 0,
			);

			if (empty($customerID)) {
				$ret = HomeConfigSectionFunc::model()->find('category_id=:category_id AND state=0 AND is_new=1', array(':category_id' => $row->id));
			}
			else {
				$sql = 'SELECT
					 		`hcf`.`id`
						FROM `home_config_sectionfunc` `hcf`
						INNER JOIN `home_config_section_category` `hcsc` ON (`hcf`.`category_id` = `hcsc`.`id`)
						WHERE `hcsc`.`id` = :category_id
					 		AND `hcf`.`is_new`=1
					 		AND `hcf`.`state`=0
					 		AND NOT EXISTS ( SELECT * FROM `home_config_sectionfunc_new_tip` `hcft`  WHERE `hcft`.`function_id`=`hcf`.`id` AND `hcft`.`customer_id`=:customer_id)';


				$command = $connection->createCommand($sql);
				$command->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
				$command->bindParam(':customer_id', $customerID, PDO::PARAM_INT);

				$ret = $command->queryRow();
			}

			if ($ret)
				$line['is_new'] = 1;

			$result[] = $line;
			unset($line);
		}

		return $result;

	}

	/**
	 * 根据分类获取板块栏
	 * @param $categoryId
	 * @param $communityID
	 * @return array
	 */
	public function getSectionFuncByCategoryId($categoryId, $communityID=0)
	{
		$list = array();

		$criteria = new CDbCriteria();
		$criteria->select = 'id, resource_id, name, img, url, native, act';
		$criteria->condition = 'category_id = :category_id AND state = 0';
		$criteria->params = array(':category_id' => $categoryId);
		$criteria->order = 'sort';

		$findFuncs = HomeConfigSectionFunc::model()->findAll($criteria);
		if (!$findFuncs) {
			return $list;
		}

		foreach ($findFuncs as $func) {

			$sql = 'SELECT `community_id` FROM `home_config_section_community_relation` WHERE `function_id`='.$func->id;
			$relation = Yii::app()->db->createCommand($sql)->queryColumn();
			$key = array_search(0, $relation);
			if ($key !== false)
				array_splice($relation, $key, 1);

			if ($relation && !in_array($communityID, $relation))
				continue;

			if ($func->act == 'proto') {
				$link = $this->getProtocolLink($func->native);
			} else {
				$link = $this->getResourceUrl($func->url, $func->resource_id);
			}

			if ($func->img) {
				$img = F::getStaticsUrl('/common/' . $func->img);
			}

			$list[] = array(
				'img' => $img,
				'name' => $func->name,
				'url' => $link,
			);
		}

		return $list;
	}

	/**
	 * 获取分类下的所有应用
	 * @param $categoryId
	 * @param int $communityID
	 * @return array
	 */
	public function getSectionCategoryFunc($categoryId, $communityID=0)
	{
		$moreCls = $this->getSectionMoreCls($categoryId, $communityID);
		if (!$moreCls && $communityID) {
			$moreCls = $this->getSectionMoreCls($categoryId);
		}

		return $moreCls ? array_values($moreCls) : array();
	}

	/**
	 * 获取用户常用功能
	 * @param $customerId
	 * @return array
	 */
	public function getUserFunc($customerId)
	{
		$result = $this->getSectionCls(
				'SELECT
            DISTINCT m.id,
            m.category_id,
            m.resource_id,
            m.`name`,
            m.img,
            m.act,
            m.url,
            m.native,
            m.is_new,
            m.label_icon,
            m.sort
        FROM
            home_config_sectionfunc AS m
        INNER JOIN home_config_common AS c ON (m.id = c.function_id)
        WHERE
            c.state = 1
        AND c.customer_id = :customer_id
        AND m.state = 0
        ORDER BY
            m.category_id,
            m.sort',
				array(
						array('key' => ':customer_id', 'value' => $customerId, 'type' => PDO::PARAM_INT),
				),
				false
		);

		return $result ? array_values($result) : array();
	}

	/**
	 * 更新我的应用
	 * @param $customerId
	 * @param $functionID
	 * @param $state
	 * @return bool
	 */
	public function updateUserFunc($customerId, $functionID, $state)
	{
		$functionModel = HomeConfigSectionFunc::model()->findByPk($functionID);
		if (!$functionModel)
			return false;

		$db = Yii::app()->db;
		$sql = 'SELECT * FROM `home_config_common` WHERE `customer_id`='.$customerId.' AND `function_id`='.$functionID;
		$result = $db->createCommand($sql)->queryRow();
		if ($result) {
			if ( 1 === $state && 1 == $result['state'])
				return true;
			if (1 === $state && 0 == $result['state'])
				$state = 1;
			if (0 === $state && 1 == $result['state'])
				$state = 0;

			$updateSql = 'UPDATE `home_config_common` SET `state`='.$state.' WHERE `customer_id`='.$customerId.' AND `function_id`='.$functionID;
			$ret = $db->createCommand($updateSql)->execute();
			if ($ret)
				return true;
		}
		else {
			if (0 === $state)
				return true;

			$ret = $db->createCommand()->insert(
					'home_config_common',
					array(
							'customer_id' => $customerId,
							'function_id' => $functionID,
							'state' => 1,
							'update_time' => time()
					)
			);

			if ($ret)
				return true;
		}

		return false;
	}

	/**
	 * 首页获取未读信息
	 * @return array
	 */
	public function getUnreadPushMessage()
	{

		$user_id = Yii::app()->user->id;

		$push = PushClient::model()->findBySql("SELECT `id`,`push_information_id`,`object_id`,`is_read`,`mobile`,`android_request_id`,`ios_request_id`,`create_time` FROM push_client
		WHERE `is_read`=0 AND `object_id`=:object_id AND `type`=:type ORDER BY `create_time` DESC",
			array(":object_id" => $user_id, ":type" => PushInformation::IS_TYPE_CUSTOMER));
		if (!empty($push)) {
			$info = array(
				'id' => (int)$push->id,
				'title' => empty($push->pushInformation) ? "" : $push->pushInformation->title,
				'content' => empty($push->pushInformation) ? "" : $push->pushInformation->content,
				//"is_read" =>$push->is_read,
				"create_time" => $push->create_time,
				'url' =>  "" ,
				'resource_id' =>0 ,
//				'url' => empty($push->pushInformation) ? "" : $push->pushInformation->url,
//				'resource_id' => empty($push->pushInformation) ? 0 : $push->pushInformation->resource_id,
			);

			$data = array();
			$data[] = $info;

			return $data;
		} else {
			return array();
		}

	}

	/**
	 * 热卖商品
	 * @param $sectionCode
	 * @param $customerId
	 * @param $communityID
	 * @return array
	 */
	public function getHotGoods($sectionCode, $customerId, $communityID)
	{
		$sql = 'SELECT * FROM `home_config_hot_goods` WHERE `section_code`=:section_code AND `state`=1 ORDER BY `sort` ASC LIMIT 4';

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':section_code', $sectionCode, PDO::PARAM_INT);

		$list = $command->queryAll();

		$result = array(
			'title' => '热卖商品',
			'list' => array(),
		);
		if ($list) {
			$SetableSmallLoansModel = new SetableSmallLoans();
			$href = $SetableSmallLoansModel->searchByIdAndType(39, '', $customerId);

			foreach($list as $row) {
				$result['list'][] = array(
						'image' => F::getStaticsUrl('/common/' . $row['image']),
						'url' => $href->completeURL.'&pid='.$row['goods_id']
					);
			}
		}

		return $result;
	}

	/**
	 * 分类图标提醒
	 * @param $categoryId
	 * @param $customerID
	 */
	public function sectionFunctionNewTip($categoryId, $customerID)
	{
		$sql = 'SELECT `hcf`.`id` FROM `home_config_sectionfunc` `hcf`
				INNER JOIN `home_config_section_category` `hcsc` ON (`hcf`.`category_id` = `hcsc`.`id`)
				WHERE `hcsc`.`id` = :category_id
				 AND `hcf`.`is_new`=1
				 AND `hcf`.`state`=0
				 AND NOT EXISTS ( SELECT * FROM `home_config_sectionfunc_new_tip` `hcft`  WHERE `hcft`.`function_id`=`hcf`.`id` AND `hcft`.`customer_id`=:customer_id)';

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
		$command->bindParam(':customer_id', $customerID, PDO::PARAM_INT);

		$result = $command->queryAll();

		if ($result) {
			unset($command);
			$connection = Yii::app()->db;
			$command = $connection->createCommand();

			foreach ($result as $row) {
				$columArray = array(
					'function_id' => $row['id'],
					'customer_id' => $customerID,
					'create_time' => time()
				);

				$command->reset();
				$command->insert('home_config_sectionfunc_new_tip', $columArray);
			}
		}
	}

	/**
	 * 获取主题
	*/
	public function getTheme(){
		$sql = "SELECT * FROM home_config_theme WHERE state=0 LIMIT 1";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		if($result){
			return $result[0];
		}else{
			return false;
		}
	}

	//获取饭票排名
	public function getTop($customer_id , $community_id){
		$sql =
			"
			SELECT  a.resource_id,
				a.top_ad_img,
				a.top_ad_url,
				a.top_ad_one,
				a.top_ad_two,
				a.top_ad_three
			FROM home_config_top_ad AS a
			LEFT OUTER JOIN home_config_top_ad_community_relation AS b
			ON a.id=b.top_ad_id
			WHERE
				a.state=0 AND
				community_id = {$community_id}
				ORDER BY b.last_time DESC LIMIT 1;
	  		"
		;
		$command = Yii::app()->db->createCommand($sql)->queryAll();
		$param = [];
		if($command && !empty($command))
		{
			$res = new PublicFunV23();
			$param = array(
				'bg_img' => $res->setAbleUploadImg($command[0]['top_ad_img']),
				'bg_url' => empty($command[0]['top_ad_url']) ? '' : $command[0]['top_ad_url'],
				'list'=>array(
					empty($command[0]['top_ad_one']) ? array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smask@3x.png','name'=>'了解饭票','url'=>'http://m.colourlife.com/Notice/MealInfo') :json_decode($command[0]['top_ad_one']),
					empty($command[0]['top_ad_two']) ? array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smmiji@3x.png','name'=>'省钱秘笈','url'=>'http://m.colourlife.com/Notice/MealUrl') :json_decode($command[0]['top_ad_two']),
					empty($command[0]['top_ad_three']) ? array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_mytickettt@3x.png','name'=>'我的饭票','url'=>'colourlife://proto?type=Ticket') :json_decode($command[0]['top_ad_three']),
				)
			);
			if(!empty($command[0]['resource_id']))
			{
				$HomeConfig = new HomeConfigResource();
				$resource = $HomeConfig->getResourceByKeyOrId($command[0]['resource_id'],1,$customer_id);//dump($resource);
				if($resource)
				{
					if($resource->act == 'proto'){
						$param['bg_url'] = $this->getProtocolLink($resource->native);
					} else {
						$param['bg_url'] = $this->getResourceUrl($resource->url, $resource->id);
					}
				}
			}
		}
		return $param;







		$customerModel = Customer::model()->findByPk($customer_id);
		$community_arr = ['2069' ,'2070' ,'2068' ,'2073' ,'2071','4234','1532','2075','2076','2074'];
		$community_arr_zhongzhu = ['4814' ,'4833' ];
		$community_arr_yinwan = ['5807', '5808', '5809', '5810'];

		if(in_array($customerModel->community_id , $community_arr_zhongzhu) && $customer_id != 1511998)
		{
			$param = array(
				'bg_img'=>F::getStaticsUrl('/common/v30/ad/').'zhongzhu.png',
				'bg_url'=>'',
				'list'=>array(
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smask@3x.png','name'=>'了解饭票','url'=>'http://m.colourlife.com/Notice/MealInfo'),
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smmiji@3x.png','name'=>'省钱秘笈','url'=>'http://m.colourlife.com/Notice/MealUrl'),
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_mytickettt@3x.png','name'=>'我的饭票','url'=>'colourlife://proto?type=Ticket')
				)
			);
			return $param;
		}

        if(in_array($customerModel->community_id , $community_arr_yinwan) && $customer_id != 1511998)
        {
            $param = array(
                'bg_img'=>F::getStaticsUrl('/common/v30/ad/').'shyw@3x.png',
                'bg_url'=>'',
                'list'=>array(
                    array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smask@3x.png','name'=>'了解饭票','url'=>'http://m.colourlife.com/Notice/MealInfo'),
                    array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smmiji@3x.png','name'=>'省钱秘笈','url'=>'http://m.colourlife.com/Notice/MealUrl'),
                    array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_mytickettt@3x.png','name'=>'我的饭票','url'=>'colourlife://proto?type=Ticket')
                )
            );
            return $param;
        }

		if(in_array($customerModel->community_id , $community_arr) && $customer_id != 1511998)
		{
			$param = array(
				'bg_img'=>F::getStaticsUrl('/common/v30/ad/').'duujksab.png',
				'bg_url'=>'',
				'list'=>array(
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smask@3x.png','name'=>'了解饭票','url'=>'http://m.colourlife.com/Notice/MealInfo'),
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smmiji@3x.png','name'=>'省钱秘笈','url'=>'http://m.colourlife.com/Notice/MealUrl'),
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_mytickettt@3x.png','name'=>'我的饭票','url'=>'colourlife://proto?type=Ticket')
				)
			);
			return $param;
		}
		$result = F::getHomeUrl('/colourHouseVip.html');
		$param = array(
			'bg_img'=>F::getStaticsUrl('/common/v30/ad/').'bj2x.png',
			//'bg_url'=>$result->completeURL,
			'bg_url'=>$result,
			'list'=>array(
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smask@3x.png','name'=>'了解饭票','url'=>'http://m.colourlife.com/Notice/MealInfo'),
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_smmiji@3x.png','name'=>'省钱秘笈','url'=>'http://m.colourlife.com/Notice/MealUrl'),
					array('img'=>F::getStaticsUrl('/lumen/images/func/').'icon_home_mytickettt@3x.png','name'=>'我的饭票','url'=>'colourlife://proto?type=Ticket')
				)
		);
		return $param;
	}


	/**
	 * 获取更多下的某一栏应用
	 * @param int $communityID
	 * @return array
	 */
	public function getMoreCategory($communityID = 0 , $categoryID)
	{
		if(empty($categoryID)){
			return false;
		}
		return $this->getCls(
			'SELECT
            DISTINCT m.id,
            m.category_id,
            m.resource_id,
            m.`name`,
            m.img,
            m.act,
            m.url,
            m.native,
            m.is_new,
            m.label_icon,
            m.sort
        FROM
            home_config_morefunc_tickmall AS m
        LEFT JOIN home_config_morefunc_community_relation AS c ON (m.id = c.function_id)
        WHERE
            c.community_id in(0, :community_id)
        AND m.category_id =:category_id
        AND m.state = 0
        ORDER BY
            m.category_id,
            m.sort',
			array(
				array('key' => ':community_id', 'value' => $communityID, 'type' => PDO::PARAM_INT),
				array('key' => ':category_id', 'value' => $categoryID, 'type' => PDO::PARAM_INT),
			)
		);
	}
}
