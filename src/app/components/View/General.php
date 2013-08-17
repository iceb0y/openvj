<?php

namespace VJ\View;

class General extends \VJ\View\Basic
{

    public function __construct($options = null)
    {

        parent::__construct($options);

        global $__TEMPLATE_NAME, $__CONFIG;

        $this->setViewsDir('../app/views/'.$__TEMPLATE_NAME.'/');
        $this->setVars([
            'BASE_PREFIX'      => $__CONFIG->Misc->basePrefix,
            'TITLE_SUFFIX'     => $__CONFIG->Misc->titleSuffix,
            'META_KEYWORD'     => $__CONFIG->Misc->metaKeyword,
            'META_DESC'        => $__CONFIG->Misc->metaDesc,
            'FOOTER_ICP'       => $__CONFIG->Misc->icp,
            'FOOTER_COPYRIGHT' => $__CONFIG->Misc->copyright,
            'FOOTER_VERSION'   => APP_NAME.' '.APP_VERSION,
        ]);
    }

}