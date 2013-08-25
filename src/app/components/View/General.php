<?php

namespace VJ\View;

class General extends \VJ\View\Basic
{

    public static function initialize()
    {

        $di = \Phalcon\DI::getDefault();
        $di->set('view', 'VJ\View\General');

    }

    public function __construct($options = null)
    {

        parent::__construct($options);

        global $__TEMPLATE_NAME, $__CONFIG, $__SESSION;

        $this->setViewsDir('../app/views/'.$__TEMPLATE_NAME.'/');
        $this->setVars([
            'BASE_PREFIX'      => $__CONFIG->Misc->basePrefix,
            'TITLE_SUFFIX'     => $__CONFIG->Misc->titleSuffix,
            'META_KEYWORD'     => $__CONFIG->Misc->metaKeyword,
            'META_DESC'        => $__CONFIG->Misc->metaDesc,
            'FOOTER_ICP'       => $__CONFIG->Misc->icp,
            'FOOTER_COPYRIGHT' => $__CONFIG->Misc->copyright,
            'FOOTER_VERSION'   => APP_NAME.' '.APP_VERSION,
            'APP_CONFIG'       => [
                'host'         => $__CONFIG->Misc->host,
                'basePrefix'   => $__CONFIG->Misc->basePrefix,
                'staticPrefix' => $__CONFIG->Misc->staticPrefix,
                'titleSuffix'  => $__CONFIG->Misc->titleSuffix,
                'debug'        => $__CONFIG->Debug->enabled
            ],
            'USER_DATA'        => [
                'csrf-token' => $__SESSION['csrf-token'],
                'uid'        => $__SESSION['user']['uid'],
                'nick'       => $__SESSION['user']['nick'],
                'gmd5'       => $__SESSION['user']['gmd5'],
                'settings'   => $__SESSION['user']['settings']
            ]
        ]);
    }

}