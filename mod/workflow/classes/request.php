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
 * @package    mod_workflow
 * @copyright  2022 SEP15
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_workflow;

use stdClass;
use dml_exception;

class request
{
    public function changeStatus(string $id, string $status)
    {
        global $DB;
        $sql = 'update {request} set status = :status where id= :id';
        $params = [
            'status' => $status,
            'id' => $id,
        ];

        try {
            return $DB->execute($sql, $params);
        } catch (dml_exception $e) {
            return false;
        }
    }

    public function validate(string $id, string $status, string $ins_comment = "")
    {
        global $DB;
        $this->changeStatus($id, $status);
        $sql = 'update {request} set instructorcomment = :ins_comment where id= :id';
        $params = [
            'ins_comment' => $ins_comment,
            'id' => $id,
        ];
        try {
            return $DB->execute($sql, $params);
        } catch (dml_exception $e) {
            return false;
        }
    }

    public function approve(string $id, string $status, string $lec_comment = "")
    {
        global $DB;
        $this->changeStatus($id, $status);
        $sql = 'update {request} set lecturercomment = :lec_comment where id= :id';
        $params = [
            'lec_comment' => $lec_comment,
            'id' => $id,
        ];
        try {
            return $DB->execute($sql, $params);
        } catch (dml_exception $e) {
            return false;
        }
    }

    public function createRequest(
        $request,
        $workflowid,
        $studentid,
        $type,
        $isbatchrequest,
        $timecreated,
        $files,
        $instructorcomment = "",
        $lecturercomment = ""
    ) {

        global $DB;
        $record = new stdClass();
        $record->request = $request;
        $record->workflowid = $workflowid;
        $record->studentid = $studentid;
        $record->type = $type;
        $record->status = 'pending';
        $record->isbatchrequest = $isbatchrequest;
        $record->timecreated = $timecreated;
        $record->files = $files;
        $record->instructorcomment = $instructorcomment;
        $record->lecturercomment = $lecturercomment;

        try {
            return $DB->insert_record('request', $record, false);
        } catch (dml_exception $e) {
            return false;
        }
    }
    public function getAllRequests()
    {
        global $DB;
        try {
            return $DB->get_records('request');
        } catch (dml_exception $e) {
            return [];
        }
    }

    public function filterRequests(string $type)
    {
        global $DB;
        return $DB->get_records_select('request', 'type = :type', [
            'type' => $type
        ]);
    }

    public function getStatus($requestid)
    {
        global $DB;
        $sql = 'id = :id;';
        $params = [
            'id' => $requestid,
        ];

        return $DB->get_field_select('request', 'status', $sql, $params);
    }

    public function getRequest($requestid)
    {
        global $DB;
        return $DB->get_record(
            'request',
            [
                'id' => $requestid
            ]
        );
    }

    public function getRequestsByWorkflow($cmid)
    {
        global $DB;
        return $DB->get_records_select('request', 'workflowid = :workflowid', [
            'workflowid' => $cmid
        ]);
    }

    public function getValidRequestsByWorkflow($workflowid){
        global $DB;
        return $DB->get_records_select('request', 'workflowid = :workflowid and status=:status', [
            'workflowid' => $workflowid,
            'status' => 'valid',
        ]);
    }

    public function getRequestsByWorkflow_Student($userid, $cmid)
    {
        global $DB;
        return $DB->get_records_select('request', 'workflowid = :workflowid and studentid=:userid', [
            'workflowid' => $cmid,
            'userid' => $userid
        ]);
    }
}
