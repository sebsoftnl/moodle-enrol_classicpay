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
 * Renderer class.
 *
 * File         renderer.php
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
 * enrol_classicpay_renderer
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_classicpay_renderer extends plugin_renderer_base {

    /**
     * Display coupon manager page for 'management' use
     */
    public function manager_page_coupon_manager() {
        global $DB;
        $action = optional_param('action', 'list', PARAM_ALPHAEXT);
        $cid = optional_param('cid', 0, PARAM_INT);
        $pageurl = clone $this->page->url;
        $pageurl->param('action', $action);
        if ($this->page->course->id <> SITEID) {
            $pageurl->param('cid', $this->page->course->id);
        }

        switch ($action) {
            case 'delete':
                require_capability('enrol/classicpay:deletecoupon', $this->page->context);
                // We'll do this using a form for now.
                $id = required_param('id', PARAM_INT);
                $this->page->set_title(get_string('coupon:delete', 'enrol_classicpay'));
                $coupon = $DB->get_record('enrol_classicpay_coupon', array('id' => $id));
                $deleteform = new \enrol_classicpay\forms\coupondelete($pageurl, $coupon);
                $pageurl->param('action', 'list');
                $deleteform->process_form($id, $pageurl);
                exit;
                break;

            case 'edit':
                require_capability('enrol/classicpay:editcoupon', $this->page->context);
                $this->page->set_title(get_string('title:couponedit', 'enrol_classicpay'));
                $id = required_param('id', PARAM_INT);
                if ($id > 0) {
                    $instance = $DB->get_record('enrol_classicpay_coupon', array('id' => $id), '*', MUST_EXIST);
                } else {
                    $instance = (object)array('id' => 0);
                }
                $options = new stdClass();
                $options->instance = $instance;
                if ($this->page->course->id <> SITEID) {
                    $options->lockcourse = $this->page->course->id;
                }
                $form = new \enrol_classicpay\forms\coupon($pageurl, $options);
                $pageurl->param('action', 'list');
                $form->process_form($instance, $pageurl);
                break;

            case 'list':
            default:
                $strnewform = '';
                if (has_capability('enrol/classicpay:createcoupon', $this->page->context)) {
                    $options = new stdClass();
                    $options->instance = (object) array('id' => 0);
                    if ($this->page->course->id <> SITEID) {
                        $options->lockcourse = $this->page->course->id;
                    }
                    $newform = new \enrol_classicpay\forms\coupon($pageurl, $options);
                    $newform->process_post($options->instance, $pageurl);
                    $strnewform = '<div class="enrol-classicpay-container">' . $newform->render() . '</div>';
                }

                $filter = optional_param('list', 'all', PARAM_ALPHA);
                $table = new \enrol_classicpay\tables\coupons($cid, $filter);
                $table->baseurl = $pageurl;
                echo $this->header();
                echo '<div class="enrol-classicpay-container">';
                $table->render(25);
                echo '</div>';
                echo $strnewform;
                echo $this->footer();
                break;
        }
    }

    /**
     * Display coupon manager page for administration use
     */
    public function admin_page_coupon_manager() {
        global $DB;
        $action = optional_param('action', 'list', PARAM_ALPHAEXT);
        $cid = optional_param('cid', 0, PARAM_INT);
        $pageurl = clone $this->page->url;
        $pageurl->param('action', $action);

        switch ($action) {
            case 'delete':
                require_capability('enrol/classicpay:deletecoupon', $this->page->context);
                $this->page->set_title(get_string('title:couponmanager:delete', 'enrol_classicpay'));
                // We'll do this using a form for now.
                $id = required_param('id', PARAM_INT);
                $coupon = $DB->get_record('enrol_classicpay_coupon', array('id' => $id));
                $deleteform = new \enrol_classicpay\forms\coupondelete($pageurl, $coupon);
                $pageurl->param('action', 'list');
                $deleteform->process_post($pageurl);
                return $this->admin_form('cpcoupons', $deleteform, array('id' => $id));
                break;

            case 'edit':
                require_capability('enrol/classicpay:editcoupon', $this->page->context);
                $this->page->set_title(get_string('title:couponmanager:edit', 'enrol_classicpay'));
                $id = required_param('id', PARAM_INT);
                if ($id > 0) {
                    $instance = $DB->get_record('enrol_classicpay_coupon', array('id' => $id), '*', MUST_EXIST);
                } else {
                    $instance = (object)array('id' => 0);
                }
                $options = new stdClass();
                $options->instance = $instance;
                if ($this->page->course->id <> SITEID) {
                    $options->lockcourse = $this->page->course->id;
                }
                $form = new \enrol_classicpay\forms\coupon($pageurl, $options);
                $pageurl->param('action', 'list');
                $form->process_post($instance, $pageurl);
                return $this->admin_form('cpcoupons', $form, $instance);
                break;

            case 'details':
                require_capability('enrol/classicpay:config', $this->page->context);
                $this->page->set_title(get_string('title:couponmanager:details', 'enrol_classicpay'));
                $id = required_param('id', PARAM_INT);
                $type = optional_param('type', 'all', PARAM_ALPHA);
                $params = array('page' => 'cpcoupons', 'sesskey' => sesskey(), 'type' => $type);
                $back = new \moodle_url('/enrol/classicpay/admin.php', $params);
                $table = new \enrol_classicpay\tables\couponusage($id);
                $table->baseurl = clone $this->page->url;
                $table->baseurl->param('id', $id);
                $table->baseurl->param('action', 'details');
                $table->baseurl->param('sesskey', sesskey());

                $out = '';
                $out .= $this->header();
                $out .= html_writer::start_div('enrol-classicpay-container');
                $out .= html_writer::start_div('enrol-classicpay-tabs');
                $out .= $this->admin_tabs('cpcoupons');
                $out .= html_writer::end_div();
                $out .= '<div><a href="' . $back . '">' . get_string('coupons:backtooverview', 'enrol_classicpay') . '</a></div>';
                ob_start();
                $table->render(25);
                $out .= ob_get_clean();
                $out .= html_writer::end_div();
                $out .= $this->footer();
                return $out;
                break;

            case 'list':
            default:
                $strnewform = '';
                $this->page->set_title(get_string('title:couponmanager', 'enrol_classicpay'));
                if (has_capability('enrol/classicpay:createcoupon', $this->page->context)) {
                    $options = new stdClass();
                    $options->instance = (object) array('id' => 0);
                    if ($this->page->course->id <> SITEID) {
                        $options->lockcourse = $this->page->course->id;
                    }
                    $newform = new \enrol_classicpay\forms\coupon($pageurl, $options);
                    $newform->process_post($options->instance, $pageurl);
                    $strnewform = html_writer::div($newform->render(), 'enrol-classicpay-container');
                }

                $filter = optional_param('list', 'all', PARAM_ALPHA);
                $table = new \enrol_classicpay\tables\coupons($cid, $filter);
                $table->baseurl = $pageurl;
                $out = '';
                $out .= $this->header();
                $out .= html_writer::start_div('enrol-classicpay-container');
                $out .= html_writer::start_div('enrol-classicpay-tabs');
                $out .= $this->admin_tabs('cpcoupons');
                $out .= html_writer::end_div();
                ob_start();
                $table->render(25);
                $out .= ob_get_clean();
                $out .= html_writer::end_div();
                $out .= $strnewform;
                $out .= $this->footer();
                return $out;
                break;
        }
    }

    /**
     * Display transaction page for administration use
     */
    public function admin_page_transactions() {
        global $DB;
        $this->page->set_title(get_string('title:transactions', 'enrol_classicpay'));
        $action = optional_param('action', 'list', PARAM_ALPHAEXT);
        $cid = optional_param('cid', 0, PARAM_INT);
        $pageurl = clone $this->page->url;
        $pageurl->param('action', $action);

        switch ($action) {
            case 'invoice':
                // Request invoice again.
                $id = optional_param('id', 0, PARAM_INT);
                $transaction = $DB->get_record('enrol_classicpay', array('id' => $id));
                $api = new \enrol_classicpay\classicpay\api();
                $api->request_invoice($transaction, null, null, null, true);
                $pageurl->param('action', null);
                redirect($pageurl, get_string('invoice:requested', 'enrol_classicpay'));
                break;
            case 'list':
            default:
                $filter = optional_param('list', 'all', PARAM_ALPHA);
                $table = new \enrol_classicpay\tables\classicpay($cid, $filter);
                $table->baseurl = $pageurl;
                $table->is_downloadable(true);
                $table->show_download_buttons_at(array(TABLE_P_BOTTOM, TABLE_P_TOP));
                $download = optional_param('download', '', PARAM_ALPHA);
                if (!empty($download)) {
                    $table->is_downloading($download, 'transactions', 'transactions');
                    $table->render(25, true);
                    exit;
                }

                $out = '';
                $out .= $this->header();
                $out .= html_writer::start_div('enrol-classicpay-container');
                $out .= html_writer::start_div('enrol-classicpay-tabs');
                $out .= $this->admin_tabs('cptransactions');
                $out .= html_writer::end_div();
                ob_start();
                $table->render(25);
                $out .= ob_get_clean();
                $out .= html_writer::end_div();
                $out .= $this->footer();
                return $out;
        }
    }

    /**
     * Display subscription page for administration use
     */
    public function admin_page_subscription_manager() {
        $this->page->set_title(get_string('title:enrolments', 'enrol_classicpay'));
        $action = optional_param('action', 'list', PARAM_ALPHAEXT);
        $cid = optional_param('cid', 0, PARAM_INT);
        $pageurl = clone $this->page->url;
        $pageurl->param('action', $action);

        $filter = optional_param('list', 'paid', PARAM_ALPHA);
        $table = new \enrol_classicpay\tables\classicpay($cid, $filter);
        $table->baseurl = $pageurl;
        $table->is_downloadable(true);
        $table->show_download_buttons_at(array(TABLE_P_BOTTOM, TABLE_P_TOP));
        $download = optional_param('download', '', PARAM_ALPHA);
        if (!empty($download)) {
            $table->is_downloading($download, 'enrolments', 'enrolments');
            $table->render(25);
            exit;
        }

        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('enrol-classicpay-container');
        $out .= html_writer::start_div('enrol-classicpay-tabs');
        $out .= $this->admin_tabs('cpsubscriptions');
        $out .= html_writer::end_div();
        ob_start();
        $table->render(25);
        $out .= ob_get_clean();
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display connection manager page for administration use.
     */
    public function admin_page_service_manager() {
        $this->page->set_title(get_string('title:service', 'enrol_classicpay'));
        $config = get_config('enrol_classicpay');
        if (!empty($config->paynlapitoken)) {
            // First, let the forms do their work, if any.
            $form = new \enrol_classicpay\classicpay\forms\cppapply($this->page->url);
            $form->process_post($this->page->url);

            $cppoform = new \enrol_classicpay\classicpay\forms\cppoapply($this->page->url);
            $cppoform->process_post($this->page->url);
        }

        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('enrol-classicpay-container');
        $out .= html_writer::start_div('enrol-classicpay-tabs');
        $out .= $this->admin_tabs('cpservice');
        $out .= html_writer::end_div();
        try {
            if (!isset($config->paynlapitoken) || empty($config->paynlapitoken)) {
                $url = new \moodle_url('/enrol/classicpay/spapply.php');
                $out .= get_string('api:notconfigured', 'enrol_classicpay', $url->out());
            } else {
                $out .= get_string('warn:servicepage', 'enrol_classicpay');
                // Display Forms.
                $out .= $form->render();
                $out .= $cppoform->render();
            }
        } catch (\enrol_classicpay\classicpay\exception $pex) {
            $out .= get_string('paynlconn:remote:error', 'enrol_classicpay', $pex->getMessage());
        }
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display legal liability page
     */
    public function admin_page_legal() {
        $this->page->set_title(get_string('title:legal', 'enrol_classicpay'));
        $out = '';
        $out .= $this->header();
        $out .= html_writer::start_div('enrol-classicpay-container');
        $out .= html_writer::start_div('enrol-classicpay-tabs');
        $out .= $this->admin_tabs('cplegal');
        $out .= html_writer::end_div();
        $out .= get_string('admin:page:legal', 'enrol_classicpay');
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Generate navigation tabs
     *
     * @param string $selected selected tab
     * @param array $params any paramaters needed for the base url
     */
    protected function admin_tabs($selected, $params = array()) {
        $tabs = array();
        $tabs[] = $this->create_pictab('cpcoupons', 'coupons', 'enrol_classicpay',
                new \moodle_url('/enrol/classicpay/admin.php', array_merge($params, array('page' => 'cpcoupons'))),
                get_string('cp:coupons', 'enrol_classicpay'));
        $tabs[] = $this->create_pictab('cptransactions', 'transactions', 'enrol_classicpay',
                new \moodle_url('/enrol/classicpay/admin.php', array_merge($params, array('page' => 'cptransactions'))),
                get_string('cp:transactions', 'enrol_classicpay'));
        $tabs[] = $this->create_pictab('cpsubscriptions', 'subscriptions', 'enrol_classicpay',
                new \moodle_url('/enrol/classicpay/admin.php', array_merge($params, array('page' => 'cpsubscriptions'))),
                get_string('cp:subscriptions', 'enrol_classicpay'));
        $tabs[] = $this->create_pictab('cpservice', 'paynlconnection', 'enrol_classicpay',
                new \moodle_url('/enrol/classicpay/admin.php', array_merge($params, array('page' => 'cpservice'))),
                get_string('cp:paynlconnection', 'enrol_classicpay'));
        $tabs[] = $this->create_pictab('cplegal', 'legal', 'enrol_classicpay',
                new \moodle_url('/enrol/classicpay/admin.php', array_merge($params, array('page' => 'cplegal'))),
                get_string('cp:legal', 'enrol_classicpay'));
        $tabs[] = $this->create_pictab('cpsettings', 'settings', 'enrol_classicpay',
                new \moodle_url('/admin/settings.php', array('section' => 'enrolsettingsclassicpay')),
                get_string('pluginname', 'enrol_classicpay'));
        return $this->tabtree($tabs, $selected);
    }

    /**
     * Render an administration form.
     * Note this will also render the header and footer of the page.
     * This will also render the administration tabs for navigational ease.
     *
     * @param string $tab currently active page/tab name. Page names are defined by the admin tree (see settings.php)
     * @param \moodleform $form the form
     * @param mixed $data form data
     * @return string
     */
    protected function admin_form($tab, \moodleform $form, $data = array()) {
        $out = $this->header();
        $out .= html_writer::start_div('enrol-classicpay-container');
        $out .= html_writer::start_div('enrol-classicpay-tabs');
        $out .= $this->admin_tabs($tab);
        $out .= html_writer::end_div();
        $out .= $form->set_data($data);
        $out .= $form->render();
        $out .= html_writer::end_div();
        $out .= $this->footer();
        return $out;
    }

    /**
     * Create a tab object with a nice image view, instead of just a regular tabobject
     *
     * @param string $id unique id of the tab in this tree, it is used to find selected and/or inactive tabs
     * @param string $pix image name
     * @param string $component component where the image will be looked for
     * @param string|moodle_url $link
     * @param string $text text on the tab
     * @param string $title title under the link, by defaul equals to text
     * @param bool $linkedwhenselected whether to display a link under the tab name when it's selected
     * @return \tabobject
     */
    protected function create_pictab($id, $pix = null, $component = null, $link = null,
            $text = '', $title = '', $linkedwhenselected = false) {
        $img = '';
        if ($pix !== null) {
            $img = $this->image_url($pix, $component) . ' ';
            $img = '<img src="' . $img . '"';
            if (!empty($title)) {
                $img .= ' alt="' . $title . '"';
            }
            $img .= ' class="icon"/> ';
        }
        return new \tabobject($id, $link, $img . $text, empty($title) ? $text : $title, $linkedwhenselected);
    }

    /**
     * Display payment cancelled page (including header() / footer())
     *
     * @return string full page
     */
    public function payment_page_cancel() {
        global $CFG;
        $out = '';
        $out .= $this->header();
        $out .= $this->notification(get_string('payment:cancelled', 'enrol_classicpay', $this->page->course));
        $out .= $this->continue_button($CFG->wwwroot . '/my');
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display enrolment status page (including header() / footer())
     *
     * @param bool $enrolled
     * @param stdClass $course course record
     * @param stdClass $transactionrecord record from enrol_classicpay table
     *
     * @return string full page
     */
    public function payment_page_enrol_status($enrolled, $course, $transactionrecord) {
        global $CFG;
        $config = get_config('enrol_classicpay');
        $out = '';
        $out .= $this->header();
        if ($enrolled) {
            // This order is paid we should enrol the user and notify.
            $out .= $this->box('<p style="text-align: center">' . get_string('enrol:ok', 'enrol_classicpay', $course) . '</p>');
            // Send a success message to the user.
            $out .= $this->continue_button(new moodle_url('/course/view.php', array('id' => $course->id)));
            if (strlen($config->htmlonthankyoupage) > 0) {
                $out .= $config->htmlonthankyoupage;
            }
        } else {
            // Send a status message to user.
            $out .= $this->box('<p style="text-align: center">' . get_string('enrol:fail', 'enrol_classicpay', $course) .
                    '</p><p style="text-align: center">' .
                    get_string('enrol:fail:tx', 'enrol_classicpay', $transactionrecord) . '</p>');
            $out .= $this->continue_button($CFG->wwwroot . '/my');
        }
        $out .= $this->footer();
        return $out;
    }

    /**
     * Display status page user is already enrolled in course X (including header() / footer())
     *
     * @param stdClass $course course record
     *
     * @return string full page
     */
    public function payment_page_enrol_already_enrolled($course) {
        $config = get_config('enrol_classicpay');
        $out = '';
        $out .= $this->header();
        // Notify user about successfull enrolment.
        $out .= $this->box('<p style="text-align: center">' . get_string('enrol:ok', 'enrol_classicpay', $course) . '</p>');
        $out .= $this->continue_button(new moodle_url('/course/view.php', array('id' => $course->id)));
        if (strlen($config->htmlonthankyoupage) > 0) {
            $out .= $config->htmlonthankyoupage;
        }
        $out .= $this->footer();
        return $out;
    }
}
