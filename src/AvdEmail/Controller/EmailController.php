<?php
namespace AvdEmail\Controller;

use DateInterval;
use DateTime;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class EmailController extends AbstractActionController
{
    private $objectManager;
    private $letterEntity;
    private $letterForm;

    public function indexAction()
    {
    }
    
    public function newAction()
    {
        $formSuccess = false;

        $this->letterForm->bind($this->letterEntity);
        
        if ($this->getRequest()->isPost()) {
            $this->letterForm->setData($this->getRequest()->getPost());
            
            if ($this->letterForm->isValid()) {
                $this->letterEntity->setDate(new DateTime());
                $this->objectManager->persist($this->letterEntity);
                $this->objectManager->flush();
                $formSuccess = true;
            }
        }

        $viewModel = new ViewModel();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }
        $viewModel->setVariables(array(
            'letterForm' => $this->letterForm,
            'formSuccess' => $formSuccess
        ));
        
        return $viewModel;
    }
    
    public function viewAction()
    {
        $queryBuilder = $this->objectManager->createQueryBuilder();

        $queryBuilder->select('l')
                     ->from('AvdEmail\Entity\Letter', 'l')
                     ->where('l.id = :id')
                     ->setParameter('id', $this->params()->fromRoute('id'));
        
        $result = null;
        
        try {
            $result = $queryBuilder->getQuery()->getSingleResult();
        } catch (\Exception $e) {
        }
        
        $viewModel = new ViewModel();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }
        $viewModel->setVariables(array(
            'letter' => $result
        ));
        return $viewModel;
    }
    
    public function inboxAction()
    {
        
    }

    public function sentAction()
    {
        $queryBuilder = $this->objectManager->createQueryBuilder();

        $queryBuilder->select('l')
                     ->from('AvdEmail\Entity\Letter', 'l');
           
        if ($this->params()->fromPost('orderBy')) {
            $queryBuilder->orderBy('l.date', $this->params()->fromPost('orderBy'));
        } else {
            $queryBuilder->orderBy('l.date', "DESC");
        }
        
        if ($this->params()->fromPost('recipient')) {
            $queryBuilder->where('l.recipient = :recipient')
                         ->setParameter('recipient', $this->params()->fromPost('recipient'));
        }
        
        if ($this->params()->fromPost('date')) {
            $dateFrom = new DateTime($this->params()->fromPost('date'));
            $dateTo = new DateTime($this->params()->fromPost('date'));
            $dateTo->add(new DateInterval('P1D'));
            $queryBuilder->andWhere($queryBuilder->expr()->between('l.date', ':from', ':to'))
                         ->setParameters(
                             array(
                               'from' => $dateFrom,
                               'to' => $dateTo
                             )
                         );
        }
        
        $currentPageNumber = 0;
        
        if ($this->params()->fromPost("paginatorPage")) {
            $currentPageNumber = $this->params()->fromPost("paginatorPage");
        }

        $paginator = new \Zend\Paginator\Paginator(
            new \AvdEmail\Paginator\Paginator($queryBuilder, $currentPageNumber)
        );
        $paginator->setCurrentPageNumber($currentPageNumber);
        
        $viewModel = new ViewModel();
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }
        
        $viewModel->setVariables(array(
            'paginator' => $paginator,
        ));
        
        return $viewModel;
    }
    
    public function deleteAction()
    {
        $deleteId = $this->params()->fromPost('deleteId');
        
        foreach ($deleteId as $id) {
            $this->objectManager
                 ->createQuery('DELETE FROM AvdEmail\Entity\Letter l WHERE l.id = :id')
                 ->setParameter('id', $id)
                 ->execute();
        }

        $viewModel = new ViewModel();
        $viewModel = $this->sentAction();
        $viewModel->setTemplate('avd-email/email/sent');
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function insertAction()
    {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        $dateInterval = new DateInterval('PT3H');
        
        $u = 1;
        $batchSize = 50;
        
        $insertCount = 500;
        
        if ($this->params()->fromRoute('id')) {
            $insertCount = (int)$this->params()->fromRoute('id');
        }

        for ($i = 1; $i < $insertCount; $i++) {
            $letterEntity = new \AvdEmail\Entity\Letter();
            if (($i % 5) == 0) {
                $u++;
            }
            $letterEntity->setRecipient('user-'.$u.'-@gmail.com');
            $letterEntity->setSubject('subject - ' . $i);
            $letterEntity->setText('text text text text text text text');
            $dateTime = new DateTime();
            $dateTime->setTimestamp($timestamp);
            $dateTime->sub($dateInterval);
            $letterEntity->setDate($dateTime);
            $timestamp = $dateTime->getTimestamp();
            $this->objectManager->persist($letterEntity);
            if (($i % $batchSize) === 0) {
                $this->objectManager->flush();
                $this->objectManager->clear();
            }
        }
        $this->objectManager->flush();
        $this->objectManager->clear();
        echo 'ok';
    }
    
    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
    public function setLetterEntity($letterEntity)
    {
        $this->letterEntity = $letterEntity;
    }
    
    public function setLetterForm($letterForm)
    {
        $this->letterForm = $letterForm;
    }
}
