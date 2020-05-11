console.log("Hello world2");
$(document).ready(function(){

    console.log("Hello world3");

    $(".load-EDITOR_FULL_CLASS").on('click',function () {
        let request = new XMLHttpRequest();
        let url = 'http://www.lemma/GET/?ELEMENT_ID=16&TEMPLATE_ID=16&WRAPPER=0';
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
