<?php

namespace VJ\RenderEngine;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{

    public function __construct($view, $di = null)
    {

        parent::__construct($view, $di);

        global $__CONFIG;

        $this->setOptions([
            'compiledPath'      => ROOT_DIR.'runtime/compiled_templates/',
            'compiledExtension' => '.compiled',
            'compileAlways'     => (bool)$__CONFIG->Template->compileAlways
        ]);

        $compiler = $this->getCompiler();

        $compiler->addFunction('asset', '\VJ\View\Basic::asset');
        $compiler->addFunction('template', '\VJ\View\Basic::template');
        $compiler->addFunction('page', '\VJ\View\Basic::page');
        
        $compiler->addFunction('processTime', '\VJ\View\Basic::processTime');
        $compiler->addFunction('hasPriv', '\VJ\View\Basic::hasPriv');

        $compiler->addFilter('json', '\VJ\Escaper::json');
        $compiler->addFilter('date', '\VJ\Utils::formatDate');
        $compiler->addFilter('time', '\VJ\Utils::formatTime');
        $compiler->addFilter('datetime', '\VJ\Utils::formatDateTime');
        $compiler->addFilter('i18n', '\VJ\View\Basic::i18n');

        // Functional
        $compiler->addFunction('getuser', '\VJ\User\Utils::getUserInfo');

    }

}