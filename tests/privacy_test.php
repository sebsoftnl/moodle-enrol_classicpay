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
 * Tests for privacy provider.
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../classes/privacy/provider.php');

use core_privacy\local\metadata\collection;
use core_privacy\tests\provider_testcase;
use enrol_classicpay\privacy\provider;

/**
 * Class enrol_classicpay_privacy_provider_testcase
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_classicpay_privacy_provider_testcase extends provider_testcase {
    public function test_it_returns_a_collection_of_metadata() {
        $collection = provider::get_metadata(new collection('enrol_classicpay'));
        $itemcollection = $collection->get_collection();
        $this->assertCount(1, $itemcollection);

        $item = reset($itemcollection);
        $this->assertEquals('enrol_classicpay', $item->get_name());

        $privacyfields = $item->get_privacy_fields();
        $this->assertCount(15, $privacyfields);
        $this->assertArrayHasKey('userid', $privacyfields);
        $this->assertArrayHasKey('courseid', $privacyfields);
        $this->assertArrayHasKey('instanceid', $privacyfields);
        $this->assertArrayHasKey('orderid', $privacyfields);
        $this->assertArrayHasKey('status', $privacyfields);
        $this->assertArrayHasKey('statusname', $privacyfields);
        $this->assertArrayHasKey('gateway_transaction_id', $privacyfields);
        $this->assertArrayHasKey('gateway', $privacyfields);
        $this->assertArrayHasKey('rawcost', $privacyfields);
        $this->assertArrayHasKey('cost', $privacyfields);
        $this->assertArrayHasKey('percentage', $privacyfields);
        $this->assertArrayHasKey('discount', $privacyfields);
        $this->assertArrayHasKey('hasinvoice', $privacyfields);
        $this->assertArrayHasKey('timecreated', $privacyfields);
        $this->assertArrayHasKey('timemodified', $privacyfields);
    }

    public function test_get_users_in_context() {
        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_page');
        $generator->create_instance(array('course'=>$course->id));
        $data = $this->getDataGenerator()->enrol_user($user->id, $course->id);

        $context = \context_user::instance($user->id);
        var_dump($context);

        $contextlist = provider::get_contexts_for_userid($user->id);
        var_dump($contextlist);
        $this->resetAfterTest(true);
    }
}