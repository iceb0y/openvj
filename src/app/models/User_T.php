<?php

namespace VJ\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User_T
{

    /** @ODM\Id */
    public $id;

    /** @ODM\String */
    public $uid;

    /** @ODM\String */
    public $luser;

    /** @ODM\String */
    public $nick;

    /** @ODM\String */
    public $lnick;

    /** @ODM\String */
    public $salt;

    /** @ODM\String */
    public $pass;

    /** @ODM\String */
    public $passfmt; // should always be 1

    /** @ODM\String */
    public $ipmatch;

    /** @ODM\String */
    public $mail;

    /** @ODM\String */
    public $qq;

    /** @ODM\String */
    public $rp;

    /** @ODM\String */
    public $vjb;

    /** @ODM\String */
    public $rank;

    /** @ODM\String */
    public $g; //gravatar email

    /** @ODM\String */
    public $gmd5;

    /** @ODM\String */
    public $gender;

    /** @ODM\String */
    public $tlogin;

    /** @ODM\String */
    public $iplogin;

    /** @ODM\String */
    public $treg;

    /** @ODM\String */
    public $ipreg;

    /** @ODM\String */
    public $sig; //signature

    /** @ODM\String */
    public $sigm;

    /** @ODM\String */
    public $group;

    /** @ODM\String */
    public $acl; //privilege

    /** @ODM\String */
    public $aclrule;

    /** @ODM\EmbedOne(targetDocument="VJ\Models\StdClass") */
    public $privacy;

    /** @ODM\EmbedOne(targetDocument="VJ\Models\StdClass") */
    public $stars;

    /** @ODM\Hash */
    public $pbms;

    /** @ODM\Hash */
    public $settings;

    /** @ODM\String */
    public $banned;

    /** @ODM\String */
    public $deleted;

}