/**
 * nextCloud - Zendesk Xtractor
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Tawfiq CADI TAZI <tawfiq@caditazi.fr>
 * @copyright Marc-Henri Pamiseux 2017
 */

(function() {
    if (!OCA.ZendExtract) {
        /**
         * Namespace for the files app
         * @namespace OCA.Weather
         */
        OCA.ZendExtract = {};
    }

    /**
     * @namespace OCA.ZendExtract.Admin
     */
    OCA.ZendExtract.Admin = {
        initialize: function() {
            $('#submitOWMApiKey').on('click', _.bind(this._onClickSubmitOWMApiKey, this));
        },

        _onClickSubmitOWMApiKey: function () {
            OC.msg.startSaving('#OWMApiKeySettingsMsg');

            var request = $.ajax({
                url: OC.generateUrl('/apps/zendextract/settings/apikey'),
                type: 'POST',
                data: {
                    domain: $('#zendextract_domain').val(),
                    email: $('#zendextract_email').val(),
                    token: $('#zendextract_token').val()
                }
            });

            request.done(function (data) {
                $('#zendextract_domain').val(data.zendextract_domain);
                $('#zendextract_email').val(data.zendextract_email);
                $('#zendextract_token').val(data.zendextract_token);
                OC.msg.finishedSuccess('#OWMApiKeySettingsMsg', 'Saved');
            });

            request.fail(function () {
                OC.msg.finishedError('#OWMApiKeySettingsMsg', 'Error');
            });
        }
    }
})();

$(document).ready(function() {
    OCA.ZendExtract.Admin.initialize();
});