<?php

namespace Saxulum\FormPolyCollection\Form\Type;

use Saxulum\FormPolyCollection\Form\EventListener\ResizePolyFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PolyCollectionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototypes = $this->buildPrototypes($builder, $options);
        if ($options['allow_add'] && $options['prototype']) {
            $builder->setAttribute('prototypes', $prototypes);
        }

        $resizeListener = new ResizePolyFormListener(
            $prototypes,
            $options['options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     * @return array
     */
    protected function buildPrototypes(FormBuilderInterface $builder, array $options)
    {
        $prototypes = array();
        foreach ($options['types'] as $type) {
            $key = $type instanceof FormTypeInterface ? $type->getName() : $type;

            $prototype = $this->buildPrototype(
                $builder,
                $options['prototype_name'],
                $type,
                $key,
                $options['options']
            );
            $prototypes[$key] = $prototype->getForm();
        }

        return $prototypes;
    }

    /**
     * @param  FormBuilderInterface     $builder
     * @param  string                   $name
     * @param  FormTypeInterface|string $type
     * @param string$key
     * @param  array                    $options
     * @return FormBuilderInterface
     */
    protected function buildPrototype(FormBuilderInterface $builder, $name, $type, $key, array $options)
    {
        $prototype = $builder->create($name, $type, array_replace(array(
            'label' => $name . 'label__',
        ), $options));

        $builder->add('_type', 'hidden', array(
            'data'   => $key,
            'mapped' => false
        ));

        return $prototype;
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'allow_add'    => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
        ));

        if ($form->getConfig()->hasAttribute('prototypes')) {
            $view->vars['prototypes'] = array_map(function (FormInterface $prototype) use ($view) {
                return $prototype->createView($view);
            }, $form->getConfig()->getAttribute('prototypes'));
        }
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototypes')) {
            $multiparts = array_filter(
                $view->vars['prototypes'],
                function (FormView $prototype) {
                    return $prototype->vars['multipart'];
                }
            );

            if ($multiparts) {
                $view->vars['multipart'] = true;
            }
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $optionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };

        $resolver->setDefaults(array(
            'allow_add'      => false,
            'allow_delete'   => false,
            'prototype'      => true,
            'prototype_name' => '__name__',
            'options'        => array(),
            'delete_empty'   => false,
        ));

        $resolver->setRequired(array(
            'types'
        ));

        $resolver->setAllowedTypes(array(
            'types' => 'array'
        ));

        $resolver->setNormalizers(array(
            'options' => $optionsNormalizer,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'saxulum_polycollection';
    }
}
