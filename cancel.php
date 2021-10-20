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
 * Return page after payment
 *
 * File         lib.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require("../../config.php");
require_once("lib.php");

require_login();

$instanceid = required_param('id', PARAM_ALPHANUMEXT);
$eid = required_param('eid', PARAM_INT);

if (!$plugininstance = $DB->get_record("enrol", array("id" => $instanceid, "status" => 0))) {
    redirect('/');
}

$course = $DB->get_record('course', array('id' => $plugininstance->courseid), '*', MUST_EXIST);
$context = context_course::instance($plugininstance->courseid);
$transactionrecord = $DB->get_record('enrol_classicpay', array('id' => $eid), '*', MUST_EXIST);

// Not for guests.
if (isguestuser()) {
    redirect('/');
}

$PAGE->set_course($course);
$PAGE->set_url('/enrol/classicpay/cancel.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_title(get_string('title:cancelpage', 'enrol_classicpay'));

$renderer = $PAGE->get_renderer('enrol_classicpay');
echo $renderer->payment_page_cancel();
