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
 * Language file for enrol_classicpay, EN
 *
 * File         enrol_classicpay.php
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'ClassicPay';
$string['pluginname_desc'] = 'This plugin allows you to purchase a course with the PAYNL gateway';
$string['promo'] = 'ClassicPay enrolment plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
(<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
{$a}<br /><br />';
$string['mailadmins'] = 'Notify admin';
$string['nocost'] = 'There is no cost associated with enrolling in this course!';
$string['currency'] = 'Currency';
$string['cost'] = 'Enrol cost';
$string['vat'] = 'VAT';
$string['vat_help'] = 'VAT percentage of course cost (note: course cost is including VAT).';
$string['assignrole'] = 'Assign role';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['expiredaction'] = 'Enrolment expiration action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['status'] = 'Allow ClassicPay enrolments';
$string['status_desc'] = 'Allow users to use ClassicPay to enrol into a course by default.';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during ClassicPay enrolments';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['title:returnpage'] = 'Payment Status';
$string['enabled'] = 'Enabled';
$string['enabled_desc'] = 'Status of the gateway if this can be used to create a transaction';
$string['expiredaction'] = 'Enrolment expiration action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['expirymessageenrollersubject'] = 'Enrolment expiry notification';
$string['expirymessageenrollerbody'] = 'Enrolment in the course \'{$a->course}\' will expire within the next {$a->threshold} for the following users:

{$a->users}

To extend their enrolment, go to {$a->extendurl}';
$string['expirymessageenrolledsubject'] = 'Enrolment expiry notification';
$string['expirymessageenrolledbody'] = 'Dear {$a->user},

This is a notification that your enrolment in the course \'{$a->course}\' is due to expire on {$a->timeend}.

If you need help, please contact {$a->enroller}.';
$string['settings'] = 'Settings';
$string['purchase'] = 'Purchase course';
$string['name'] = 'Name';
$string['minimum'] = 'Minimum';
$string['maximum'] = 'Maximum';
$string['button:pay'] = 'Pay';
$string['enrol:fail'] = 'You have not been enrolled to this course.';
$string['enrol:fail:tx'] = 'Your transaction status is: {$a->statusname}.';
$string['enrol:ok'] = 'Thanks for your purchase.<br> You have now been enrolled for course: {$a->fullname}';
$string['enrol:already'] = 'You have already been enrolled for course: {$a->fullname}';
$string['payment:cancelled'] = 'You have cancelled your payment for course: {$a->fullname}';

$string['title:cancelpage'] = 'Payment cancelled';
$string['title:transactions'] = 'Clasicpay - transactions';
$string['title:enrolments'] = 'Classicpay - enrolments';
$string['title:service'] = 'Classicpay - account information';
$string['title:legal'] = 'Classicpay - liability information';
$string['title:couponmanager'] = 'Coupon management';
$string['title:couponmanager:delete'] = 'Coupon manager - removal';
$string['title:couponmanager:edit'] = 'Coupon manager - edit';
$string['title:couponmanager:details'] = 'Coupon manager details';
$string['paynlsettings'] = 'PAYNL Account merchant settings';
$string['paynlsettings_desc'] = 'Below you will need to set the PAYNL merchant settings that wil enable you to initialize and utilize payments.';
$string['paynlapitoken'] = 'PAYNL API token';
$string['paynlapitoken_desc'] = 'The token is needed for communicating with the PAYNL API';
$string['paynlserviceid'] = 'PAYNL Service ID';
$string['paynlserviceid_desc'] = 'The Service ID is needed to identify the service on the PAYNL API';
$string['paynlmerchantid'] = 'PAYNL Merchant ID';
$string['paynlmerchantid_desc'] = 'The Merchant ID is needed to identify the merchant on the PAYNL API';
$string['th:courseid'] = 'Course';
$string['th:code'] = 'Code';
$string['th:discount'] = 'Discount';
$string['th:percentage'] = 'Percentage';
$string['th:validfrom'] = 'Valid from';
$string['th:validto'] = 'Valid to';
$string['th:numused'] = '#Used';
$string['th:maxusage'] = 'Max usage';
$string['th:txid'] = 'Transaction ID';
$string['th:action'] = 'Action(s)';
$string['th:status'] = 'Status';
$string['th:user'] = 'User';
$string['th:paymentcreated'] = 'Transaction started';
$string['th:paymentmodified'] = 'Last updated';
$string['th:cost'] = 'Cost';
$string['th:rawcost'] = 'Course Price';
$string['title:couponedit'] = 'ClassicPay - Edit coupon';
$string['title:transactions'] = 'ClassicPay - Transactions';
$string['coupon:delete'] = 'Delete Coupon';
$string['coupon:delete:warn'] = '<p>You are about to remove a coupon with the following details.</p>
<p>Course: <i>{$a->course}</i><br/>Couponcode: <i>{$a->code}</i><br/>Validity: <i>{$a->validfrom} - {$a->validto}</i></p>
<p>Are you sure you want to do this?</p>';
$string['coupon:edit'] = 'Edit Coupon';
$string['coupon:saved'] = 'Coupon successfully inserted';
$string['coupon:updated'] = 'Coupon data successfully updated';
$string['coupon:new'] = 'Add a new coupon';
$string['coupon:edit'] = 'Edit existing coupon';
$string['coupon:invalid'] = 'Invalid coupon code';
$string['coupon:expired'] = 'Coupon code has expired';
$string['coupon:deleted'] = 'Coupon successfully deleted';
$string['coupon:details'] = 'Coupon details';
$string['entiresite'] = 'Entire site / any course';
$string['couponcode'] = 'Couponcode';
$string['couponcodemissing'] = 'Couponcode must be set';
$string['validfrom'] = 'Valid from';
$string['validfrommissing'] = 'Start date of validity must be set';
$string['validto'] = 'Valid to';
$string['validtomissing'] = 'End date for validity must be set';
$string['percentage'] = 'Percentage';
$string['percentagemissing'] = 'Percentage must be given';
$string['maxusage'] = 'Maximum usage';
$string['maxusage_help'] = 'Maximum number of times this coupon code can be used.<br/>
If 0 is entered, it means unlimited usage.';
$string['coupon:newprice'] = 'Discount: {$a->percentage}<br/>Discount: {$a->currency} {$a->discount}<br/>New price: <b>{$a->currency} {$a->newprice}</b>';
$string['checkcode'] = 'Check coupon code';
$string['paynlsettings_apply'] = '<a href="">Apply for PAYNL</a>';
$string['cp:coupons'] = 'Manage coupons';
$string['cp:subscriptions'] = 'Enrolments';
$string['cp:paynlconnection'] = 'Classicpay Service Info';
$string['cp:transactions'] = 'Transactions';
$string['cp:apply'] = 'Merchant Application';
$string['cp:legal'] = 'Liability information';
$string['couponcodeexists'] = 'Coupon code already exists';
$string['coupon:status:impending'] = 'IMPENDING';
$string['coupon:status:active'] = 'ACTIVE';
$string['coupon:status:expired'] = 'EXPIRED';
$string['coupon:status:maxused'] = 'MAXUSED';
$string['coupons:backtooverview'] = 'Back to coupon list';
$string['err:percentage-negative'] = 'Discount percentage can\'t be negative';
$string['err:percentage-exceed'] = 'Discount percentage can\'t exceed 100%';
$string['validfromhigherthanvalidto'] = 'Validity from data is past validity to date';
$string['classicpay:config'] = 'Configure classicpay';
$string['classicpay:createcoupon'] = 'Create coupons';
$string['classicpay:editcoupon'] = 'Edit coupons';
$string['classicpay:deletecoupon'] = 'Delete coupons';
$string['classicpay:manage'] = 'Manage classicpay';
$string['classicpay:unenrol'] = 'Unenrol users';
$string['classicpay:unenrolself'] = 'Unenrol self';
$string['gettokentime'] = 'Time on remote API: {$a}';
$string['paynlconn:remote:error'] = 'Error while calling remote PAY API: {$a}';
$string['apply:alreadyconfigured'] = 'You already have a PAYNL Merchant token configured.';
$string['api:notconfigured'] = 'You do not have a PAYNL Merchant token configured yet.<br/>
Please configure your settings to include a PAYNL Merchant token or <a href="{$a}">apply here</a>';
$string['paylogin'] = 'Login to PAYNL';
$string['registrationcancelled'] = 'Registration cancelled. You are now being redirected to the homepage.';
$string['page:title:spapply'] = 'Apply for a PAYNL Merchant';
$string['apply:email'] = 'Email address';
$string['apply:phone'] = 'Phone number';
$string['apply:phone2'] = 'Alternate phone number';
$string['apply:firstname'] = 'First Name';
$string['apply:lastname'] = 'Last Name';
$string['apply:companyname'] = 'Organisation Name';
$string['apply:cocnumber'] = 'COC Number';
$string['apply:gender'] = 'Gender';
$string['apply:gender:male'] = 'Male';
$string['apply:gender:female'] = 'Female';
$string['apply:street'] = 'Street';
$string['apply:housenumber'] = 'Housenumber';
$string['apply:zipcode'] = 'Zipcode';
$string['apply:city'] = 'City';
$string['apply:countrycode'] = 'Country Code';
$string['apply:bankaccountowner'] = 'Bankaccount Owner';
$string['apply:bankaccountnumber'] = 'Bankaccount Number (IBAN)';
$string['apply:bic'] = 'BIC / Swift';
$string['apply:vatnumber'] = 'VAT number';
$string['apply:languageid'] = 'Language ID';
$string['apply:authorizedtosign'] = 'Authorized to sign';
$string['apply:authorizedtosign:no'] = 'No';
$string['apply:authorizedtosign:yes'] = 'Authorized to sign independently';
$string['apply:authorizedtosign:shared'] = 'Shared authorized to sign';
$string['apply:ubo'] = 'UBO';
$string['apply:cocdocument'] = 'COC Excerpt';
$string['apply:bankdocument'] = 'Copy bankaccount balance';
$string['apply:iddocument'] = 'Identification document';
$string['apply:submit'] = 'Apply';
$string['apply:sitename'] = 'Site name';
$string['apply:siteurl'] = 'Site url';
$string['apply:addsignee'] = 'Add a signee';
$string['apply:bankname'] = 'Bank name';
$string['apply:bankcity'] = 'Bank city';
$string['apply:paymentprofile'] = 'Payment method(s)';
$string['apply:header:signees'] = 'Optional extra signees for the application';
$string['apply:header:paymentprofiles'] = 'Applicable payment methods for the application';
$string['apply:header:details'] = 'Registration information';
$string['apply:button:addsignee'] = 'Add {no} signee(s) to the form';
$string['apply:page:heading'] = 'Apply for a PAYNL Merchant token.';
$string['apply:nav'] = 'PAYNL Merchant application.';

$string['apply:email_help'] = 'Email address. This is needed to apply for PAYNL get in contact with you regarding your application.';
$string['apply:phone_help'] = 'Phone number where you can be reached. We will try and contact you if there are any problems regarding the application';
$string['apply:phone2_help'] = 'Secondary phonenumber. If not needed, you can leave this blank';
$string['apply:firstname_help'] = 'Your first name';
$string['apply:lastname_help'] = 'You last name';
$string['apply:companyname_help'] = 'Your organisation name';
$string['apply:cocnumber_help'] = 'Your Chamber of Commerce registration number';
$string['apply:gender_help'] = 'Your gender';
$string['apply:street_help'] = 'Street address of the organisation';
$string['apply:housenumber_help'] = 'house number of the organisation';
$string['apply:zipcode_help'] = 'Zip code of the organisation';
$string['apply:city_help'] = 'City where the organisation is located';
$string['apply:countrycode_help'] = 'Country where the organisation is located';
$string['apply:bankaccountowner_help'] = 'Owner\'s name of the bank account';
$string['apply:bankaccountnumber_help'] = 'Bank account number. This should be an IBAN number for Europe.';
$string['apply:bic_help'] = 'BIC or SWIFT code';
$string['apply:vatnumber_help'] = 'Possible VAT Registration number for the organisation';
$string['apply:languageid_help'] = 'Preferred language for your account';
$string['apply:authorizedtosign_help'] = 'Indicates if you are authorised to sign. If unknown, leave option to NO';
$string['apply:ubo_help'] = 'UBO';
$string['apply:usecompanyauth_help'] = 'Use organisation authentication?';
$string['apply:sendemail_help'] = 'Indicates if you want to receive a registration email and what type.';
$string['apply:invoiceinterval_help'] = 'How often do you want invoices to be processed?';
$string['apply:cocdocument_help'] = 'Excerpt of your Chamber of Commerce registration. We need this for validation purposes.';
$string['apply:bankdocument_help'] = 'Latest copy of your bankaccount balance. We need this for validation purposes.<br/>
Any payment details are allowed to be made unreadable, as long as the account details are visible.';
$string['apply:iddocument_help'] = 'Latest copy of your ID (passport or ID card), or a copy of your driver\'s license.';
$string['apply:settlebalance_help'] = '??';
$string['apply:sitename_help'] = 'Site name. We need this for program registration purposes.';
$string['apply:siteurl_help'] = 'Site url. We need this for program registration purposes.';
$string['apply:addsignee_help'] = 'If you want to add a signee, complete the following fields.';
$string['apply:bankname_help'] = 'Name of your bank, e.g. Rabobank, ING, ...';
$string['apply:bankcity_help'] = 'Name of the city your bank is located.';

$string['apply:success'] = '<div><p>Registration successfully received and processed</p><p>{$a->info}</p>';
$string['apply:fail'] = '<div><p>Registration failure:</p><p>{$a->errcode}: {$a->error}</p><p>{$a->info}</p></div>';

$string['task:process_pending_orders'] = 'Synchronises pending payment order status in case we missed exchange requests';
$string['task:sync_cpplus'] = 'Synchronise whether or not we have a classicpay PLUS account';
$string['task:request_invoices'] = 'Synchronise invoice request queue';
$string['classicpay:plus:status:valid'] = 'You have a classicpay plus account';
$string['classicpay:plus:status:invalid'] = 'You do not have a classicpay plus account';
$string['classicpay:plus:description'] = '<div class="enrol-classicpay-info">When requesting a Classicpay Plus account, it effectively means you\'re applying to create / request invoices.<br/>
This does bring a few extra costs, but it has the advantage both you and the enduser will automatically receive an invoice from our service when a course has been purchased.<br/>
By enabling Classicpay Plus you automatically agree with us billing you 10 cent, on top of the transactions costs, per transaction.<br/>
These will periodically be billed to you by us. You will receive an invoice for the number of generated invoices.<br/>
The first date of generating an invoice is leading. It isn\'t necessarily the case the number of transactions within a certain period is the same as the number of billed generated invoices.</div>';
$string['cppapply:enable'] = 'Enable Classicpay Plus';
$string['cppapply:disable'] = 'Disable Classicpay Plus';

$string['cppoapply:header:paymentprofiles'] = 'Your configured payment methods';
$string['cppoapply:paymentprofiles:simple'] = '<div class="enrol-classicpay-info">Freely available methods.<br/>
These methods can be switched on or off without need for extra details.</div>';
$string['cppoapply:paymentprofiles:setting'] = '<div class="enrol-classicpay-info"><span style="color:red">*&nbsp;</span>The following methods require you to supply extra account information.<br/>
Upon selecting these you will receive a confirmation email that should tell you we\'ll get in touch with you regarding the details.<br/></div>';

$string['apply:cpp:success'] = 'Successfully toggled Classic Pay plus';
$string['apply:cpp:fail'] = 'Failure toggling Classic Pay plus';
$string['cppapply:header'] = 'Classicpay Plus account status';
$string['button:cppo:update'] = 'Update payment methods';
$string['err:setserviceprofiles'] = 'Error updating Payment options: {$a->error}';
$string['setserviceprofiles:success'] = 'Successfully updated Payment options:<br/>{$a}';
$string['warn:servicepage'] = '<div class="enrol-classicpay-warn">Every action you perform on this page will be performed on our classicpay service.<br/>
Be careful to read and fully understand the comments before proceeding.</div>';
$string['apply:information'] = '<div class="enrol-classicpay-info"><strong>Important application information.</strong><br/>
Please be careful to read the instructions very well before proceeding to apply.<br/>
<ul>
<li>Make sure to fill out this form to your best knowledge and honesty.</li>
<li>Double check this form before submitting.</li>
<li>Take note of the fact <b>all</b> documents are in fact required for your application.</li>
<li>Uploading every required document will speed up your application.</li>
<li>Your application will not be halted when you do not upload the required documents.<br/>
You will be able to start immediately, provided your application is successful<br/>
However, we will get in contact with you regarding the required documents and you will still have to provide them.<br/>
Failing to do so, or any type of abuse will lead to suspension of your account.</li>
<li>Any abuse or misuse will be reported to the authorities and can lead to your account being discontinued or blacklisted, either by us or by the payment service provider.</li>
<li>Any indication of forgery in or falsification of required documents will lead to you being reported to the authorities, either by us or by the payment service provider.</li>
<li><b><i>Unless all documents have been approved, your funds will not be released by the payment service provider.</i></b></li>
</ul></div>';
$string['event:payment:started'] = 'Classicpay payment started';
$string['event:payment:complete'] = 'Classicpay payment completed';
$string['admin:page:legal'] = '<p>Classic Pay and Classic Pay Plus have been developed by Sebsoft.</p>
<p>However, Sebsoft can <i>not</i>, in any way, be held responsible or liable for processing transactions through the use of this system.<br/>
All liability claims will get rejected a priori.</p>
<p>You as merchant (or website owner) will be bound to Pay.nl by means of a (lawful) contract for the purpose of obtaining your collected funds (i.e. the payments done).<br/>
The payout will be provided by "Stichting Derdengelden Pay.nl", the legal company that\'s responsible for managing your funds.<br/>
Sebsoft will send you an invoice for all transactions made and/or generated invoices (applicable for ClassicPay Plus).</p>';