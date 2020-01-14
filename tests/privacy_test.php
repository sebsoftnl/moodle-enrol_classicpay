<?php

namespace enrol_classicpay\tests;

require_once(__DIR__ . '/../classes/privacy/provider.php');

use advanced_testcase;
use core_privacy\local\metadata\collection;
use enrol_classicpay\privacy\provider;

class enrol_classicpay_privacy_testcase extends advanced_testcase {
    public function test_it_has_a_collection_of_metadata() {
        $data = provider::get_metadata(new collection('enrol_classicpay'));
        $this->assertObjectHasAttribute('collection', $data);
    }

    public function test_a_user_can_see_their_personal_data() {
        $user = $this->getDataGenerator()->create_user();
        $this->resetAfterTest(true);
    }
}