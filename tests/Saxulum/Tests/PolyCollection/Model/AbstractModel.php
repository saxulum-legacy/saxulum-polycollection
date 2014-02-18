<?php

namespace Saxulum\Tests\PolyCollection\Model;

abstract class AbstractModel
{
    public $text;

    public function __construct($text = null)
    {
        $this->text = $text;
    }
}
