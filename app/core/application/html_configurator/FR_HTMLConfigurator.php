<?php


namespace app\core\application\html_configurator;


use app\core\util\Env;

class FR_HTMLConfigurator
{
    public function constructDocument(){
        /*$title = Env::get("FR_TITLE");*/
        $title = "Лемма";
        $scripts_injection = Env::get("FR_SCRIPTS_HTML");
        $user_interface = "";
        $content = Env::get("FR_OUTPUT_BUFFER");
        $home = "";
        $null_interface = "";

        if(0 == Env::get("WRAPPER")){
            $html_document = $content;
        }
        else{
            //будем считать, что это безопасно (потом можно переделать в parseSite(), когда сможем автоматически формировать все
            // доп данные всавляемые)
            $site = eval('return "' . addslashes(Env::get("FR_OUTPUT_SITE")) . '";');
            $html_document = "<html>".$site."</html>";
        }
        Env::set("HTML_DOCUMENT",$html_document);
    }


    //не факт, что это работает, скопипастено с какого-то сайта, чтоб не было утеряно
    function parseSite(){
        $allVars = get_defined_vars();
        $file = Env::get("FR_OUTPUT_SITE");

        foreach ($allVars as $var => $val) {
            $file = preg_replace("@\\$" . $var . "([^a-zA-Z_0-9\x7f-\xff]|$)@", $val, $file);
        }
        return $file;
    }

}
