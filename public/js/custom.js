/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function setFileChange(input, label) {
    label.text(input.val().split('\\').pop());
}

$("input[type='file']").each(function () {
    let input = $(this);
    let label = input.siblings("label");
    input.change(() => setFileChange(input, label));
});

$("input").each(function () {
    $(this).change(() => $(this).removeClass('is-invalid'));
})
$("select").each(function () {
    $(this).change(() => $(this).removeClass('is-invalid'));
})
$("textarea").each(function () {
    $(this).change(() => $(this).removeClass('is-invalid'));
})

$(document).ready(function () {
    let $subTotal = $("#subTotal");
    $("#toggleInput").on("change", function () {
        var value = $(this).data("variable");
        if (this.checked) {
            var orgPrice = (value).toFixed(2);
            $subTotal.val(orgPrice);
            $subTotal.prop("readOnly", false);
        } else {
            var rentPrice = (value * 0.1).toFixed(2);
            $subTotal.val(rentPrice);
            $subTotal.prop("readOnly", true);
            $("#subTotalPrice").text(rentPrice);
        }
    });

    $subTotal.keyup(function () {
        var inputValue = $(this).val();
        $("#subTotalPrice").text(inputValue);
    });

    $subTotal.on("input", function () {
        var maxPrice = $(this).data("variable");
        if (this.value > maxPrice) {
            this.value = maxPrice;
        }
    });
});

function convertFormToJSON(form) {
    const array = $(form).serializeArray(); // Encodes the set of form elements as an array of names and values.
    const json = {};
    $.each(array, function () {
        json[this.name] = this.value || "";
    });
    return json;
}

function createAndValidateAjax(form, callback) {
    let inputs = $(form+' '+'input')
    let formDataJson = JSON.stringify(convertFormToJSON(form))
    form = $(form);
    let type = form.attr('method');
    let method = form.children('input[name="_method"]');
    if(method){
        type = method.val()
    }

    $.ajax({
        url: form.attr('action'),
        dataType: "json",
        contentType: 'application/json',
        async: true,
        type: type,
        data: formDataJson,
        success: function (data) {
            inputs.each(function () {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            })
            console.log(data)
            if (callback)
                callback(data)
        },
        error: function (data) {
            let errors = data.responseJSON.errors;

            inputs.each(function () {
                let input = $(this)

                if (errors[input.attr('name')]) {
                    $(this).addClass('is-invalid');

                    let errorElem = input.siblings('.invalid-feedback')

                    errors[input.attr('name')].forEach(function (value){
                        errorElem.text(value)
                    })
                }else{
                    input.addClass('is-valid');
                }
            })
        }
    })
}

$(function () {
    var start = moment().subtract(29, 'days');
    var end = moment();

    $('#date_range').daterangepicker({
        opens: 'left',
        startDate: start,
        endDate: end,
        value: null
    }, function (start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
});
