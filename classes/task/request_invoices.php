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
 * this file contains the task to cleanup historic logs.
 *
 * File         request_invoices.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Description of request_invoices
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class request_invoices extends \core\task\scheduled_task {

    /**
     * Return the localised name for this task
     *
     * @return string task name
     */
    public function get_name() {
        return get_string('task:request_invoices', 'enrol_classicpay');
    }

    /**
     * Executes the task
     *
     * @return void
     */
    public function execute() {
        // We will batch queue items per 10. The remote process itself is intensive enough.
        global $DB;
        $sql = "SELECT p.*, q.id as queueid FROM {enrol_classicpay_ivq} q
                JOIN {enrol_classicpay} p ON q.classicpayid=p.id
                ORDER BY q.id ASC";
        $api = new \enrol_classicpay\classicpay\api();
        $queueitems = $DB->get_records_sql($sql, null, 0, 10);
        foreach ($queueitems as $item) {
            // Request invoice (deletes queue item too).
            $api->request_invoice($item, null, null, $item->queueid);
            // Do a "fitty mu" powernap.
            usleep(50000);
        }
    }

}
