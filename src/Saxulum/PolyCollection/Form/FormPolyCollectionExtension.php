<?php

namespace Saxulum\PolyCollection\Form;

use Saxulum\PolyCollection\Form\Type\PolyCollectionType;
use Symfony\Component\Form\AbstractExtension;

class FormPolyCollectionExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return array(
            new PolyCollectionType(),
        );
    }
}
