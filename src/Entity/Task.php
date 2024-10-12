<?php

namespace App\Entity;

use App\Trait\EntityDataManager;
use App\Trait\Timestamp;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    use Timestamp;
    use EntityDataManager;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: TaskStatus::class)]
    private ?TaskStatus $status = null;

    #[Ignore]
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
