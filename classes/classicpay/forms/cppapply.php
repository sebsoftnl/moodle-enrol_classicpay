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
 * Contains form to apply for PLUS services through Sebsoft
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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * application form for classicpay plus
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cppapply extends \moodleform {

    /**
     * form definition
     */
    protected function definition() {
        global $OUTPUT;
        $mform = $this->_form;
        $mform->setDisableShortforms();

        $mform->addElement('header', 'hprofile_', get_string('cppapply:header', 'enrol_classicpay'));

        $cpapi = new \enrol_classicpay\classicpay\api();
        $result = $cpapi->check_classicpayplus();
        $iscpp = (bool)$result->result;
        // Apply or not.
        if (isset($result->error)) {
            $str = '<div class="enrol-classicpay-info">'
                    .get_string('classicpay:plus:status:error', 'enrol_classicpay', $result->error).'</div>';
            $mform->addElement('static', 'cppstatus', '', $str);
        } else {
            if ($iscpp) {
                $str = '<div class="enrol-classicpay-info"><img src="'.$OUTPUT->image_url('i/completion-auto-y').'" class="icon"/> '
                        .get_string('classicpay:plus:status:valid', 'enrol_classicpay').'</div>';
                $mform->addElement('static', 'cppstatus', '', $str);
                $submitlabel = get_string('cppapply:disable', 'enrol_classicpay');
                $mform->addElement('hidden', 'enable', 0);
            } else {
                $str = '<div class="enrol-classicpay-info"><img src="'.$OUTPUT->image_url('i/completion-auto-n').'" class="icon"/> '
                        .get_string('classicpay:plus:status:invalid', 'enrol_classicpay').'</div>';
                $mform->addElement('static', 'cppstatus', '', $str);
                $submitlabel = get_string('cppapply:enable', 'enrol_classicpay');
                $mform->addElement('hidden', 'enable', 1);
            }
            $mform->setType('enable', PARAM_INT);
            $mform->addElement('static', 'static_', '', get_string('classicpay:plus:description', 'enrol_classicpay'));

            $mform->addElement('submit', 'button', $submitlabel);
        }
    }

    /**
     * Process form. This method takes care of full processing, including display,
     * of the form.
     *
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return void
     */
    public function process_form($redirect) {
        global $OUTPUT;
        if (!$this->process_post($redirect)) {
            echo $OUTPUT->header();
            echo '<div class="enrol-classicpay-container">';
            $this->display();
            echo '</div>';
            echo $OUTPUT->footer();
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
        // And apply.
        $api = new \enrol_classicpay\classicpay\api();
        $result = $api->apply_classicpayplus((bool)$data->enable);

        if (isset($result->error)) {
            $message = get_string('apply:cpp:error', 'enrol_classicpay', $result->error);
        } else if ($result->result === true) {
            $message = get_string('apply:cpp:success', 'enrol_classicpay');
            set_config('isclassicpayplus', 1, 'enrol_classicpay');
        } else {
            $message = get_string('apply:cpp:fail', 'enrol_classicpay');
            set_config('isclassicpayplus', 0, 'enrol_classicpay');
        }
        redirect($redirect, $message, 3);
    }

}
