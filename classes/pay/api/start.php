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
 * Transaction start class for PAY
 *
 * File         start.php
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
 * enrol_classicpay\pay\api\start
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class start extends api {

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
    protected $action = 'start';
    /**
     * Transaction amount
     * @var float
     */
    private $amount;
    /**
     * Payment Option ID
     * @var int
     */
    private $paymentoptionid;
    /**
     * Payment Option sub ID
     * @var int
     */
    private $paymentoptionsubid;
    /**
     * Finish url (this is the url the user is sent back to after processing the payment)
     * @var string
     */
    private $finishurl;
    /**
     * Exchange url (this is the url used for asynchronous transaction updates)
     * @var string
     */
    private $exchangeurl;
    /**
     * Payment description
     * @var string
     */
    private $description;
    /**
     * End user information
     * @var array
     */
    private $enduser;
    /**
     * Extra data field #1
     * @var string
     */
    private $extra1;
    /**
     * Extra data field #2
     * @var string
     */
    private $extra2;
    /**
     * Extra data field #3
     * @var string
     */
    private $extra3;
    /**
     * Promotor ID
     * @var mixed
     */
    private $promotorid;
    /**
     * info
     * @var mixed
     */
    private $info;
    /**
     * tool
     * @var string
     */
    private $tool;
    /**
     * object
     * @var mixed
     */
    private $object;
    /**
     * Domain ID
     * @var mixed
     */
    private $domainid;
    /**
     * Transfer data
     * @var mixed
     */
    private $transferdata;

    /**
     * Product array
     * @var array
     */
    private $products = array();

    /**
     * Set promotor ID
     * @param mixed $promotorid
     */
    public function set_promotorid($promotorid) {
        $this->promotorid = $promotorid;
    }

    /**
     * Set info
     * @param mixed $info
     */
    public function set_info($info) {
        $this->info = $info;
    }

    /**
     * Set tool
     * @param string $tool
     */
    public function set_tool($tool) {
        $this->tool = $tool;
    }

    /**
     * Set object
     * @param mixed $object
     */
    public function set_object($object) {
        $this->object = $object;
    }

    /**
     * Set transferdata
     * @param string $transferdata
     */
    public function set_transferdata($transferdata) {
        $this->transferdata = $transferdata;
    }

    /**
     * Add a product to an order
     * Attention! This is purely an adminstrative option, the amount of the order is not modified.
     *
     * @param string $id
     * @param string $description
     * @param int $price
     * @param int $quantity
     * @param int $vatpercentage
     * @throws exception
     */
    public function add_product($id, $description, $price, $quantity, $vatpercentage) {
        if (!is_numeric($price)) {
            throw new exception('Price moet numeriek zijn', 1);
        }
        if (!is_numeric($quantity)) {
            throw new exception('Quantity moet numeriek zijn', 1);
        }

        $quantity = $quantity * 1;

        // Description can only be 45 chars long.
        $description = substr($description, 0, 45);

        $arrproduct = array(
            'productId' => $id,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'vatCode' => $vatpercentage,
        );
        $this->products[] = $arrproduct;
    }

    /**
     * Set the enduser data in the following format
     *
     * array(
     *  initals
     *  lastName
     *  language
     *  accessCode
     *  gender (M or F)
     *  dob (DD-MM-YYYY)
     *  phoneNumber
     *  emailAddress
     *  bankAccount
     *  iban
     *  bic
     *  sendConfirmMail
     *  confirmMailTemplate
     *  address => array(
     *      streetName
     *      streetNumber
     *      zipCode
     *      city
     *      countryCode
     *  )
     *  invoiceAddress => array(
     *      initials
     *      lastname
     *      streetName
     *      streetNumber
     *      zipCode
     *      city
     *      countryCode
     *  )
     * )
     * @param array $enduser
     */
    public function set_enduser($enduser) {
        $this->enduser = $enduser;
    }

    /**
     * Set the amount(in cents) of the transaction
     *
     * @param int $amount
     * @throws exception
     */
    public function set_amount($amount) {
        if (is_numeric($amount)) {
            $this->amount = $amount;
        } else {
            throw new exception('Amount is niet numeriek', 1);
        }
    }

    /**
     * Set payment options ID
     * @param int $paymentoptionid
     * @throws exception
     */
    public function set_paymentoptionid($paymentoptionid) {
        if (is_numeric($paymentoptionid)) {
            $this->paymentoptionid = $paymentoptionid;
        } else {
            throw new exception('PaymentOptionId is niet numeriek', 1);
        }
    }

    /**
     * Set payment options sub ID
     * @param int $paymentoptionsubid
     * @throws exception
     */
    public function set_paymentoptionsubid($paymentoptionsubid) {
        if (is_numeric($paymentoptionsubid)) {
            $this->paymentoptionsubid = $paymentoptionsubid;
        } else {
            throw new exception('PaymentOptionSubId is niet numeriek', 1);
        }
    }

    /**
     * Set the url where the user will be redirected to after payment.
     *
     * @param string $finishurl
     */
    public function set_finishurl($finishurl) {
        $this->finishurl = $finishurl;
    }

    /**
     * Set the comunication url, the pay.nl server will call this url when the status of the transaction changes
     *
     * @param string $exchangeurl
     */
    public function set_exchangeurl($exchangeurl) {
        $this->exchangeurl = $exchangeurl;
    }

    /**
     * Set Extra1 input
     *
     * @param string $extra1
     */
    public function set_extra1($extra1) {
        $this->extra1 = $extra1;
    }

    /**
     * Set Extra2 input
     *
     * @param string $extra2
     */
    public function set_extra2($extra2) {
        $this->extra2 = $extra2;
    }

    /**
     * Set Extra3 input
     *
     * @param string $extra3
     */
    public function set_extra3($extra3) {
        $this->extra3 = $extra3;
    }

    /**
     * Set domain ID
     * @param string $domainid
     */
    public function set_domainid($domainid) {
        $this->domainid = $domainid;
    }

    /**
     * Set the description for the transaction
     * @param string $description
     */
    public function set_description($description) {
        $this->description = $description;
    }

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
            throw new exception('apiToken not set', 1);
        } else {
            $data['serviceId'] = $this->serviceid;
        }
        if (empty($this->amount)) {
            throw new exception('Amount is niet geset', 1);
        } else {
            $data['amount'] = $this->amount;
        }
        if (!empty($this->paymentoptionid)) {
            $data['paymentOptionId'] = $this->paymentoptionid;
        }
        if (empty($this->finishurl)) {
            throw new exception('FinishUrl is niet geset', 1);
        } else {
            $data['finishUrl'] = $this->finishurl;
        }
        if (!empty($this->exchangeurl)) {
            $data['transaction']['orderExchangeUrl'] = $this->exchangeurl;
        }

        if (!empty($this->description)) {
            $data['transaction']['description'] = $this->description;
        }

        if (!empty($this->paymentoptionsubid)) {
            $data['paymentOptionSubId'] = $this->paymentoptionsubid;
        }

        // Set IP and browserdata; browserdata is set with dummydata.
        $data['ipAddress'] = $_SERVER['REMOTE_ADDR'];
        $data['browserData'] = array(
            'browser_name_regex' => '^mozilla/5\.0 (windows; .; windows nt 5\.1; .*rv:.*) gecko/.* firefox/0\.9.*$',
            'browser_name_pattern' => 'Mozilla/5.0 (Windows; ?; Windows NT 5.1; *rv:*) Gecko/* Firefox/0.9*',
            'parent' => 'Firefox 0.9',
            'platform' => 'WinXP',
            'browser' => 'Firefox',
            'version' => 0.9,
            'majorver' => 0,
            'minorver' => 9,
            'cssversion' => 2,
            'frames' => 1,
            'iframes' => 1,
            'tables' => 1,
            'cookies' => 1,
        );
        if (!empty($this->products)) {
            $data['saleData']['invoiceDate'] = date('d-m-Y');
            $data['saleData']['deliveryDate'] = date('d-m-Y', strtotime('+1 day'));
            $data['saleData']['orderData'] = $this->products;
        }

        if (!empty($this->enduser)) {
            $data['enduser'] = $this->enduser;
        }

        if (!empty($this->extra1)) {
            $data['statsData']['extra1'] = $this->extra1;
        }
        if (!empty($this->extra2)) {
            $data['statsData']['extra2'] = $this->extra2;
        }
        if (!empty($this->extra3)) {
            $data['statsData']['extra3'] = $this->extra3;
        }
        if (!empty($this->promotorid)) {
            $data['statsData']['promotorid'] = $this->promotorid;
        }
        if (!empty($this->info)) {
            $data['statsData']['info'] = $this->info;
        }
        if (!empty($this->tool)) {
            $data['statsData']['tool'] = $this->tool;
        }
        if (!empty($this->object)) {
            $data['statsData']['object'] = $this->object;
        }
        if (!empty($this->domainid)) {
            $data['statsData']['domain_id'] = $this->domainid;
        }
        if (!empty($this->transferdata)) {
            $data['statsData']['transferData'] = $this->transferdata;
        }

        return $data;
    }

}
