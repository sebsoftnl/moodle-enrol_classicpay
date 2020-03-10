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

use context_user;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;

/**
 * Class provider
 *
 * @package     enrol_classicpay\privacy
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider {
    /**
     * Provides a collection of stored metadata about a user
     *
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

    /**
     * Get the lists of contexts that contain user information for the specified user.
     *
     * @param int $userid
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        $sql = "SELECT context.id
                FROM {context} context
                JOIN {course} course ON course.id = context.instanceid AND context.contextlevel = :courselevel
                JOIN {enrol_classicpay} cp ON cp.courseid = course.id
                WHERE cp.userid = :userid";

        $parameters = [
                'userid' => $userid,
                'courselevel' => CONTEXT_COURSE
        ];

        $contextlist->add_from_sql($sql, $parameters);

        return $contextlist;
    }

    /**
     * Export all user data for the specified user.
     *
     * @param approved_contextlist $contextlist
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $userid = $contextlist->get_user()->id;
        $transactiondata = [];
        $alluserdata = $DB->get_records_sql(
                "SELECT cp.id, cp.userid, cp.orderid, cp.status, cp.statusname, cp.gateway, cp.rawcost, cp.cost, cp.percentage,
                            cp.discount, cp.hasinvoice, cp.timecreated, cp.timemodified
                        FROM {enrol_classicpay} cp
                        WHERE cp.userid = :userid",
                array('userid' => $userid)
        );

        foreach ($alluserdata as $userdata) {
            $transactiondata[] = (object) [
                    'userid' => $userdata->userid,
                    'orderid' => $userdata->orderid,
                    'status' => $userdata->status,
                    'statusname' => $userdata->statusname,
                    'gateway' => $userdata->gateway,
                    'rawcost' => $userdata->rawcost,
                    'cost' => $userdata->cost,
                    'percentage' => $userdata->percentage,
                    'discount' => $userdata->discount,
                    'hasinvoice' => $userdata->hasinvoice,
                    'timecreated' => transform::datetime($userdata->timecreated),
                    'timemodified' => transform::datetime($userdata->timemodified)
            ];
        }

        $data = (object) [
                'enrol_classicpay' => $transactiondata
        ];

        writer::with_context(context_user::instance($userid))->export_data([
                get_string('pluginname', 'enrol_classicpay')
        ], $data);
    }
}