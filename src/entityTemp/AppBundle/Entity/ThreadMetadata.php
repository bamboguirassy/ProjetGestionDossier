<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThreadMetadata
 */
class ThreadMetadata
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Thread
     */
    private $thread;

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
     * Set thread
     *
     * @param \AppBundle\Entity\Thread $thread
     * @return ThreadMetadata
     */
    public function setThread(\AppBundle\Entity\Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \AppBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set participant
     *
     * @param \AppBundle\Entity\User $participant
     * @return ThreadMetadata
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
