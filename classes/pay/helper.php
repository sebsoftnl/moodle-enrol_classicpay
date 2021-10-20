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
 * Helper implementation for PAY
 *
 * File         helper.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_classicpay\pay;

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_classicpay\pay\helper
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {

    /**
     * Bepaal de status aan de hand van het statusid.
     * Over het algemeen worden allen de statussen -90(CANCEL), 20(PENDING) en 100(PAID) gebruikt
     *
     * @param int $stateid status id as returned from PAY
     * @return string status string
     */
    public static function get_state_text($stateid) {
        switch ($stateid) {
            case -70:
            case -71:
                return 'CHARGEBACK';
            case -51:
                return 'PAID CHECKAMOUNT';
            case -80:
                return 'EXPIRED';
            case -81:
                return 'REFUND';
            case -82:
                return 'PARTIAL REFUND';
            case -90:
                return 'CANCEL';
            case 20:
            case 25:
            case 50:
                return 'PENDING';
            case 60:
                return 'OPEN';
            case 75:
            case 76:
                return 'CONFIRMED';
            case 80:
                return 'PARTIAL PAYMENT';
            case 100:
                return 'PAID';
            default:
                if ($stateid < 0) {
                    return 'CANCEL';
                } else {
                    return 'UNKNOWN';
                }
        }
    }

    /**
     * remove all empty nodes in an array
     *
     * @param array $array input
     * @return array filtered array
     */
    public static function filter_array_recursive($array) {
        $newarray = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::filter_array_recursive($value);
            }
            if (!empty($value)) {
                $newarray[$key] = $value;
            }
        }
        return $newarray;
    }

    /**
     * Find out if the connection is secure
     *
     * @return bool true if secure, false otherwise
     */
    public static function is_secure() {
        $issecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $issecure = true;
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ||
                !empty($_SERVER['HTTP_X_FORWARDED_SSL']) &&
                $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $issecure = true;
        }
        return $issecure;
    }

    /**
     * Get URI
     *
     * @return string
     */
    public static function get_uri() {
        if (self::is_secure()) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }

        $uri .= $_SERVER['SERVER_NAME'];

        if (!empty($_SERVER['REQUEST_URI'])) {
            $uri .= $_SERVER['REQUEST_URI'];
            $uridir = $uri;
            if (substr($uri, -4) == '.php') {
                $uridir = dirname($uri);
            }

            if ($uridir != 'http:' && $uridir != 'https:') {
                $uri = $uridir;
            }
        }

        return $uri . '/';
    }

    /**
     * Sort paymentoptions by name
     *
     * @param array $paymentoptions paymentoptions
     * @return array sorted input
     */
    public static function sort_payment_options($paymentoptions) {
        uasort($paymentoptions, 'sort_payment_options');
        return $paymentoptions;
    }
}

/**
 * Binary safe string comparison
 * @link http://php.net/manual/en/function.strcmp.php
 * @param string $a first string
 * @param string $b second string
 * @return int &lt; 0 if <i>str1</i> is less than
 * <i>str2</i>; &gt; 0 if <i>str1</i>
 * is greater than <i>str2</i>, and 0 if they are
 * equal.
 */
function sort_payment_options($a, $b) {
    return strcmp($a['name'], $b['name']);
}
