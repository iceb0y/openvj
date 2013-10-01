<?php

namespace VJ\Models;

class Contest extends \VJ\Collection
{

    public $_id;

    public $id;

    public $owner;
   
    public $participate; //能参与的组
    
    public $title;

    public $problem;

    public $type;
    
    public $starttime;
    
    public $endtime;

}
