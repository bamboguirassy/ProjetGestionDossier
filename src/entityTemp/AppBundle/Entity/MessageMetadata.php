<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageMetadata
 */
class MessageMetadata
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Message
     */
    private $message;

    /**
     * @var \AppBundle\Entity\User
     */
    private $participant;


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
     * Set message
     *
     * @param \AppBundle\Entity\Message $message
     * @return MessageMetadata
     */
    public function setMessage(\AppBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \AppBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set participant
     *
     * @param \AppBundle\Entity\User $participant
     * @return MessageMetadata
     */
    public function setParticipant(\AppBundle\Entity\User $participant = null)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get participant
     *
     * @return \AppBundle\Entity\User 
     */
    public function getParticipant()
    {
        return $this->participant;
    }
}
