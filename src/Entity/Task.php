<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(enumType: TaskStatus::class)]
    private ?TaskStatus $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(targetEntity: ToDoList::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ToDoList $list = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

     /**
      * Set the "created_at" property with a DateTimeImmutable instance.
      * @param string $time Optional.     Deafult is 'now'.
      * @param string $timezone Optional. Default is 'UTC'.
      */
    #[ORM\PrePersist]
    public function setCreatedAt(string $time = 'now', string $timezone = 'UTC'): static
    {
        $this->created_at = new \DateTimeImmutable($time, new \DateTimeZone($timezone));

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }
    
    /**
      * Set the "uptadet_at" property with a DateTimeImmutable instance.
      * @param string $time Optional.     Deafult is 'now'.
      * @param string $timezone Optional. Default is 'UTC'.
      */
    #[ORM\PreUpdate]
    public function setUpdatedAt(string $time = 'now', string $timezone = 'UTC'): static
    {
        $this->updated_at = new \DateTimeImmutable($time, new \DateTimeZone($timezone));

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getList(): ?ToDoList
    {
        return $this->list;
    }

    public function setList(?ToDoList $list): static
    {
        $this->list = $list;

        return $this;
    }
}
