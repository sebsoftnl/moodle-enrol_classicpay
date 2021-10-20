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
 * this file contains the coupon table class.
 *
 * File         couponusage.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\tables;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

/**
 * enrol_classicpay\tables\couponusage
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class couponusage extends \table_sql {

    /**
     * Coupon id
     *
     * @var int
     */
    protected $couponid;

    /**
     * Create a new instance of the statustable
     *
     * @param int $couponid coupon id
     */
    public function __construct($couponid) {
        global $USER;
        parent::__construct(__CLASS__ . '-' . $USER->id . '-' . $couponid);
        $this->couponid = $couponid;
        $this->sortable(true, 'timeceated');
        $this->request  = array(
                TABLE_VAR_SORT   => 'tsort',
                TABLE_VAR_HIDE   => 'thide',
                TABLE_VAR_SHOW   => 'tshow',
                TABLE_VAR_IFIRST => 'tifirst',
                TABLE_VAR_ILAST  => 'tilast',
                TABLE_VAR_PAGE   => 'tpage',
                TABLE_VAR_RESET  => 'treset',
                TABLE_VAR_DIR    => 'tdir',
        );
    }

    /**
     *
     * Set the sql to query the db.
     * This method is disabled for this class, since we use internal queries
     *
     * @param string $fields
     * @param string $from
     * @param string $where
     * @param array $params
     * @throws exception
     */
    public function set_sql($fields, $from, $where, array $params = null) {
        // We'll disable this method.
        throw new exception('err:statustable:set_sql');
    }

    /**
     * Display the general suspension status table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    public function render($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(array('courseid', 'username', 'code', 'type', 'value', 'statusname',
            'timecreated', 'numused', 'maxusage'));
        $this->define_headers(array(
            get_string('th:courseid', 'enrol_classicpay'),
            get_string('th:user', 'enrol_classicpay'),
            get_string('th:code', 'enrol_classicpay'),
            get_string('th:type', 'enrol_classicpay'),
            get_string('th:value', 'enrol_classicpay'),
            get_string('th:status', 'enrol_classicpay'),
            get_string('th:paymentcreated', 'enrol_classicpay'),
            get_string('th:numused', 'enrol_classicpay'),
            get_string('th:maxusage', 'enrol_classicpay'),
            get_string('th:action', 'enrol_classicpay'))
        );

        $fields = "cu.id, c.id as couponid, c.courseid, c.code, c.type, c.value, c.validfrom, "
                . "c.validto, c.maxusage, c.numused, cp.timecreated, cp.timemodified, "
                . "cp.userid, cp.status, cp.statusname, " . $DB->sql_fullname() . " AS username";
        $from = "{enrol_classicpay_coupon} c
                        JOIN {enrol_classicpay_cuse} cu ON cu.couponid=c.id
                        JOIN {enrol_classicpay} cp ON cu.classicpayid=cp.id
                        JOIN {user} u ON cp.userid=u.id";
        $where = array('c.id = ?');
        $params = array($this->couponid);

        parent::set_sql($fields, $from, implode(' AND ', $where), $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Take the data returned from the db_query and go through all the rows
     * processing each col using either col_{columnname} method or other_cols
     * method or if other_cols returns NULL then put the data straight into the
     * table.
     */
    public function build_table() {
        if ($this->rawdata) {
            foreach ($this->rawdata as $row) {
                $formattedrow = $this->format_row($row);
                $this->add_data_keyed($formattedrow, $this->get_row_class($row));
            }
        }
    }

    /**
     * Render visual representation of the 'courseid' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_courseid($row) {
        global $DB;
        if ($row->courseid == 0) {
            return get_string('entiresite', 'enrol_classicpay');
        } else {
            $fullname = $DB->get_field('course', 'fullname', array('id' => $row->courseid));
            $url = new \moodle_url('/course/view.php', array('id' => $row->courseid));
            return '<a href="' . $url . '">' . $fullname . '</a>';
        }
    }

    /**
     * Render visual representation of the 'type' column for use in the table
     *
     * @param \stdClass $row
     * @return localised type string
     */
    public function col_type($row) {
        return get_string('coupontype:' . $row->type, 'enrol_classicpay');
    }

    /**
     * Render visual representation of the 'value' column for use in the table
     *
     * @param \stdClass $row
     * @return formatted value
     */
    public function col_value($row) {
        $rs = number_format($row->value, 2);
        if ($row->type === 'percentage') {
            $rs .= ' %';
        }
        return $rs;
    }

    /**
     * Render visual representation of the 'timecreated' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_timecreated($row) {
        return userdate($row->timecreated);
    }

    /**
     * Render visual representation of the 'username' column for use in the table
     *
     * @param \stdClass $row
     * @return string
     */
    public function col_username($row) {
        $url = new \moodle_url('/user/profile.php', array('id' => $row->userid));
        return '<a href="' . $url . '">' . $row->username . '</a>';
    }

    /**
     * Return the image tag representing an action image
     *
     * @param string $action
     * @return string HTML image tag
     */
    protected function get_action_image($action) {
        global $OUTPUT;
        return '<img src="' . $OUTPUT->image_url($action, 'enrol_classicpay') .
                '" title="' . get_string('coupon:' . $action, 'enrol_classicpay') . '" class="icon"/>';
    }

    /**
     * Return a string containing the link to an action
     *
     * @param \stdClass $row
     * @param string $action
     * @return string link representing the action with an image
     */
    protected function get_action($row, $action) {
        return '<a href="' . new \moodle_url($this->baseurl, array('action' => $action, 'id' => $row->id,
            'sesskey' => sesskey(), 'type' => $this->displaytype)) .
                '" alt="' . get_string('coupon:' . $action, 'enrol_classicpay') .
                '">' . $this->get_action_image($action) . '</a>';
    }

}
