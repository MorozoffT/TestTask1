<?php

namespace app\Services;

use app\Dto\StudentDto;
use app\Entities\Student;
use app\Entities\Group;
use app\Utils\MyException;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../Utils/ExistenceChecker.php';

class StudentService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $fullName, int $groupId): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $group = existenceGroupChecker($this->entityManager, $groupId);

            $newStudent = (new Student())
                ->setFullName($fullName)
                ->setGroup($group);

            $this->entityManager->persist($newStudent);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function delete(int $studentId): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $student = existenceStudentChecker($this->entityManager, $studentId);

            $this->entityManager->remove($student);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function get(int $studentId = null): array
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $repository = $this->entityManager->getRepository(Student::class);

            if (is_null($studentId)) {
                $rows = $repository->findAll();
            } else {
                $rows = existenceStudentChecker($this->entityManager, $studentId)->toArray();
            }

            return $rows;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function update(StudentDto $studentDto): void
    {
        $conn = $this->entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $student = existenceStudentChecker($this->entityManager, $studentDto->getId());
            $group = existenceGroupChecker($this->entityManager, $studentDto->getGroupId());

            $student->setGroup($studentDto->getGroupId());
            $student->setFullName($studentDto->getFullName());

            $this->entityManager->flush();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}
