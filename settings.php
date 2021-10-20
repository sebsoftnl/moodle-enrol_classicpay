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
 * general global plugin settings
 *
 * File         settings.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $config = get_config('enrol_classicpay');
    // Logo.
    $image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
            $OUTPUT->image_url('logo', 'enrol_classicpay') . '" /></a>&nbsp;&nbsp;&nbsp;';
    $donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
            $OUTPUT->image_url('donate', 'enrol_classicpay') . '" /></a>';
    $header = '<div class="block-selectrss-logopromo">' . $image . $donate . '</div>';
    $settings->add(new admin_setting_heading('enrol_classicpay_logopromo',
            get_string('promo', 'enrol_classicpay'),
            get_string('promodesc', 'enrol_classicpay', $header)));

    // Settings.
    $settings->add(new admin_setting_heading('enrol_classicpay_settings', '',
            get_string('pluginname_desc', 'enrol_classicpay')));
    $settings->add(new admin_setting_configcheckbox('enrol_classicpay/mailstudents',
            get_string('mailstudents', 'enrol_classicpay'), '', 0));
    $settings->add(new admin_setting_configcheckbox('enrol_classicpay/mailteachers',
            get_string('mailteachers', 'enrol_classicpay'), '', 0));
    $settings->add(new admin_setting_configcheckbox('enrol_classicpay/mailadmins',
            get_string('mailadmins', 'enrol_classicpay'), '', 0));

    $options = array(
        ENROL_EXT_REMOVED_KEEP => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect('enrol_classicpay/expiredaction',
            get_string('expiredaction', 'enrol_classicpay'), get_string('expiredaction_help', 'enrol_classicpay'),
            ENROL_EXT_REMOVED_SUSPENDNOROLES, $options));

    $options = array();
    for ($i = 0; $i < 24; $i++) {
        $options[$i] = $i;
    }
    $settings->add(new admin_setting_configselect('enrol_classicpay/expirynotifyhour',
            get_string('expirynotifyhour', 'core_enrol'), '', 6, $options));

    // Enrol instance defaults.
    $settings->add(new admin_setting_heading('enrol_classicpay_defaults',
            get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $optionsyesno = array(
        ENROL_INSTANCE_ENABLED => get_string('yes'),
        ENROL_INSTANCE_DISABLED => get_string('no')
    );
    $settings->add(new admin_setting_configselect('enrol_classicpay/status', get_string('status', 'enrol_classicpay'),
            get_string('status_desc', 'enrol_classicpay'), ENROL_INSTANCE_DISABLED, $optionsyesno));
    $settings->add(new admin_setting_configtext('enrol_classicpay/cost', get_string('cost', 'enrol_classicpay'),
            '', 10.00, PARAM_FLOAT, 4));
    $settings->add(new admin_setting_configtext('enrol_classicpay/vat', get_string('vat', 'enrol_classicpay'),
            get_string('vat_help', 'enrol_classicpay'), 21, PARAM_INT, 4));

    $classicpaycurrencies = enrol_get_plugin('classicpay')->get_currencies();
    $settings->add(new admin_setting_configselect('enrol_classicpay/currency',
            get_string('currency', 'enrol_classicpay'), '', 'EUR', $classicpaycurrencies));

    $settings->add(new admin_setting_configcheckbox('enrol_classicpay/enablecoupon', get_string('enablecoupon', 'enrol_classicpay'),
            get_string('enablecoupon_help', 'enrol_classicpay'), 1));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_classicpay/roleid',
                get_string('defaultrole', 'enrol_classicpay'),
                get_string('defaultrole_desc', 'enrol_classicpay'), $student->id, $options));
    }
    $settings->add(new admin_setting_configduration('enrol_classicpay/enrolperiod',
            get_string('enrolperiod', 'enrol_classicpay'), get_string('enrolperiod_desc', 'enrol_classicpay'), 0));

    $options = array(
        0 => get_string('no'),
        1 => get_string('expirynotifyenroller', 'core_enrol'),
        2 => get_string('expirynotifyall', 'core_enrol')
    );
    $settings->add(new admin_setting_configselect('enrol_classicpay/expirynotify',
            get_string('expirynotify', 'core_enrol'),
            get_string('expirynotify_help', 'core_enrol'), 0, $options));

    $settings->add(new admin_setting_configduration('enrol_classicpay/expirythreshold',
            get_string('expirythreshold', 'core_enrol'),
            get_string('expirythreshold_help', 'core_enrol'), 86400, 86400));

    $settings->add(new admin_setting_configtext('enrol_classicpay/htmlonthankyoupage',
            get_string('htmlonthankyoupage', 'enrol_classicpay'),
            get_string('htmlonthankyoupage_desc', 'enrol_classicpay'), '', PARAM_RAW, 4096));

    if (!during_initial_install()) {
        $settingsstr = get_string('paynlsettings_desc', 'enrol_classicpay');
        if (!isset($config->paynlapitoken) || empty($config->paynlapitoken)) {
            $url = new moodle_url('/enrol/classicpay/spapply.php');
            $settingsstr .= '<br/>' . get_string('paynlsettings_apply', 'enrol_classicpay', $url->out());
        }
        $settings->add(new admin_setting_heading('paynlsettings',
                get_string('paynlsettings', 'enrol_classicpay'), $settingsstr));
        $settings->add(new admin_setting_configtext('enrol_classicpay/paynlapitoken',
                get_string('paynlapitoken', 'enrol_classicpay'),
                get_string('paynlapitoken_desc', 'enrol_classicpay'), ''));
        $settings->add(new admin_setting_configtext('enrol_classicpay/paynlserviceid',
                get_string('paynlserviceid', 'enrol_classicpay'),
                get_string('paynlserviceid_desc', 'enrol_classicpay'), ''));
        $settings->add(new admin_setting_configtext('enrol_classicpay/paynlmerchantid',
                get_string('paynlmerchantid', 'enrol_classicpay'),
                get_string('paynlmerchantid_desc', 'enrol_classicpay'), ''));
    }

}

// We shall add some navigation.
if ($hassiteconfig) {
    $node = new admin_category('classicpay', get_string('pluginname', 'enrol_classicpay'));
    $ADMIN->add('root', $node);
    $ADMIN->add('classicpay', new admin_externalpage('cpcoupons', get_string('cp:coupons', 'enrol_classicpay'),
            new moodle_url('/enrol/classicpay/admin.php', array('page' => 'cpcoupons'))));
    $ADMIN->add('classicpay', new admin_externalpage('cptransactions', get_string('cp:transactions', 'enrol_classicpay'),
            new moodle_url('/enrol/classicpay/admin.php', array('page' => 'cptransactions'))));
    $ADMIN->add('classicpay', new admin_externalpage('cpsubscriptions', get_string('cp:subscriptions', 'enrol_classicpay'),
            new moodle_url('/enrol/classicpay/admin.php', array('page' => 'cpsubscriptions'))));
    $ADMIN->add('classicpay', new admin_externalpage('cpservice', get_string('cp:paynlconnection', 'enrol_classicpay'),
            new moodle_url('/enrol/classicpay/admin.php', array('page' => 'cpservice'))));
    $ADMIN->add('classicpay', new admin_externalpage('cpapply', get_string('cp:apply', 'enrol_classicpay'),
            new moodle_url('/enrol/classicpay/spapply.php')));
    $ADMIN->add('classicpay', new admin_externalpage('cplegal', get_string('cp:legal', 'enrol_classicpay'),
            new moodle_url('/enrol/classicpay/admin.php', array('page' => 'cplegal'))));
}
