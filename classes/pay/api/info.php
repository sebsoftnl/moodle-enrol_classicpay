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
 * Info class for PAY
 *
 * File         info.php
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
use enrol_classicpay\pay\api\exception;

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_classicpay\pay\api\info
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class info extends api {

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
    protected $action = 'info';

    /**
     * Set transaction ID
     *
     * @param string $transactionid
     */
    public function set_transactionid($transactionid) {
        $this->postdata['transactionId'] = $transactionid;
    }

    /**
     * Gather the POST data
     *
     * @return array result data
     * @throws exception on errors
     */
    protected function gather_postdata() {
        $data = parent::gather_postdata();
        if ($this->apitoken == '') {
            throw new exception('apiToken not set', 1);
        } else {
            $data['token'] = $this->apitoken;
        }
        if (!isset($this->postdata['transactionId'])) {
            throw new exception('transactionId is not set', 1);
        }
        return $data;
    }
}
