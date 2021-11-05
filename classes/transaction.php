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
 * this file contains the transaction implementation for PAYNL.
 *
 * File         transaction.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay;

defined('MOODLE_INTERNAL') or die;

use enrol_classicpay\pay\api\info;

/**
 * Description of transaction
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class transaction {

    /**
     * Transaction
     * @var \stdClass transaction record (enrol_classicpay record)
     */
    protected $transactionrecord;
    /**
     * remote transaction info (PAYNL)
     * @var \stdClass
     */
    protected $transactioninfo;
    /**
     * user record
     * @var \stdClass user record
     */
    protected $user;
    /**
     * Course
     * @var \stdClass course record
     */
    protected $course;
    /**
     * course context
     * @var \context_course
     */
    protected $coursecontext;
    /**
     * plugin
     * @var enrol_classicpay_plugin
     */
    protected $plugin;
    /**
     * @var \stdClass plugin instance record
     */
    protected $plugininstance;

    /**
     * return transaction record
     *
     * @return \stdClass
     */
    public function get_transactionrecord() {
        return $this->transactionrecord;
    }

    /**
     * set transaction record
     *
     * @param \stdClass $transactionrecord
     * @return \enrol_classicpay\transaction
     */
    public function set_transactionrecord($transactionrecord) {
        $this->transactionrecord = $transactionrecord;
        return $this;
    }

    /**
     * is transaction already paid according to INTERNAL record state?
     * @return bool
     */
    public function is_paid() {
        return ((int) $this->transactionrecord->status === 100);
    }

    /**
     * create new transaction instance
     * @param \stdClass $transactionrecord
     */
    public function __construct($transactionrecord = null) {
        $this->set_transactionrecord($transactionrecord);
        $this->plugin = enrol_get_plugin('classicpay');
    }

    /**
     * load transaction info from PAYNL
     */
    public function cp_get_transactioninfo() {
        $paynl = new info();
        $paynl->set_apitoken(get_config('enrol_classicpay', 'paynlapitoken'));
        $paynl->set_serviceid(get_config('enrol_classicpay', 'paynlserviceid'));
        $paynl->set_transactionid($this->transactionrecord->gateway_transaction_id);
        $this->transactioninfo = $paynl->do_request();
    }

    /**
     * Load and validate external transaction info.
     * @throws \Exception
     */
    private function load_validate_external() {
        // Generate data we complement error messages with.
        $data = new \stdClass();
        $custom = explode('|', $this->transactioninfo['statsDetails']['extra1']);
        $data->userid = (int) $custom[0];
        $data->instanceid = (int) $custom[1];
        $data->courseid = (int) $custom[2];
        $data->orderid = $custom[3];
        $data->paymentgross = $this->transactioninfo['paymentDetails']['paidCosts'];
        $data->paymentcurrency = $this->transactioninfo['paymentDetails']['paidCurrency'];
        $data->timemodified = time();
        $data->txuserid = $this->transactionrecord->userid;
        $data->txinstanceid = $this->transactionrecord->instanceid;
        $data->txcourseid = $this->transactionrecord->courseid;
        $data->txorderid = $this->transactionrecord->orderid;

        // Validate local record with returned info.
        if ((int)$data->userid !== (int)$this->transactionrecord->userid) {
            $this->message_error_to_admin("Not a matching user id", $data);
            throw new \Exception("Not a matching user id");
        }
        if ((int)$data->instanceid !== (int)$this->transactionrecord->instanceid) {
            $this->message_error_to_admin("Not a matching instance id", $data);
            throw new \Exception("Not a matching instance id");
        }
        if ((int)$data->courseid !== (int)$this->transactionrecord->courseid) {
            $this->message_error_to_admin("Not a matching course id", $data);
            throw new \Exception("Not a matching course id");
        }
        if ((string)$data->orderid !== (string)$this->transactionrecord->orderid) {
            $this->message_error_to_admin("Not a matching order id", $data);
            throw new \Exception("Not a matching order id");
        }
    }

    /**
     * Load and validate internal info.
     * @throws \Exception
     */
    private function load_validate_internal() {
        global $DB;

        // Validate the user and course records.
        if (!$this->user = $DB->get_record("user", array("id" => $this->transactionrecord->userid))) {
            $this->message_error_to_admin("Not a valid user id", $this->transactionrecord);
            throw new \Exception("Not a valid user id");
        }
        if (!$this->course = $DB->get_record("course", array("id" => $this->transactionrecord->courseid))) {
            $this->message_error_to_admin("Not a valid course id", $this->transactionrecord);
            throw new \Exception("Not a valid course id");
        }
        if (!$this->coursecontext = \context_course::instance($this->course->id, IGNORE_MISSING)) {
            $this->message_error_to_admin("Not a valid context id", $this->transactionrecord);
            throw new \Exception("Not a valid context id");
        }
        if (!$this->plugininstance = $DB->get_record("enrol", array("id" => $this->transactionrecord->instanceid, "status" => 0))) {
            $this->message_error_to_admin("Not a valid instance id", $this->transactionrecord);
            throw new \Exception("Not a valid instance id");
        }
    }

    /**
     * Synchronizes the transaction, checking if the payment status is PAID.
     * If the status is changed to PAID, the user will be enrolled.
     *
     * @return boolean
     */
    public function synchronize() {
        global $DB;
        // Do nothing if we have an internal PAID status.
        if ($this->is_paid()) {
            // Could this be a free enrolment?
            if ((int)$this->transactionrecord->cost === 0 && empty($this->transactionrecord->gateway_transaction_id)) {
                return $this->free_enrolment();
            }
            return true;
        }
        // Load transaction info.
        $this->cp_get_transactioninfo();
        // Validate info and load variables.
        $this->load_validate_external();
        try {
            $this->load_validate_internal();
        } catch (\Exception $ex) {
            $this->transactionrecord->status = -90;
            $this->transactionrecord->statusname = 'FROZENERROR';
            $this->transactionrecord->timemodified = time();
            $DB->update_record('enrol_classicpay', $this->transactionrecord);
            return false;
        }

        $this->transactionrecord->status = $this->transactioninfo['paymentDetails']['state'];
        $this->transactionrecord->statusname = $this->transactioninfo['paymentDetails']['stateName'];
        $this->transactionrecord->timemodified = time();
        $DB->update_record('enrol_classicpay', $this->transactionrecord);

        $status = false;
        if ((int) $this->transactionrecord->status === 100) {
            // We shall enrol user here.
            $status = $this->do_enrol();
        }
        return $status;
    }

    /**
     * Enrols someone for free (provided the record is PAID but no transaction ID is set).
     *
     * @return boolean
     */
    private function free_enrolment() {
        // Validate info and load variables.
        $this->load_validate_internal();
        $status = false;
        if ((int) $this->transactionrecord->status === 100) {
            // We shall enrol user here.
            $status = $this->do_enrol();
        }
        return $status;
    }

    /**
     * Perform internal enrolment
     *
     * @return boolean
     */
    public function do_enrol() {
        global $CFG, $DB;
        // PAID.
        if ($this->plugininstance->enrolperiod) {
            $timestart = time();
            $timeend = $timestart + $this->plugininstance->enrolperiod;
        } else {
            $timestart = 0;
            $timeend = 0;
        }

        // Enrol user.
        $this->plugin->enrol_user($this->plugininstance, $this->user->id, $this->plugininstance->roleid, $timestart, $timeend);

        // Call invoice services, IF this wasn't a free enrolment.
        if (!empty($this->transactionrecord->gateway_transaction_id) && (int)$this->transactionrecord->cost > 0) {
            $queue = array('classicpayid' => $this->transactionrecord->id);
            if (!$DB->record_exists('enrol_classicpay_ivq', $queue)) {
                $DB->insert_record('enrol_classicpay_ivq', (object)$queue);
            }
        }

        // Pass $view=true to filter hidden caps if the user cannot see them.
        $capability = 'moodle/course:update';
        $users = get_users_by_capability($this->coursecontext, $capability, 'u.*', 'u.id ASC', '', '', '', '', false, true);
        if (!empty($users)) {
            $users = sort_by_roleassignment_authority($users, $this->coursecontext);
            $teacher = array_shift($users);
        } else {
            $teacher = false;
        }

        $mailstudents = $this->plugin->get_config('mailstudents');
        $mailteachers = $this->plugin->get_config('mailteachers');
        $mailadmins = $this->plugin->get_config('mailadmins');
        $shortname = format_string($this->course->shortname, true, array('context' => $this->coursecontext));

        if (!empty($mailstudents)) {
            $a = new \stdClass();
            $a->coursename = format_string($this->course->fullname, true, array('context' => $this->coursecontext));
            $a->profileurl = "$CFG->wwwroot/user/view.php?id=".$this->user->id;

            if (class_exists('\\core\\message\\message')) {
                $admin = get_admin();
                $eventdata = new \core\message\message();
                $eventdata->modulename = 'moodle';
                $eventdata->component = 'enrol_classicpay';
                $eventdata->name = 'classicpay_enrolment';
                $eventdata->userfrom = empty($teacher) ? get_admin() : $teacher;
                $eventdata->userto = $this->user;
                $eventdata->subject = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage = get_string('welcometocoursetext', '', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml = '';
                $eventdata->smallmessage = '';
                $eventdata->notification = 1;
                $eventdata->contexturl = null;
                $eventdata->contexturlname = null;
                $eventdata->replyto = trim($admin->email);
                $eventdata->attachment = '';
                $eventdata->attachname = '';
                // Below is needed on Moodle 3.2.
                if (isset($CFG->branch) && $CFG->branch >= 32) {
                    $eventdata->courseid = 0;
                    $eventdata->timecreated = time();
                }
            } else {
                $eventdata = new \stdClass();
                $eventdata->modulename = 'moodle';
                $eventdata->component = 'enrol_classicpay';
                $eventdata->name = 'classicpay_enrolment';
                $eventdata->userfrom = empty($teacher) ? get_admin() : $teacher;
                $eventdata->userto = $this->user;
                $eventdata->subject = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage = get_string('welcometocoursetext', '', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml = '';
                $eventdata->smallmessage = '';
            }
            message_send($eventdata);
        }

        if (!empty($mailteachers) && !empty($teacher)) {
            $a->course = format_string($this->course->fullname, true, array('context' => $this->coursecontext));
            $a->user = fullname($this->user);

            if (class_exists('\\core\\message\\message')) {
                $admin = get_admin();
                $eventdata = new \core\message\message();
                $eventdata->modulename = 'moodle';
                $eventdata->component = 'enrol_classicpay';
                $eventdata->name = 'classicpay_enrolment';
                $eventdata->userfrom = $this->user;
                $eventdata->userto = $teacher;
                $eventdata->subject = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage = get_string('enrolmentnewuser', 'enrol', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml = '';
                $eventdata->smallmessage = '';
                $eventdata->notification = 1;
                $eventdata->contexturl = null;
                $eventdata->contexturlname = null;
                $eventdata->replyto = trim($admin->email);
                $eventdata->attachment = '';
                $eventdata->attachname = '';
                // Below is needed on Moodle 3.2.
                if (isset($CFG->branch) && $CFG->branch >= 32) {
                    $eventdata->courseid = 0;
                    $eventdata->timecreated = time();
                }
            } else {
                $eventdata = new \stdClass();
                $eventdata->modulename = 'moodle';
                $eventdata->component = 'enrol_classicpay';
                $eventdata->name = 'classicpay_enrolment';
                $eventdata->userfrom = $this->user;
                $eventdata->userto = $teacher;
                $eventdata->subject = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage = get_string('enrolmentnewuser', 'enrol', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml = '';
                $eventdata->smallmessage = '';
            }
            message_send($eventdata);
        }

        if (!empty($mailadmins)) {
            $a->course = format_string($this->course->fullname, true, array('context' => $this->coursecontext));
            $a->user = fullname($this->user);
            $admins = get_admins();
            foreach ($admins as $admin) {
                if (class_exists('\\core\\message\\message')) {
                    $eventdata = new \core\message\message();
                    $eventdata->modulename = 'moodle';
                    $eventdata->component = 'enrol_classicpay';
                    $eventdata->name = 'classicpay_enrolment';
                    $eventdata->userfrom = $this->user;
                    $eventdata->userto = $admin;
                    $eventdata->subject = get_string("enrolmentnew", 'enrol', $shortname);
                    $eventdata->fullmessage = get_string('enrolmentnewuser', 'enrol', $a);
                    $eventdata->fullmessageformat = FORMAT_PLAIN;
                    $eventdata->fullmessagehtml = '';
                    $eventdata->smallmessage = '';
                    $eventdata->notification = 1;
                    $eventdata->contexturl = null;
                    $eventdata->contexturlname = null;
                    $eventdata->replyto = trim($admin->email);
                    $eventdata->attachment = '';
                    $eventdata->attachname = '';
                    // Below is needed on Moodle 3.2.
                    if (isset($CFG->branch) && $CFG->branch >= 32) {
                        $eventdata->courseid = 0;
                        $eventdata->timecreated = time();
                    }
                } else {
                    $eventdata = new \stdClass();
                    $eventdata->modulename = 'moodle';
                    $eventdata->component = 'enrol_classicpay';
                    $eventdata->name = 'classicpay_enrolment';
                    $eventdata->userfrom = $this->user;
                    $eventdata->userto = $admin;
                    $eventdata->subject = get_string("enrolmentnew", 'enrol', $shortname);
                    $eventdata->fullmessage = get_string('enrolmentnewuser', 'enrol', $a);
                    $eventdata->fullmessageformat = FORMAT_PLAIN;
                    $eventdata->fullmessagehtml = '';
                    $eventdata->smallmessage = '';
                }
                message_send($eventdata);
            }
        }
        return true;
    }

    /**
     * Send an error notification to the admin(s).
     *
     * @param string $subject subject of error notification
     * @param \stdClass $data extra data to send (translated to key: value)
     */
    protected function message_error_to_admin($subject, $data) {
        global $CFG;
        $admin = get_admin();
        $site = get_site();

        $message = "$site->fullname:  Transaction failed.\n\n$subject\n\n";

        foreach ($data as $key => $value) {
            $message .= "$key => $value\n";
        }

        if (class_exists('\\core\\message\\message')) {
            $eventdata = new \core\message\message();
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'enrol_classicpay';
            $eventdata->name = 'classicpay_enrolment';
            $eventdata->userfrom = $admin;
            $eventdata->userto = $admin;
            $eventdata->subject = "CLASSICPAY ERROR: " . $subject;
            $eventdata->fullmessage = $message;
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml = '';
            $eventdata->smallmessage = '';
            $eventdata->notification = 1;
            $eventdata->contexturl = null;
            $eventdata->contexturlname = null;
            $eventdata->replyto = trim($admin->email);
            $eventdata->attachment = '';
            $eventdata->attachname = '';
            // Below is needed on Moodle 3.2.
            if (isset($CFG->branch) && $CFG->branch >= 32) {
                $eventdata->courseid = 0;
                $eventdata->timecreated = time();
            }
        } else {
            $eventdata = new \stdClass();
            $eventdata->modulename = 'moodle';
            $eventdata->component = 'enrol_classicpay';
            $eventdata->name = 'classicpay_enrolment';
            $eventdata->userfrom = $admin;
            $eventdata->userto = $admin;
            $eventdata->subject = "CLASSICPAY ERROR: " . $subject;
            $eventdata->fullmessage = $message;
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml = '';
            $eventdata->smallmessage = '';
        }
        message_send($eventdata);
    }

    /**
     * Request to send invoice from Sebsoft service.
     *
     * @return \stdClass
     * @throws \Exception
     */
    protected function request_invoice() {
        $ch = curl_init();
        $apiurl = 'https://customerpanel.sebsoft.nl/classicpay/sendinvoice.php';
        $params = array(
            'email' => $this->user->email,
            'firstname' => $this->user->firstname,
            'lastname' => $this->user->lastname,
            'fullname' => fullname($this->user),
            'country' => $this->user->country,
            'txid' => $this->transactionrecord->gateway_transaction_id,
            'svcid' => get_config('enrol_classicpay', 'paynlserviceid'),
            'vat' => (int)$this->plugininstance->customint1,
        );
        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            throw new \Exception($error, $errno);
        }

        curl_close($ch);

        $returndata = json_decode($result);
        return $returndata;
    }

}
