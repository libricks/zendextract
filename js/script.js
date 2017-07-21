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
    $(".options").change(function(){
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

    $("#datetimepicker1").on("dp.change", function (e) {
        $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker2").on("dp.change", function (e) {
        $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
    });


    $(".hold-submit").on("click", function($e){
        $form = $(this).closest("form");
        if($form[0].checkValidity()){
            $(this).hide();
            $(this).next().show();
            $form.submit();
        }else{
            $e.preventDefault();
        }




    })
})


function updateOptions(){
$(".options").each(function(){
    $(this).closest("td").next().children().hide();
    $value = $(this).find(":selected").val();
    $(this).closest("td").next().find("[data-option='"+$value+"']").show();

});
}

