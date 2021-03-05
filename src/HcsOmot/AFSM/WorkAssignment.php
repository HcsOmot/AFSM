<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

class WorkAssignment {
    public static function assignTo(string $employee, int $expiresAfter): AssignedWorkAssignment
    {
        return AssignedWorkAssignment::assignToEmployee($employee, $expiresAfter);
    }
}