<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

class ExpiredWhileInProgressWorkAssignment {
    private WorkAssignmentInProgress $assignment;

    public function __construct(WorkAssignmentInProgress $assignmentInProgress)
    {
        $this->assignment = $assignmentInProgress;
    }

    public static function assignment(WorkAssignmentInProgress $workAssignmentInProgress): self
    {
        return new self($workAssignmentInProgress);
    }
}