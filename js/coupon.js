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
 * FIle contains the couponchecker
 *
 * File         coupon.js
 * Encoding     UTF-8
 *
 * @package     enrol_classicpay
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

Y.use('node', 'io', function(Y) {
    Y.on("domready", function() {
        var enrol_classicpay_xhr = null;

        /**
         * Check if the coupon is valid.
         */
        function enrol_classicpay_docheckcoupon() {
            var courseid = Y.one('input[name="courseid"]').get('value');
            var instanceid = Y.one('input[name="instanceid"]').get('value');
            var url = M.cfg.wwwroot + '/enrol/classicpay/couponcheck.php';
            var code = Y.one('input[name="coupon"]').get('value');
            if (enrol_classicpay_xhr !== null) {
                enrol_classicpay_xhr.abort();
            }

            enrol_classicpay_xhr = Y.io(url,{
                method: 'POST',
                data: 'code=' + code + '&courseid=' + courseid + '&instanceid=' + instanceid,
                on: {
                    success: function(id, result) {
                        var response = JSON.parse(result.responseText);
                        if (response.error) {
                            Y.one('#enrol-classicpay-coupondiscount').set('innerHTML', response.error).addClass('error');
                            Y.one('#enrol-classicpay-basecost').removeClass('enrol-classicpay-strike');
                        } else {
                            Y.one('#enrol-classicpay-coupondiscount').set('innerHTML', response.html).removeClass('error');
                            Y.one('#enrol-classicpay-basecost').addClass('enrol-classicpay-strike');
                        }
                    },
                    failure: function(id, result) {
                        var response = JSON.parse(result.responseText);
                        if (response.error) {
                            Y.one('#enrol-classicpay-coupondiscount').set('innerHTML', response.error).addClass('error');
                            Y.one('#enrol-classicpay-basecost').removeClass('enrol-classicpay-strike');
                        }
                    }
                }
            });

        }

        var cp = Y.one('#btncheckcoupon');
        cp.on('click', function(e){
            enrol_classicpay_docheckcoupon();
            e.halt();
        });
    });
});