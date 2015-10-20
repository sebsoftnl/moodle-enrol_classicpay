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
 * Adds new instance of enrol_classicpay to specified course
 *
 * File         edit.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');
require_once('edit_form.php');

$courseid = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT); // Instanceid.

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/classicpay:config', $context);

$PAGE->set_url('/enrol/classicpay/edit.php', array('courseid' => $course->id, 'id' => $instanceid));
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', array('id' => $course->id));
if (!enrol_is_enabled('classicpay')) {
    redirect($return);
}

$plugin = enrol_get_plugin('classicpay');

if ($instanceid) {
    $instance = $DB->get_record('enrol', array(
        'courseid' => $course->id,
        'enrol' => 'classicpay',
        'id' => $instanceid
    ), '*', MUST_EXIST);
    $instance->cost = format_float($instance->cost, 2, true);
} else {
    require_capability('moodle/course:enrolconfig', $context);
    // No instance yet, we have to add new instance.
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id' => $course->id)));
    $instance = new stdClass();
    $instance->id = null;
    $instance->courseid = $course->id;
    $instance->expirynotify    = $plugin->get_config('expirynotify');
    $instance->expirythreshold = $plugin->get_config('expirythreshold');
}

$mform = new enrol_classicpay_edit_form(null, array($instance, $plugin, $context));

if ($mform->is_cancelled()) {
    redirect($return);
} else {
    if ($data = $mform->get_data()) {

        if ($data->expirynotify == 2) {
            $data->expirynotify = 1;
            $data->notifyall = 1;
        } else {
            $data->notifyall = 0;
        }
        if (!$data->expirynotify) {
            // Keep previous/default value of disabled expirythreshold option.
            $data->expirythreshold = $instance->expirythreshold;
        }

        if ($instance->id) {
            $reset = ($instance->status != $data->status);

            $instance->status = $data->status;
            $instance->name = $data->name;
            $instance->cost = unformat_float($data->cost);
            $instance->customint1 = intval($data->customint1);
            $instance->customint2 = intval($data->customint2);
            $instance->currency = $data->currency;
            $instance->roleid = $data->roleid;
            $instance->enrolperiod = $data->enrolperiod;
            $instance->enrolstartdate = $data->enrolstartdate;
            $instance->enrolenddate = $data->enrolenddate;
            $instance->expirynotify    = $data->expirynotify;
            $instance->notifyall       = $data->notifyall;
            $instance->expirythreshold = $data->expirythreshold;
            $instance->timemodified = time();
            $DB->update_record('enrol', $instance);

            if ($reset) {
                $context->mark_dirty();
            }

        } else {
            $fields = array(
                'status' => $data->status,
                'name' => $data->name,
                'cost' => unformat_float($data->cost),
                'customint1' => intval($data->customint1),
                'customint2' => intval($data->customint2),
                'currency' => $data->currency,
                'roleid' => $data->roleid,
                'enrolperiod' => $data->enrolperiod,
                'enrolstartdate' => $data->enrolstartdate,
                'enrolenddate' => $data->enrolenddate,
                'expirynotify'    => $data->expirynotify,
                'notifyall'       => $data->notifyall,
                'expirythreshold' => $data->expirythreshold
            );
            $plugin->add_instance($course, $fields);
        }

        redirect($return);
    }
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_classicpay'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_classicpay'));
$mform->display();
echo $OUTPUT->footer();
