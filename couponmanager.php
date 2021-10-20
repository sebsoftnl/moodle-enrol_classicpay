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

$cid = optional_param('cid', 0, PARAM_INT);
$action = optional_param('action', 'list', PARAM_ALPHAEXT);

// Not for guests.
if (isguestuser()) {
    redirect('/');
}

if ($cid > 0) {
    $PAGE->set_context(context_course::instance($cid));
    $PAGE->set_course($DB->get_record('course', array('id' => $cid)));
} else {
    $PAGE->set_context(context_system::instance());
}

require_capability('enrol/classicpay:config', $PAGE->context);
$pageurl = new moodle_url('/enrol/classicpay/couponmanager.php', array('action' => $action));
$PAGE->set_url($pageurl);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_title(get_string('title:couponmanager', 'enrol_classicpay'));
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add(get_string('pluginname', 'enrol_classicpay'));

// Since out page url is not at all 100% the same at all times we override the active url.
navigation_node::override_active_url(new moodle_url('/enrol/classicpay/couponmanager.php', array('cid' => $cid)));

$renderer = $PAGE->get_renderer('enrol_classicpay');
$renderer->manager_page_coupon_manager();
