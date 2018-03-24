#CICD脚本文件说明文档
### JAVA项目请参考`ssh://git@code.listcloud.cn:10022/adminCode/colourlife_new_job.git`
### SH配置文件（常用的）
#### config文件

```
#以下为配置修改
#用于镜像名称
project_name="colourlife_czy_business_platforms"
#提供主要容器名(基于dockerfile镜像启动的容器）即.yaml文件中容器名
primary_container_name="czy_business_platforms"
#提供项目中所有启动容器列表
container_names="czy_business_platforms"
#docker仓库
docker_registry="docker.ops.colourlife.com:5000"
#auth是否docker仓库有设置密码，yes有，no无
auth=yes
#user是docker仓库用户名
user=admin
#pass是docker仓库密码
pass=admin@123!
#预正式serverprod部署的服务器别名
serverprod=t1t2
#测试serverdev部署的服务器别名
serverdev=none
```
### CI环节

#### 1、Dockerfile 需自行提供，以下只是简单示例（需要根据项目改动）

```
FROM docker.listcloud.cn:5000/nginx-php5.6
COPY . /var/www/html
COPY ./devops/config/default /etc/nginx/sites-available/default.conf
WORKDIR /var/www/html
```

#### 2、docker-compose-development.yaml 需自行提供，以下只是简单示例（需要根据项目改动）
有redis

```
redis:
  image: redis
czy_business_platforms:
  build: .
  links:
    - redis1:redis.db
```
无redis

```
czy_business_platforms:
  build: .
```

#### 3、devops/cicd/bin/ci.sh（需要部分改动）

```
#!/bin/bash
# 设置持续集成的脚本
set -e
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
    commit_id=`git rev-list --tags --max-count=1`
    date=`git describe --tags ${commit_id}`
fi
#*************
#!!!此处配置不一定要写，需各自项目视情况而定
#做配置文件修改
cp .env_$1 .env
#*************
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
        docker rm -f ${dir_name}_${name}_1
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
```

## CD部分
### CD部分结构
```
ProjectName（项目文件夹）
├──devops
├────cicd（cicd脚本目录）
├──────ci.sh（ci入口脚本）
├──────cd.sh（cd入口脚本）
├──────config（常规配置文件）
├──────cd-shell（cd依赖脚本目录）
├────────test-shell（测试脚本目录）
├──────────test.sh
├────────cd-deploy.sh
├────────cd-ff.sh
├────────cd-ft.sh
├────────cd-pf.sh
├────────cd-pt.sh
├────shell（其他脚本目录）
├──Dockerfile
├──docker-compose-development.yaml(ci阶段compose.yaml文件）
├──docker-compose.yaml(cd阶段compose.yaml文件）
```
#### 1、入口脚本cd.sh （无需改动）

```
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
    echo "input one of the parameters:dev or prod "
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
```
#### 2、CD依赖脚本cd-deploy.sh（无需改动）

