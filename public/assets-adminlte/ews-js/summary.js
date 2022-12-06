$("span.number").each(function () {
    var v = Number($(this).text()).toLocaleString('en-IN');
    $(this).text(v);
});