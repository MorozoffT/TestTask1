<?php

namespace app\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'student_groups')]
class Group implements \JsonSerializable
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'id', type: 'integer')]
    private int $id;

    #[Column(name: 'title', type: 'string')]
    private string $title;

    #[Column(name: 'course', type: 'integer')]
    private int $course;

    #[OneToMany(mappedBy: 'group', targetEntity: Student::class)]
    private Collection $students;

    public function __construct() {
        $this->students = new ArrayCollection();
    }

    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function setStudents(Collection $students): Group
    {
        $this->students = $students;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCourse(): int
    {
        return $this->course;
    }

    public function setTitle(?string $newTitle): self
    {
        if (!is_null($newTitle)) {
            $this->title = $newTitle;
        }
        return $this;
    }

    public function setCourse(?string $newCourse): self
    {
        if (!is_null($newCourse)) {
            $this->course = $newCourse;
        }
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'course' => $this->course,
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'course' => $this->course,
        ];
    }
}
