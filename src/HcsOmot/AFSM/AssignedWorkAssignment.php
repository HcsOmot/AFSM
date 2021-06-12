<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

class AssignedWorkAssignment {
//    private string $employee;
//    private int $expiresAfter;
//    private int $daysPassedSinceAssigned;
//    private bool $isUsable;
    private WorkAssignmentState $state;

    public static function reconstitute(WorkAssignmentState $assignmentState): self
    {
        return new self($assignmentState);
    }

    public function getState(): WorkAssignmentState
    {
        return $this->state;
    }

    private function __construct(WorkAssignmentState $assignmentState)
    {
//        $this->employee = $employee;
//        $this->expiresAfter = $expiresAfter;
//        $this->daysPassedSinceAssigned = 0;
//        $this->isUsable = true;
        $this->state = $assignmentState;
    }

    public static function assignToEmployee(string $employee, int $expiresAfter): self
    {
//         NOTE: It probably should not be possible to assign the same task more than once to the same employee
//          The act of re-assignment is probably a transfer of a WorkAssignment to another employee
        $state = new WorkAssignmentState($employee, $expiresAfter, false);
        return new self($state);
    }

    public function startWork(): WorkAssignmentInProgress
    {
        $this->preventRestartingWork();
        $workAssignmentInProgress = WorkAssignmentInProgress::fromAssignedWorkAssignment($this);
        $this->preventFurtherUse();
        return $workAssignmentInProgress;
    }

    public function recordDayHasPassed(): ExpiredWorkAssignment|self
    {
        $this->daysPassedSinceAssigned++;
        if ($this->daysPassedSinceAssigned > $this->expiresAfter) {
            return ExpiredWorkAssignment::fromAssignedAssignment($this);
        }
        return $this;
    }

    private function preventFurtherUse(): void
    {
        $this->state->setUnusable();
    }

    private function preventRestartingWork(): void
    {
        if (false === $this->state->isUsable()) {
//            TODO: Refactor the exception?
//            PROBLEM: If we want to signal the info / id of assignment already in progress, originating from this
//              specific assigned work assignment, the transition Assigned -> InProgress would need to hold a forward reference
            throw new CannotStartWorkOnAssignmentMoreThanOnce();
        }
    }

    public function daysPassedSinceAssignment(): int
    {
        return $this->daysPassedSinceAssigned;
    }

    public function expiresAfter(): int
    {
        return $this->expiresAfter;
    }
}