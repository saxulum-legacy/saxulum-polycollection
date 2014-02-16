<?php

namespace Saxulum\Tests\FormPolyCollection\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class FirstType extends AbstractType
{
    protected $dataClass = 'Saxulum\\Tests\\FormPolyCollection\\Model\\First';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('text2', 'text');
    }

    public function getName()
    {
        return 'first_type';
    }
}
