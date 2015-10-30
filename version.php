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
 * Version information for enrol_classicpay
 *
 * File         version.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
defined('MOODLE_INTERNAL') || die();
$plugin->version     = 2015060104;
$plugin->requires    = 2014051200;      // YYYYMMDDHH (This is the release version for Moodle 2.7).
$plugin->cron        = 60;
$plugin->component   = 'enrol_classicpay';
$plugin->maturity    = MATURITY_STABLE;
$plugin->release     = '2.7.0 (build 2015060104)';
$plugin->dependencies = array();