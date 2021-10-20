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
 * Upgrade script for enrol_classicpay
 *
 * File         upgrade.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade
 *
 * @param int $oldversion old (current) plugin version
 * @return boolean
 */
function xmldb_enrol_classicpay_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2015060102) {
        $table = new xmldb_table('enrol_classicpay_coupon');
        // First, rename old 'percentage' field.
        $xmldbfield = new xmldb_field('percentage', XMLDB_TYPE_NUMBER, '8,5', null, XMLDB_NOTNULL, null, null, 'code');
        $dbman->rename_field($table, $xmldbfield, 'value');

        // Now, add type field.
        $xmldbfield = new xmldb_field('type', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'percentage', 'code');
        $dbman->add_field($table, $xmldbfield);

        upgrade_plugin_savepoint(true, 2015060102, 'enrol', 'classicpay');
    }

    if ($oldversion < 2015060104) {
        // Set customint2 to 1, since we created a setting for enabling entering couponcodes.
        $DB->execute("UPDATE {enrol} SET customint2 = ? WHERE enrol = ?", array(1, 'classicpay'));
        upgrade_plugin_savepoint(true, 2015060104, 'enrol', 'classicpay');
    }

    return true;
}
