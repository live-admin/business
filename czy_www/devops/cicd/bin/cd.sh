#!/bin/bash
#设置部署的脚本
env=$1
method=$2
date=`date +%Y%m%d`
#linux下昨天时间
last_date=`date -d '1 days ago' +%Y%m%d`
#mac下时间
#last_date=`date -v-1d +"%y%m%d"`
source config
#提供所要部署机子的别名#
if [ "$env" == "dev" ];then
    server=${serverdev}
elif [ "$env" == "prod" ];then
    server=${serverprod}
fi

#分割线，以下原则上不做修改
export image="${project_name}_${env}"
if [ -z "$project_name" ] ;then
    echo "project_name is necessary ,please constant it in the file"
    exit 1;
fi
#判断是否有入参
if [ $# != 2 ];then
    echo "first input parameter:dev or prod,second input parameter:auto or manual"
    exit 1
elif [ $1 != "dev" ] && [ $1 != "prod" ];then
    echo "input one of the parameters:dev or prod"
    exit 1
elif [ $2 != "auto" ] && [ $2 != "manual" ]; then
    echo "input one of the parameters:auto or manual "
    exit 1
fi
if [ $env == "prod" ];then
    date=`date --date=now +%W`
    last_date=$[$date-1]
fi
if [ $method == "manual" ];then
    #仅适用于预正式半自动部署
    if [ -z ${input_date} ];then
        echo "||默认最新版本||"
    else
        date=${input_date}
    fi
fi
#镜像变量设置
export last_image_name="${docker_registry}/${project_name}_${env}:${last_date}"
export image_name="${docker_registry}/${project_name}_${env}:${date}"
#dir_name由Jenkins传来
#jenkins脚本处
WORKDIR="/home/jenkins/workspace/${dir_name}"
export DESTDIR="/root/workspace/${dir_name}"
#删除远程文件
ansible ${server} -m shell -a "rm -rf ${DESTDIR}"
#移动文件至宿主机（只要是cd阶段所需脚本）
status=`ansible ${server} -m copy -a "src=${WORKDIR}/devops/ dest=${DESTDIR}/devops mode=0777"`
statu=`ansible ${server} -m copy -a "src=${WORKDIR}/docker-compose.yaml dest=${DESTDIR}/ mode=0777"`
stat=`ansible ${server} -m copy -a "src=${WORKDIR}/constant.txt dest=${DESTDIR}/ mode=0777"`
if [ -z `echo "$status" |grep FAILED` ] && [ -z `echo "$statu" |grep FAILED`] && [ -z `echo "$stat" |grep FAILED`];then
    #文件迭代成功
    echo "||文件迭代成功||"
else
    #文件迭代失败
    echo "|文件迭代失败,进行删镜像操作=>"
    ansible ${server} -m shell -a "export image=${image} && export image_name=${image_name} && export last_image_name=${last_image_name} && cd ${DESTDIR}/devops/cicd/bin/cd-shell && ./cd-ft.sh"
    exit 1
fi

#cd运作开始
statu=`docker pull ${last_image_name}`
status=`echo "$statu" |grep Digest`
if [ -z "$status" ]; then
    #初始化镜像
    echo "||初始化时期（日版第一天内的部署，周版本的第一周内的部署）||"
    statu=`ansible ${server} -m shell -a "export method=${method} && export image=${image} && export image_name=${image_name} && export last_image_name=${last_image_name}  && cd ${DESTDIR}/devops/cicd/bin/cd-shell && ./cd-pt.sh"`
    echo "||初始化测试部署状态："$statu"||"
    status=`echo "$statu"|grep FAILED`
    if [ -z "$status" ]; then
        #成功
        echo "||通过测试，部署成功||"
    else
        echo "||测试失败，进行删私有仓库镜像操作=>"
        #失败
        ansible ${server} -m shell -a "export method=${method} && export image=${image} && export image_name=${image_name} && export last_image_name=${last_image_name}  && cd ${DESTDIR}/devops/cicd/bin/cd-shell && ./cd-ft.sh"
        echo "||错误信息请查看上方关键字'**错误信息：'输出内容||"
	    exit 1
   fi
else
    #镜像存在,非初始化deploy 必须填四个参数
    echo "||非初始化||"
    echo "iamge名："${image_name}"||"
    statu=`ansible ${server} -m shell -a "export method=${method} && export image=${image} && export image_name=${image_name} && export last_image_name=${last_image_name}  && cd ${DESTDIR}/devops/cicd/bin/cd-shell && ./cd-pf.sh"`
    echo "||非初始化测试部署状态："${statu}"||"
    status=`echo "$statu"|grep FAILED`
    if [ -z "$status" ]; then
        #成功
        echo "||通过测试，部署成功||"
    else
        #失败
        echo "||测试失败，进行版本回退=>"
        ansible ${server} -m shell -a "export method=${method} && export image=${image} && export image_name=${image_name} && export last_image_name=${last_image_name}  && cd ${DESTDIR}/devops/cicd/bin/cd-shell && ./cd-ff.sh"
        echo "||错误信息请查看上方关键字'**错误信息：'输出内容||"
        exit 1
    fi
fi


