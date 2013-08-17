<?php

namespace VJ;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{

    public function __construct($view, $di = NULL)
    {

        parent::__construct($view, $di);

        global $__CONFIG;

        $this->setOptions([
            'compiledPath'      => ROOT_DIR.'runtime/compiled_templates/',
            'compiledExtension' => '.compiled',
            'compileAlways'     => (bool)$__CONFIG->Template->compileAlways
        ]);

        $compiler = $this->getCompiler();
        $compiler->addFunction('view_static', 'VJ\View::view_static');
        $compiler->addFunction('view_processTime', 'VJ\View::view_processTime');
        $compiler->addFilter('i18n', 'VJ\View::view_i18n');
        $compiler->addFilter('html', 'VJ\Escaper::html');
        $compiler->addFilter('attr', 'VJ\Escaper::htmlAttr');
        $compiler->addFilter('uri', 'VJ\Escaper::uri');
        $compiler->addFilter('json', 'json_encode');

    }

}