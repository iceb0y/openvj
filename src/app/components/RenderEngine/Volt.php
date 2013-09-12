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

        $compiler->addFunction('view_static', '\VJ\View\Basic::view_static');
        $compiler->addFunction('view_processTime', '\VJ\View\Basic::view_processTime');
        $compiler->addFunction('has_priv', '\VJ\View\Basic::has_priv');

        $compiler->addFilter('i18n', '\VJ\View\Basic::i18n');

        // Functional
        $compiler->addFunction('getuser', '\VJ\User\Utils::getUserInfo');

    }

}