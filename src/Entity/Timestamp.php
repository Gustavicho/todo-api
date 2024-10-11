<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
    #[ORM\Column(name: 'created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    /**
     * Set the "createdAt" property with a DateTimeImmutable instance.
     * @param string $time Optional.     Deafult is 'now'.
     * @param string $timezone Optional. Default is 'UTC'.
     */
    #[ORM\PrePersist]
    public function setCreatedAt(string $time = 'now', string $timezone = 'UTC'): static
    {
        $this->createdAt = new \DateTimeImmutable($time, new \DateTimeZone($timezone));

        return $this;
    }

    /**
      * Set the "uptadet_at" property with a DateTimeImmutable instance.
      * @param string $time Optional.     Deafult is 'now'.
      * @param string $timezone Optional. Default is 'UTC'.
      */
    #[ORM\PreUpdate]
    public function setUpdatedAt(string $time = 'now', string $timezone = 'UTC'): static
    {
        $this->updatedAt = new \DateTimeImmutable($time, new \DateTimeZone($timezone));

        return $this;
    }
}
