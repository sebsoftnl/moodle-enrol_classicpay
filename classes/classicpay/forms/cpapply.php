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
 * File         cpapply.php
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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * application form for PAYNL
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cpapply extends \moodleform {

    /**
     * api url to register service at
     * @var string
     */
    private $apiurl = 'https://customerpanel.sebsoft.nl/classicpay/register.php';

    /**
     * form definition
     */
    protected function definition() {
        global $CFG;
        $mform = $this->_form;
        $mform->setDisableShortforms();

        // Gather all menu based options.
        $api = new api();
        // Languages.
        $languages = $api->get_languages();
        // PaymentProfiles.
        $profiles = $api->get_paymentprofiles();
        // Country list.
        $countries = $api->get_countries();
        // Gender.
        $gender = array(
            'male' => get_string('apply:gender:male', 'enrol_classicpay'),
            'female' => get_string('apply:gender:female', 'enrol_classicpay'),
        );
        // Signee authorization to sign options.
        $signauth = array(
            0 => get_string('apply:authorizedtosign:no', 'enrol_classicpay'),
            1 => get_string('apply:authorizedtosign:yes', 'enrol_classicpay'),
            2 => get_string('apply:authorizedtosign:shared', 'enrol_classicpay'),
        );
        // True/false options.
        $truefalse = array(
            'false' => get_string('no'),
            'true' => get_string('yes'),
        );

        // Form Elements.
        $mform->addElement('header', 'hintro_', get_string('apply:header:details', 'enrol_classicpay'));
        $mform->addElement('static', 'hexplain_', '', get_string('apply:information', 'enrol_classicpay'));
        // Email addresses.
        $mform->addElement('text', 'email', get_string('apply:email', 'enrol_classicpay'));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('email', 'apply:email', 'enrol_classicpay');

        $mform->addElement('text', 'phone', get_string('apply:phone', 'enrol_classicpay'));
        $mform->setType('phone', PARAM_TEXT);
        $mform->addRule('phone', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('phone', 'apply:phone', 'enrol_classicpay');

        $mform->addElement('text', 'phone2', get_string('apply:phone2', 'enrol_classicpay'));
        $mform->setType('phone2', PARAM_TEXT);
        $mform->addHelpButton('phone2', 'apply:phone2', 'enrol_classicpay');

        $mform->addElement('text', 'firstName', get_string('apply:firstname', 'enrol_classicpay'));
        $mform->setType('firstName', PARAM_TEXT);
        $mform->addRule('firstName', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('firstName', 'apply:firstname', 'enrol_classicpay');

        $mform->addElement('text', 'lastName', get_string('apply:lastname', 'enrol_classicpay'));
        $mform->setType('lastName', PARAM_TEXT);
        $mform->addRule('lastName', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('lastName', 'apply:lastname', 'enrol_classicpay');

        $mform->addElement('text', 'companyName', get_string('apply:companyname', 'enrol_classicpay'));
        $mform->setType('companyName', PARAM_TEXT);
        $mform->addRule('companyName', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('companyName', 'apply:companyname', 'enrol_classicpay');

        $mform->addElement('text', 'cocNumber', get_string('apply:cocnumber', 'enrol_classicpay'));
        $mform->setType('cocNumber', PARAM_ALPHANUMEXT);
        $mform->addRule('cocNumber', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('cocNumber', 'apply:cocnumber', 'enrol_classicpay');

        $mform->addElement('select', 'gender', get_string('apply:gender', 'enrol_classicpay'), $gender);
        $mform->setType('gender', PARAM_ALPHA);
        $mform->addHelpButton('gender', 'apply:gender', 'enrol_classicpay');

        $mform->addElement('text', 'street', get_string('apply:street', 'enrol_classicpay'));
        $mform->setType('street', PARAM_TEXT);
        $mform->addRule('street', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('street', 'apply:street', 'enrol_classicpay');

        $mform->addElement('text', 'houseNumber', get_string('apply:housenumber', 'enrol_classicpay'));
        $mform->setType('houseNumber', PARAM_ALPHANUM);
        $mform->addRule('houseNumber', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('houseNumber', 'apply:housenumber', 'enrol_classicpay');

        $mform->addElement('text', 'postalCode', get_string('apply:zipcode', 'enrol_classicpay'));
        $mform->setType('postalCode', PARAM_ALPHANUMEXT);
        $mform->addRule('postalCode', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('postalCode', 'apply:zipcode', 'enrol_classicpay');

        $mform->addElement('text', 'city', get_string('apply:city', 'enrol_classicpay'));
        $mform->setType('city', PARAM_TEXT);
        $mform->addRule('city', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('city', 'apply:city', 'enrol_classicpay');

        // Optional fields.
        $mform->addElement('select', 'countryCode', get_string('apply:countrycode', 'enrol_classicpay'), $countries);
        $mform->setType('countryCode', PARAM_ALPHA);
        $mform->setDefault('countryCode', 'NL');
        $mform->addHelpButton('countryCode', 'apply:countrycode', 'enrol_classicpay');

        $mform->addElement('text', 'bankAccountOwner', get_string('apply:bankaccountowner', 'enrol_classicpay'));
        $mform->setType('bankAccountOwner', PARAM_TEXT);
        $mform->addRule('bankAccountOwner', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('bankAccountOwner', 'apply:bankaccountowner', 'enrol_classicpay');

        $mform->addElement('text', 'bankAccountNumber', get_string('apply:bankaccountnumber', 'enrol_classicpay'));
        $mform->setType('bankAccountNumber', PARAM_ALPHANUMEXT);
        $mform->addRule('bankAccountNumber', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('bankAccountNumber', 'apply:bankaccountnumber', 'enrol_classicpay');

        $mform->addElement('text', 'BIC', get_string('apply:bic', 'enrol_classicpay'));
        $mform->setType('BIC', PARAM_ALPHANUM);
        $mform->addRule('BIC', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('BIC', 'apply:bic', 'enrol_classicpay');

        $mform->addElement('text', 'bankName', get_string('apply:bankname', 'enrol_classicpay'));
        $mform->setType('bankName', PARAM_TEXT);
        $mform->addHelpButton('bankName', 'apply:bankname', 'enrol_classicpay');

        $mform->addElement('text', 'bankCity', get_string('apply:bankcity', 'enrol_classicpay'));
        $mform->setType('bankCity', PARAM_TEXT);
        $mform->addHelpButton('bankCity', 'apply:bankcity', 'enrol_classicpay');

        $mform->addElement('text', 'vatNumber', get_string('apply:vatnumber', 'enrol_classicpay'));
        $mform->setType('vatNumber', PARAM_ALPHANUMEXT);
        $mform->addHelpButton('vatNumber', 'apply:vatnumber', 'enrol_classicpay');

        $mform->addElement('select', 'languageId', get_string('apply:languageid', 'enrol_classicpay'), $languages);
        $mform->setDefault('languageId', 1);
        $mform->addHelpButton('languageId', 'apply:languageid', 'enrol_classicpay');

        $mform->addElement('select', 'authorizedToSign', get_string('apply:authorizedtosign', 'enrol_classicpay'), $truefalse);
        $mform->setType('authorizedToSign', PARAM_ALPHA);
        $mform->addHelpButton('authorizedToSign', 'apply:authorizedtosign', 'enrol_classicpay');

        $fpoptions = array('maxbytes' => (5 * 1024 * 1024), 'accepted_types' => '*');
        $mform->addElement('filepicker', 'cocdocument', get_string('apply:cocdocument', 'enrol_classicpay'), null, $fpoptions);
        $mform->setType('cocdocument', PARAM_RAW);
        $mform->addHelpButton('cocdocument', 'apply:cocdocument', 'enrol_classicpay');

        $mform->addElement('filepicker', 'bankdocument', get_string('apply:bankdocument', 'enrol_classicpay'), null, $fpoptions);
        $mform->setType('bankdocument', PARAM_RAW);
        $mform->addHelpButton('bankdocument', 'apply:bankdocument', 'enrol_classicpay');

        $mform->addElement('filepicker', 'iddocument', get_string('apply:iddocument', 'enrol_classicpay'), null, $fpoptions);
        $mform->setType('iddocument', PARAM_RAW);
        $mform->addHelpButton('iddocument', 'apply:iddocument', 'enrol_classicpay');

        $mform->addElement('text', 'sitename', get_string('apply:sitename', 'enrol_classicpay'));
        $mform->setType('sitename', PARAM_TEXT);
        $mform->setDefault('sitename', get_site()->shortname);
        $mform->addHelpButton('sitename', 'apply:sitename', 'enrol_classicpay');

        $mform->addElement('text', 'siteurl', get_string('apply:siteurl', 'enrol_classicpay'));
        $mform->setType('siteurl', PARAM_TEXT);
        $mform->setDefault('siteurl', $CFG->wwwroot);
        $mform->addHelpButton('siteurl', 'apply:siteurl', 'enrol_classicpay');
        $mform->hardFreeze('siteurl');

        // Payment profiles.
        $mform->addElement('header', 'hprofile_', get_string('apply:header:paymentprofiles', 'enrol_classicpay'));
        $options = array();
        foreach ($profiles as $profile) {
            $name = 'paymentprofile['.$profile->id.']';
            $label = '<div class="pp_s25 pp' . $profile->id . '"></div>&nbsp;' . $profile->name;
            $options[] = $mform->createElement('checkbox', $name, '', $label, array('value' => $profile->id));
        }
        $mform->addGroup($options, '', get_string('apply:paymentprofile', 'enrol_classicpay'), '<br/>', false);

        // Signee.
        $mform->addElement('header', 'hsignee_', get_string('apply:header:signees', 'enrol_classicpay'));

        $repeat = array();
        $repeat[] = $mform->createElement('text', 'signeeemail', get_string('apply:email', 'enrol_classicpay'));
        $repeat[] = $mform->createElement('text', 'signeefirstname', get_string('apply:firstname', 'enrol_classicpay'));
        $repeat[] = $mform->createElement('text', 'signeelastname', get_string('apply:lastname', 'enrol_classicpay'));
        $repeat[] = $mform->createElement('select', 'signeeauthorisedtosign',
                get_string('apply:authorizedtosign', 'enrol_classicpay'), $signauth);
        $repeat[] = $mform->createElement('filepicker', 'signeeidentification',
                get_string('apply:iddocument', 'enrol_classicpay'), null, $fpoptions);

        $repeatno = optional_param('signeerepeats', 1, PARAM_INT);
        $repeateloptions = array();
        $repeateloptions['signeeauthorisedtosign']['default'] = 2;

        $mform->setType('signeeemail', PARAM_EMAIL);
        $mform->setType('signeefirstname', PARAM_TEXT);
        $mform->setType('signeelastname', PARAM_TEXT);
        $mform->setType('signeeauthorisedtosign', PARAM_INT);
        $mform->setType('signeeidentification', PARAM_RAW);

        $name = get_string('apply:button:addsignee', 'enrol_classicpay');
        $this->repeat_elements($repeat, $repeatno, $repeateloptions, 'signeerepeats', 'option_add_fields', 1, $name, true);

        $this->add_action_buttons(true, get_string('apply:submit', 'enrol_classicpay'));
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
        $errors = parent::validation($data, $files);
        if ($data['signeerepeats'] > 0) {
            for ($i = 0; $i < $data['signeerepeats']; $i++) {
                if (empty($data['signeeemail'][$i])) {
                    continue;
                }
                if (empty($data['signeefirstname'][$i])) {
                    $errors['signeefirstname['.$i.']'] = get_string('required');
                }
                if (empty($data['signeelastname'][$i])) {
                    $errors['signeelastname['.$i.']'] = get_string('required');
                }
            }
        }
        return $errors;
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
     * Check if we a hace an uploaded file for a repeated filepicker element.
     *
     * This is a rather nasty hack since Moodle does not seem to support repeated/index based
     * filepicker / filemanager elements decently.
     *
     * @param string $elname base element name
     * @param int $idx index of the file upload.
     * @return boolean
     */
    private function has_repeat_upload($elname, $idx) {
        global $USER;
        // This is EXTREMELY nasty. Moodle has no sane support for repeated filepicker elements, so we'll hack this ourselves.
        $values = $this->_form->exportValues($elname.'['.$idx.']');
        if (empty($values[$elname][$idx])) {
            return false;
        }
        $draftid = $values[$elname][$idx];
        $fs = get_file_storage();
        $context = \context_user::instance($USER->id);
        if (!$files = $fs->get_area_files($context->id, 'user', 'draft', $draftid, 'id DESC', false)) {
            return false;
        }
        $file = reset($files);

        // Set name.
        $fn = $file->get_filename();
        return !empty($fn);
    }

    /**
     * Process form post. This method takes care of processing cancellation and
     * submission of the form.
     *
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return bool
     */
    protected function process_post($redirect) {
        global $CFG, $USER;
        if ($this->is_cancelled()) {
            redirect($redirect, get_string('registrationcancelled', 'enrol_classicpay'), 3);
        }
        if (!$data = $this->get_data()) {
            return false;
        }

        $registrationdata = clone $data;
        // Load files.
        $this->load_file('cocdocument', $registrationdata);
        $this->load_file('bankdocument', $registrationdata);
        $this->load_file('iddocument', $registrationdata);

        $signeedocs = array();
        for ($i = 0; $i < $data->signeerepeats; $i++) {
            $elname = 'signeeidentification['.$i.']';
            // This is EXTREMELY nasty. Moodle has no sane support for repeated filepicker elements, so we'll hack this ourselves.
            $values = $this->_form->exportValues($elname);
            if (empty($values['signeeidentification'][$i])) {
                continue;
            }
            $draftid = $values['signeeidentification'][$i];
            $fs = get_file_storage();
            $context = \context_user::instance($USER->id);
            if (!$files = $fs->get_area_files($context->id, 'user', 'draft', $draftid, 'id DESC', false)) {
                continue;
            }
            $file = reset($files);

            // Set name.
            $fn = $file->get_filename();
            $signeedocs['signees[' . $i . '][iddocument]'] = $CFG->dataroot . '/' . $fn;
            // Copy to dataroot.
            $file->copy_content_to($CFG->dataroot . '/' . $fn);
            // Remove file (I know, but submitted files are confidential).
            $file->delete();
        }

        $registrationdata->signeedocs = $signeedocs;

        // Process.
        $exception = null;
        $info = null;
        try {
            // Work on a copy of the data, this is easier since we will perform actions afterwards.
            unset($registrationdata->signeeidentification);
            unset($registrationdata->submitbutton);
            $result = $this->relay_registration($registrationdata);
            // Set configuration.
            if ($result->status === 'true') {
                set_config('paynlapitoken', $result->result->merchantToken, 'enrol_classicpay');
                set_config('paynlmerchantid', $result->result->merchantId, 'enrol_classicpay');
                set_config('paynlserviceid', $result->result->serviceId, 'enrol_classicpay');
            } else {
                throw new \Exception($result->error);
            }
        } catch (\Exception $ex) {
            $exception = $ex;
        }
        // Clean uploaded files.
        $this->unlink_file('cocdocument', $registrationdata);
        $this->unlink_file('bankdocument', $registrationdata);
        $this->unlink_file('iddocument', $registrationdata);
        foreach ($registrationdata->signeedocs as $fn) {
            @unlink($fn);
        }

        if ($exception !== null) {
            $a = (object) array(
                'errcode' => $ex->getCode(),
                'error' => $ex->getMessage(),
                'info' => $info
            );
            redirect($redirect, get_string('apply:fail', 'enrol_classicpay', $a), 5);
        } else {
            $a = (object) array(
                'info' => $info
            );
            redirect($redirect, get_string('apply:success', 'enrol_classicpay', $a), 5);
        }
    }

    /**
     * Load a file to the registration data
     *
     * @param string $elname
     * @param \stdClass $registrationdata
     * @return boolean
     */
    private function load_file($elname, &$registrationdata) {
        global $CFG;
        $filename = $this->get_new_filename($elname);
        if ($filename !== false) {
            $filepath = $CFG->dataroot . '/' . $filename;
            if ($this->save_file($elname, $filepath)) {
                $registrationdata->$elname = $filepath;
                return true;
            }
        }
        unset($registrationdata->$elname);
        return false;
    }

    /**
     * Unlink a file from the registration data
     *
     * @param string $elname
     * @param \stdClass $registrationdata
     * @return boolean
     */
    private function unlink_file($elname, $registrationdata) {
        if (isset($registrationdata->$elname) && file_exists($registrationdata->$elname)) {
            @unlink($registrationdata->$elname);
            return true;
        }
        return false;
    }

    /**
     * Relay the registration form data.
     *
     * @param array|\stdClass $data The form data, which is an adjusted copy of the original post data.
     * @return array result array containing:<code><pre>
     * string $merchantToken API token
     * string $merchantId Merchant ID
     * string $serviceId Service ID
     * </pre></code>
     * @throws \Exception
     */
    protected function relay_registration($data) {
        if (isset($data->cocdocument)) {
            $data->cocdocument = curl_file_create($data->cocdocument);
        }
        if (isset($data->bankdocument)) {
            $data->bankdocument = curl_file_create($data->bankdocument);
        }
        if (isset($data->iddocument)) {
            $data->iddocument = curl_file_create($data->iddocument);
        }
        if (isset($data->signeedocs)) {
            foreach ($data->signeedocs as $name => $file) {
                $data->{$name} = curl_file_create($file);
            }
        }
        // Build signees.
        if ($data->signeerepeats > 0) {
            for ($i = 0; $i < $data->signeerepeats; $i++) {
                if (empty($data->signeeemail[$i])) {
                    continue;
                }
                $data->{'signees[' . $i . '][email]'} = $data->signeeemail[$i];
                $data->{'signees[' . $i . '][firstname]'} = $data->signeefirstname[$i];
                $data->{'signees[' . $i . '][lastname]'} = $data->signeelastname[$i];
                $data->{'signees[' . $i . '][authorised_to_sign]'} = $data->signeeauthorisedtosign[$i];
            }
        }
        // Unset old data.
        unset($data->signeerepeats);
        unset($data->signeeemail);
        unset($data->signeefirstname);
        unset($data->signeelastname);
        unset($data->signeeauthorisedtosign);
        unset($data->signeedocs);
        // Build paymentprofileids.
        if (isset($data->paymentprofile)) {
            $data->paymentprofile = implode(',', array_keys($data->paymentprofile));
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiurl);
        curl_setopt($ch, CURLOPT_POST, true);
        // Makes sure of multipart/form-data :).
        curl_setopt($ch, CURLOPT_POSTFIELDS, (array)$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
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

if (!function_exists('curl_file_create')) {
    /**
     * create a curl file reference
     * @param string $filename full path name to file
     * @param string $mimetype file mimetype
     * @param string $postname file post name
     * @return string
     */
    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }
}
