<?php

namespace Saxulum\FormPolyCollection\Form;

use Saxulum\FormPolyCollection\Form\Type\PolyCollectionType;
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
