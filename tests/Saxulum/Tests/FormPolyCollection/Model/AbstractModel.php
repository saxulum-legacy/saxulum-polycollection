<?php

namespace Saxulum\Tests\FormPolyCollection\Model;

class AbstractModel
{
    public $text;

    public function __construct($text = null)
    {
        $this->text = $text;
    }
}
