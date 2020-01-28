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
 * Helper functions for tests
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL' || die());

class helper_functions {
    /**
     * Simulate a free enrol, through ClassicPay. Currently the method that does the enrolment and adds the necessary data
     * to the enrol_classicpay table is too interwoven with the PAY API. Since we're testing for contexts, and not PAY's API
     * we manually create and insert a record here.
     *
     * @param $userid
     * @param $courseid
     * @param $coursecontext
     * @return stdClass
     */
    public static function insert_classicpay_record($userid, $courseid, $coursecontext) {

        $record = new stdClass();
        $record->userid = $userid;
        $record->courseid = $courseid;
        $record->instanceid = $coursecontext->instanceid;
        $record->orderid = uniqid(time());
        $record->status = '100';
        $record->statusname = 'PAID';
        $record->gateway_transaction_id = uniqid();
        $record->gateway = 'Moodle';
        $record->rawcost = 0;
        $record->cost = 0;
        $record->percentage = '100';
        $record->discount = 5;
        $record->hasinvoice = 0;
        $record->timecreated = time();
        $record->timemodified = time();

        return $record;
    }
}