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
 * apply for an account
 *
 * File         spapply.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');
require($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->libdir . "/adminlib.php");
admin_externalpage_setup('cpapply');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/enrol/classicpay/spapply.php');
$PAGE->set_title(get_string('page:title:spapply', 'enrol_classicpay'));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('apply:page:heading', 'enrol_classicpay'));
$PAGE->navbar->add(get_string('apply:nav', 'enrol_classicpay'));

$config = get_config('enrol_classicpay');
if (!empty($config->paynlapitoken)) {
    echo $OUTPUT->header();
    echo get_string('apply:alreadyconfigured', 'enrol_classicpay');
    echo $OUTPUT->footer();
    exit;
}

$PAGE->requires->js('/enrol/classicpay/js/openiban.js');
$form = new enrol_classicpay\classicpay\forms\cpapply($PAGE->url);
$form->process_form(new moodle_url('/'));
