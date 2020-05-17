console.log("Hello world2");
//увидеть элемент в шаблоне
$(document).ready(function(){

    console.log("Hello world3");

    $(".get-obj-3").on('click',function () {
        var elementId = prompt("Введите ID Элемента");
        var templateId = prompt("Введите ID Шаблона");
        if (isNaN(elementId) && isNaN(templateId)) {
            alert("Неверные данные");
            h = "/nullInterface";
            $(".get-obj-3").attr('href',h);
        } else {
            var h = $(".get-obj-3").attr('href');
            h+="&ELEMENT_ID="+elementId+"&"+"TEMPLATE_ID="+templateId;
            $(".get-obj-3").attr('href',h);
        }

    });
});
