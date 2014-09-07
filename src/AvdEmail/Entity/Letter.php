<?php
namespace AvdEmail\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="letters")
 */
class Letter
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
     
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $recipient;
    
    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $subject;
    
    /**
     * @ORM\Column(type="string", length=5000)
     */
    protected $text;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function setText($text)
    {
        $this->text = $text;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }
}
