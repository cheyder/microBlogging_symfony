<?php

namespace App\Entity;

use App\Repository\LikeNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeNotificationRepository::class)
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MicroPost::class)
     */
    private $microPost;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $likedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMicroPost(): ?MicroPost
    {
        return $this->microPost;
    }

    public function setMicroPost(?MicroPost $microPost): self
    {
        $this->microPost = $microPost;

        return $this;
    }

    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    public function setLikedBy(?User $likedBy): self
    {
        $this->likedBy = $likedBy;

        return $this;
    }
}
