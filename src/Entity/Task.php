<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task_list', 'with_tasks'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['task_list', 'with_tasks'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['task_list', 'with_tasks'])]
    private ?string $description = null;

    #[ORM\Column(enumType: TaskStatus::class)]
    #[Groups(['task_list', 'with_tasks'])]
    private ?TaskStatus $status = null;

    #[ORM\Column(name: 'created_at')]
    #[Groups(['task_list', 'with_tasks'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', nullable: true)]
    #[Groups(['task_list', 'with_tasks'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: ToDoList::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
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
        return $this->createdAt;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
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

    public function getStatus(): ?TaskStatus
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
