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
 * File contains the openiban API implementation
 *
 * File         openiban.js
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
        var enrol_classicpay_ibanxhr = null;
        var ibanelement = Y.one('#id_bankAccountNumber');
        var bicelement = Y.one('#id_BIC');
        if (ibanelement && bicelement) {
            ibanelement.on('change', function(e) {
                if (enrol_classicpay_ibanxhr !== null) {
                    enrol_classicpay_ibanxhr.abort();
                }
                console.log(e);

                enrol_classicpay_ibanxhr = Y.io('https://openiban.com/validate/' + ibanelement.get('value'), {
                    method: 'GET',
                    data: 'validateBankCode=1&getBIC=1',
                    headers: {'X-Requested-With': 'disable'},
                    on:{
                        success: function (id, result) {
                            var response = JSON.parse(result.responseText);
                            if (response.valid === true) {
                                bicelement.set('value', response.bankData.bic);
                            }
                        }
                    }
                });
            });
        }
    });
});