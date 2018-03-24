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