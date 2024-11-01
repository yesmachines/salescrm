/* Select2 Init*/
"use strict";
$(".select2").select2();

$(".model-select2").select2({
    dropdownParent: $(".modal")
});
$("#input_tags").select2({
    tags: true,
    tokenSeparators: [',', ' ']
});