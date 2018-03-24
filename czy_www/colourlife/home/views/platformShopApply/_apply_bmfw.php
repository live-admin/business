<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>平台招商申请-彩之云 </title>
    <link href="<?php echo F::getStaticsUrl('/common/css/mobile.css'); ?>" rel="stylesheet">
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
</head>
<body>
<div class="main">
    <div class="applyfor_interview">
        <div class="person_info">
            <?php if(empty($cate) || $cate->type==0){?>
                <div style="color: red;font-size: 16px;font-weight: bold;">类别不存在</div>
            <?php }else{?>
                <h3><?php echo $cate->title;?></h3>
                <div class="cross_line"></div>
                <p class="h3_p">填写申请</p>
                <form action="" method="post" onsubmit="return false" id="applyForm">
                    <input type="hidden" name="apply[cate_id]" id="cate_id" value="<?php echo $cate->id;?>"/>
                    <p>
                        <label class="wordlike3">公司名称：</label>
                        <input type="text" class="info_input" name="apply[comp_name]" id="comp_name"/>
                    </p>
                    <p>
                        <label class="wordlike3">服务区域：</label>
                        <select id="province_data" style="width: 70px;">
                            <option value="0" selected disabled="disabled">选择省</option>
                            <?php
                            $model=PlatformShopApply::model();
                            $provinceData=$model->getRegionList(0);
                            foreach($provinceData as $_v){ ?>
                                <option value="<?php echo $_v['id']; ?>"><?php echo $_v['name']; ?></option>
                            <?php } ?>
                        </select>
                        <select id="city_data" style="width: 80px;">
                            <option value="0" selected disabled="disabled">选择市</option>
                        </select>
                        <select id="area_data" style="width: 80px;">
                            <option value="0" selected disabled="disabled">选择地区</option>
                        </select>
                        <input type="hidden" class="info_input" name="apply[region_id]" id="region_id"/>
                    </p>
                    <p>
                        <label class="wordlike3">所属行业：</label>
                        <input type="text" class="info_input" name="apply[sell_goods]" id="sell_goods"/>
                    </p>
                    <p>
                        <label class="wordlike3">联系人　：</label>
                        <input type="text" class="info_input" name="apply[contact_name]" id="contact_name"/>
                    </p>
                    <p>
                        <label class="wordlike3">联系电话：</label>
                        <input type="text" class="info_input" name="apply[contact_phone]" id="contact_phone"/>
                    </p>
                    <p>

                    <div style="color: red;" >
                        <ul class="showRet">
                        </ul>
                    </div>
                    <div class="btn_contairn btn_contairn_two">
                        <a href="/platformShopApply/<?php echo $cate->id;?>" class="dark_btn">上一步</a>
                        <a href="javascript:void(0)" id="submit_button" class="dark_btn" style="float:right;">提交</a>
                    </div>

                </form>
            <?php }?>
        </div>
    </div>
    <script>
        $('#province_data').change(function (){
            $('#city_data').empty();
            $('#area_data').empty();
            $('#region_id').val("");
            $('#city_data').append("<option value="+"0"+" selected disabled="+"disabled"+">选择市</option>");
            $('#area_data').append("<option value="+"0"+" selected disabled="+"disabled"+">选择地区</option>");
            $.post(
                '/platformShopApply/getRegionList',
                {'pid':$(this).val()},
                function (data){
                    if(data!=0){
                        for (var i = 0; i < data.length; i++) {
                            $('#city_data').append("<option value=" + data[i].id + ">" + data[i].name + "</option>");
                        }
                    }
                }
                ,'json');
        });

        $('#city_data').change(function (){
            $('#area_data').empty();
            $('#region_id').val("");
            $('#area_data').append("<option value="+"0"+" selected disabled="+"disabled"+">选择地区</option>");
            $.post(
                '/platformShopApply/getRegionList',
                {'pid':$(this).val()},
                function (data){
                    if(data!=0){
                        for (var i = 0; i < data.length; i++) {
                            $('#area_data').append("<option value=" + data[i].id + ">" + data[i].name + "</option>");
                        }
                    }
                }
                ,'json');
        });

        $('#area_data').change(function (){
            var region_id=$(this).val();
            $('#region_id').val(region_id);
        });

        $(document).ready(function(){
            $("#submit_button").click(function(){
                $(".showRet li").remove();
                var noError=true;
                if($("#comp_name").val().length<3){
                    $(".showRet").append("<li>公司名称不符合要求</li>");
                    noError=false;
                }
                if($("#region_id").val().length<1){
                    $(".showRet").append("<li>服务区域不符合要求</li>");
                    noError=false;
                }
                if($("#sell_goods").val().length<3){
                    $(".showRet").append("<li>所属行业不符合要求</li>");
                    noError=false;
                }
                if($("#contact_name").val().length<1){
                    $(".showRet").append("<li>联系人不符合要求</li>");
                    noError=false;
                }
                if($("#contact_phone").val().length<1){
                    $(".showRet").append("<li>联系人电话不符合要求</li>");
                    noError=false;
                }else{
                    var isMobile=/^(?:13\d|15\d)\d{5}(\d{3}|\*{3})$/;
                    var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;

                    if(!isMobile.test($("#contact_phone").val()) && !isPhone.test($("#contact_phone").val())){
                        $(".showRet").append("<li>联系人电话不符合要求</li>");
                        noError=false;
                    }
                }

                if(noError){
                    $.ajax({
                        type: "POST",
                        url: "/platformShopApply/apply",
                        data:$("#applyForm").serialize(),
                        dataType:"json",
                        error: function(){
                            alert("请求异常");
                        },
                        success: function(ret){
                            if(ret.success==1){
                                $(".showRet").append("<li>申请成功，请等候审核！</li>");
                                $("#submit_button").remove();
                                window.setTimeout("location.href = '/platformShopApply'",3000);

                            }else{
                                for(var key in ret.data.errors){
                                    $(".showRet").append("<li>"+ret.data.errors[key]+"</li>");
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
