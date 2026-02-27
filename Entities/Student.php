<?php

namespace app\Entities;

use app\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ORM\Table(name: 'students')]
class Student implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'full_name', type: 'string')]
    private ?string $fullName;

    // #[ORM\Column(name: 'group_id', type: 'integer')]

    #[ManyToOne(targetEntity: Group::class, inversedBy: 'students')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: true)]
    private ?Group $group;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setFullName(string $newFullName): self
    {
        $this->fullName = $newFullName;
        return $this;
    }

    public function setGroup($newGroup): self
    {
        $this->group = $newGroup;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName,
            'group_id' => $this->group->getId(),
        ];
    }
}
