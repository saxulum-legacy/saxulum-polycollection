<?php

namespace Saxulum\FormPolyCollection\Form\EventListener;

use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

class ResizePolyFormListener extends ResizeFormListener
{
    /**
     * @var array
     */
    protected $typeMap = array();

    /**
     * @var array
     */
    protected $classMap = array();

    /**
     * @param  array                     $prototypes
     * @param  array                     $options
     * @param  bool                      $allowAdd
     * @param  bool                      $allowDelete
     * @param  bool                      $deleteEmpty
     * @throws \InvalidArgumentException
     */
    public function __construct(array $prototypes, array $options = array(), $allowAdd = false, $allowDelete = false, $deleteEmpty = false)
    {
        foreach ($prototypes as $prototype) {
            /** @var FormInterface $prototype */

            $dataClass = $prototype->getConfig()->getOption('data_class');
            if (is_null($dataClass)) {
                throw new \InvalidArgumentException("Only form types with data_class are supported!");
            }

            $type = $prototype->getConfig()->getType()->getInnerType();
            $key = $type instanceof FormTypeInterface ? $type->getName() : $type;

            $this->typeMap[$key] = $type;
            $this->classMap[$dataClass] = $type;
        }

        parent::__construct(null, $options, $allowAdd, $allowDelete);
    }

    /**
     * @param  FormEvent               $event
     * @throws UnexpectedTypeException
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        // Then add all rows again in the correct order
        foreach ($data as $name => $value) {


            $type = $this->getTypeForObject($value);
            $form->add($name, $type, array_replace(array(
                'property_path' => '['.$name.']',
            ), $this->options));
        }
    }

    /**
     * @param  object                   $object
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function getTypeForObject($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException("Data is not an object!");
        }

        $dataClass = get_class($object);

        if (!array_key_exists($dataClass, $this->classMap)) {
            throw new InvalidArgumentException("There is no form type for data_class '{$dataClass}'!");
        }

        return $this->classMap[$dataClass];
    }

    /**
     * @param  FormEvent               $event
     * @throws UnexpectedTypeException
     */
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data || '' === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // Remove all empty rows
        if ($this->allowDelete) {
            foreach ($form as $name => $child) {
                if (!isset($data[$name])) {
                    $form->remove($name);
                }
            }
        }

        // Add all additional rows
        if ($this->allowAdd) {
            foreach ($data as $name => $value) {
                if (!$form->has($name)) {
                    $type = $this->getTypeForData($value);
                    $form->add($name, $type, array_replace(array(
                        'property_path' => '['.$name.']',
                    ), $this->options));
                }
            }
        }
    }

    /**
     * @param  array                     $data
     * @return FormTypeInterface|string
     * @throws \InvalidArgumentException
     */
    protected function getTypeForData(array $data)
    {
        if (!array_key_exists('_type', $data) || !array_key_exists($data['_type'], $this->typeMap)) {
            throw new InvalidArgumentException('Unable to determine the Type for given data');
        }

        return $this->typeMap[$data['_type']];
    }
}
