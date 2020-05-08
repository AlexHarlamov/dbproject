<?php


namespace app\core\application\html_configurator;


use app\core\util\Env;

class FR_HTMLConfigurator
{
    public function constructDocument(){
        $title = "Simple title";
        $scripts_injection = "";
        $scripts_injection = Env::get("FR_SCRIPTS_HTML");
        /*$title = Env::get("FR_TITLE");*/
        $content_body = Env::get("FR_OUTPUT_BUFFER");
        $html_document = "
        <html>
            <head>
                <title>
                    $title
                 </title>
            <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\" integrity=\"sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh\" crossorigin=\"anonymous\">
            <script src=\"https://code.jquery.com/jquery-3.4.1.slim.min.js\" integrity=\"sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n\" crossorigin=\"anonymous\"></script>
            <script src=\"https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js\" integrity=\"sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo\" crossorigin=\"anonymous\"></script>
            <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\" integrity=\"sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6\" crossorigin=\"anonymous\"></script>
            $scripts_injection
            </head>
            <body>
                $content_body
            </body>
        </html>
        ";
        Env::set("HTML_DOCUMENT",$html_document);
    }
}
