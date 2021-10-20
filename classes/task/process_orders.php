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
 * File         process_orders.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Description of process_orders
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_orders extends \core\task\scheduled_task {

    /**
     * Return the localised name for this task
     *
     * @return string task name
     */
    public function get_name() {
        return get_string('task:process_pending_orders', 'enrol_classicpay');
    }

    /**
     * Executes the task
     *
     * Process all pending orders.
     * Used by our plugin task in case we missed return / exchange callbacks from PAYNL.
     *
     * @return void
     */
    public function execute() {
        global $DB;
        mtrace('Processing pending orders for PAYNL in case we missed exchange requests.');

        // We process these 10 at a time.
        $select = 'status NOT IN (?,?, ?, ?)';
        $params = array(-60 /*CANCEL-FAILURE*/, -80 /*CANCEL-EXPIRE*/, -90 /*CANCEL*/, 100 /*PAID*/);
        $results = $DB->get_records_select('enrol_classicpay', $select, $params, 'timecreated DESC', '*', 0, 10);
        foreach ($results as $transactionrecord) {
            try {
                $paynltransaction = new \enrol_classicpay\transaction($transactionrecord);
                if (!$paynltransaction->is_paid()) {
                    $paynltransaction->synchronize();
                    // Do a "fitty mu" powernap.
                    usleep(50000);
                }
            } catch (\Exception $e) {
                // Don't do a damn thing.
                mtrace("Error during execution: " . $e->getMessage());
            }
        }
    }

}
