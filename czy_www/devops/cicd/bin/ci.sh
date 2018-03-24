#!/bin/bash
# 设置持续集成的脚本
export env=$1

#加载配置文件
source config
if [ $auth == "yes" ];then
    docker login -u ${user} -p ${pass} ${docker_registry}
fi
#当前时间到精确到年月日如20170622
date=`date +%Y%m%d`

#判断是否有入参
if [ $# != 1 ];then
    echo “input parameter:dev or prod or pro”
    exit 1
elif [ $1 != "dev" ] && [ $1 != "prod" ] && [ $1 != "pro" ];then
    echo “input one of the parameters:dev, prod or pro”
    exit 1
fi
if [ $env == "prod" ];then
    date=`date --date=now +%W`
fi
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
chmod -v +x ./*.sh
cd ../../../
if [ $env == "pro" ];then
    branchs=`git symbolic-ref --short -q HEAD`
    echo ${branchs} > a.txt
    content=`cat a.txt`
    if [ $content=="master" ];then
        commit_id=`git rev-list --tags --max-count=1`
        date=`git describe --tags ${commit_id}`
        #释放release分支&&建release分支
        git push origin --delete release
        git checkout -b release
        git push origin release
        #删除原来的地址
        git remote rm origin
        git remote add origin $gitaddress
        git push origin --delete release
        git push origin release
    else
        git checkout master
        commit_id=`git rev-list --tags --max-count=1`
        date=`git describe --tags ${commit_id}`
        git checkout release
    fi
fi

#*****************************************
#!!!此处配置不一定要写，需各自项目视情况而定
#做配置文件修改
cp local.php_$1 colourlife/common/config/local.php
cp main.php_$1 colourlife/common/config/main.php
#*****************************************

#设置变量
export project_dir="`pwd`/"
export dir_name=`basename ${project_dir}`
export dir_name=`echo $dir_name | tr 'A-Z' 'a-z'|tr -d '_'|tr -d '\n'`

docker-compose -f "./docker-compose-development.yaml" up -d
content=`docker ps | grep "$dir_name"_"$primary_container_name"_1`

#判断容器是否有启动
if [ -z "$content" ];then
    echo "container run fail"
    #删除本地容器
    for name in ${container_names}
    do
        docker rm -f ${dir_name}_${name}_1/
    done
    #删除本地构建的镜像
    docker rmi -f ${dir_name}_$primary_container_name
    exit 1
fi

image_name="${docker_registry}/${project_name}_${env}:${date}"
#提交镜像
docker commit ${dir_name}_${primary_container_name}_1 ${image_name}
statu=`docker push "${image_name}" |grep digest`
touch constant.txt
message=`echo ${statu} |cut -f 3 -d ' '|tr -d "\n"`
echo $message > constant.txt

#删除本地容器
for name in ${container_names}
do
    docker rm -f ${dir_name}_${name}_1
done

#删除本地构建的镜像(ps:基础镜像不必删除)
docker rmi -f ${dir_name}_$primary_container_name
docker rmi -f ${image_name}
