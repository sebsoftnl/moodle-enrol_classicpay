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
require_once(__DIR__ . '/helper_functions.php');

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\writer;
use core_privacy\tests\provider_testcase;
use enrol_classicpay\privacy\provider;

/**
 * Class enrol_classicpay_privacy_provider_testcase
 *
 * @copyright   Sebsoft.nl
 * @author      Nick Stolk <nick@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_classicpay_privacy_provider_testcase extends provider_testcase {

    public function setUp() {
        $this->resetAfterTest(true);
    }

    /** @test */
    public function it_returns_a_collection_of_metadata() {
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

    /** @test */
    public function it_get_contexts_for_the_user() {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $userrole = $DB->get_record('role', array('shortname' => 'coursecreator'));
        $this->getDataGenerator()->enrol_user($user->id, $course->id, $userrole->id);
        $this->assertEmpty(provider::get_contexts_for_userid($user->id));

        $context = context_course::instance($course->id);

        $record = helper_functions::generate_classicpay_record($user->id, $course->id, $context);
        $record->id = $DB->insert_record('enrol_classicpay', $record);

        // Get the context id from the context table, join on course.id, then join on enrol_classicpay.course_id and where clause for user_id
        $contextlist = provider::get_contexts_for_userid($user->id);
        $this->assertCount(1, $contextlist);
        $this->assertEquals($context->id, $contextlist->get_contextids()[0]);

        // Create another course and enrol the first user
        $course2 = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($user->id, $course2->id, $userrole->id);

        $context2 = context_course::instance($course2->id);

        $record2 = helper_functions::generate_classicpay_record($user->id, $course2->id, $context2);
        $record2->id = $DB->insert_record('enrol_classicpay', $record2);

        $newContextlist = provider::get_contexts_for_userid($user->id);
        $this->assertCount(2, $newContextlist);
        $this->assertEquals($context2->id, $newContextlist->get_contextids()[1]);

        // Generating a new user and enrolling the second user in the first course
        $user2 = $this->getDataGenerator()->create_user();

        $this->getDataGenerator()->enrol_user($user2->id, $course->id, $userrole->id);
        $record3 = helper_functions::generate_classicpay_record($user2->id, $course->id, $context);
        $record3->id = $DB->insert_record('enrol_classicpay', $record3);

        $contextlistForUser2 = provider::get_contexts_for_userid($user2->id);
        $this->assertCount(1, $contextlistForUser2);
        $this->assertEquals($context->id, $contextlistForUser2->get_contextids()[0]);

        $contextlistForUser1 = provider::get_contexts_for_userid($user->id);
        $this->assertCount(2, $contextlistForUser1);
    }

    /** @test */
    public function it_exports_user_data() {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $userrole = $DB->get_record('role', array('shortname' => 'coursecreator'));

        $course1 = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($user->id, $course1->id, $userrole->id);

        $course2 = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($user->id, $course2->id, $userrole->id);

        $context1 = context_course::instance($course1->id);
        $context2 = context_course::instance($course2->id);

        $record1 = helper_functions::generate_classicpay_record($user->id, $course1->id, $context1);
        $record1->id = $DB->insert_record('enrol_classicpay', $record1);

        $record2 = helper_functions::generate_classicpay_record($user->id, $course2->id, $context2);
        $record2->id = $DB->insert_record('enrol_classicpay', $record2);

        $usercontext = context_user::instance($user->id);
        $writer = writer::with_context($usercontext);
        $this->assertFalse($writer->has_any_data());

        $approvedlist = new approved_contextlist($user, 'enrol_classicpay', [$context1->id, $context2->id]);
        provider::export_user_data($approvedlist);
        $this->assertTrue($writer->has_any_data());
    }
}