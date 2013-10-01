<?php

namespace VJ\Models;

class User extends \VJ\Collection
{

    public $_id;

    public $uid;

    public $luser;

    public $nick;

    public $lnick;

    public $salt;

    public $pass;

    public $passfmt; // should always be 1

    public $ipmatch;

    public $mail;

    public $qq;

    public $rp;

    public $vjb;

    public $rank;

    public $g; //gravatar email

    public $gmd5;

    public $gender;

    public $tlogin;

    public $iplogin;

    public $treg;

    public $ipreg;

    public $sig; //signature

    public $sigm;

    public $group;

    public $acl; //privilege

    public $aclrule;

    public $privacy;

    public $stars;

    public $pbms;

    public $settings;

}