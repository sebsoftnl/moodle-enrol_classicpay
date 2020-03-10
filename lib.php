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
 * Course Payment enrolment
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

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_classicpay_plugin
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_classicpay_plugin extends enrol_plugin {

    /**
     * Returns optional enrolment information icons.
     *
     * This is used in course list for quick overview of enrolment options.
     *
     * We are not using single instance parameter because sometimes
     * we might want to prevent icon repetition when multiple instances
     * of one type exist. One instance may also produce several icons.
     *
     * @param array $instances all enrol instances of this type in one course
     *
     * @return array of pix_icon
     */
    public function get_info_icons(array $instances) {
        return array(new pix_icon('icon', get_string('pluginname', 'enrol_classicpay'), 'enrol_classicpay'));
    }

    /**
     * users with role assign cap may tweak the roles later
     *
     * @return false means anybody may tweak roles, it does not use itemid and component when assigning roles
     */
    public function roles_protected() {
        return false;
    }

    /**
     * Does this plugin allow manual changes in user_enrolments table?
     *
     * All plugins allowing this must implement 'enrol/xxx:manage' capability
     *
     * @param stdClass $instance course enrol instance
     *
     * @return true means it is possible to change enrol period and status in user_enrolments table
     */
    public function allow_unenrol(stdClass $instance) {
        return true;
    }

    /**
     * Does this plugin allow manual changes in user_enrolments table?
     *
     * All plugins allowing this must implement 'enrol/xxx:manage' capability
     *
     * @param stdClass $instance course enrol instance
     *
     * @return true means it is possible to change enrol period and status in user_enrolments table
     */
    public function allow_manage(stdClass $instance) {
        return true;
    }

    /**
     * Is enrolment possible/enabled?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function show_enrolme_link(stdClass $instance) {
        return ($instance->status == ENROL_INSTANCE_ENABLED);
    }

    /**
     * Sets up navigation entries.
     *
     * @param navigation_node $instancesnode navigation node
     * @param stdClass $instance enrol record instance
     *
     * @throws coding_exception
     * @return void
     */
    public function add_course_navigation($instancesnode, stdClass $instance) {
        if ($instance->enrol !== 'classicpay') {
            throw new coding_exception('Invalid enrol instance type!');
        }
        $context = context_course::instance($instance->courseid);
        if (has_capability('enrol/classicpay:config', $context)) {
            $managelink = new moodle_url('/enrol/classicpay/edit.php', array(
                'courseid' => $instance->courseid,
                'id' => $instance->id
            ));
            $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);

            // If we allow coupons for this instance, we'll add a link to direct configuration.
            if ((bool)$instance->customint2) {
                // Now manipulate upwards, bail as quickly as possible if not appropriate.
                $navigation = $instancesnode;
                while ($navigation->parent !== null) {
                    $navigation = $navigation->parent;
                }
                if (!$courseadminnode = $navigation->get("courseadmin")) {
                    return;
                }
                // Locate or add our own node if appropriate.
                if (!$caclassicpaynode = $courseadminnode->get("caclassicpay")) {
                    $nodeproperties = array(
                        'text'          => get_string('pluginname', 'enrol_classicpay'),
                        'shorttext'     => get_string('pluginname', 'enrol_classicpay'),
                        'type'          => navigation_node::TYPE_CONTAINER,
                        'key'           => 'caclassicpay'
                    );
                    $caclassicpaynode = new navigation_node($nodeproperties);
                    $courseadminnode->add_node($caclassicpaynode, 'users');
                }
                // Add coupon manager node.
                $caclassicpaynode->add(get_string('cp:coupons', 'enrol_classicpay'),
                    new moodle_url('/enrol/classicpay/couponmanager.php', array('cid' => $instance->courseid)),
                    navigation_node::TYPE_CONTAINER, get_string('cp:coupons', 'enrol_classicpay'),
                    'cacoupons2', new pix_icon('coupons', '', 'enrol_classicpay'));
            }
        }
    }

    /**
     * Returns edit icons for the page with list of instances
     *
     * @param stdClass $instance
     *
     * @return array
     * @throws coding_exception
     */
    public function get_action_icons(stdClass $instance) {
        global $OUTPUT;

        if ($instance->enrol !== 'classicpay') {
            throw new coding_exception('invalid enrol instance!');
        }
        $context = context_course::instance($instance->courseid);

        $icons = array();

        if (has_capability('enrol/classicpay:config', $context)) {
            $editlink = new moodle_url("/enrol/classicpay/edit.php", array(
                'courseid' => $instance->courseid,
                'id' => $instance->id
            ));
            $icons[] = $OUTPUT->action_icon($editlink,
                    new pix_icon('t/edit', get_string('edit'), 'core', array('class' => 'iconsmall')));
        }

        return $icons;
    }

    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     *
     * @param int $courseid
     *
     * @return moodle_url page url
     */
    public function get_newinstance_link($courseid) {
        $context = context_course::instance($courseid, MUST_EXIST);

        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/classicpay:config', $context)) {
            return null;
        }

        // Multiple instances supported - different cost for different roles.
        return new moodle_url('/enrol/classicpay/edit.php', array('courseid' => $courseid));
    }

    /**
     * Creates course enrol form, checks if form submitted
     * and enrols user if necessary. It can also redirect.
     *
     * @param stdClass $instance
     *
     * @return string html text, usually a form in a text box
     */
    public function enrol_page_hook(stdClass $instance) {
        global $USER, $OUTPUT, $DB, $CFG;

        $gatewaymethod = optional_param('gateway', false, PARAM_ALPHA);

        ob_start();

        if ($DB->record_exists('user_enrolments', array('userid' => $USER->id, 'enrolid' => $instance->id))) {
            return ob_get_clean();
        }

        if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
            return ob_get_clean();
        }

        if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
            return ob_get_clean();
        }

        $cost = (float)($instance->cost <= 0) ? $this->get_config('cost') : $instance->cost;

        if (abs($cost) < 0.01 || isguestuser()) { // No cost, other enrolment methods (instances) should be used.
            return ob_get_clean();
        }

        // Limiting to specified cohort.
        if ($instance->customint5) {
            require_once("$CFG->dirroot/cohort/lib.php");
            if (!cohort_is_member($instance->customint5, $USER->id)) {
                return ob_get_clean();
            }
        }

        $course = $DB->get_record('course', array('id' => $instance->courseid));

        $config = new stdClass();
        $config->instanceid = $instance->id;
        $config->courseid = $instance->courseid;
        $config->userid = $USER->id;
        $config->userfullname = fullname($USER);
        $config->currency = $instance->currency;
        $config->cost = $cost;
        $config->vat = (int)$instance->customint1;
        $config->instancename = $this->get_instance_name($instance);
        $config->localisedcost = format_float($cost, 2, true);
        $config->coursename = $course->fullname;
        $config->locale = $USER->lang;
        $config->enablecoupon = (int)$instance->customint2;

        global $PAGE;
        $form = new \enrol_classicpay\forms\paymentinit($PAGE->url, $config);
        $form->set_data($instance);
        $form->process_post($instance);
        $form->display();

        return $OUTPUT->box(ob_get_clean());
    }

    /**
     * Restore instance and map settings.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $course
     * @param int $oldid
     */
    public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
        global $DB;
        if ($step->get_task()->get_target() == backup::TARGET_NEW_COURSE) {
            $merge = false;
        } else {
            $merge = array(
                'courseid' => $data->courseid,
                'enrol' => $this->get_name(),
                'roleid' => $data->roleid,
                'cost' => $data->cost,
                'currency' => $data->currency,
            );
        }
        if ($merge and $instances = $DB->get_records('enrol', $merge, 'id')) {
            $instance = reset($instances);
            $instanceid = $instance->id;
        } else {
            if (!empty($data->customint5)) {
                if ($step->get_task()->is_samesite()) {
                    // Keep cohort restriction unchanged - we are on the same site.
                    $data->customint5 = $data->customint5;
                } else {
                    // Use some id that can not exist in order to prevent self enrolment,
                    // because we do not know what cohort it is in this site.
                    $data->customint5 = -1;
                }
            }
            $instanceid = $this->add_instance($course, (array)$data);
        }
        $step->set_mapping('enrol', $oldid, $instanceid);
    }

    /**
     * Restore user enrolment.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $instance
     * @param int $userid
     * @param int $oldinstancestatus
     */
    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus) {
        $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $data->status);
    }

    /**
     * Gets an array of the user enrolment actions
     *
     * @param course_enrolment_manager $manager
     * @param stdClass $ue A user enrolment object
     *
     * @return array An array of user_enrolment_actions
     */
    public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol($instance) && has_capability("enrol/classicpay:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array(
                'class' => 'unenrollink',
                'rel' => $ue->id
            ));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/classicpay:manage", $context)) {
            $url = new moodle_url('/enrol/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array(
                'class' => 'editenrollink',
                'rel' => $ue->id
            ));
        }

        return $actions;
    }

    /**
     * Called for all enabled enrol plugins that returned true from is_cron_required().
     *
     * @return void
     */
    public function cron() {
        $trace = new text_progress_trace();
        $this->process_expirations($trace);
        $this->send_expiry_notifications($trace);
    }

    /**
     * Execute synchronisation.
     *
     * @param progress_trace $trace
     *
     * @return int exit code, 0 means ok
     */
    public function sync(progress_trace $trace) {
        $this->process_expirations($trace);
        return 0;
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     *
     * @return bool
     */
    public function can_delete_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/classicpay:config', $context);
    }

    /**
     * Is it possible to hide/show enrol instance via standard UI?
     *
     * @param stdClass $instance
     *
     * @return bool
     */
    public function can_hide_show_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/classicpay:config', $context);
    }

    /**
     * get all currencies that are supported by this block
     *
     * @return array
     */
    public function get_currencies() {
        $codes = array('EUR');
        $currencies = array();
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }
        return $currencies;
    }

}
