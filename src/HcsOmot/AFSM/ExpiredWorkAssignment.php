<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

class ExpiredWorkAssignment {
    private AssignedWorkAssignment $assignment;

    private function __construct(AssignedWorkAssignment $assignment)
    {
        $this->assignment = $assignment;
    }
    public static function fromAssignedAssignment(AssignedWorkAssignment $assignment): self
    {
        return new self($assignment);
    }

    public function recordDayHasPassed(): void
    {
//        NOTE: The Passage of Time has no effect on the dead
    }
}