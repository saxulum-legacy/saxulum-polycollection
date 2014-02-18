<?php

namespace Saxulum\Tests\PolyCollection\Form\Type;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbstractType extends BaseType
{
    protected $dataClass = null;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'  => $this->dataClass,
            'max_length'  => 50,
        ));
    }

    public function getName()
    {
        return 'abstract_type';
    }
}
