#!/bin/bash

#等待15秒,有些项目run容器可能需要更长的时间,需按需修改,否则会经常出现测试失败的结果
sleep 15s

#最简单的测试，判断请求http返回码是否等于200
result=$(curl -o /dev/null -s -w %{http_code} single-czytest.colourlife.com)
if [ "$result" == "200" ]; then
    echo '测试通过'
else
    echo "||**错误信息：127.0.0.1:8001 此接口有问题，请修复**||"
    exit 1;
fi
