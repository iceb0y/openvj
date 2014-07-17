<?php

namespace VJ;

class Collection extends \Phalcon\Mvc\Collection
{

    public function getSource()
    {

        // Use the class name as the source
        $class = explode('\\', get_class($this));

        return end($class);
    }
}