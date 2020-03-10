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
 * base API class for PAY
 *
 * File         api.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\classicpay;
use enrol_classicpay\classicpay\exception;

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_classicpay\classicpay\api
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class api {

    /**
     * Request type POST
     */
    const REQUEST_TYPE_POST = 1;
    /**
     * Request type GET
     */
    const REQUEST_TYPE_GET = 0;

    /**
     * Main base API url
     * @var string
     */
    protected $apiurl = 'https://customerpanel.sebsoft.nl/classicpay/';

    /**
     * API request type
     * @var int
     */
    protected $requesttype = self::REQUEST_TYPE_GET;
    /**
     * generated postdata for requests
     * @var string
     */
    protected $postdata = array();

    /**
     * Perform the API request and return results.
     *
     * @param string $relurl relative url
     * @param array|null $params call parameters
     * @return array processed result
     * @throws \Exception
     */
    protected function do_request($relurl, $params = null) {
        $apiurl = $this->apiurl . $relurl;

        $ch = curl_init();
        if ($params !== null) {
            $strdata = http_build_query($params, '', '&');
            if ($this->requesttype == self::REQUEST_TYPE_GET) {
                $apiurl .= '?' . $strdata;
            } else {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $strdata);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $apiurl);
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

    /**
     * return a list of valid registration languages
     *
     * @return array
     */
    public function get_languages() {
        static $languages;
        if ($languages === null) {
            $languages = array();
            $this->requesttype = self::REQUEST_TYPE_GET;
            $arrreturn = $this->do_request('languages.php');
            foreach ($arrreturn as $language) {
                if ((bool)$language->available) {
                    $languages[$language->id] = $language->name . ' | ' . $language->abbreviation;
                }
            }
        }
        return $languages;

    }

    /**
     * return a list of valid registration countries
     *
     * @return array
     */
    public function get_countries() {
        static $countries;
        if ($countries === null) {
            $countrylist = get_string_manager()->get_list_of_countries(false);
            $countries = array();
            $this->requesttype = self::REQUEST_TYPE_GET;
            $arrreturn = $this->do_request('countries.php');
            foreach ($arrreturn as $country) {
                $name = $country->name;
                if (isset($countrylist[$country->name])) {
                    $name = $countrylist[$country->name];
                }
                $countries[$country->code] = $name . ' (' . $country->code . ')';
            }
        }
        return $countries;
    }

    /**
     * return a list of valid payment profiles
     *
     * @param string $type either 'standard', 'extra' or 'all'
     * @return array
     */
    public function get_paymentprofiles($type = 'standard') {
        static $profiles;
        if ($profiles === null) {
            $profiles = array();
            $this->requesttype = self::REQUEST_TYPE_GET;
            $arrreturn = $this->do_request('profiles.php', array('type' => $type));
            foreach ($arrreturn as $profile) {
                $profiles[$profile->id] = (object)array(
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'img' => $profile->img
                );
            }
        }
        return $profiles;
    }

    /**
     * return a list of valid payment profiles
     *
     * @return array
     */
    public function get_servicepaymentprofiles() {
        $profiles = array();
        $this->requesttype = self::REQUEST_TYPE_GET;
        $params = array(
            'apitoken' => get_config('enrol_classicpay', 'paynlapitoken'),
            'svcid' => get_config('enrol_classicpay', 'paynlserviceid')
        );
        $arrreturn = $this->do_request('serviceprofiles.php', $params);
        if (isset($arrreturn->error)) {
            throw new exception(get_string('err:getserviceprofiles', 'enrol_classicpay', $arrreturn));
        }
        foreach ($arrreturn->result as $profile) {
            $profiles[$profile->id] = (object)array(
                'id' => $profile->id,
                'name' => $profile->name,
                'img' => $profile->img,
                'enabled' => $profile->enabled,
                'settings' => $profile->settings
            );
        }
        return $profiles;
    }

    /**
     * return a list of valid payment profiles
     *
     * @param array $paymentprofiletoggles key value array where key is the profileId
     *      and the value is a 0 or 1 indicating it's enabled state
     * @return array
     */
    public function set_servicepaymentprofiles($paymentprofiletoggles) {
        $this->requesttype = self::REQUEST_TYPE_GET;
        $params = array(
            'apitoken' => get_config('enrol_classicpay', 'paynlapitoken'),
            'svcid' => get_config('enrol_classicpay', 'paynlserviceid')
        );
        foreach ($paymentprofiletoggles as $id => $enabled) {
            $params["paymentprofile[{$id}]"] = $enabled;
        }

        return $this->do_request('setserviceprofiles.php', $params);
    }

    /**
     * return a list of valid payment profiles
     *
     * @return array
     */
    public function check_classicpayplus() {
        $this->requesttype = self::REQUEST_TYPE_POST;
        $params = array(
            'apitoken' => get_config('enrol_classicpay', 'paynlapitoken'),
            'svcid' => get_config('enrol_classicpay', 'paynlserviceid')
        );
        return $this->do_request('checkcpp.php', $params);
    }

    /**
     * return a list of valid payment profiles
     *
     * @param bool $enable true to enable or false to disable plus account
     * @return array
     */
    public function apply_classicpayplus($enable = true) {
        $this->requesttype = self::REQUEST_TYPE_POST;
        $params = array(
            'apitoken' => get_config('enrol_classicpay', 'paynlapitoken'),
            'svcid' => get_config('enrol_classicpay', 'paynlserviceid'),
            'enable' => ($enable ? 1 : 0)
        );
        return $this->do_request('cppapply.php', $params);
    }

    /**
     * Request to send invoice from Sebsoft service.
     *
     * @param \stdClass $transaction enrol_classicpay instance
     * @param \stdClass $user user instance
     * @param \stdClass $plugininstance enrol record instance
     * @param int $queueid optional queueid
     * @param bool $forcererun force a rerun of the invoice?
     * @return boolean
     */
    public function request_invoice($transaction, $user = null, $plugininstance = null, $queueid = null, $forcererun = false) {
        global $DB;
        if ($user === null) {
            $user = $DB->get_record('user', array('id' => $transaction->userid), '*', MUST_EXIST);
        }
        if ($plugininstance === null) {
            $plugininstance = $DB->get_record('enrol', array('id' => $transaction->instanceid), '*', MUST_EXIST);
        }
        $this->requesttype = self::REQUEST_TYPE_POST;
        $params = array(
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'fullname' => fullname($user),
            'address' => $user->address,
            'country' => $user->country,
            'txid' => $transaction->gateway_transaction_id,
            'svcid' => get_config('enrol_classicpay', 'paynlserviceid'),
            'vat' => (int)$plugininstance->customint1,
        );
        // TODO: For now, we pass details. This MUST be changed as soon as the ISP provides the product info.
        // We SHALL completely remove the 'pdata' part as soon as we CAN.
        $pdata = array();
        $course = $DB->get_record('course', array('id' => $plugininstance->courseid));
        $pdata[] = array('description' => $course->fullname, 'price' => round($plugininstance->cost * 100), 'quantity' => 1);
        $cuse = $DB->get_record('enrol_classicpay_cuse', array('classicpayid' => $transaction->id));
        if ($cuse) {
            $coupon = $DB->get_record('enrol_classicpay_coupon', array('id' => $cuse->couponid));
            if ($coupon->type === 'percentage') {
                $discount = intval((($coupon->value / 100) * $plugininstance->cost) * -100);
            } else if ($coupon->type === 'value') {
                $discount = intval($coupon->value * -100);
            }
            $pdata[] = array('description' => 'COUPON', 'price' => $discount, 'quantity' => 1);
        }
        $params['pdata'] = base64_encode(json_encode($pdata));

        // Re-run?
        if ($forcererun) {
            $params['forcererun'] = 1;
        }

        $result = $this->do_request('sendinvoice.php', $params);
        if (isset($result->status) && $result->status === 'true') {
            // DELETE queue item.
            if ((int)$queueid > 0) {
                $rs = $DB->delete_records('enrol_classicpay_ivq', array('id' => $queueid));
            }
            $transaction->hasinvoice = 1;
            $DB->update_record('enrol_classicpay', $transaction);
            return true;
        } else {
            return false;
        }
    }

}
