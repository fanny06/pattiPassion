<?php

namespace PP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity(repositoryClass="PP\CoreBundle\Repository\InscriptionRepository")
 */
class Inscription
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="psw", type="string", length=255)
     */
    private $psw;

    /**
     * @var string
     *
     * @ORM\Column(name="pswverif", type="string", length=255)
     */
    private $pswverif;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Inscription
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set psw
     *
     * @param string $psw
     *
     * @return Inscription
     */
    public function setPsw($psw)
    {
        $this->psw = $psw;

        return $this;
    }

    /**
     * Get psw
     *
     * @return string
     */
    public function getPsw()
    {
        return $this->psw;
    }

    /**
     * Set pswverif
     *
     * @param string $pswverif
     *
     * @return Inscription
     */
    public function setPswverif($pswverif)
    {
        $this->pswverif = $pswverif;

        return $this;
    }

    /**
     * Get pswverif
     *
     * @return string
     */
    public function getPswverif()
    {
        return $this->pswverif;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Inscription
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Inscription
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}

