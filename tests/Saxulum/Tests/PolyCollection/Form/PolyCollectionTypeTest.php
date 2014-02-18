<?php

namespace Saxulum\Tests\PolyCollection\Form;

use Saxulum\Tests\PolyCollection\Model\First;
use Saxulum\Tests\PolyCollection\Model\Fourth;
use Saxulum\Tests\PolyCollection\Model\Second;
use Saxulum\Tests\PolyCollection\Model\Third;
use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;

class PolyCollectionTypeTest extends TypeTestCase
{
    public function testObjectNotCoveredByTypesArray()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
        ));
        $form->setData(array(
            new First('Green'),
            new First('Green'),
            new Second('Green'),
            new Third('Brown'),
        ));
    }

    public function testInvalidObject()
    {
        $this->setExpectedException('Symfony\\Component\\Form\\Exception\\ExceptionInterface');
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
        ));
        $form->setData(array(
            new \stdClass
        ));
    }

    public function testInvalidBindType()
    {
        $this->setExpectedException('InvalidArgumentException');
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
            'allow_add' => true
        ));
        $form->bind(array(
            array(
                '_type' => 'unknown_type',
                'text' => 'Green'
            )
        ));
    }

    public function testBindInvalidData()
    {
        $this->setExpectedException('Symfony\Component\Form\Exception\UnexpectedTypeException');
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
        ));
        $form->bind('invalid_data');
    }

    public function testMultipartPropagation()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'fourth_type'
            ),
            'allow_add' => true
        ));

        $this->assertTrue($form->createView()->vars['multipart']);
    }

    public function testBindNullEmptiesCollection()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
            'allow_delete' => true
        ));
        $form->bind(null);

        $this->assertCount(0, $form->getData());
    }

    public function testResizedUpIfBoundWithExtraDataAndAllowAdd()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
            'allow_add' => true
        ));

        $form->setData(array(
            new First('Green'),
        ));
        $form->bind(array(
            array(
                '_type' => 'first_type',
                'text' => 'Green',
                'text2' => 'Car'
            ),
            array(
                '_type' => 'second_type',
                'text' => 'Red',
                'checked' => true
            )
        ));

        $this->assertTrue($form->has('0'));
        $this->assertTrue($form->has('1'));
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\First',
            $form[0]->getData()
        );
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\Second',
            $form[1]->getData()
        );
        $this->assertEquals('Red', $form[1]->getData()->text);
        $this->assertTrue($form[1]->getData()->checked);
    }

    public function testNotResizedIfBoundWithExtraData()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
        ));
        $form->setData(array(
            new First('Green'),
        ));
        $form->bind(array(
            array(
                '_type' => 'first_type',
                'text' => 'Green',
                'text2' => 'Car'
            ),
            array(
                '_type' => 'second_type',
                'text' => 'Red',
                'checked' => true
            )
        ));

        $this->assertTrue($form->has('0'));
        $this->assertFalse($form->has('1'));
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\AbstractModel',
            $form[0]->getData()
        );
    }

    public function testResizedDownIfBoundWithMissingDataAndAllowDelete()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
            'allow_delete' => true
        ));
        $form->setData(array(
            new First('Green'),
            new First('Red'),
            new Second('Blue'),
        ));
        $form->bind(array(
            array(
                '_type' => 'first_type',
                'text' => 'Green',
                'text2' => 'Car'
            ),
            array(
                '_type' => 'second_type',
                'text' => 'Red',
                'checked' => true
            )
        ));

        $this->assertTrue($form->has('0'));
        $this->assertTrue($form->has('1'));
        $this->assertFalse($form->has('2'));
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\First',
            $form[0]->getData()
        );
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\First',
            $form[1]->getData()
        );
    }

    public function testNotResizedIfBoundWithMissingData()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
        ));
        $form->setData(array(
            new First('Green'),
            new Second('Red'),
            new Second('Blue'),
        ));
        $form->bind(array(
            array(
                '_type' => 'first_type',
                'text' => 'Brown',
                'text2' => 'Car'
            ),
            array(
                '_type' => 'second_type',
                'text' => 'Yellow',
                'checked' => true
            )
        ));

        $this->assertTrue($form->has('0'));
        $this->assertTrue($form->has('1'));
        $this->assertTrue($form->has('2'));
        $this->assertEquals('Brown', $form[0]->getData()->text);
        $this->assertEquals('Yellow', $form[1]->getData()->text);
        $this->assertTrue($form[1]->getData()->checked);
        $this->assertEquals('', $form[2]->getData()->text);
        $this->assertFalse($form[2]->getData()->checked);
    }

    public function testSetDataAdjustsSize()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(
                'first_type',
                'second_type',
                'third_type',
                'fourth_type',
            ),
            'options' => array(
                'max_length' => 20,
            )
        ));
        $form->setData(array(
            new First('Red', 'Car'),
            new Second('Blue', true),
            new Third('Blue'),
        ));

        $this->assertCount(3, $form);
        $this->assertInstanceOf('Symfony\\Component\\Form\\Form', $form[0]);
        $this->assertInstanceOf('Symfony\\Component\\Form\\Form', $form[1]);
        $this->assertInstanceOf('Symfony\\Component\\Form\\Form', $form[2]);

        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\First',
            $form[0]->getData()
        );
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\Second',
            $form[1]->getData()
        );
        $this->assertInstanceOf(
            'Saxulum\\Tests\\FormPolyCollection\\Model\\Third',
            $form[2]->getData()
        );
        $this->assertEquals('Red', $form[0]->getData()->text);
        $this->assertEquals('Blue', $form[1]->getData()->text);
        $this->assertTrue($form[1]->getData()->checked);
        $this->assertEquals('Blue', $form[2]->getData()->text);
        $this->assertTrue($form[2]->getData()->another);

        $this->assertEquals(20, $form[0]->getConfig()->getOption('max_length'));
        $this->assertEquals(20, $form[1]->getConfig()->getOption('max_length'));
        $this->assertEquals(20, $form[2]->getConfig()->getOption('max_length'));

        $form->setData(array(
            new Fourth('Orange')
        ));

        $this->assertCount(1, $form);
        $this->assertInstanceOf('Symfony\\Component\\Form\\Form', $form[0]);
        $this->assertFalse(isset($form[1]));
        $this->assertFalse(isset($form[2]));
        $this->assertEquals('Orange', $form[0]->getData()->text);
        $this->assertEquals(20, $form[0]->getConfig()->getOption('max_length'));
    }

    public function testContainsNoChildByDefault()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(),
        ));

        $this->assertCount(0, $form);
    }

    public function testThrowsExceptionIfObjectIsNotTraversable()
    {
        $form = $this->factory->create('saxulum_polycollection', null, array(
            'types' => array(),
        ));
        $this->setExpectedException('Symfony\Component\Form\Exception\UnexpectedTypeException');
        $form->setData(new \stdClass());
    }

    public function testTypesMissingThrowsException()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');

        $this->factory->create('saxulum_polycollection', null, array());
    }

    protected function getExtensions()
    {
        return array(
            new FormExtension()
        );
    }
}
