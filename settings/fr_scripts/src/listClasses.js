
$(document).ready(function(){
    $(".working-area table:first").addClass("classList");
    $(".classList thead tr").append("<td>DETAILED</td><td>ELEMENTS</td><td>RELATION_FROM</td><td>RELATION_TO</td>");

    var arr= $(".classList tbody tr");
    $.each(arr,function(index,element0){

    var id = $(element0).children().first().text();

    var h = "/nullInterface/GET/?OBJ=1&CLASS_ID="+id;
    $(element0).append("<td><a href="+h+">detailed</a></td>");

    h = "/nullInterface/GET/?OBJ=2&CLASS_ID="+id;
    $(element0).append("<td><a href="+h+">elements</a></td>");

        let request = new XMLHttpRequest();
        let url = '/nullInterface/GET/?OBJ=-2&CLASS_ID='+id+'&WRAPPER=0';
        request.open('GET', url, false);
        request.send();
        let str = request.responseText;
        let current = str.replace(/#/g,id);
        $(element0).append("<td>"+current+"</td>");

        request = new XMLHttpRequest();
        url = '/nullInterface/GET/?OBJ=-3&CLASS_ID='+id+'&WRAPPER=0';
        request.open('GET', url, false);
        request.send();
        str = request.responseText;
        current = str.replace(/#/g,id);
        $(element0).append("<td>"+current+"</td>");

    });


});