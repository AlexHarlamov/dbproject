
$(document).ready(function(){
    $(".working-area table:first").addClass("classList");
    $(".classList thead tr").append("<td></td><td></td>");

    var arr= $(".classList tbody tr");
    $.each(arr,function(index,element0){

    var id = $(element0).children().first().text();

    var h = "/nullInterface/GET/?OBJ=1&CLASS_ID="+id;
    $(element0).append("<td><a href="+h+">detailed</a></td>");

    h = "/nullInterface/GET/?OBJ=2&CLASS_ID="+id;
    $(element0).append("<td><a href="+h+">elements</a></td>");


    });

});