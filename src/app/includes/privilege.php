<?php

const PRIV = 0; //总权限

//开发
const PRIV_DEV = 10; //Development
const PRIV_DEV_ACCESS = 11; //访问DevelopmentHome
const PRIV_DEV_DB = 20; //修改数据库
const PRIV_DEV_EXEC = 40; //执行任意指令

//访问
const PRIV_LOG_IN = 1000; //登录到该账户
const PRIV_LOG_OUT = 1001; //登出

//用户
const PRIV_USER = 10000; //用户总权限
const PRIV_USER_BAR = 10001; //用户控制条

//题目
const PRIV_PROBLEM = 11000; //题库总权限

//题单
const PRIV_PROBLEMLIST = 12000; //题单总权限

//评测记录
const PRIV_RECORD = 13000; //记录总权限

//讨论
const PRIV_DISCUSSION = 14000; //讨论总权限
const PRIV_DISCUSSION_REPLY_TOPIC = 14101; //创建评论
const PRIV_DISCUSSION_REPLY_COMMENT = 14102; //回复评论

//比赛
const PRIV_TEST = 15000; //比赛总权限

//团队
const PRIV_TEAM = 16000; //团队总权限

//RP
const PRIV_RP = 17000; //RP总权限

//App
const PRIV_APP = 18000; //应用总权限

//其他
const PRIV_ETC = 19000; //其他总权限

//管理
const PRIV_ADMIN = 20000; //进入管理后台