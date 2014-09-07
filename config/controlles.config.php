<?php
return array(
    'factories' => array(
        'AvdEmail\Controller\Email' => function($serviceLocator) {
            $ctr = new \AvdEmail\Controller\EmailController();
            
            $objectManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $letterEntity = new \AvdEmail\Entity\Letter();
            $letterForm = new \AvdEmail\Form\LetterForm($objectManager);
            
            $ctr->setObjectManager($objectManager);
            $ctr->setLetterEntity($letterEntity);
            $ctr->setLetterForm($letterForm);
            
            return $ctr;
        }
    )
);
