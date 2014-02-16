<?php

namespace Saxulum\Tests\FormPolyCollection\Model;

class Second extends AbstractModel
{
    public $checked;

    public function __construct($text = null, $checked = false)
    {
        parent::__construct($text);

        $this->checked = $checked;
    }
}
