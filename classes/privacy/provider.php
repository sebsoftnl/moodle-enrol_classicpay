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
 * Privacy provider for ClassicPay.
 *
 * @package     enrol_classicpay\privacy
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;

/**
 * Class provider
 *
 * @package enrol_classicpay\privacy
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider {
    /**
     * Provides a collection of stored metadata about a user
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
                'enrol_classicpay',
                [
                        'userid' => 'privacy:metadata:enrol_classicpay:userid',
                        'courseid' => 'privacy:metadata:enrol_classicpay:courseid',
                        'instanceid' => 'privacy:metadata:enrol_classicpay:instanceid',
                        'orderid' => 'privacy:metadata:enrol_classicpay:orderid',
                        'status' => 'privacy:metadata:enrol_classicpay:status',
                        'statusname' => 'privacy:metadata:enrol_classicpay:statusname',
                        'gateway_transaction_id' => 'privacy:metadata:enrol_classicpay:gateway_transaction_id',
                        'gateway' => 'privacy:metadata:enrol_classicpay:gateway',
                        'rawcost' => 'privacy:metadata:enrol_classicpay:rawcost',
                        'cost' => 'privacy:metadata:enrol_classicpay:cost',
                        'percentage' => 'privacy:metadata:enrol_classicpay:percentage',
                        'discount' => 'privacy:metadata:enrol_classicpay:discount',
                        'hasinvoice' => 'privacy:metadata:enrol_classicpay:hasinvoice',
                        'timecreated' => 'privacy:metadata:enrol_classicpay:timecreated',
                        'timemodified' => 'privacy:metadata:enrol_classicpay:timemodified'
                ]
        );
        return $collection;
    }
}