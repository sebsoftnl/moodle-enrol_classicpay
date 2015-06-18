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
require_once("../../config.php");
require_once("lib.php");
require_once($CFG->libdir . "/adminlib.php");

$page = required_param('page', PARAM_ALPHA);
admin_externalpage_setup($page);
require_capability('enrol/classicpay:config', context_system::instance());

$pageurl = new moodle_url('/enrol/classicpay/admin.php', array('page' => $page));
$PAGE->set_url($pageurl);
$PAGE->set_heading($SITE->fullname);

$renderer = $PAGE->get_renderer('enrol_classicpay');
switch($page) {
    case 'cpcoupons':
        echo $renderer->admin_page_coupon_manager();
        break;
    case 'cpsubscriptions':
        echo $renderer->admin_page_subscription_manager();
        break;
    case 'cpservice':
        echo $renderer->admin_page_service_manager();
        break;
    case 'cptransactions':
        echo $renderer->admin_page_transactions();
        break;
    case 'cplegal':
        echo $renderer->admin_page_legal();
        break;
    default:
        break;
}