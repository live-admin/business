<?php

class CommunityLogic
{
	public static function getRegionCommunity($employee_id, $type = 'list', $searchContent = "", $page = 1, $pagesize = 10)
	{
//        $criteria = new CDbCriteria;
//        $branchList = $regionIds = array();
//
//        $employee = Employee::model()->enabled()->findByPk($employee_id);
//
//        $branchList = $employee->getBranchIds();
//        if($type=="search"){
//            $criteria->addCondition("`name` LIKE '%".$searchContent."%' OR `domain` LIKE '%".$searchContent."%'");
//        }
//        $criteria->addInCondition('branch_id', $branchList);
//        $criteria->order="domain ASC";
//        $count=count(Community::model()->enabled()->findAll($criteria));
//        $criteria->limit=$pagesize;
//        $page_count=(intval($page)-1)*$pagesize;
//        $criteria->offset=$page_count;
//        $communityLists = Community::model()->enabled()->findAll($criteria);
//        $list = array();
//        if (count($communityLists) > 0) {
//            $num=0;
//            foreach ($communityLists as $community) {
//                $list[$num]['id']=$community->id;
//                $list[$num]['name']=$community->name;
//                $list[$num]['total']=$count;
//                $list[$num]['branch']=Branch::model()->getMyParentBranchName($community->branch_id,$isOneSelf=false);
//                $num++;
//            }
//        }
//        return $list;

//      ICE 接入 获取这个employee_id组织架构下的小区
		########和actionCommunitiesByBranch方法类似 参考了ICEGetOrgCommunity方法#######

		$employee = ICEEmployee::model()->findByPk($employee_id);

		$result = array();
		if (empty($employee->orgId)) {
			return $result;
		}

		$communityIDs = array();
		$communityList = array();
//		如果是搜索的话
		if ($type == "search") {
			$communities = ICECommunity::model()->ICECommunitySearchAllData(
				array(
					'pid' => $employee->orgId,
					'keyword' => $searchContent,
				),
				true
			);

			// 内部账号所在组织结构所有子节点
			$orgs = ICEBranch::model()->findByPk($employee->orgId)->ICEGetOrgSubs();

			// 合并 jurisdiction/account 权限分配的组织结构节点
			$jurOrgs = $employee->ICEGetAccountJurisdiction();
			if ($jurOrgs && is_array($jurOrgs)) {
				foreach ($jurOrgs as $org) {
					$uuid = $org['org_id'];

					// 如果分配的权限已经包含在账号所属组织结构，则跳过
					if (in_array($uuid, $orgs)) {
						continue;
					}

					$communities = array_merge(
						$communities,
						ICECommunity::model()->ICECommunitySearchAllData(
							array(
								'pid' => $uuid,
								'keyword' => $searchContent,
							),
							$org['is_all'] == 1
						)
					);
				}
			}

			foreach ($communities as $community) {
				if (!isset($community['czy_id']) || !$community['czy_id']) {
					continue;
				}

				$communityIDs[$community['czy_id']] = $community;

			}

			$communityList = array_values($communityIDs);
		} else {
//			如果是list列表的话
			//		取出所有节点数据  $isOnly代表是不是只取id
			$isOnly = false;
			$communityList = $employee->ICEGetOrgCommunity($isOnly);
		}


//		统计结果总数 为返回值添加total
		$count = count($communityList);

//      分页 用array_slice弹出数组(优化 只拼接弹出的)
		$communities = array_slice($communityList, (intval($page) - 1) * intval($pagesize), intval($pagesize));

		$result = array();
//		拼接community集合数据
		foreach ($communities as $k => $community) {
//			为下面获取branchString
			$forbranchString = ICECommunity::model()->FindByPk($community['czy_id']);

			$result[$k]['id'] = $community['czy_id'];
			$result[$k]['name'] = $community['name'];
			$result[$k]['total'] = $count;
			$result[$k]['branch'] = '';
			if (!empty($forbranchString)) {
				$result[$k]['branch'] = $forbranchString->branchString;
			}
		}

		return $result;


	}
}