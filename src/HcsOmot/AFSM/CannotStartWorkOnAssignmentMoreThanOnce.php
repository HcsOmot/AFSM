<?php
declare(strict_types=1);

namespace App\HcsOmot\AFSM;


class CannotStartWorkOnAssignmentMoreThanOnce extends \DomainException {
    public function __construct()
    {
        parent::__construct();
    }
}