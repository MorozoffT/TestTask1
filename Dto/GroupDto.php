<?php

namespace app\Dto;

class GroupDto
{
    public int $id;

    public string $title;

    public int $courseNumber;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): GroupDto
    {
        $this->title = $title;
        return $this;
    }

    public function getCourseNumber(): int
    {
        return $this->courseNumber;
    }

    public function setCourseNumber(int $courseNumber): GroupDto
    {
        $this->courseNumber = $courseNumber;
        return $this;
    }
}