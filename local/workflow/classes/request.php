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

class request
{

    public function changeStatus(string $requestid, string $status)
    {
        global $DB;
        $sql = 'update {local_request} set status = :status where requestid= :requestid';
        $params = [
            'status'=>$status,
            'requestid'=>$requestid,
        ];

        try{
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
        $instructorcomment,
        $lecturercomment
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

        try{
            return $DB->insert_record('local_request',$record,false);
        }catch (dml_exception $e){
            return false;
        }
    }
    public function getAllRequests()
    {
        global $DB;
        try {
            return $DB->get_records('local_request');
        } catch (dml_exception $e) {
            return [];
        }
    }

    public function filterRequests(string $type)
    {
        global $DB;
        return $DB->get_records_select('local_request', 'type = :type', [
            'type' => $type
        ]);
    }

    public function getStatus($requestid)
    {
        global $DB;
        $sql = 'requestid = :requestid;';
        $params = [
            'requestid' => $requestid,
        ];

        return $DB->get_field_select('local_request', 'status', $sql, $params);
    }

    public function getRequest($requestid)
    {
        global $DB;
        return $DB->get_record(
            'local_request',
            [
                'requestid' => $requestid
            ]
        );
    }
}
