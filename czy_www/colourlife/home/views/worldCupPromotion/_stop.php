<div class="modifys" style="width:90%;padding:10px 3%;">本轮竞猜已经结束，请等待开奖结果。不要走开，下一轮竞猜活动马上开启！</div>

<div class="changechoice">
    <a href="/luckyApp/worldCupIndex" class="rulebtn" style="margin-top:20px">返回</a>
    <a href="/worldCupPromotion/myGuess" class="rulebtn" style="margin-top:20px">查看我的竞猜</a>
</div>

<?php $caiPiaoUrl=SmallLoans::model()->getGoucaiUrl(); ?>
<?php if($caiPiaoUrl){ ?>
<div class="caipiao_box" style="display:block;">
    <p class="greenfont">还不过瘾？马上来买彩票吧，现在彩票充值50送20元哦！</p>
    <a href="<?php echo $caiPiaoUrl; ?>" class="rulebtn">马上购彩</a>
</div>
<?php } ?>
        </div>
    </div>
</div>
