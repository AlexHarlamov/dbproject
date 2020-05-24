//добвать просмотр через шаблоны
$(document).ready(function(){
    $(".working-area table:first").addClass("elementList");
    $(".elementList thead tr").append("<td>With template</td>");

    let currentUrl = window.location.href;
    let key = 'CLASS_ID';

    query_string = currentUrl.split('?');
    string_values = query_string[1].split('&');
    for(i=0;  i < string_values.length; i++) {
        if( string_values[i].match(key))
            req_value = string_values[i].split('=');
    }
    let classId = req_value[1];

    let request = new XMLHttpRequest();
    let url = '/nullInterface/GET/?OBJ=-1&CLASS_ID='+classId+'&WRAPPER=0';
    request.open('GET', url, false);
    request.send();
    let str = request.responseText;

    let arr= $(".elementList tbody tr");
    $.each(arr,function(index,element0){

        let elementId = $(element0).children().first().text();
        console.log(elementId);
        let current = str.replace(/#/g,elementId);
        console.log(current);
        $(element0).append("<td>"+current+"</td>");
    });


});