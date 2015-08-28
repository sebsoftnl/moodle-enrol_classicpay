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
 * Coupon editing
 *
 * File         couponcheck.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define('AJAX_SCRIPT', true);
require("../../config.php");
require_once("lib.php");

require_login();

$courseid = required_param('courseid', PARAM_INT);
$code = required_param('code', PARAM_TEXT);
$instanceid = required_param('instanceid', PARAM_INT);

// Not for guests.
if (isguestuser()) {
    throw new moodle_exception('no guest access');
}

$coupon = $DB->get_record('enrol_classicpay_coupon', array('code' => $code));
if (!$coupon) {
    throw new moodle_exception('coupon:invalid', 'enrol_classicpay');
} else {
    if ($coupon->courseid > 0 && ((int) $coupon->courseid !== $courseid)) {
        throw new moodle_exception('coupon:invalid', 'enrol_classicpay');
    } else if ($coupon->validfrom > time()) {
        throw new moodle_exception('coupon:invalid', 'enrol_classicpay');
    } else if ($coupon->validto < time()) {
        throw new moodle_exception('coupon:expired', 'enrol_classicpay');
    } else if ($coupon->maxusage > 0 && $coupon->numused >= $coupon->maxusage) {
        throw new moodle_exception('coupon:invalid', 'enrol_classicpay');
    }
}

// Validate enrol record.
$enrol = $DB->get_record('enrol', array('id' => $instanceid));
if (!$enrol) {
    throw new moodle_exception('enrol:invalid', 'enrol_classicpay');
}

// Generate result.
$rs = array();
if ($coupon->type === 'percentage') {
    $percentage = $coupon->value;
    $discount = intval($coupon->value * $enrol->cost) / 100;
} else {
    $discount = floatval($coupon->value);
    $percentage = intval(100 * ($coupon->value / $enrol->cost));
}
$rs['currency'] = $enrol->currency;
$rs['cost'] = format_float($enrol->cost);
$rs['percentage'] = format_float($percentage, 2, true) . '%';
$rs['discount'] = format_float($discount, 2, true);
$rs['newprice'] = format_float($enrol->cost - $discount, 2, true);
$rs = (object) $rs;
$rs->html = get_string('coupon:newprice', 'enrol_classicpay', $rs);

echo json_encode($rs);
