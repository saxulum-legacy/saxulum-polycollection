<?php

namespace Saxulum\Tests\FormPolyCollection\Form;

use Saxulum\FormPolyCollection\Form\Type\PolyCollectionType;
use Saxulum\Tests\FormPolyCollection\Form\Type\FirstType;
use Saxulum\Tests\FormPolyCollection\Form\Type\FourthType;
use Saxulum\Tests\FormPolyCollection\Form\Type\SecondType;
use Saxulum\Tests\FormPolyCollection\Form\Type\ThirdType;
use Symfony\Component\Form\AbstractExtension;

/**
 * Testing extension for the PolyCollection
 *
 * @author Tim Nagel <t.nagel@infinite.net.au>
 * */
class FormExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return array(
            new PolyCollectionType(),
            new FirstType(),
            new SecondType(),
            new ThirdType(),
            new FourthType(),
        );
    }
}
