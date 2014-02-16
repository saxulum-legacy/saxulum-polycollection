<?php

namespace Saxulum\Tests\FormPolyCollection\Form\Type;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbstractType extends BaseType
{
    protected $dataClass = 'Saxulum\\Tests\\FormPolyCollection\\Model\\AbstractModel';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', 'text');

        $builder->add('_type', 'hidden', array(
            'data'   => $this->getName(),
            'mapped' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'  => $this->dataClass,
            'model_class' => $this->dataClass,
            'max_length'  => 50,
        ));
    }

    public function getName()
    {
        return 'abstract_type';
    }
}
