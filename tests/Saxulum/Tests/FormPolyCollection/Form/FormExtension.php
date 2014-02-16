<?php

/*
 * (c) Infinite Networks <http://www.infinite.net.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saxulum\Tests\FormPolyCollection\Form;

use Saxulum\FormPolyCollection\Form\Type\PolyCollectionType;
use Saxulum\Tests\FormPolyCollection\Form\Type\AbstractType;
use Saxulum\Tests\FormPolyCollection\Form\Type\FirstType;
use Saxulum\Tests\FormPolyCollection\Form\Type\FourthType;
use Saxulum\Tests\FormPolyCollection\Form\Type\SecondType;
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
            new AbstractType(),
            new FirstType(),
            new SecondType(),
            new FourthType(),
        );
    }
}