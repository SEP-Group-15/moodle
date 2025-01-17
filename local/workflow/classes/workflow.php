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
 * Version details
 *
 * @package    local_workflow
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_workflow;
use stdClass;
use dml_exception;

class workflow{
    public function create($name, $courseid, $activityid, $instructorid, $startdate, $enddate, $commentsallowed, $filesallowed){
        global $DB;
        $record = new stdClass();
        $record->name = $name;
        $record->courseid = $courseid;
        $record->activityid = $activityid;
        $record->instructorid = $instructorid;
        $record->startdate = $startdate;
        $record->enddate = $enddate;
        $record->commentsallowed = $commentsallowed;
        $record->filesallowed = $filesallowed;

        try{
            return $DB->insert_record('local_workflow',$record,false);
        }catch (dml_exception $e){
            return false;
        }
    }

    public function remove(string $workflowid){
        global $DB;
        $DB->delete_records_select('local_workflow','workflowid = ?',[$workflowid]);
    }

    public function getName(string $workflowid){
        global $DB;
        $sql = 'workflowid = :workflowid;';
        $params=[
            'workflowid'=>$workflowid,
        ];

        return $DB->get_field_select('local_workflow','name',$sql,$params);
    }

    public function getWorkflow(string $workflowid){
        global $DB;
        return $DB->get_record('local_workflow',
        [
            'workflowid'=>$workflowid
        ]);
    }

    public function getAllWorkflows(){
        global $DB;
        try {
            return $DB->get_records('local_workflow');
        } catch (dml_exception $e) {
            return [];
        }
    }
}