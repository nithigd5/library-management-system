/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

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
    $("#toggleInput").on("change", function () {
        var value = $(this).data("variable");
        if (this.checked) {
            var orgPrice = (value).toFixed(2);
            $("#subTotal").val(orgPrice);
            $("#subTotal").prop("readOnly", false);
        } else {
            var rentPrice = (value * 0.1).toFixed(2);
            $("#subTotal").val(rentPrice);
            $("#subTotal").prop("readOnly", true);
            $("#subTotalPrice").text(rentPrice);
        }
    });

    $("#subTotal").keyup(function(){
        var inputValue = $(this).val();
        $("#subTotalPrice").text(inputValue);
    });

    $("#subTotal").on("input", function () {
        var maxPrice = $(this).data("variable");
        if (this.value > maxPrice) {
            this.value = maxPrice;
        }
    });
});




