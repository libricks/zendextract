/**
 * nextCloud - Zendesk Xtractor
 *
 * This file is licensed under the GNU Affero General Public License version 3
 * or later. See the COPYING file.
 *
 * @author Tawfiq Cadi Tazi <tawfiq@caditazi.fr>
 * @copyright Copyright (C) 2017 SARL LIBRICKS
 * @license AGPL
 * @license https://opensource.org/licenses/AGPL-3.0
 */

$(function () {
    $('.sortable button.move').click(function ($e) {

        var row = $(this).closest('tr');
        if ($(this).hasClass('up')) {

            if (row.prev().prev().length > 0)
                row.prev().before(row);
        }
        else
            row.next().after(row);
    });
    $(".options").change(function () {
        updateOptions();
    });
    updateOptions();

    $('#datetimepicker1').datetimepicker({
        locale: 'fr-FR',
        format: 'l'
    });
    $('#datetimepicker2').datetimepicker({
        locale: 'fr-FR',
        format: 'l',
        useCurrent: false
    });

    $('#datetimepicker3').datetimepicker({
        locale: 'fr-FR',
        format: 'l',
        useCurrent: false
    });

    $('#datetimepicker4').datetimepicker({
        locale: 'fr-FR',
        format: 'l',
        useCurrent: false
    });

    $('#datetimepicker5').datetimepicker({
        locale: 'fr-FR',
        format: 'l',
        useCurrent: false
    });

    $('#datetimepicker6').datetimepicker({
        locale: 'fr-FR',
        format: 'l',
        useCurrent: false
    });

    $("#datetimepicker1").on("dp.change", function (e) {
        $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker2").on("dp.change", function (e) {
        $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
    });

    $("#datetimepicker3").on("dp.change", function (e) {
        $('#datetimepicker4').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker4").on("dp.change", function (e) {
        $('#datetimepicker3').data("DateTimePicker").maxDate(e.date);
    });

    $("#datetimepicker5").on("dp.change", function (e) {
        $('#datetimepicker6').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker6").on("dp.change", function (e) {
        $('#datetimepicker5').data("DateTimePicker").maxDate(e.date);
    });


    $(".hold-submit").on("click", function ($e) {
        var $form = $(this).closest("form");
        $(".message").html("");
        var date_ok = true;
        if ($("#datetimepicker1").val() == "" || $("#datetimepicker2").val() == "") {
            if ($("#datetimepicker3").val() == "" || $("#datetimepicker4").val() == "") {
                if ($("#datetimepicker5").val() == "" || $("#datetimepicker6").val() == "") {
                    $e.preventDefault();
                    $(".message").html("Veuillez renseigner soit une date de traitement, soit une date de contact, soit une date de création");
                    date_ok = false;
                }
            }
        }
        if (date_ok) {
            if ($form[0].checkValidity()) {
                $(this).hide();
                $(this).next().show();
                $form.submit();
            } else {
                $e.preventDefault();
            }
        }


    })
})


function updateOptions() {
    $(".options").each(function () {
        $(this).closest("td").next().children().hide();
        $value = $(this).find(":selected").val();
        $(this).closest("td").next().find("[data-option='" + $value + "']").show();

    });
}

