<?php

namespace Saxulum\Tests\FormPolyCollection\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class SecondType extends AbstractType
{
    protected $dataClass = 'Saxulum\\Tests\\FormPolyCollection\\Model\\Second';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('checked', 'checkbox', array('required' => false));
    }

    public function getName()
    {
        return 'second_type';
    }
}
