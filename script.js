let cnt = 0;

$(document).ready(function(){
    $.each(ranges, function(a,b) { 
        if(!$("#" + b.group).length) $("#results").append(`<div class="groupname" id="${b.group}"><h4>Network ${b.group}</h4></div>`);
        getRange(b.start,b.end, b.group);
    });
});

function getRange(startip,endip,group) {

    $.get("scanrange.php?start=" + startip + "&end=" + endip, function(data) {
        $("#" + group).append(makeTable(data, startip, endip));
        $("#" + group).append("<hr/>");
        $(".groupname").show();
    });
}

function makeTable(data, startip, endip) {

    cnt = cnt +1;

    let tab = $(`<table data-weight="${cnt}"/>`);
    tab.append(`<tr><td colspan="2" class="tabletitle">${startip} - ${endip}</td></tr>`);
    $.each(data, function(a,b) {
        let e = `<tr><td>${b.ip}</td><td>${b.name}</td></tr>`;
        tab.append(e);
    });
    return tab;
}
