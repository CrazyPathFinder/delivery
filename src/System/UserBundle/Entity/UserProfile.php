<?php

namespace System\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * System\UserBundle\Entity\UserProfile
 *
 * @ORM\Table(name="users_profiles")
 * @ORM\Entity
 */
class UserProfile
{
    /**
     * @var string $userName
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\MaxLength(limit=150, groups={"registration", "edit_profile"})
     * @ORM\Column(name="user_name", type="string", length=150, nullable=false)
     */
    private $userName;

    /**
     * @var string $userPhoto
     *
     * @ORM\Column(name="user_photo", type="string", length=150, nullable=true)
     */
    private $userPhoto;

    /**
     * @var Users
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="System\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

   
    /**
     * Set userName
     *
     * @param string $userName
     * @return UserProfile
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    
        return $this;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userPhoto
     *
     * @param string $userPhoto
     * @return UserProfile
     */
    public function setUserPhoto($userPhoto)
    {
        $this->userPhoto = $userPhoto;
    
        return $this;
    }

    /**
     * Get userPhoto
     *
     * @return string 
     */
    public function getUserPhoto()
    {
        return $this->userPhoto;
    }

    /**
     * Set user
     *
     * @param System\UserBundle\Entity\User $user
     * @return UserProfile
     */
    public function setUser(\System\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return System\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

   
}