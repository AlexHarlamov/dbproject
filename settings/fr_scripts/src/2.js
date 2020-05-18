console.log("Hello world2");
//для создания класса
$(document).ready(function(){

    console.log("Hello world3");

    $(".accept-preview").on('click', function () {
        let template = $(".template-sample").val();
        $(".template-preview").html(template);
    });

});
