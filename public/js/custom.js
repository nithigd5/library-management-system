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
