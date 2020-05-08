console.log("Hello world0");
$(document).ready(function(){

    console.log("Hello world1");

    $(".load-template-edit-form").on('click',function () {
        let request = new XMLHttpRequest();
        let url = 'http://axiom.dev/GET/?ELEMENT_ID=16&TEMPLATE_ID=5';
        request.open('GET', url);
        request.onload = function () {
            $(".working-area").html(request.response);
            $(".accept-preview").on('click', function () {
                let template = $(".template-sample").val();
                $(".template-preview").html(template);
            });
        };
        request.responseType = 'text';
        request.send();
    });
});