```
#!/bin/bash
#设置部署的脚本
#env为preTest准备测试，fail测试失败;exist为true还未初始化，false已经初始化过
export image_name=$1
export last_image_name=$2
echo "镜像：${image_name}"
env=$3
exist=$4
cd ../
source config
cd cd-shell
if [ $auth == "yes" ];then
    docker login -u ${user} -p ${pass} ${docker_registry}
fi


#分割线，以下原则上不做修改
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
cd ../../../../
export project_dir="`pwd`/"
export dir_name=`basename ${project_dir}`
export dir_name=`echo $dir_name | tr 'A-Z' 'a-z'|tr -d '_'|tr -d '\n'`
if [ $# != 4 ];then
    echo “input first parameter:image_name、seconde parameter:last_image_name、third parameter:preTest or fail、forth parameter：true or false”
    exit 1
fi
if [ "$env" == "fail" ]; then
    echo "||测试失败，使用上个版本镜像=>"
    image_name_td=$1
    export image_name=$2
    if [ "$method" == "manual" ]; then
        cd /home/logStatu
        loga=`cat ${dir_name}_a`
        if [ "${loga}" != "${image_name_td}" ] && [ "${loga}" ];then
            echo $loga > ${dir_name}
        fi
        echo "" > ${dir_name}_a
        export image_name=`cat ${dir_name}`
        cd $DIR
        cd ../../../../
    fi
fi
#部署阶段，不是第一次部署，进行删本地容器操作
if [ "$exist" != "true" ]; then
    docker pull ${image_name}
    echo "||进行非初始化部署前工作，删除容器=>"
    for i in ${container_names} ;
        do
            docker rm -f ${dir_name}_${i}_1 ;
        done
        #env失败拉的是昨天的镜像
else
    status=`docker ps |grep "${dir_name}"_"${primary_container_name}"_1`
    #真正第一次部署
    if [ -z "$status" ];then
        echo "||将进行首次初始化部署=>"
        docker pull ${image_name}
    else
        #已经过了第一次部署
        docker pull ${image_name}
        echo "||已经部署过，但还处在初始化期间内，将进行删除容器及容器对应的构建镜像操作=>"
        for i in ${container_names} ;
            do
                docker rm -f ${dir_name}_${i}_1 ;
            done
    fi
fi

#容器部署
echo "容器部署阶段"
#容器开始部署
if [ "$env" == "fail" ];then
    if [ "$exist" != "true" ];then
        docker-compose -f "./docker-compose.yaml" up -d
        echo "||回退版本部署使用的镜像："${image_name}"||"
        status=`docker ps |grep "${dir_name}"_"${primary_container_name}"_1`
        if [ -z "$status" ]; then
            echo "||**错误信息：回退版本部署的容器运行失败,请检查端口及配置=>"
            exit 1;
        fi
    fi
else
    #env=pretest
    docker-compose -f "./docker-compose.yaml" up -d
    echo "||未测试部署所使用的镜像："${image_name}"||"
    status=`docker ps |grep "${dir_name}"_"${primary_container_name}"_1`
    if [ -z "$status" ]; then
        echo "||**错误信息：未测试部署容器运行失败,请检查端口及配置||"
        exit 1;
    fi
fi
#env来进行判断是否需要测试（测试脚本暂缺）
if [ "$env" != "fail" ]; then
    #测试脚本
    echo "||进行部署后，测试工作=>"
    ./devops/cicd/bin/cd-shell/test-shell/test.sh
    result=`echo $?`
    if [ "$result" == 0 ]; then
        echo "||部署的容器通过测试||"
        if [ "$method" == "manual" ]; then
            if [ "$exist" != "true" ];then
                loga=`cat /home/logStatu/${dir_name}_a`
                if [ "${loga}" != "${image_name}" ] && [ "${loga}" ];then
                    echo $loga > /home/logStatu/${dir_name}
                fi
                last_image_name=`cat /home/logStatu/${dir_name}`
            fi
            cd /home && mkdir logStatu
            cd logStatu
            echo ${image_name} > ${dir_name}_a


        fi
    else
        echo "||部署的容器测试未通过，判定失败部署||"
        exit 1;
    fi
    #初始化成功就不用删昨天镜像
    if [ "$exist" != "true" ]; then
        if [ "${last_image_name}" != "${image_name}" ];then
            docker pull ${last_image_name}
            docker rmi -f ${last_image_name}
        fi
    fi
     docker images|grep none|awk '{print $3 }'|xargs docker rmi
     echo "||删除项目部署产生的none镜像||"
else
    #测试失败
    #当不是第一次部署
    if [ "$exist" != "true" ]; then
        docker tag ${image_name} ${image_name_td}
        docker push ${image_name_td}
        docker rmi -f ${image_name_td}
        docker images|grep none|awk '{print $3 }'|xargs docker rmi
        echo "||删除项目部署产生的none镜像||"
    else
        #第一次部署
        docker rmi -f ${image_name_td}
        echo "||删除项目部署产生的none镜像||"
        docker images|grep none|awk '{print $3 }'|xargs docker rmi
        #删除私有仓库镜像
        echo "||进行删除私有仓库中的镜像=>"
        message=`cat constant.txt`
        curl -X DELETE ${docker_registry}/v2/${image}/manifests/${message}
    fi
fi
```
#### 3、CD部分docker-compose.yaml脚本(基本上同CI阶段相同）（需要部分改动）
有redis

```
redis1:
  image: redis
  ports:
    - "6677:6379"
cbp:
  image: "${image_name}"
  volumes:
    - "/etc/localtime:/etc/localtime"
  ports:
    - "8705:80"
  links:
    - redis1:redis.dbss
```
无redis

```
czy_business_platforms:
  image: "${image_name}"
  volumes:
    - "/etc/localtime:/etc/localtime"
  ports:
    - "8705:80"
  restart: always
```
#### 4、测试脚本（根据相关项目进行修改）

```
#!/bin/bash

#等待15秒,有些项目run容器可能需要更长的时间,需按需修改,否则会经常出现测试失败的结果
sleep 15s

#最简单的测试，判断请求http返回码是否等于200
result=$(curl -o /dev/null -s -w %{http_code} 127.0.0.1:8705/coupon/hello)
if [ "$result" == "200" ]; then
    echo '测试通过'
else
    echo "||**错误信息：127.0.0.1:8705/coupon/hello 此接口有问题，请修复**||"
    exit 1;
fi
```
#### 5、CD部分依赖脚本（无需改动）
##### 5.1、cd-pt.sh
```
#!/bin/bash
set -e
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
./cd-deploy.sh "${image_name}" "${last_image_name}" "preTest" "true"
```
##### 5.2、cd-pf.sh
```
#!/bin/bash
set -e
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
./cd-deploy.sh "${image_name}" "${last_image_name}" "preTest" "false"
```
##### 5.3、cd-ft.sh
```
#!/bin/bash
set -e
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
./cd-deploy.sh "${image_name}" "${last_image_name}" "fail" "true"
```
##### 5.4、cd-ff.sh
```
#!/bin/bash
set -e
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
cd $DIR
./cd-deploy.sh "${image_name}" "${last_image_name}" "fail" "false"
```







