<?php

namespace Saxulum\Tests\FormPolyCollection\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class FourthType extends AbstractType
{
    protected $dataClass = 'Saxulum\\Tests\\FormPolyCollection\\Model\\Fourth';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('file', 'file', array('required' => false));
    }

    public function getName()
    {
        return 'fourth_type';
    }
}
