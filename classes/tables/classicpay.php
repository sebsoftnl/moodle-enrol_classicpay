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
 * File         classicpay.php
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
 * enrol_classicpay\tables\classicpay
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class classicpay extends \table_sql {

    /**
     * table type identifier for all classicpay
     */
    const ALL = 'all';

    /**
     * table type identifier paid classicpay
     */
    const PAID = 'paid';

    /**
     * Course id
     *
     * @var int
     */
    protected $courseid;

    /**
     * internal display type
     *
     * @var string
     */
    protected $displaytype;

    /**
     * Are we configured to be a classicpay plus account?
     * @var bool
     */
    protected $isclassicpayplus;

    /**
     * Create a new instance of the statustable
     *
     * @param int $courseid course id
     * @param string $type table render type
     */
    public function __construct($courseid = 0, $type = 'all') {
        global $USER;
        parent::__construct(__CLASS__ . '-' . $USER->id . '-' . $courseid . '-' . $type);
        $this->courseid = $courseid;
        $this->displaytype = $type;
        $this->sortable(true, 'timemodified', 'DESC');
        $this->no_sorting('action');
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
        try {
            $api = new \enrol_classicpay\classicpay\api();
            $result = $api->check_classicpayplus();
            $this->isclassicpayplus = (bool)$result->result;
        } catch (\Exception $ex) {
            $this->isclassicpayplus = false;
        }
    }

    /**
     * Return a list of applicable viewtypes for this table
     *
     * @return array list of view types
     */
    public static function get_viewtypes() {
        return array(
            self::ALL,
            self::PAID,
        );
    }

    /**
     * Return a list of HTML links for viewtypes of this table.
     *
     * @param \moodle_url $url base url for the page
     * @return array list of view types
     */
    public static function get_viewtype_menu($url) {
        $rs = array();
        foreach (self::get_viewtypes() as $type) {
            $murl = clone $url;
            $murl->param('listtype', $type);
            $rs[] = '<a href="' . $murl->out() . '">' . get_string('classicpay:filter:' . $type, 'enrol_classicpay') . '</a>';
        }
        return $rs;
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
        switch ($this->displaytype) {
            case self::PAID:
                $this->render_all($pagesize, $useinitialsbar);
                break;
            case self::ALL:
            default:
                $this->render_all($pagesize);
                break;
        }
    }

    /**
     * Display the general suspension status table for users that haven't
     * been excluded
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_all($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(array('coursefullname', 'user', 'statusname', 'rawcost',
            'cost', 'discount', 'percentage', 'gateway_transaction_id', 'timecreated', 'timemodified', 'action'));
        $this->define_headers(array(
            get_string('th:courseid', 'enrol_classicpay'),
            get_string('th:user', 'enrol_classicpay'),
            get_string('th:status', 'enrol_classicpay'),
            get_string('th:rawcost', 'enrol_classicpay'),
            get_string('th:cost', 'enrol_classicpay'),
            get_string('th:discount', 'enrol_classicpay'),
            get_string('th:percentage', 'enrol_classicpay'),
            get_string('th:txid', 'enrol_classicpay'),
            get_string('th:paymentcreated', 'enrol_classicpay'),
            get_string('th:paymentmodified', 'enrol_classicpay'),
            get_string('th:action', 'enrol_classicpay'),
        ));
        $fields = 'cp.*, c.fullname as coursefullname, '.$DB->sql_fullname().' as user, NULL AS action';
        $where = array();
        $params = array();
        if ($this->courseid > 0) {
            $where[] = 'cp.courseid = ?';
            $params[] = $this->courseid;
        }

        switch ($this->displaytype) {
            case self::PAID:
                $where[] = 'cp.status = ?';
                $params[] = 100;
                break;
            case self::ALL:
            default:
                break;
        }

        if (empty($where)) {
            $where[] = '1 = ?';
            $params[] = 1;
        }
        $from = '{enrol_classicpay} cp LEFT JOIN {course} c ON cp.courseid=c.id LEFT JOIN {user} u ON cp.userid=u.id';
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
            return $DB->get_field('course', 'fullname', array('id' => $row->courseid));
        }
    }

    /**
     * Render visual representation of the 'timemodified' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_timemodified($row) {
        return userdate($row->timemodified);
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
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        $actions = array();
        if ($this->isclassicpayplus && (int)$row->status === 100 &&
                (int)$row->hasinvoice === 1 && $this->displaytype === self::ALL) {
            $actions[] = $this->get_action($row, 'invoice');
        }
        return implode(' ', $actions);
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
                '" title="' . get_string('coupon:' . $action, 'enrol_classicpay') . '"/>';
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
