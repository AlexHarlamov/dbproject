console.log("Hello world2");
//для создания класса
$(document).ready(function(){

    console.log("Hello world3");

    $(".accept-preview").on('click', function () {
        let template = $(".template-sample").val();
        $(".template-preview").html(template);
    });

    $(".class-edit-attr").on('click', function () {
        let attrForm ="<div class=\"form-row\">\n" +
            "      \n" +
            "    <div class=\"form-group col-md-2\">\n" +
            "      <input type=\"text\" class=\"form-control\" id=\"AttributeName#\" name=\"AttributeName#\">\n" +
            "    </div>\n" +
            "      \n" +
            "    <div class=\"form-group col-md-4\">\n" +
            "      <textarea class=\"form-control\" rows=\"1\" id=\"AttributeComment#\" placeholder=\"\" name=\"AttributeComment#\"></textarea>\n" +
            "  </div>\n" +
            "    \n" +
            "    <div class=\"form-group col-md-2\">\n" +
            "      <select id=\"AttributeType#\" class=\"form-control\" name=\"AttributeType#\">\n" +
            "        <option selected>1</option>\n" +
            "        <option>2</option>\n" +
            "        <option>3</option>\n" +
            "        <option>4</option>\n" +
            "        <option>5</option>\n" +
            "        <option>6</option>\n" +
            "        <option>7</option>\n" +
            "        <option>8</option>\n" +
            "        <option>9</option>\n" +
            "      </select>\n" +
            "         </div> \n" +
            "    \n" +
            "    <div class=\"form-group col-md-2\">\n" +
            "      <input type=\"number\" class=\"form-control\" id=\"AttributeSize#\" name=\"AttributeSize#\">\n" +
            "    </div>\n" +
            "        \n" +
            "    <div class=\"form-group col-md-1\">\n" +
            "      <input type=\"number\" class=\"form-control\" id=\"AttributeIndexed#\" name=\"AttributeIndexed#\">\n" +
            "    </div>\n" +
            "        \n" +
            " <div class=\"form-group col-md-1\">\n" +
            "      <select id=\"AttributeNull#\" class=\"form-control\" name=\"AttributeNull#\">\n" +
            "        <option selected>1</option>\n" +
            "        <option>0</option>\n" +
            "      </select>\n" +
            "    </div>\n" +
            "</div>";

        let length = $(".attributes").length;

        console.log(length);
        let current = attrForm.replace(/#/g,length);
        $(".attributes").append(current);
    });

});
