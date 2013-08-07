<?php

if (!apc_load_constants('openvj-const-privilege'))
{
    apc_define_constants('openvj-const-privilege', array
    (
        'PRIV'                             => 0, //总权限
        
        //开发
        'PRIV_DEV'                         => 10, //Development
        'PRIV_DEV_ACCESS'                  => 11, //访问DevelopmentHome
        'PRIV_DEV_DB'                      => 20, //修改数据库
        'PRIV_DEV_EXEC'                    => 40, //执行任意指令

        //用户
        'PRIV_USER'                        => 10000, //用户总权限

        //题目
        'PRIV_PROBLEM'                     => 11000, //题库总权限

        //题单
        'PRIV_PROBLEMLIST'                 => 12000, //题单总权限

        //评测记录
        'PRIV_RECORD'                      => 13000, //记录总权限

        //讨论
        'PRIV_DISCUSS'                     => 14000, //讨论总权限
        
        //比赛
        'PRIV_TEST'                        => 15000, //比赛总权限

        //团队
        'PRIV_TEAM'                        => 16000, //团队总权限

        //RP
        'PRIV_RP'                          => 17000, //RP总权限

        //App
        'PRIV_APP'                         => 18000, //应用总权限

        //其他
        'PRIV_ETC'                         => 19000, //其他总权限

        //管理
        'PRIV_ADMIN'                       => 20000, //进入管理后台

    ));
}
