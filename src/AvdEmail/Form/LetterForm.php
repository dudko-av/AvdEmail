<?php
namespace AvdEmail\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class LetterForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('letter-form');
        $this->setAttribute('metod', 'post');
        $this->setAttribute('action', '/email/new');
        $this->setHydrator(new DoctrineHydrator($objectManager));
        
        $letterFieldset = new \AvdEmail\Form\LetterFieldset($objectManager);
        $letterFieldset->setUseAsBaseFieldset(true);
        $this->add($letterFieldset);
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Submit',
                'class' => 'btn btn-success btn-sm pull-right',
                'value' => 'Отправить'
            )
        ));
        
        $this->add(array(
            'name' => 'reset',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Reset',
                'class' => 'btn btn-warning btn-sm mg-left-6 pull-right',
                'value' => 'Отменить'
            )
        ));
    }
}
