<?php

namespace app\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'students')]
class Student implements \JsonSerializable
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private int $id;

    #[Column(name: 'full_name', type: 'string')]
    private string $fullName;

    #[ManyToOne(targetEntity: Group::class, inversedBy: 'students')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: true)]
    private ?Group $group;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setFullName(?string $newFullName): self
    {
        if (!is_null($newFullName)) {
            $this->fullName = $newFullName;
        }
        return $this;
    }

    public function setGroup($newGroup): self
    {
        if (!(is_null($newGroup))) {
            $this->group = $newGroup;
        }
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName,
            'group_id' => $this->group->getId(),
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName,
            'group_id' => $this->group->getId(),
        ];
    }
}
