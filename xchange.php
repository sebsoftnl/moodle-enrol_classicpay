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
// No login check is expected since this is a signup script.
// @codingStandardsIgnoreLine
require("../../config.php");
require_once("lib.php");
require_once($CFG->libdir . '/enrollib.php');
require_once($CFG->libdir . '/filelib.php');

$orderid = required_param('orderid', PARAM_ALPHANUMEXT);
$gateway = required_param('gateway', PARAM_ALPHANUMEXT);
$eid = required_param('eid', PARAM_INT);
$instanceid = required_param('instanceid', PARAM_INT); // If no instanceid is given.

if (!$plugininstance = $DB->get_record("enrol", array("id" => $instanceid, "status" => 0))) {
    echo 'false|result=NO (ACTIVE) PLUGIN INSTANCE';
    exit;
}

// Get renderer.
$transactionrecord = $DB->get_record('enrol_classicpay', array('id' => $eid));
$paynltransaction = new \enrol_classicpay\transaction($transactionrecord);
// If paid already, don't do anything but display/return a message.
if ($paynltransaction->is_paid()) {
    echo 'true|status=OK';
}
// If we get here, synchronize transaction.
try {
    $enrolled = $paynltransaction->synchronize();
    echo 'true|status=enrolment=' . ($enrolled ? 'true' : 'false');
} catch (Exception $e) {
    echo 'false|' . $e->getMessage();
}
