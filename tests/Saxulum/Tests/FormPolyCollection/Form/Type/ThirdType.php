<?php

namespace Saxulum\Tests\FormPolyCollection\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class ThirdType extends AbstractType
{
    protected $dataClass = 'Saxulum\\Tests\\FormPolyCollection\\Model\\Third';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('another', 'checkbox', array('required' => false));
    }

    public function getName()
    {
        return 'third_type';
    }
}
