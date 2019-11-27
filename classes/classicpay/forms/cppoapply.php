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
 * Contains form to apply for paymentoptions through Sebsoft
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

namespace enrol_classicpay\classicpay\forms;
use enrol_classicpay\classicpay\api;
use enrol_classicpay\classicpay\exception as apiexception;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * application form for classicpay payment options
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cppoapply extends \moodleform {

    /**
     * form definition
     */
    protected function definition() {
        $mform = $this->_form;
        $mform->setDisableShortforms();

        $cpapi = new api();
        // PaymentProfiles.
        try {
            $profiles = $cpapi->get_servicepaymentprofiles();
            // Payment profiles.
            $mform->addElement('header', 'hprofile_', get_string('cppoapply:header:paymentprofiles', 'enrol_classicpay'));
            $options = array();
            $options1 = array();
            $defaults = array();
            foreach ($profiles as $profile) {
                $name = 'paymentprofile[' . $profile->id . ']';
                $label = '';
                $label .= '<div class="pp_s25 pp' . $profile->id . '"></div>&nbsp;';
                if ($profile->enabled) {
                    $defaults[$name] = 1;
                }
                $label .= $profile->name;
                if (!empty($profile->settings)) {
                    $label .= '<span style="color:red"> *</span>';
                    $options1[] = $mform->createElement('advcheckbox', $name, '', $label, null, array(0, 1));
                } else {
                    $options[] = $mform->createElement('advcheckbox', $name, '', $label, null, array(0, 1));
                }
            }
            $mform->addElement('static', '_simple', '', get_string('cppoapply:paymentprofiles:simple', 'enrol_classicpay'));
            $mform->addGroup($options, '', get_string('apply:paymentprofile', 'enrol_classicpay'), '<br/>', false);
            $mform->addElement('static', '_settings', '', get_string('cppoapply:paymentprofiles:setting', 'enrol_classicpay'));
            $mform->addGroup($options1, '', get_string('apply:paymentprofile', 'enrol_classicpay'), '<br/>', false);

            $mform->setDefaults($defaults);

            $mform->addElement('submit', 'button', get_string('button:cppo:update', 'enrol_classicpay'));
        } catch (apiexception $aex) {
            $mform->addElement('static', '_error', '', $aex->getMessage());
        }
    }

    /**
     * Process form post. This method takes care of processing cancellation and
     * submission of the form.
     *
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return bool
     */
    public function process_post($redirect) {
        if (!$data = $this->get_data()) {
            return false;
        }
        // Generate params and send off.
        $api = new api();
        $result = $api->set_servicepaymentprofiles($data->paymentprofile);
        if ((bool)$result->status) {
            $string = implode('<br/>', $result->result->results);
            $message = get_string('setserviceprofiles:success', 'enrol_classicpay', $string);
        } else {
            $message = get_string('err:setserviceprofiles', 'enrol_classicpay', $result);
        }
        redirect($redirect, $message, 5);
    }

}
