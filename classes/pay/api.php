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

namespace enrol_classicpay\pay;
use enrol_classicpay\pay\exception;
use enrol_classicpay\pay\api\exception as apiexception;

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_classicpay\pay\api
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
    protected $apiurl = 'http://rest-api.pay.nl';

    /**
     * API version for this controller
     * @var string
     */
    protected $version = 'v3';
    /**
     * Controller for this API
     * @var string
     */
    protected $controller = '';
    /**
     * API action
     * @var string
     */
    protected $action = '';
    /**
     * Service ID
     * @var string
     */
    protected $serviceid = '';
    /**
     * API token
     * @var string
     */
    protected $apitoken = '';
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
     * Set the serviceid
     * The serviceid always starts with SL- and can be found on: https://admin.pay.nl/programs/programs
     *
     * @param string $serviceid
     */
    public function set_serviceid($serviceid) {
        $this->serviceid = $serviceid;
    }

    /**
     * Set the API token
     * The API token is used to identify your company.
     * The API token can be found on: https://admin.pay.nl/my_merchant on the bottom
     *
     * @param string $apitoken
     */
    public function set_apitoken($apitoken) {
        $this->apitoken = $apitoken;
    }

    /**
     * Gather the post data
     * @return array
     */
    protected function gather_postdata() {
        return $this->postdata;
    }

    /**
     * Process the API result
     * @param array $data
     * @return array
     */
    protected function process_result($data) {
        return $data;
    }

    /**
     * Build the API url for a request
     *
     * @return string
     * @throws exception
     */
    private function get_api_url() {
        if ($this->version == '') {
            throw new exception('version not set', 1);
        }
        if ($this->controller == '') {
            throw new exception('controller not set', 1);
        }
        if ($this->action == '') {
            throw new exception('action not set', 1);
        }

        return $this->apiurl . '/' . $this->version . '/' . $this->controller . '/' . $this->action . '/json/';
    }

    /**
     * Return the post data
     *
     * @return array
     */
    public function get_postdata() {
        return $this->gather_postdata();
    }

    /**
     * Perform the API request and return results.
     *
     * @return array processed result
     * @throws exception on failure
     */
    public function do_request() {
        try {
            $this->gather_postdata();
        } catch (exception $aex) {
            throw $aex;
        }

        $url = $this->get_api_url();
        $data = $this->get_postdata();

        $strdata = http_build_query($data, '', '&');

        $apiurl = $url;

        $ch = curl_init();
        if ($this->requesttype == self::REQUEST_TYPE_GET) {
            $apiurl .= '?' . $strdata;
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $strdata);
        }

        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if ($result == false) {
            $error = curl_error($ch);
        }

        curl_close($ch);

        $arrresult = json_decode($result, true);

        if ($this->validate_result($arrresult)) {
            return $this->process_result($arrresult);
        }
    }

    /**
     * Validate API result
     *
     * @param array $arrresult the API result
     * @return bool true if success, false otherwise
     * @throws apiexception if errors occured
     */
    protected function validate_result($arrresult) {
        if ($arrresult['request']['result'] == 1) {
            return true;
        } else {
            if (isset($arrresult['request']['errorId']) && isset($arrresult['request']['errorMessage']) ) {
                throw new apiexception($arrresult['request']['errorId'] . ' - ' . $arrresult['request']['errorMessage']);
            } else if (isset($arrresult['error'])) {
                throw new apiexception($arrresult['error']);
            } else {
                throw new apiexception('Unexpected api result');
            }
        }
    }
}
