define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function ($) {
    'use strict';

    return function () {
        /*
         * Port validation
         */
        $.validator.addMethod(
            "validate-port",
            function (value, element) {
                /*
                 * @see http://regexlib.com/REDetails.aspx?regexp_id=2814
                 */
                return /^(6553[0-5]|655[0-2][0-9]\d|65[0-4](\d){2}|6[0-4](\d){3}|[1-5](\d){4}|[1-9](\d){0,3})$/.test(value);
            },
            $.mage.__("Please enter a valid port.")
        );
        /*
         * Cron expression validation method
         */
        $.validator.addMethod(
            "validate-cron-expression",
            function (value, element) {
                /*
                 * @see https://stackoverflow.com/a/52189918
                 */
                return /^(\*|([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])|\*\/([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])) (\*|([0-9]|1[0-9]|2[0-3])|\*\/([0-9]|1[0-9]|2[0-3])) (\*|([1-9]|1[0-9]|2[0-9]|3[0-1])|\*\/([1-9]|1[0-9]|2[0-9]|3[0-1])) (\*|([1-9]|1[0-2])|\*\/([1-9]|1[0-2])) (\*|([0-6])|\*\/([0-6]))$/.test(value);
            },
            $.mage.__("Please enter a valid cron expression.")
        );
    }
});
