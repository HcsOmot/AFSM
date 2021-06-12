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

class WorkAssignmentBehaviourTest extends TestCase {
    public function testWorkAssignmentCanBeAssignedToAnEmployee(): void
    {
        $actual = WorkAssignment::assignTo('Rambo', 10);
        $this->assertInstanceOf(AssignedWorkAssignment::class, $actual);
    }

    public function testAssignedWorkAssignmentCanBeStarted(): void
    {
        $assignedAssignment = WorkAssignment::assignTo('Dumbo', 10);
        $inProgressAssignment = $assignedAssignment->startWork();
        $this->assertInstanceOf(WorkAssignmentInProgress::class, $inProgressAssignment);
    }

    public function testAssignedWorkAssignmentCanExpire(): void
    {
        $assignedAssignment = WorkAssignment::assignTo('Flumbo', 1);
        $assignedAssignment->recordDayHasPassed();
        $actual = $assignedAssignment->recordDayHasPassed();
        $this->assertInstanceOf(ExpiredWorkAssignment::class, $actual);
    }

    public function testAssignedWorkAssignmentWillNotExpireBeforeTime(): void
    {
        $assignedAssignment = WorkAssignment::assignTo('Gumbo', 10);
        $actual = $assignedAssignment->recordDayHasPassed();
        $this->assertInstanceOf(AssignedWorkAssignment::class, $actual);
    }

    public function testPassageOfTimeHasNoEffectOnExpiredAssignment(): void
    {
//        NOTE: this is true of all expired types:
//                  - Expired (expired after being assigned)
//                  - ExpiredWhileInProgress (expired after work was started)
//              As well as for a finished assignment (Done)
        $assignedAssignment = WorkAssignment::assignTo('Jimbo', 1);
        $expiredAssignment = $assignedAssignment->recordDayHasPassed()->recordDayHasPassed();
        $this->assertInstanceOf(ExpiredWorkAssignment::class, $expiredAssignment);
//        QUESTION: How do we test this? There's no additional behaviour or state on an ExpiredWorkAssignment
        /** @var ExpiredWorkAssignment $expiredAssignment */
        $expiredAssignment->recordDayHasPassed();
    }

    public function testAssignedWorkAssignmentCannotBeStartedMoreThanOnce(): void
    {
        $assignedAssignment = WorkAssignment::assignTo('Jumbo', 10);
        $assignedAssignment->startWork();
        $this->expectException(CannotStartWorkOnAssignmentMoreThanOnce::class);
        $assignedAssignment->startWork();
    }

    public function testInProgressWorkAssignmentWillExpireIfNotFinishedOnTime(): void
    {
        $assignedAssignment = WorkAssignment::assignTo("Jimbo's brother Fred", 2);
        $assignedAssignment->recordDayHasPassed();
        $inProgressAssignment = $assignedAssignment->startWork();
        $inProgressAssignment->recordDayHasPassed();
        $expiredWhileInProgressAssignment = $inProgressAssignment->recordDayHasPassed();
        $this->assertInstanceOf(
            ExpiredWhileInProgressWorkAssignment::class,
            $expiredWhileInProgressAssignment
        );
    }
}
