<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>四方协议</title>
		<meta name="format-detection" content="telephone=no" />
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>css/layout.css" />
	</head>
	<body>
		<div class="contaner agment">
		<?php if (!empty($data)){?>
			<div class="title">
				<p>借款协议</p>
				<p></p>
			</div>
			
			<!--甲方-->
			<div class="first ">
				<div class="first_one_box">
					<div class="first_one_box_p1">
						<p>甲方(出借人)：</p>
					</div>	
					<div class="first_one_box_p2">
						<p></p>
					</div>	
				</div>
				<div class="first_two_box">
					<div class="first_two_box_p1">
						<p>钱生花用户名：</p>
					</div>	
					<div class="first_two_box_p2">
						<p><?php echo $this->getName($data['investerName']);?></p>
					</div>	
				</div>
				<div class="first_three_box">
					<div class="first_three_box_p1">
						<p>身份证号/工商注册号：</p>
					</div>	
					<div class="first_three_box_p2">
						<p><?php echo substr($data['investerIdNo'], 0, -4).'****';?></p>
					</div>	
				</div>
			</div>
			
			<!--乙方-->
			<div class="first">
				<div class="first_one_box">
					<div class="first_one_box_p1">
						<p>乙方(借款人)：</p>
					</div>	
					<div class="first_one_box_p2">
						<p></p>
					</div>	
				</div>
				<div class="first_two_box">
					<div class="first_two_box_p1">
						<p>钱生花用户名：</p>
					</div>	
					<div class="first_two_box_p2">
						<p><?php echo $this->getName($data['borrowerName']);?></p>
					</div>	
				</div>
				<div class="first_three_box">
					<div class="first_three_box_p1">
						<p>身份证号/工商注册号：</p>
					</div>	
					<div class="first_three_box_p2">
						<p><?php echo substr($data['borrowerIdNo'],0,-4).'****';?></p>
					</div>	
				</div>
			</div>
			
			<!--丙方-->
			<div class="first">
				<div class="first_one_box">
					<div class="first_one_box_p1 bing_one_box_p1">
						<p>丙方(借款管理服务方)：</p>
					</div>	
				</div>
				<div class="first_two_box">
					<div class="first_two_box_p1 bing_one_box_p1">
						<p><?php echo $data['userC'];?></p>
					</div>	
				</div>
				<div class="first_three_box">
					<div class="first_three_box_p1 bing_one_box_p1">
						<p>联系方式：0755-82044824</p>
					</div>	
				</div>
			</div>
			
			<!--丁方-->
			<div class="first ">
				<div class="first_one_box">
					<div class="first_one_box_p1 bing_one_box_p1">
						<p>丁方(网络服务方)：</p>
					</div>	
				</div>
				<div class="first_two_box">
					<div class="first_two_box_p1 bing_one_box_p1">
						<p><?php echo $data['userD'];?></p>
					</div>	
				</div>
				<div class="first_three_box">
					<div class="first_three_box_p1 bing_one_box_p1">
						<p>联系方式：0755-83025306</p>
					</div>	
				</div>
			</div>
			<?php if (!empty($data['investTime'])){
				$date=explode("-", $data['investTime']);				
			}?>
			<div class="second">
				<h4>鉴于：</h4>
				<p>1、丙方是一家在深圳市合法成立并有效存续的有限责任公司，负责为出借人与借款人报告订立合同的机会，审查借款人的借款申请及相关文件，对借款人是否具备还款能力等情况进行判断，并受出借人的委托负责借款的日常管理工作；</p>	
				<p>2、丁方是一家在深圳市合法成立并有效存续的有限责任公司，拥有钱生花PC平台（域名：www.qianshenghua.com）、APP平台和微信平台等（以下简称“钱生花”）的经营权，为出借人与借款人提供咨询及信息服务；</p>	
				<p>3、甲方、乙方已在钱生花注册，双方均承诺提供给丁方的信息是完全真实的；</p>	
				<p>4、乙方有借款需求，甲方同意向乙方提供借款，双方有意建立借贷关系；</p>
				<p>5、甲方保证对本协议涉及的出借款具有完全的支配能力，本协议所涉及的出借款为其合法所得的自有财产；</p>	
				<p>6、各方约定，当乙方对甲方还款逾期时，由丙方受让甲方对乙方的债权。</p>
				<p>以上各方经协商一致，于[ <span><?php echo $date[0];?></span> ]年[ <span><?php echo $date[1];?></span> ]月[ <span><?php echo $date[2];?></span> ]日签订如下协议，共同遵照履行：</p>
			</div>
			<div class="second_table">
				<h4>第一条 借款基本信息</h4>
				<div class="second_table_biao">
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1">
							<p>借款本金</p>
						</div>
						<div class="second_table_biao_box_p2">
							<p><?php echo $data['investedAmount'];?></p>
						</div>
					</div>
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1">
							<p>还款方式</p>
						</div>
						<div class="second_table_biao_box_p2">
							<p><?php echo $data['repayType'];?></p>
						</div>
					</div>
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1">
							<p>借款期限(月)</p>
						</div>
						<div class="second_table_biao_box_p2">
							<p><?php echo $data['loanPeriod'];?></p>
						</div>
					</div>
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1">
							<p>放款日</p>
						</div>
						<div class="second_table_biao_box_p2">
							<p><?php echo $data['creditTime'];?></p>
						</div>
					</div>
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1">
							<p>还款日</p>
						</div>
						<div class="second_table_biao_box_p2">
							<p><?php echo $data['creditDate'];?></p>
						</div>
					</div>
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1">
							<p>最终到期日</p>
						</div>
						<div class="second_table_biao_box_p2">
							<p><?php echo $data['creditEndTime'];?></p>
						</div>
					</div>
					<div class="second_table_biao_box">
						<div class="second_table_biao_box_p1 second_table_biao_box_p1_last">
							<p>借款用途</p>
						</div>
						<div class="second_table_biao_box_p2 second_table_biao_box_p2_last">
							<p><?php echo $data['productUsage'];?></p>
						</div>
					</div>
				</div>
				
					<div class="second_p2">
						<p>注： 1、借款期限是指自放款日起至最终到期日止的期间，每月为一期。</p>
						<p>2、放款日是指借款本金由第三方支付机构从甲方的个人托管账户中成功划出的日期，此日期以第三方支付机构的划款凭证记载为准。本次借款本金分多笔从甲方账户中划扣，资金全部到达乙方提现账户的时间可能不在同一天，但放款日以第一笔借款本金成功从甲方账户划扣的时间为准。</p>
						<p>3、还款日为法定节假日的，自动提前至节假日前最后一个工作日。</p>
						<p>4、最终到期日是指借款期限最后一个月的还款日。</p>
					</div>
					
					<div class="second_p3">
						<h4>第二条 各方权利和义务</h4>
						<h4 >(一)<span style="text-decoration: underline;">甲方的权利和义务</span></h4>
						<p>1、甲方应按本协议的约定在放款日将借款本金足额支付给乙方。</p>
						<p>2、甲方同意向乙方出借相应借款本金时，授权丁方向第三方支付机构发出指令将该借款本金由甲方的个人托管账户直接划转至乙方的个人托管账户。</p>
						<p>3、甲方享有其所出借款项带来的利息收益的权利。</p>
						<p>4、甲方应确保其向丁方提供的信息和资料的真实性，并保证其出借的借款本金来源合法，甲方是该借款本金的合法所有人，如果第三人对该借款归属、合法性问题发生争议，由甲方负责解决。如甲方未能按照丁方的要求解决，则放弃其所出借的借款本金所带来的所有利息收益。</p>
						<p>5、如乙方违反本协议的约定，甲方有权要求丙方和丁方提供其已获得的乙方信息，乙方同意丙方和丁方向甲方提供其信息。</p>
						<p>6、甲方因获得所出借款项带来的利息收益需缴纳税费的，应依法缴纳。</p>
						<p>7、甲方有权将本协议项下的债权转让给第三方，无须事先征得乙方同意，但应当取得丁方确认。</p>
					</div>
					
					<div class="second_p3">
						<h4>(二)<span style="text-decoration: underline;">乙方权利和义务</span></h4>
						<p>1、乙方必须按时足额向甲、丙、丁三方支付本协议项下的借款本金、利息、咨询费、手续费、服务费等其他费用，乙方可采取如下任一种还款方式：</p>
						<p>（1）	自主充值，乙方按时足额将每期应还款项存入其在第三方支付机构的个人托管账户，并授权丁方向第三方支付机构发送指令，将应还各方的款项分别划转至各方在第三方支付机构的托管账户；</p>
						<p>（2）	以委托代扣方式还款（具体约定见《代扣授权书》）;</p>
						<p>（3）	转账到指定账号还款（具体见乙、丙双方签订的《借款咨询服务协议》）。</p>
						<p>2、乙方不得改变本协议约定的借款用途，并保证所借款项不作任何违法用途。</p>
						<p>3、乙方应确保其提供的信息和资料的真实性，不得提供虚假信息或隐瞒重要事实。</p>
						<p>4、乙方有权了解其在丙方的信用评审进度及结果。</p>
						<p>5、乙方不得将本协议项下的任何权利义务转让给本协议以外的任何其他方。</p>
						<p>6、乙方同意本协议项下债权进行转让时无需另行通知乙方即对乙方发生法律效力，乙方应按本协议约定继续履行还款义务，并授权丁方向第三方支付机构发出指令，将应还甲方的款项根据债权转让协议划转至债权受让方。</p>
						<p>7、乙方同意本协议中的借款本金可能分笔到账，并认可以第一笔借款本金成功从甲方账户划扣的时间为放款日。</p>
					</div>
					
					<div class="second_p3">
						<h4>(三)<span style="text-decoration: underline;">丙方的权利和义务</span></h4>
						<p>1、丙方有义务为甲、乙双方提供订立合同的机会，促成甲、乙双方建立借贷关系。丙方有权就所提供的服务向乙方收取咨询费，咨询费的计算及收取方式由乙、丙双方在《借款咨询服务协议》中约定。</p>
						<p>2、丙方应负责审核乙方的借款申请，对乙方是否具备还款能力等情况进行判断，并负责本协议项下借款的日常管理工作。丙方有权在有必要时，自行或委托第三方代甲方对乙方进行违约提醒及借款催收，提醒与催收方式包括但不限于电话通知、发律师函、对乙方提起诉讼等。甲方在此明确委托丙方代其进行以上工作，并授权丙方可以将此工作委托给其他方。乙方对前述委托的提醒、催收事项已明确知晓并应积极配合。</p>
						<p>3、如乙方对甲方还款逾期，丙方同意在逾期后第3个工作日代乙方先行向甲方偿还乙方逾期归还之借款本息（即当期应还本息），并按照本协议约定的还款日，每月代乙方偿还余下各期的应还本息。甲、乙、丙三方同意在丙方完成首期代偿后，丙方就取代甲方成为乙方的债权人。乙方除了依据与丙方所签署的《借款咨询服务协议》的约定向丙方支付相关费用外，还应向丙方履行本协议项下其应向甲方履行的义务。</p>
						<p>4、丙方有权了解乙方的信息和借款使用及归还情况。</p>
					</div>
					
					<div class="second_p3">
						<h4>(四)<span style="text-decoration: underline;">丁方的权利和义务</span></h4>
						<p>1、丁方为甲、乙双方提供稳定、安全的金融服务网络平台。</p>
						<p>2、丁方有权就所提供的服务向乙方收取手续费和服务费：手续费<span style="text-decoration:underline ;"> <?php echo $data['preServiceFee'];?> </span>（一次性/每月）收取，按借款金额的<span style="text-decoration: underline;"> <?php echo $data['preServiceFeePec'];?> </span>%/月计算；服务费<span style="text-decoration: underline;"> <?php echo $data['preConsultFee'];?> </span>（一次性/每月）收取，按借款金额的<span style="text-decoration: underline;"> <?php echo $data['preConsultFeePec'];?> </span>%/月计算。丁方选择手续费支付方式（勾选以下之一）：</p>
						<p>3、丁方应对甲方和乙方提供的信息及本协议内容保密，但甲、乙任何一方违反本协议的约定，或应相关权力部门（包括但不限于法院、仲裁机构、金融监管机构等）的要求，丁方有权披露。</p>
					</div>
					
					<div class="second_p3">
						<h4>第三条 违约责任</h4>
						<p>1、本协议各方均应严格履行本协议约定的义务，非经各方协商一致或依照本协议约定，任何一方不得解除本协议。任何一方违约，违约方应赔偿给其他各方造成的损失，包括但不限于调查费、诉讼费、律师费等。</p>
						<p>2、如乙方发生下列情况之一，则视乙方严重违约：</p>
						<p>（1）提供的资料中含有虚假成分，或故意隐瞒重要事实；</p>
						<p>（2）擅自改变借款用途；</p>
						<p>（3）乙方逾期支付任何一期还款；</p>
						<p>（4）乙方提供的借款资料中影响到甲方权益的部分（如姓名、乙方住所/联系地址、单位地址、电话、联系方式等）在借款期间发生变更且不及时进行书面通知的；</p>
						<p>（5）乙方或乙方控股、参股（或实际控制）的企业发生重大违纪、违法或被索赔事件以及发生重大债权债务纠纷引起诉讼、仲裁等事件；</p>
						<p>（6）乙方或乙方控股、参股（或实际控制）的企业或担保人经营出现严重困难和财务状况发生恶化；</p>
						<p>（7）其它可能影响乙方财务状况和偿债能力的事件。</p>
						<p>3、在发生上述第2条约定的任何一种情形时，丙方均有权采取以下的一种或一种以上救济措施：</p>
						<p>（1）有权宣布本协议和《借款咨询服务协议》提前到期，并要求乙方立即偿还借款剩余本金、利息、咨询费、手续费、服务费、提前还款违约金、滞纳金、损失赔偿等其他各项费用。提前还款违约金和滞纳金由乙、丙双方在《借款咨询服务协议》中约定，与甲方无关。</p>
						<p>（2）如乙方对丙方、丙方关联方或其它第三方有任何应收款项，无须乙方另行同意，丙方、丙方关联方或其它第三方可将其应付的任何款项（如有）支付给本协议项下有关债权人，直至乙方还清本协议项下的借款剩余本金、利息及其他全部费用，且付款方无须因此承担任何责任。</p>
						<p>4、如本协议项下的借款被丙方宣布提前到期，乙方在第三方支付机构的个人托管账         户里有任何余款，丁方有权按照本协议第三条第5项的冲账顺序将乙方的余款用         于偿还乙方的债务，并要求乙方支付因此产生的相关费用。</p>
						<p>5、当乙方的还款不足时，冲账顺序为：</p>
						<p>（1）逾期两期及以上的，应首先冲减时间在前的应还款项，然后再冲减时间在后应还款项；</p>
						<p>（2）同一期内部的冲减顺序依次为：</p>
						<p>a.乙方根据《借款咨询服务协议》的约定，应向丙方支付的委外催收费用；</p>
						<p>b.因乙方还款逾期时乙方应向丙方支付的滞纳金；</p>
						<p>c.乙方按照《借款咨询服务协议》的约定，应向丙方支付的征信查询费；</p>
						<p>d.乙方应支付予丁方的手续费；</p>
						<p>e.乙方应支付予丁方的服务费；</p>
						<p>f.乙方应支付予丙方的咨询费；</p>
						<p>g.本协议项下借款的利息；</p>
						<p>h.借款本金。 </p>
						<p>5、如果乙方逾期支付任何一期还款，丁方有权将乙方的“逾期记录”记入丁方的“信用信息库”，并有权将乙方违约失信的相关信息及乙方其他信息向媒体、用人单位、公安机关、检查机关、法律机关披露，丁方对此不承担任何责任。</p>
						<p>6、本借款协议中甲方与乙方之间的借款是独立的，一旦乙方逾期还款，甲方有权单独向乙方追索或者提起诉讼。如乙方提供虚假信息的，丙方和丁方亦可单独向乙方追索或者提起诉讼。 </p>
					</div>
					
					<div class="second_p3">
						<h4>第四条 提前还款</h4>
						<p>1、自借款起始日<span style="text-decoration: underline;"> <?php echo $data['lockDate'];?> </span>个月内，乙方不得申请提前还款。</p>
						<p>2、提前全部还款。乙方申请提前全部还款，须得到丙方同意，乙方除须还清截至还款当日为止的借款剩余本金及应还利息、手续费、服务费、咨询费等全部款项之外，还须向丙方支付提前还款违约金。</p>
						<p>3、提前部分还款。丙方不接受提前部分还款，若乙方提前部分还款的，提前还款部分将由丁方代乙方保管（不计取利息），不提前冲减本息及费用，下一期还款时从中冲减应还款项，若余额不足，乙方应按约定的还款方式补足差额部分。</p>
					</div>
					
					<div class="second_p3">
						<h4>第五条 特别约定</h4>
						<p style="text-indent: 0.5rem;">在保障本协议约定的甲方本金和利息收益的前提下，甲方授权丁方代其向丙方或其他第三方转让本协议中甲方对乙方的债权，甲方认可该转让的效力。</p>
					</div>
					
					<div class="second_p3">
						<h4>第六条 法律适用及争议解决</h4>
						<p style="text-indent: 0.5rem;">本协议的签订、履行、终止、解释均适用中华人民共和国法律，如发生争议，各方应将争议提交丁方所在地有管辖权的人民法院解决。</p>
					</div>
					
					<div class="second_p3">
						<h4>第七条 附则</h4>
						<p>1、本协议采用电子文本形式制成，并永久保存在丁方为此设立的专用服务器上备查，各方均认可该形式的协议的效力。乙、丙、丁三方可线下签署本协议的纸质版本，作为电子文本形式制成的本协议的效力补充。</p>
						<p>2、本协议自文本最终生成之日生效。</p>
						<p>3、本协议签订之日起至借款全部清偿之日止，甲方或乙方的下列信息如发生变更的，应当在相关信息发生变更三日内将更新后的信息提供给丁方：姓名、家庭联系人及紧急联系人、工作单位、居住地址、住所电话、手机号码、电子邮箱、银行账户。若因不及时提供上述变更信息而带来的损失或额外费用应由该方承担。</p>
						<p>4、如果本协议中的任何一条或多条违反相关法律法规的规定，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。</p>
						<p class="second_p3_span"><span>乙方：</span><span><?php echo $this->getName($data['userB']);?></span><span>丙方：</span><span><?php echo $data['userC'];?></span><span>丁方：</span><span><?php echo $data['userD'];?></span></p>
						<p class="second_p3_last_one">签署日期：<span style="text-decoration: underline;"> <?php echo $date[0];?> </span>年<span style="text-decoration: underline;"> <?php echo $date[1];?> </span>月<span style="text-decoration: underline;"> <?php echo $date[2];?> </span>日</p>
					</div>
				</div>
				<?php }?>
		</div>
			
		
	</body>
</html>