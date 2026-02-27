<?php

namespace app\Services;

use app\Entities\Student;
use app\Entities\Group;
use Doctrine\ORM\EntityManager;

class StudentService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $fullName, $groupId): void
    {
        $group = $this->entityManager->getRepository(Group::class)->find($groupId);

        if (!is_null($group)) {
            $newStudent = new Student();
            $newStudent->setFullName($fullName);
            $newStudent->setGroup($group);

            $this->entityManager->persist($newStudent);
            $this->entityManager->flush();
        } else {
            echo json_encode([
                'success' => false,
                'rows' => "group $groupId not exist"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function delete(string $studentId): void
    {
        $student = $this->entityManager->find(Student::class, $studentId);
        if (is_null($student)) {
            echo json_encode([
                'success' => false,
                'rows' => "Student $studentId not found"
            ]);
            exit;
        }

        $this->entityManager->remove($student);
        $this->entityManager->flush();
    }

    public function get(string $studentId = null): void
    {
        $repository = $this->entityManager->getRepository(Student::class);

        if (is_null($studentId)) {
            $rows = $repository->findAll();
        } else {
            $rows = $repository->findBy(['id' => $studentId]);
        }

        echo json_encode([
            'success' => true,
            'rows' => $rows
        ], JSON_UNESCAPED_UNICODE);
    }

    public function update(string $studentId, string $groupId, string $fullName = null): void
    {
        $student = $this->entityManager->find(Student::class, $studentId);
        if (is_null($student)) {
            echo json_encode([
                'success' => false,
                'rows' => "Student $studentId not found"
            ]);
            exit;
        }
        $group = $this->entityManager->find(Group::class, $groupId);
        if (is_null($group)) {
            echo json_encode([
                'success' => false,
                'rows' => "Group $groupId not found"
            ]);
            exit;
        }

        $student->setGroup($group);
        if (!is_null($fullName)) {
            $student->setFullName($fullName);
        }

        $this->entityManager->flush();
    }
}
