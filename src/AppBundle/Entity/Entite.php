<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entite
 *
 * @ORM\Table(name="entite")
 * @ORM\Entity
 */
class Entite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=145, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="sigle", type="string", length=45, nullable=true)
     */
    private $sigle;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", length=65535, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_telephone_1", type="text", length=16777215, nullable=true)
     */
    private $numeroTelephone1;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_telephone_2", type="text", length=16777215, nullable=true)
     */
    private $numeroTelephone2;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;
    
     /**
     * @var \Entite
     *
     * @ORM\ManyToOne(targetEntity="Entite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entite_id", referencedColumnName="id")
     * })
     */
    private $entite;



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
     * Set nom
     *
     * @param string $nom
     * @return Entite
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set sigle
     *
     * @param string $sigle
     * @return Entite
     */
    public function setSigle($sigle)
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * Get sigle
     *
     * @return string 
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Entite
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set numeroTelephone1
     *
     * @param string $numeroTelephone1
     * @return Entite
     */
    public function setNumeroTelephone1($numeroTelephone1)
    {
        $this->numeroTelephone1 = $numeroTelephone1;

        return $this;
    }

    /**
     * Get numeroTelephone1
     *
     * @return string 
     */
    public function getNumeroTelephone1()
    {
        return $this->numeroTelephone1;
    }

    /**
     * Set numeroTelephone2
     *
     * @param string $numeroTelephone2
     * @return Entite
     */
    public function setNumeroTelephone2($numeroTelephone2)
    {
        $this->numeroTelephone2 = $numeroTelephone2;

        return $this;
    }

    /**
     * Get numeroTelephone2
     *
     * @return string 
     */
    public function getNumeroTelephone2()
    {
        return $this->numeroTelephone2;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Entite
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    public function __toString() {
        return $this->nom;
    }

    /**
     * Set entite
     *
     * @param \AppBundle\Entity\Entite $entite
     * @return Entite
     */
    public function setEntite(\AppBundle\Entity\Entite $entite = null)
    {
        $this->entite = $entite;

        return $this;
    }

    /**
     * Get entite
     *
     * @return \AppBundle\Entity\Entite 
     */
    public function getEntite()
    {
        return $this->entite;
    }
}
