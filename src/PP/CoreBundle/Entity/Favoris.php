<?php

namespace PP\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favoris
 *
 * @ORM\Table(name="favoris")
 * @ORM\Entity(repositoryClass="PP\CoreBundle\Repository\FavorisRepository")
 */
class Favoris
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
     * @ORM\ManyToOne(targetEntity="PP\CoreBundle\Entity\Recette")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recette;
    
     /**
     * @ORM\ManyToOne(targetEntity="PP\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set recette
     *
     * @param \PP\CoreBundle\Entity\Recette $recette
     *
     * @return Favoris
     */
    public function setRecette(\PP\CoreBundle\Entity\Recette $recette)
    {
        $this->recette = $recette;

        return $this;
    }

    /**
     * Get recette
     *
     * @return \PP\CoreBundle\Entity\Recette
     */
    public function getRecette()
    {
        return $this->recette;
    }

    /**
     * Set user
     *
     * @param \PP\UserBundle\Entity\User $user
     *
     * @return Favoris
     */
    public function setUser(\PP\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PP\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
