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
 * GetService class for PAY
 *
 * File         getservice.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\pay\api;
use enrol_classicpay\pay\api;
use enrol_classicpay\pay\exception;

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_classicpay\pay\api\getservice
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class getservice extends api {

    /**
     * API version for this controller
     * @var string
     */
    protected $version = 'v3';
    /**
     * Controller for this API
     * @var string
     */
    protected $controller = 'transaction';
    /**
     * API action
     * @var string
     */
    protected $action = 'getService';

    /**
     * Gather the POST data
     *
     * @return array result data
     * @throws exception on errors
     */
    protected function gather_postdata() {
        $data = parent::gather_postdata();

        // Check all mandatory fields.
        if ($this->apitoken == '') {
            throw new exception('apiToken not set', 1);
        } else {
            $data['token'] = $this->apitoken;
        }
        if (empty($this->serviceid)) {
            throw new exception('serviceId not set', 1);
        } else {
            $data['serviceId'] = $this->serviceid;
        }
        return $data;
    }

    /**
     * process API result into something more usable.
     *
     * @param array $arrreturn API result
     * @return array usable result information
     */
    protected function process_result($arrreturn) {
        if (!$arrreturn['request']['result']) {
            return $arrreturn;
        }

        $arrreturn['paymentOptions'] = array();

        $countryoptionlist = $arrreturn['countryOptionList'];
        unset($arrreturn['countryOptionList']);
        if (isset($countryoptionlist) && is_array($countryoptionlist)) {
            foreach ($countryoptionlist as $strcountrcode => $arrcountry) {
                foreach ($arrcountry['paymentOptionList'] as $arrpaymentprofile) {

                    if (!isset($arrreturn['paymentOptions'][$arrpaymentprofile['id']])) {
                        $arrreturn['paymentOptions'][$arrpaymentprofile['id']] = array(
                            'id' => $arrpaymentprofile['id'],
                            'name' => $arrpaymentprofile['name'],
                            'visibleName' => $arrpaymentprofile['name'],
                            'img' => $arrpaymentprofile['img'],
                            'path' => $arrreturn['service']['basePath'] . $arrpaymentprofile['path'],
                            'paymentOptionSubList' => array(),
                            'countries' => array(),
                        );
                    }

                    if (!empty($arrpaymentprofile['paymentOptionSubList'])) {
                        $arrreturn['paymentOptions'][$arrpaymentprofile['id']]['paymentOptionSubList'] =
                                $arrpaymentprofile['paymentOptionSubList'];
                    }

                    $arrreturn['paymentOptions'][$arrpaymentprofile['id']]['countries'][$strcountrcode] = array(
                        'id' => $strcountrcode,
                        'name' => $arrcountry['visibleName'],
                    );
                }
            }
        }
        return $arrreturn;
    }

}
