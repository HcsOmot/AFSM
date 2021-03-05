<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

class WorkAssignmentInProgress {
    private AssignedWorkAssignment $assignment;
    private int $daysPassedSinceWorkStarted;

    private function __construct(AssignedWorkAssignment $assignment)
    {
        $this->assignment = $assignment;
        $this->daysPassedSinceWorkStarted = 0;
    }

    public static function fromAssignedWorkAssignment(AssignedWorkAssignment $assignment): self
    {
        return new self($assignment);
    }

    public function recordDayHasPassed(): ExpiredWhileInProgressWorkAssignment|self
    {
        $this->daysPassedSinceWorkStarted++;
        if ($this->assignment->daysPassedSinceAssignment() + $this->daysPassedSinceWorkStarted > $this->assignment->expiresAfter()) {
            return ExpiredWhileInProgressWorkAssignment::assignment($this);
        }

        return $this;
    }
}