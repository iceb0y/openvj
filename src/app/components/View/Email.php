<?php

namespace VJ\View;

class Email extends Basic
{

    public function __construct($options = null)
    {
        parent::__construct($options);

        global $__CONFIG;

        $this->setViewsDir('../app/views/'.$__CONFIG->Mail->template.'/');
        $this->setDI(new \Phalcon\DI\FactoryDefault());
        $this->setVars([

            'TITLE_SUFFIX' => $__CONFIG->Mail->titleSuffix,
            'SITE_NAME'    => $__CONFIG->Mail->siteName,
            'SITE_URI'     => $__CONFIG->Mail->siteURI,

        ]);
    }
}