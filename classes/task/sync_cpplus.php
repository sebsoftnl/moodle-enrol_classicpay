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
 * File         sync_cpplus.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Description of sync_cpplus
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sync_cpplus extends \core\task\scheduled_task {

    /**
     * Return the localised name for this task
     *
     * @return string task name
     */
    public function get_name() {
        return get_string('task:sync_cpplus', 'enrol_classicpay');
    }

    /**
     * Executes the task
     *
     * @return void
     */
    public function execute() {
        // Check if we're ClassicPay plus, and set variable.
        $api = new \enrol_classicpay\classicpay\api();
        $result = $api->check_classicpayplus();
        $bit = (($result->result === true) ? 1 : 0);
        // Set config and we're done.
        set_config('isclassicpayplus', $bit, 'enrol_classicpay');
    }

}
