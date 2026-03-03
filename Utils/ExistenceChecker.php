<?php

use app\Entities\Group;
use app\Entities\Student;
use app\Utils\MyException;
use Doctrine\ORM\EntityManager;

function existenceGroupChecker(EntityManager $entityManager, int $groupId): Group
{
    $group = $entityManager->find(Group::class, $groupId);
    if (is_null($group)) {
        throw new MyException('Группа не существует.');
    }

    return $group;
}

function existenceStudentChecker(EntityManager $entityManager, int $studentId): Student
{
    $student = $entityManager->find(Student::class, $studentId);
    if (is_null($student)) {
        throw new MyException('Студент не существует.');
    }

    return $student;
}
