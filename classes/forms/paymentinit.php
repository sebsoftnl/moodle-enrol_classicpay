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
 * Contains form to apply for PAYNL services through Sebsoft
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

namespace enrol_classicpay\forms;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * enrol_classicpay\forms\paymentinit
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class paymentinit extends \moodleform {

    /**
     * form definition
     */
    public function definition() {
        global $CFG, $PAGE;
        if ((bool)$this->_customdata->enablecoupon) {
            $PAGE->requires->js('/enrol/classicpay/js/coupon.js');
        }
        $mform = $this->_form;
        $mform->setDisableShortforms(true);

        // This element is only here so the form will actually get submitted.
        $mform->addElement('hidden', 'action', 'starttransaction');
        $mform->setType('action', PARAM_ALPHA);

        $mform->addElement('hidden', 'instanceid', $this->_customdata->instanceid);
        $mform->setType('instanceid', PARAM_INT);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $html = '<div>';
        $html .= '<h2>' . $this->_customdata->instancename . '</h2>';
        $html .= '<p id="enrol-classicpay-basecost"><b>';
        $html .= get_string("cost") . ': ' . $this->_customdata->currency . ' ' . $this->_customdata->localisedcost;
        $html .= '</b></p>';
        $html .= '<p><b>';
        $html .= get_string("vat", 'enrol_classicpay') . ': ' . $this->_customdata->vat . '%';
        $html .= '</b></p>';
        $html .= '<p id="enrol-classicpay-coupondiscount"></p>';
        try {
            $paynl = new \enrol_classicpay\pay\api\getservice();
            $paynl->set_apitoken(get_config('enrol_classicpay', 'paynlapitoken'));
            $paynl->set_serviceid(get_config('enrol_classicpay', 'paynlserviceid'));
            $result = $paynl->do_request();

            // Add payment options.
            $html .= '<table>';
            foreach ($result['paymentOptions'] as $option) {
                $html .= '<tr><td>';
                $html .= '<div class="pp_s25 pp' . $option['id'] . '"></div>&nbsp;</td><td>' . $option['visibleName'];
                $html .= '</td></tr>';
            }
            $html .= '</table>';
        } catch (\Exception $e) {
            $html .= get_string('paynlconn:remote:error', 'enrol_classicpay', $e->getMessage());
        }
        $html .= '</div>';

        $mform->addElement('static', 'bankinfo', '', $html);

        if ((bool)$this->_customdata->enablecoupon) {
            $mform->addElement('text', 'coupon', get_string('couponcode', 'enrol_classicpay'));
            $mform->setType('coupon', PARAM_TEXT);
            $mform->addElement('static', 'checkme', '',
                    '<a href="#" id="btncheckcoupon">' . get_string('checkcode', 'enrol_classicpay') . '</a>');
        }

        $this->add_action_buttons(false, get_string('button:pay', 'enrol_classicpay'));
    }

    /**
     * Custom validation on the server.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        // Validate coupon code.
        if (!empty($data['coupon'])) {
            $coupon = $DB->get_record('enrol_classicpay_coupon', array('code' => $data['coupon']));
            if (!$coupon) {
                $errors['coupon'] = get_string('coupon:invalid', 'enrol_classicpay');
            } else {
                if ($coupon->courseid > 0 && ((int) $coupon->courseid !== (int) $data['courseid'])) {
                    $errors['coupon'] = get_string('coupon:invalid', 'enrol_classicpay');
                } else if ($coupon->validfrom > time()) {
                    $errors['coupon'] = get_string('coupon:invalid', 'enrol_classicpay');
                } else if ($coupon->validto < time()) {
                    $errors['coupon'] = get_string('coupon:expired', 'enrol_classicpay');
                }
            }
        }
        return $errors;
    }

    /**
     * Process form post.
     *
     * @param \stdClass $instance enrol instance.
     * @return void
     */
    public function process_post($instance) {
        if (!($data = $this->get_data())) {
            return;
        }
        global $CFG, $DB, $USER;

        $course = $DB->get_record('course', array('id' => $instance->courseid));
        $coupon = null;
        if ($data->coupon) {
            $coupon = $DB->get_record('enrol_classicpay_coupon', array('code' => $data->coupon), '*', MUST_EXIST);
        }

        // Check if coupon is 100%.
        $freeenrol = false;
        if ($coupon !== null) {
            if ($coupon->type === 'percentage' && intval($coupon->value) === 100) {
                $freeenrol = true;
            }
            if ($coupon->type === 'value' && ($coupon->value >= $instance->cost)) {
                $freeenrol = true;
            }
        }

        // Create record.
        $record = new \stdClass();
        $record->userid = $USER->id;
        $record->courseid = $instance->courseid;
        $record->instanceid = $instance->id;
        $record->orderid = uniqid(time());
        $record->status = 0;
        $record->statusname = 'WAIT';
        $record->gateway_transaction_id = '';
        $record->gateway = 'payNL';
        $record->rawcost = $instance->cost;
        $record->cost = $instance->cost;
        $record->percentage = 0;
        $record->discount = 0;
        $record->timecreated = time();
        $record->timemodified = 0;

        if ($freeenrol) {
            $record->status = 100;
            $record->statusname = 'PAID';
            $record->gateway = 'Moodle';
            $record->cost = 0;
            $record->rawcost = 0;
            $record->percentage = '100';
            $record->discount = $instance->cost;
            $record->timemodified = time();
        }

        $record->id = $DB->insert_record('enrol_classicpay', $record);

        // If this is a free enrolment, enrol end redirect to the course!
        if ($freeenrol) {
            $transaction = new \enrol_classicpay\transaction($record);
            $transaction->synchronize();
            if ($coupon !== null) {
                // Set coupon used.
                $coupon->numused++;
                $DB->update_record('enrol_classicpay_coupon', $coupon);
            }
            $url = new \moodle_url('/course/view.php', array('id' => $course->id));
            $message = '<p style="text-align: center">' . get_string('enrol:ok', 'enrol_classicpay', $course) . '</p>';
            redirect($url, $message);
            exit;
        }

        $cancelurl = new \moodle_url('/enrol/classicpay/cancel.php', array('id' => $record->instanceid, 'eid' => $record->id));
        $returnurl = new \moodle_url('/enrol/classicpay/return.php', array(
            'orderid' => $record->orderid,
            'instanceid' => $record->instanceid,
            'eid' => $record->id,
            'gateway' => $record->gateway
        ));
        $exchangeurl = new \moodle_url('/enrol/classicpay/xchange.php', array(
            'orderid' => $record->orderid,
            'instanceid' => $record->instanceid,
            'eid' => $record->id,
            'gateway' => $record->gateway
        ));

        $enduser = array();
        $enduser['language'] = $USER->lang;
        $enduser['lastName'] = $USER->lastname;
        $enduser['initials'] = $USER->firstname;
        if (!empty($USER->phone1)) {
            $enduser['phoneNumber'] = $USER->phone1;
        } else if (!empty($USER->phone2)) {
            $enduser['phoneNumber'] = $USER->phone2;
        }
        $enduser['address'] = array();
        $enduser['address']['streetName'] = $USER->address;
        $enduser['address']['city'] = $USER->city;
        $enduser['address']['countryCode'] = $USER->country;
        $enduser['emailAddress'] = $USER->email;

        $desc = ((strlen($course->shortname) > 32) ? substr($course->shortname, 0, 29) . "..." : $course->shortname);
        $finalamount = intval(bcmul($record->cost, 100));

        $paynl = new \enrol_classicpay\pay\api\start();
        $paynl->set_apitoken(get_config('enrol_classicpay', 'paynlapitoken'));
        $paynl->set_serviceid(get_config('enrol_classicpay', 'paynlserviceid'));
        $paynl->set_description($desc);
        $paynl->set_info('Classicpayv1');
        $paynl->set_extra1($record->userid . '|' . $record->instanceid . '|' . $record->courseid . '|' . $record->orderid);
        $paynl->set_exchangeurl($exchangeurl->out(false));
        $paynl->set_finishurl($returnurl->out(false));
        $paynl->set_enduser($enduser);

        // Insert / add products (note: VAT is not used for now).
        $paynl->add_product($instance->courseid, $course->fullname, intval(bcmul($record->cost, 100)), 1, 'N');
        if ($coupon !== null) {
            if ($coupon->type === 'percentage') {
                $discount = intval((($coupon->value / 100) * $record->cost) * -100);
                $percentage = $coupon->value;
            } else {
                $percentage = intval( 100 * ($coupon->value / $record->cost));
                $discount = $coupon->value * -100;
            }

            $finalamount += $discount;
            $paynl->add_product($coupon->code, 'COUPON', $discount, 1, 'N');
            // Set coupon used.
            $coupon->numused++;
            $DB->update_record('enrol_classicpay_coupon', $coupon);
            // Insert coupon usage record.
            $DB->insert_record('enrol_classicpay_cuse', (object) array('couponid' => $coupon->id, 'classicpayid' => $record->id));
            // Update values on our transaction record.
            $record->discount = $discount / -100;
            $record->percentage = $percentage;
        }
        $paynl->set_amount($finalamount);

        $record->cost = (float) ($finalamount / 100);
        $record->timemodified = time();
        $DB->update_record('enrol_classicpay', $record);

        $result = $paynl->do_request();

        if (strlen($result['request']['errorMessage']) > 0) {
            redirect($cancelurl, $result['request']['errorMessage']);
            exit;
        }

        if (empty($result) || empty($result['transaction']['paymentURL']) || strlen($result['transaction']['paymentURL']) == 0) {
            $result['transaction']['paymentURL'] = $cancelurl;
        }

        $record->gateway_transaction_id = $result['transaction']['transactionId'];
        $record->timemodified = time();
        $DB->update_record('enrol_classicpay', $record);

        header("Location: " . $result['transaction']['paymentURL']);
        exit;
    }

}
