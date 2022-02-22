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
 * Privacy provider.
 *
 * File         provider.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\privacy;

defined('MOODLE_INTERNAL') || die;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;
use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_userlist;

/**
 * Privacy provider.
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider,
        \core_privacy\local\request\core_userlist_provider {

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
                ],
                'privacy:metadata:enrol_classicpay'
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

        $user = $contextlist->get_user();
        foreach ($contextlist->get_contexts() as $context) {
            // Check that the context is a system context.
            if ($context->contextlevel != CONTEXT_COURSE) {
                continue;
            }

            // Add contextual data for given user.
            $alldata = [$context->id => []];
            $alluserdata = $DB->get_recordset_sql(
                    "SELECT * FROM {enrol_classicpay} cp WHERE cp.userid = :userid",
                    array('userid' => $user->id)
            );
            foreach ($alluserdata as $userdata) {
                $alldata[$context->id][] = (object) [
                    'userid' => $userdata->userid,
                    'orderid' => $userdata->orderid,
                    'status' => $userdata->status,
                    'statusname' => $userdata->statusname,
                    'gateway' => $userdata->gateway,
                    'rawcost' => $userdata->rawcost,
                    'cost' => $userdata->cost,
                    'percentage' => $userdata->percentage,
                    'discount' => $userdata->discount,
                    'hasinvoice' => transform::yesno($userdata->hasinvoice),
                    'timecreated' => transform::datetime($userdata->timecreated),
                    'timemodified' => transform::datetime($userdata->timemodified)
                ];
            }
            $alluserdata->close();

            // The data is organised in: {?}/transactiondata.json.
            array_walk($alldata, function($transactions, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['enrol_classicpay'],
                    'transactiondata',
                    (object)['transactions' => $transactions]
                );
            });

        }
    }

    /**
     * Delete all use data which matches the specified context.
     *
     * @param context $context The module context.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context->contextlevel != CONTEXT_COURSE) {
            return;
        }

        // Delete all coupon related records.
        $DB->delete_records('classicpay', ['course' => $context->instanceid]);
        $DB->execute("DELETE FROM {enrol_classicpay_ivq} WHERE classicpayid NOT IN (SELECT id FROM {enrol_classicpay})");
        $DB->execute("DELETE FROM {enrol_classicpay_cuse} WHERE classicpayid NOT IN (SELECT id FROM {enrol_classicpay})");

    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();
        foreach ($contextlist->get_contexts() as $context) {
            // Check that the context is a system context.
            if ($context->contextlevel != CONTEXT_COURSE) {
                continue;
            }
            $DB->delete_records_select('enrol_classicpay', 'courseid = :courseid AND userid = :userid',
                    ['courseid' => $context->instanceid, 'userid' => $user->id]);
            $DB->execute("DELETE FROM {enrol_classicpay_ivq} WHERE classicpayid NOT IN (SELECT id FROM {enrol_classicpay})");
            $DB->execute("DELETE FROM {enrol_classicpay_cuse} WHERE classicpayid NOT IN (SELECT id FROM {enrol_classicpay})");
        }

    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        if (!$context instanceof \context_course) {
            return;
        }
        $sql = 'SELECT DISTINCT userid FROM {enrol_classicpay} WHERE course = ?';
        $params = [$context->instanceid];
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param  approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        if (!$context instanceof \context_course) {
            return;
        }

        $userids = $userlist->get_userids();
        list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $userparams['course'] = $context->instanceid;
        $DB->delete_records_select('enrol_classicpay', 'course = :course AND userid '.$usersql, $userparams);
        $DB->execute("DELETE FROM {enrol_classicpay_ivq} WHERE classicpayid NOT IN (SELECT id FROM {enrol_classicpay})");
        $DB->execute("DELETE FROM {enrol_classicpay_cuse} WHERE classicpayid NOT IN (SELECT id FROM {enrol_classicpay})");
    }

}
