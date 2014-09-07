<?php
namespace AvdEmail\Form;

use AvdEmail\Entity\Letter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class LetterFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('letter');
        
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new Letter());
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'recipient',
            'attributes' => array(
                'autofocus' => 'true',
                'required' => 'true',
                'placeholder' => 'введите е-мейл получателя',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Получатель',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'subject',
            'options' => array(
                'label' => 'Тема письма'
            ),
            'attributes' => array(
                'placeholder' => '',
                'class' => 'form-control',
                'required' => true,
                'maxlength' => 200
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'text',
            'options' => array(
                'label' => 'Текст письма'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'rows' => 10,
                'required' => true,
                'maxlength' => 5000
            ),
        ));
    }
    
    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false,
            ),
            'recipient' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'message' => 'Неверный email адресс'
                        )
                    ),
                    array(
                        'name' => 'NotEmpty'
                    )
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                )
            ),
            'subject' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 500
                        )
                    )
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                )
            ),
            'text' => array(
                'required' => true
                ,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 5000
                        )
                    )
                ),
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                )
            )
        );
    }
}
