<?php
declare(strict_types=1);

namespace Tests\HcsOmot\AFSM\Unit;

use App\HcsOmot\AFSM\AssignedWorkAssignment;
use App\HcsOmot\AFSM\CannotStartWorkOnAssignmentMoreThanOnce;
use App\HcsOmot\AFSM\ExpiredWhileInProgressWorkAssignment;
use App\HcsOmot\AFSM\ExpiredWorkAssignment;
use App\HcsOmot\AFSM\WorkAssignment;
use App\HcsOmot\AFSM\WorkAssignmentInProgress;
use PHPUnit\Framework\TestCase;

class WorkAssignmentReconstitutionTest extends TestCase {
    public function testWorkAssignmentCanBeAssignedToAnEmployee(): void
    {
        $actual = WorkAssignment::assignTo('Rambo', 10);
        $assignmentState = $actual->getState();
        $expected = AssignedWorkAssignment::reconstitute($assignmentState);
        $this->assertEquals($expected, $actual);
    }


}
