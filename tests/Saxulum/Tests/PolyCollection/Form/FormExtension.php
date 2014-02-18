<?php

namespace Saxulum\Tests\PolyCollection\Form;

use Saxulum\PolyCollection\Form\Type\PolyCollectionType;
use Saxulum\Tests\PolyCollection\Form\Type\FirstType;
use Saxulum\Tests\PolyCollection\Form\Type\FourthType;
use Saxulum\Tests\PolyCollection\Form\Type\SecondType;
use Saxulum\Tests\PolyCollection\Form\Type\ThirdType;
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
