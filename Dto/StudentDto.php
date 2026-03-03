<?php

namespace app\Dto;

class StudentDto
{
    public int $id;
    public string $fullName;
    public int $groupId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): StudentDto
    {
        $this->id = $id;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): StudentDto
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): StudentDto
    {
        $this->groupId = $groupId;
        return $this;
    }


}