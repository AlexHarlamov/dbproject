console.log("Hello world2");
//увидеть класс или элементы класса
$(document).ready(function(){

    console.log("Hello world3");

    $(".get-obj-2").on('click',function () {
        var classId = prompt("Введите ID Класса");
        if (isNaN(classId)) {
            alert("Неверные данные");
            h = "/nullInterface";
            $(".get-obj-2").attr('href',h);
        } else {
            var h = $(".get-obj-2").attr('href');
            h+="&CLASS_ID="+classId;
            $(".get-obj-2").attr('href',h);
        }

    });
});