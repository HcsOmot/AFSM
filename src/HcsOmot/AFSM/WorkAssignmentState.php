<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;

/** @psalm-immutable */
class WorkAssignmentState {
    public function setUnusable()
    {
        $this->isUsable = false;
    }

    public function __construct(
        private $assignedToEmployee,
        private int $expiresAfter,
        private bool $isUsable
    )
    {}

    public function isUsable()
    {

    }
}