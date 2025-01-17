<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for local_worklow
 *
 * @package    local_worklow
 * @category   phpunit
 */

defined('MOODLE_INTERNAL') || die();

use local_workflow\workflow;

global $CFG;
require_once($CFG->dirroot . '/local/workflow/lib.php');

class local_workflow_workflow_test extends advanced_testcase
{

    public function test_create()
    {
        $this->resetAfterTest();
        $this->setUser(2);
        $workflow = new workflow();
        $workflows = $workflow->getAllWorkflows();
        $this->assertEmpty($workflows);

        $name = "Test workflow";
        $courseid = '100';
        $activityid = "100";
        $instructorid = "200";
        $startdate = "20220919";
        $enddate = "20230203";
        $commentsallowed = 1;
        $filesallowed = 0;

        $result = $workflow->create(
            $name,
            $courseid,
            $activityid,
            $instructorid,
            $startdate,
            $enddate,
            $commentsallowed,
            $filesallowed
        );

        $this->assertTrue($result);
        $workflows = $workflow->getAllWorkflows();
        $this->assertNotEmpty($workflows);

        $record = array_pop($workflows);

        $this->assertEquals("Test workflow", $record->name);
        $test_workflow_name = $workflow->getName($record->workflowid);
        $this->assertEquals("Test workflow",$test_workflow_name);
    }

    public function test_remove(){
        $this->resetAfterTest();
        $this->setUser(2);
        $workflow = new workflow();
        $workflows = $workflow->getAllWorkflows();
        $this->assertEmpty($workflows);

        $name = "Test workflow";
        $courseid = '100';
        $activityid = "100";
        $instructorid = "200";
        $startdate = "20220919";
        $enddate = "20230203";
        $commentsallowed = 1;
        $filesallowed = 0;

        $result = $workflow->create(
            $name,
            $courseid,
            $activityid,
            $instructorid,
            $startdate,
            $enddate,
            $commentsallowed,
            $filesallowed
        );

        $this->assertTrue($result);
        $workflows = $workflow->getAllWorkflows();
        $this->assertNotEmpty($workflows);

        $record = array_pop($workflows);

        $remove_id = $workflow->getWorkflow($record->workflowid)->workflowid;
        $workflow->remove($remove_id);
        $workflows = $workflow->getAllWorkflows();
        $this->assertEmpty($workflows);
    }
}