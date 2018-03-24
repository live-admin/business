<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
    <meta name="MobileOptimized" content="240"/>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/activity/serviceAgreement/'); ?>css/layout.cssjs/flexible.js"></script>
    <title>彩富人生服务协议</title>
    <style>
        * {margin:0;padding:0;}
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, form, img, dl, dt, dd, table, th, td, blockquote, fieldset, div, strong, label, em{border:0 none; margin:0; padding:0;}
        ul, ol, li{list-style:none outside none;}
        .xieyiphone{margin:0 auto; padding:0 10px; text-align:left; font-size:12px; color:#555;}
        .xieyiphone h3{font-size:16px; margin:15px 0; text-align:center; color:#000;}
        .xieyiphone ul{margin-bottom:15px;}
        .xieyiphone dl{margin-top:15px;}
        .xieyiphone dl dt{font-size:14px; color:#000; margin:10px 0;}
        .xieyiphone dl dd{ text-indent:8px; line-height:200%;}
        .xieyiphone li,.xieyi p{ line-height:200%;}
        #table{ border:1px #999999 solid !important;}
        #table tr>td:nth-of-type(1){ border:1px #999999 solid !important;width: 45%;}
        #table tr>td:nth-of-type(2){ border:1px #999999 solid !important;width: 55%;}
        .infoUnderLine{text-decoration: underline;}
        .btn{position:fixed;bottom:0;width:100%;border-radius:0;color:#fff;background:#27a2f0;text-align:center;height:0.88rem; border: none;outline: none;height: 2.0rem;line-height: 2.0rem;text-decoration: none;}
    </style>
</head>

<body>
<div class="phone_contairn">
    <div class="xieyiphone" style="padding-bottom:50px; font-size:12px;">
        <h3><?php echo $info['content'][0]['productName']; ?>服务协议</h3>
        <ul>
            <li>甲方：<span class="infoUnderLine"><?php echo $info['content'][0]['userAName']; ?></span></li>
            <li>地址：<span class="infoUnderLine"><?php echo $info['content'][0]['userAAddress']; ?></span></li>
            <li>邮编：<span class="infoUnderLine"><?php echo $info['content'][0]['userACode']; ?></span></li>
        </ul>
        <ul>
            <li>乙方：<span class="infoUnderLine"><?php echo $info['content'][0]['userBName']; ?></span></li>
            <li>地址：<span class="infoUnderLine"><?php echo $info['content'][0]['userBAddress']; ?></span></li>
            <li>邮编：<span class="infoUnderLine"><?php echo $info['content'][0]['userBCode']; ?></span></li>
        </ul>
        <ul>
            <li>丙方：<span><?php echo $info['content'][0]['userCRealName']; ?></span></li>
            <li>用户名：<span><?php echo $info['content'][0]['userCName']; ?></span></li>
            <li>身份证号码：<span><?php echo $info['content'][0]['userCIdNo']; ?></span></li>
        </ul>
        <ul>
            <li>甲、乙、丙三方统称为"各方"，单称为"一方"。</li>
        </ul>
        <p>鉴于：</p>
        <p style="margin:8px 0;">
            甲方是一家科技型、综合型物业服务运营集团，其关联公司深圳市彩之云网络科技有限公司是彩之云APP的运营商；乙方是P2P平台钱生花(包括www.qianshenghua.com网站及相应APP)的运营商；丙方是甲方或其关联公司在管社区内特定物业的业主，或自愿为甲方或其关联公司在管社区内特定物业缴纳物业管理费的人士。
        </p>
        <p>
            【彩富人生计划】（下称本计划），是甲、乙双方联合向丙方提供的一项金融服务，该项服务包含基础投资服务和增值投资服务，本计划将钱生花P2P平台服务接入彩之云APP，并以该平台为媒介向丙方提供金融服务，以满足丙方缴纳物业管理费及金融增值服务的需求。
        </p>
        <dl>
            <dt>一、本计划概述</dt>
            <dd>
                丙方同意通过彩之云APP投资钱生花平台上的P2P产品，取得投资收益并以其中部分收益交纳特定物业的物业管理费。丙方加入本计划的相关条件如下：
            </dd>
        </dl>
        <dl>
            <dd style=" position:relative">
                <div style="position:absolute; z-index:-1;  top: -40px;right: -10px;"><img style="  width: 80%;height: 80%;" src="<?php echo F::getStaticsUrl('/home/activity/serviceAgreement/'); ?>images/stamp.png"/></div>
                <table width="100%"  cellpadding="0" cellspacing="0" id="table">
                    <tr>
                        <td>投资期限</td>
                        <td><span style="text-decoration: underline;">&nbsp;<?php echo $info['content'][0]['period']; ?>&nbsp;</span>个月</td>
                    </tr>
                    <tr>
                        <td>投资起算日</td>
                        <td><?php echo $info['content'][0]['beginDate']; ?></td>
                    </tr>
                    <tr>
                        <td>投资到期日</td>
                        <td><?php echo $info['content'][0]['endDate']; ?></td>
                    </tr>
                    <tr>
                        <td>基础投资预期年化收益率</td>
                        <td><?php echo ($info['content'][0]['rate']*100); ?>%</td>
                    </tr>
                    <tr>
                        <td>基础投资金额</td>
                        <td><?php echo $info['content'][0]['investAmount']; ?></td>
                    </tr>
                    <tr>
                        <td>增值投资预期年化收益率</td>
                        <td><?php echo ($info['content'][0]['increaseRate']*100); ?>%</td>
                    </tr>
                    <tr>
                        <td>增值投资金额</td>
                        <td><?php echo $info['content'][0]['increaseAmount']; ?></td>
                    </tr>
                    <tr>
                        <td>锁定期</td>
                        <td>
                            <span><?php echo $info['content'][0]['lockDate']; ?> </span>个月(自投资起算日开始计算)
                        </td>
                    </tr>
                </table>
            </dd>
        </dl>
        <dl>
            <dd style="color:#000">
                丙方已经知悉、了解并同意：本协议所示的相关预期年化收益率不代表最终实际收益，丙方投资以及相应的收益存在不能够按期收回的风险；在实际收益率未达到预期收益率的情况下，丙方仅能收取其实际收益。乙方不对丙方投资的收回、可获收益金额作出任何承诺、保证。
            </dd>
        </dl>

        <dl>
            <dt>二、本协议生效时间</dt>
            <dd>
                丙方勾选同意本协议，确认基础投资金额和增值投资金额，并按该金额向钱生花合作的第三方支付机构进行充值，选择以自动投标的方式进行投资，以及设定委托划款协议后，本协议形成最终文本并在三方之间发生法律效力。
            </dd>
        </dl>
        <dl>
            <dt>三、本计划详情</dt>
            <dd>
                1、丙方同意根据本协议、钱生花平台上的相关合同及有关的投资规则，按系统计算、丙方确认的基础投资金额和增值投资金额，对钱生花平台上的P2P产品进行投资。
            </dd>
            <dd>
                2、丙方的基础投资和增值投资通过本计划所产生的实际收益中超过对应的预期年化收益率的部分（下称“收益余额”），将全部用于向甲方及其关联公司交纳特定物业在本协议生效之日至投资期满的期间内应交的管理费。
            </dd>
            <dd>
                3、本协议生效后，丙方的基础投资款项和增值投资款项将根据钱生花平台的自动投标规则作为借款出借给钱生花平台上有需要的借款人，之后的回款按钱生花平台上借款相关协议的约定返还至丙方的第三方支付个人账户。丙方同意并授权，其个人账户内的收益余额由乙方向第三方支付机构发出指令按月付至甲方指定的账户，余款按照钱生花平台的自动投标规则继续投资直至投资期限届满。
            </dd>
        </dl>
        <dl style="position:relative">
            <div style="position:absolute; z-index:-1;  top: -110px;right: -10px;"><img style="  width: 80%;height: 80%;" src="<?php echo F::getStaticsUrl('/home/activity/serviceAgreement/'); ?>images/stamp.png"/></div>
            <dt>四、本计划的退出</dt>
            <dd>1、到期退出</dd>
            <dd>投资期满后，乙方需协助丙方对已投资债权进行转让，并将转让所得退回至其第三方支付个人账户。丙方可对退回账户内的基础投资金额和增值投资金额以及对应的预期年化收益率范围内的收益进行提现或继续参与投资活动，并同意收益余额由乙方向第三方支付机构发出指令付至甲方指定账户。在投资期满且收益余额付至甲方指定账户后，本协议终止。</dd>
            <dd>2、提前退出</dd>
            <dd>
                （1）锁定期内丙方不得申请提前赎回，从锁定期结束后的第一日到投资到期日前三日，丙方可通过彩之云APP申请提前全额赎回，丙方可以且仅可提出一次提前赎回申请，且必须申请全额赎回，丙方的提前赎回申请一旦提出，则不可撤销。
            </dd>
            <dd>（2）丙方的提前赎回申请经乙方确认后，乙方通过彩之云APP告知丙方的提前赎回申请受理成功，则丙方成功提前退出本计划。</dd>
            <dd>（3）提前赎回费用：丙方申请提前赎回的，应向乙方支付提前赎回费用。提前赎回费用分为两部分：</dd>
            <dd>&nbsp;&nbsp;&nbsp;基础投资部分的提前赎回费用，即基础投资按照本协议第一条的约定已产生的收益；</dd>
            <dd>
                &nbsp;&nbsp;&nbsp;增值投资部分的提前赎回费用，按照增值投资金额2%的标准计算，如果超过增值投资按照本协议第一条的约定产生的收益，则该部分提前赎回费用以该收益为限。
            </dd>
            <dd>
                （4）丙方申请提前赎回后，乙方需协助丙方对已投资债权进行转让，并将转让所得退回至其第三方支付个人账户。丙方同意并授权，由乙方向第三方支付机构发出指令，从丙方第三方支付个人账户中扣减收益余额及提前退出费用，分别付至甲方指定的账户、乙方指定账户，丙方第三方支付个人账户中的剩余款项可由丙方进行提现或继续参与投资活动。在丙方申请赎回且收益余额、提前退出费用分别付至甲方指定账户、乙方指定账户后，本协议终止。
            </dd>
            <dd>3、本协议终止后，丙方须按时自行向甲方或甲方关联公司交纳特定物业的管理费。</dd>
        </dl>
        <dl>
            <dt>五、本计划保障措施</dt>
            <dd>本计划由深圳市合和年投资咨询有限公司依据钱生花平台的相关规则提供有关保障服务。</dd>

        </dl>
        <dl style="position:relative">
            <div style="position:absolute; z-index:-1;  top: 100px;right: -10px;"><img style="  width: 80%;height: 80%;" src="<?php echo F::getStaticsUrl('/home/activity/serviceAgreement/'); ?>images/stamp.png"/></div>
            <dt>六、各方权利义务</dt>
            <dt>（一）甲方的权利和义务</dt>
            <dd>1、甲方提供现场、非现场投资指引工作安排；</dd>
            <dd>2、参与活动的订单在彩之云APP平台上生成，并由丙方进行确认；</dd>
            <dd>
                3、丙方成功进行投资后，七个工作日内甲方应派工作人员将纸质的物业管理费预抵扣凭证上门送至丙方，该凭证作为本协议附件，与本协议有同等法律效力。若丙方提前退出本计划的，抵扣凭证丧失法律效力，抵扣期限及抵扣金额等以实际发生为准。
            </dd>
            <dt>
                （二） 乙方的权利和义务
            </dt>
            <dd>1、乙方负责通过钱生花平台为甲、丙双方实现本计划；</dd>
            <dd>
                2、乙方应对甲方和丙方的信息及本协议内容保密，但如任何一方违约，或因相关权力部门要求（包括但不限于法院、仲裁机构、金融监管机构等），乙方有权进行披露。
            </dd>
            <dt>（三）丙方权利和义务</dt>
            <dd>
                1、丙方声明在签署本协议前，丙方已认真阅读本协议有关条款，对有关条款不存在任何疑问或异议，并对协议各方的权利、义务、责任与风险有清楚和准确的理解。
            </dd>
            <dd>2、丙方保证为履行本协议而向甲方和乙方提供的全部资料及信息均真实、有效，无虚假及误导性陈述。</dd>
            <dd>
                3、丙方保证所使用的资金为合法取得，且具有排他性的支配权，不利用乙方网站平台进行信用卡套现、洗钱或其他违法、违纪行为，否则应依法承担由此产生的法律责任与后果。
            </dd>
            <dd>
                4、丙方应当按照本协议及本计划的规则进行投资，丙方确认本计划投资金额并据此进行充值后，视为同意本协议约定的投资和费用抵扣方式以及计算逻辑。
            </dd>
            <dd>
                5、丙方应保证其第三方支付账户可正常使用，如因丙方原因导致其账户被冻结、查封或遭遇其他情形不能正常使用，则本协议立即提前终止，且丙方应当赔偿甲方、乙方因此产生的损失。
            </dd>
        </dl>
        <dl style="position:relative">
            <div style="position:absolute; z-index:-1;  bottom: -260px;right: -10px;"><img style="  width: 80%;height: 80%; margin: 0 0 0.88rem 0;" src="<?php echo F::getStaticsUrl('/home/activity/serviceAgreement/'); ?>images/stamp.png"/></div>
            <dt>七、特别备注：</dt>
            <dd>1、本协议采用电子文本形式制成，并永久保存在乙方为此设立的专用服务器上备查，各方均认可该形式的协议效力。</dd>
            <dd>2、本协议自文本最终生成之日生效。</dd>
            <dd>
                3、本协议签订之日，若丙方的下列信息发生变更，其应当在相关信息发生变更三日内将更新后的信息提供给甲方：本人、本人的家庭联系人及紧急联系人、工作单位、居住地址、住所电话、手机号码、电子邮箱、银行账户的变更。若因任何一方不及时提供上述变更信息而带来的损失或额外费用应由该方承担。
            </dd>
            <dd>4、如果本协议中的任何一条或多条违反适用的法律法规，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。</dd>
            <dd>
                5、由于地震、火灾、战争等不可抗力导致的交易中断、延误的，甲乙丙三方互不承担责任。但应在条件允许的情况下，应采取一切必要的补救措施以减小不可抗力造成的损失。
            </dd>
            <dd>6、本协议项下产生的纠纷，各方先行协商解决，协商不成的，可向甲方所在地有管辖权的人民法院提起诉讼。</dd>
        </dl>
    </div>

</div>
<a class="btn" href="<?php echo $info['downloadURL']; ?>">
<!--    <input class="btn" type="buttom" value="下载" />-->
    下载
</a>


</body>

</html>