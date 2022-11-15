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
 * Contains the class used for the displaying the participants table.
 *
 * @package    core_user
 * @copyright  2017 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace mod_workflow\table;

use core_table\dynamic as dynamic_table;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->dirroot . '/user/lib.php');

/**
 * Class for the displaying the participants table.
 *
 * @package    mod_workflow
 * @copyright  2017 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class requests extends \table_sql implements dynamic_table
{

    public function out($pagesize, $useinitialsbar, $downloadhelpbutton='')
    {
        global $CFG, $OUTPUT, $PAGE;

        $this->set_sql('*', 'mdl_request', '1=1');

        // Define the headers and columns.
        $headers = [];
        $columns = [];

        $mastercheckbox = new \core\output\checkbox_toggleall('request-table', true, [
            'id' => 'select-all-requests',
            'name' => 'select-all-requests',
            'label' => get_string('selectall'),
            'labelclasses' => 'sr-only',
            'classes' => 'm-1',
            'checked' => false,
        ]);
        $headers[] = $OUTPUT->render($mastercheckbox);
        $columns[] = 'select';


        $headers[] = 'Request';
        $columns[] = 'request';


        $headers[] = 'Student ID';
        $columns[] = 'studentid';

        $headers[] = 'Request Type';
        $columns[] = 'type';

        $headers[] = 'Status';
        $columns[] = 'status';

        $headers[] = "Instructor's Comment";
        $columns[] = 'instructorcomment';


        $this->define_columns($columns);
        $this->define_headers($headers);

        // The name column is a header.
        $this->define_header_column('request');

        // Make this table sorted by last name by default.
        $this->sortable(true, 'id');

        $this->set_attribute('id', 'id');

        $this->define_baseurl($CFG->wwwroot . '/mod/workflow/view.php');

        parent::out($pagesize, true);

//        if (has_capability('moodle/course:enrolreview', $this->context)) {
//            $params = [
//                'contextid' => $this->context->id,
//                'uniqueid' => $this->uniqueid,
//            ];
//            $PAGE->requires->js_call_amd('core_user/status_field', 'init', [$params]);
//        }
    }




}