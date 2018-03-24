<script>
(function() {
    // 当前页面配置信息
    pageConfig = {
        "pageItemType"		: 1,	// 当前页面所属的大类
        "mainNavCurIndex"	: 6		// 主栏目中当前栏目的索引值，从1开始，0表示没有当前效果
    }
})();
</script>
<link href="<?php echo F::getStaticsUrl('/common/css/xiaodai.css'); ?>" rel="stylesheet">
<div class="topic">
     <div class="topic_head">
        <a href="<?php echo !empty($info)?$info->completeURL:Yii::app ()->user->loginUrl; ?>">
            <img src="<?php echo F::getStaticsUrl("/common/images/smallLoans/head.jpg");?>" />
        </a>
     </div>
     <div class="topic_content">
       
       <ul class="xd_part1 clearfix">
         <li class="xd_txt1">
           <div>
             <h3>高收益</h3>
             <p>
               成为理财人，通过主动投标或加入优选理财计划将<br />
               资金进行出借投资，可获得预期8-10%的稳定年<br />
               化收益。
             </p>
           </div>
           <a href="<?php echo !empty($info)?$info->completeURL:Yii::app ()->user->loginUrl; ?>" class="clickhere">&nbsp;</a>
         </li>
         <li class="xd_txt2">
           <div>
             <h3>快速到账</h3>
             <p>
               成为借款人，按照要求完善信用信息，获得<br />
               信用认证后，通过发标进行借款，最快2.5小<br />
               时可获得所需资金。
             </p>
           </div>
           <a href="<?php echo !empty($info)?$info->completeURL:Yii::app ()->user->loginUrl; ?>" class="clickhere">&nbsp;</a>
         </li>
         <li class="xd_txt3">
           <div>
             <h3>安全保障</h3>
             <p>
               所有投资标的100%适用本金保障计划，<br />
               如遇借款人违约，E理财将通过风险备<br />
               用金有效保障理财人的本金安全。。
             </p>
           </div>
           <a href="<?php echo !empty($info)?$info->completeURL:Yii::app ()->user->loginUrl; ?>" class="clickhere">&nbsp;</a>
         </li>
       </ul>
       <table class="xd_table">
         <thead>
           <tr>
             <th style="width:203px;">
               <p>产品名称</p>
             </th>
             <th style="width:169px;">
               <p>信用等级</p>
             </th>
             <th style="width:169px;">
               <p>年利率</p>
             </th>
             <th style="width:249px;">
               <p>借贷金额</p>
             </th>
             <th>
               <p>期限</p>
             </th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td>
               <p>楼盘收购短期借贷</p>
             </td>
             <td>
               <p>AAA</p>
             </td>
             <td>
               <p>10.00% </p>
             </td>
             <td>
               <p>￥2,000,000.00</p>
             </td>
             <td>
               <p>12月</p>
             </td>
           </tr>
           <tr>
             <td>
               <p>楼盘收购短期借贷</p>
             </td>
             <td>
               <p>AAA</p>
             </td>
             <td>
               <p>10.00% </p>
             </td>
             <td>
               <p>￥2,000,000.00</p>
             </td>
             <td>
               <p>12月</p>
             </td>
           </tr>
           <tr>
             <td>
               <p>楼盘收购短期借贷</p>
             </td>
             <td>
               <p>AAA</p>
             </td>
             <td>
               <p>10.00% </p>
             </td>
             <td>
               <p>￥2,000,000.00</p>
             </td>
             <td>
               <p>12月</p>
             </td>
           </tr>
           <tr>
             <td>
               <p>楼盘收购短期借贷</p>
             </td>
             <td>
               <p>AAA</p>
             </td>
             <td>
               <p>10.00% </p>
             </td>
             <td>
               <p>￥2,000,000.00</p>
             </td>
             <td>
               <p>12月</p>
             </td>
           </tr>
         </tbody>
       
       </table>
       <div class="xd_part3">
           <a href="<?php echo !empty($info)?$info->completeURL:Yii::app ()->user->loginUrl; ?>" class="touzi">理财从此开始</a>
       </div>
       
       
     </div>
  </div>
  
