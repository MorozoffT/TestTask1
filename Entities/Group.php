<?php

namespace app\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity]
#[ORM\Table(name: 'student_groups')]
class Group implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'title', type: 'string')]
    private ?string $title;

    #[ORM\Column(name: 'course', type: 'integer')]
    private ?string $course;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getCourse(): ?string
    {
        return $this->course;
    }

    public function setTitle(string $newTitle): self
    {
        $this->title = $newTitle;
        return $this;
    }

    public function setCourse(string $newCourse): self
    {
        $this->course = $newCourse;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'course' => $this->course,
        ];
    }
}
