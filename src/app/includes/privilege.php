<?php

//DO NOT REMOVE THE COMMENT BELOW!
//PRIVILEGE-TABLE-BEGIN

const PRIV = 0; //总权限

//开发
const PRIV_DEV = 100; //开发总权限
const PRIV_DEV_ACCESS = 101; //进入开发组后台
const PRIV_DEV_DB = 102; //修改数据库
const PRIV_DEV_EXEC = 103; //执行任意指令

//管理
const PRIV_ADMIN = 200; //管理总权限
const PRIV_ADMIN_ACCESS = 201; //进入管理后台

//登入登出
const PRIV_LOG_IN = 1000; //登录到该账户
const PRIV_LOG_OUT = 1001; //登出

//用户
const PRIV_USER = 10000; //用户总权限
const PRIV_USER_BASIC = 10001; //用户基本权限
const PRIV_USER_MODIFY_SETTINGS = 10002; //修改用户设置
const PRIV_USER_MODIFY_ACCOUNT = 10003; //修改账号信息
const PRIV_USER_GET_DETAILS_PUBLIC = 10101; //获取特定用户详细信息
const PRIV_USER_GET_DETAILS_ANY = 10102; //不受隐私限制获取特定用户所有信息
const PRIV_USER_GET_LOGININFO = 10103; //获取特定用户登录记录
const PRIV_USER_DELETE_FLAG = 10201; //标记用户为已删除
const PRIV_USER_DELETE_PERM = 10202; //彻底删除用户
const PRIV_USER_BAN = 10203; //封禁用户

//题目
const PRIV_PROBLEM = 11000; //题库总权限
const PRIV_PROBLEM_CREATE = 11001; //创建题目
const PRIV_PROBLEM_DELETE_SELF = 11002; //删除自己的题目
const PRIV_PROBLEM_DELETE_ANY = 11003; //删除任意题目
const PRIV_PROBLEM_MODIFY_SELF = 11004; //修改自己的题目
const PRIV_PROBLEM_MODIFY_ANY = 11005; //修改任意题目
const PRIV_PROBLEM_RESTORE = 11006; //恢复被删除的题目
const PRIV_PROBLEM_TRASH = 11007; //清理被删除的题目
//题单
const PRIV_PROBLEMLIST = 11500; //题单总权限
const PRIV_PROBLEMLIST_CREATE = 11501; //创建题单
const PRIV_PROBLEMLIST_DELETE_SELF = 11502; //删除自己的题单
const PRIV_PROBLEMLIST_DELETE_ANY = 11503; //删除任意题单
const PRIV_PROBLEMLIST_MODIFY_SELF = 11504; //修改自己的题单
const PRIV_PROBLEMLIST_MODIFY_ANY = 11505; //修改任意题单
const PRIV_PROBLEMLIST_RESTORE = 11506; //恢复被删除的题单
const PRIV_PROBLEMLIST_TRASH = 11507; //清理被删除的题单

//评测
const PRIV_JUDGE = 12000; //评测总权限
const PRIV_JUDGE_JUDGE = 12001; //评测
const PRIV_JUDGE_REJUDGE = 12002; //重测
//记录
const PRIV_RECORD = 12500; //记录总权限
const PRIV_RECORD_LIST = 12501; //查看所有记录
const PRIV_RECORD_VIEW_STATUS = 12502; //查看记录基本状态
const PRIV_RECORD_VIEW_CODE = 12503; //查看记录对应代码

//讨论
const PRIV_DISCUSSION = 13000; //讨论总权限
const PRIV_DISCUSSION_COMMENT_TOPIC = 13001; //评论
const PRIV_DISCUSSION_COMMENT_DELETE_SELF = 13002; //删除自己的评论
const PRIV_DISCUSSION_COMMENT_MODIFY_SELF = 13003; //修改自己的评论
const PRIV_DISCUSSION_REPLY_COMMENT = 13004; //回复评论
const PRIV_DISCUSSION_REPLY_DELETE_SELF = 13005; //删除自己的回复
const PRIV_DISCUSSION_REPLY_MODIFY_SELF = 13006; //修改自己的回复
const PRIV_DISCUSSION_DELETE_ANY = 13007; //删除任意评论或回复
const PRIV_DISCUSSION_MODIFY_ANY = 13008; //修改任意评论或回复

//评价
const PRIV_STAR = 13101; //标星
const PRIV_VOTE = 13102; //支持或反对

//话题
const PRIV_TOPIC = 14000; //话题总权限
const PRIV_TOPIC_CREATE = 14001; //创建话题
const PRIV_TOPIC_DELETE_SELF = 14002; //删除自己的话题
const PRIV_TOPIC_DELETE_ANY = 14003; //删除任意话题
const PRIV_TOPIC_MODIFY_SELF = 14004; //修改自己的话题
const PRIV_TOPIC_MODIFY_ANY = 14005; //修改任意话题
const PRIV_TOPIC_HIGHLIGHT = 14006; //高亮话题
//题解
const PRIV_SOLUTION = 14500; //题解总权限
const PRIV_SOLUTION_CREATE = 14501; //创建题解
const PRIV_SOLUTION_DELETE_SELF = 14502; //删除自己的题解
const PRIV_SOLUTION_DELETE_ANY = 14503; //删除任意题解
const PRIV_SOLUTION_MODIFY_SELF = 14504; //修改自己的题解
const PRIV_SOLUTION_MODIFY_ANY = 14505; //修改任意题解

//比赛
const PRIV_TEST = 15000; //比赛总权限

//团队
const PRIV_TEAM = 16000; //团队总权限

//App
const PRIV_APP = 18000; //应用总权限

//其他
const PRIV_OTHER = 19000; //其他总权限


//PRIVILEGE-TABLE-END
//DO NOT REMOVE THE COMMENT ABOVE!