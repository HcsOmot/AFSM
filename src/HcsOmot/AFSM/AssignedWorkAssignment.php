<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

class AssignedWorkAssignment {
    private string $employee;
    private int $expiresAfter;
    private int $daysPassedSinceAssigned;
    private bool $isUsable;

    private function __construct(string $employee, int $expiresAfter)
    {
        $this->employee = $employee;
        $this->expiresAfter = $expiresAfter;
        $this->daysPassedSinceAssigned = 0;
        $this->isUsable = true;
    }

    public static function assignToEmployee(string $employee, int $expiresAfter): self
    {
        return new self($employee, $expiresAfter);
    }

    public function startWork(): WorkAssignmentInProgress
    {
        $this->verifyUsage();
        $workAssignmentInProgress = WorkAssignmentInProgress::fromAssignedWorkAssignment($this);
        $this->markUnusable();
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

    private function markUnusable(): void
    {
        $this->isUsable = false;
    }

//        TODO: Poor method name, we can do better. What's going on here? What's the point of this guard clause?
    private function verifyUsage(): void
    {
        if (false === $this->isUsable) {
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