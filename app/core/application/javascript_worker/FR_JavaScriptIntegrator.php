<?php

namespace app\core\application\javascript_worker;

use app\core\exception\ScriptFileNotFoundException;
use app\core\util\Env;

/**
 * Class FR_JavaScriptIntegrator
 * @package app\core\application\javascript_worker
 *
 * Class which provides functionality of analyzing templates and integration javascript files into it
 */
class FR_JavaScriptIntegrator
{

    private array $fr_library;


    public function registerScript(string $script_path, string $script_name){
        if( !file_exists(DEFAULT_JS_PATH.$script_path)){
            throw new ScriptFileNotFoundException($script_path);
        }else{
            $this->fr_library[$script_name] = $script_path;
        }
    }

    public function processAttaching(){
        $this->inner();
        $this->site();
    }

    private function inner(){
        $content = Env::get("FR_OUTPUT_BUFFER");
        $scripts = [];
        preg_match_all(ATTACH_LIB_REGEX, $content, $matches);
        for ($i = 0 ; $i < count($matches[0]); $i++){
            $content = str_ireplace($matches[0][$i]."\r\n","",$content);
            if(!empty($this->fr_library[$matches[1][$i]])){
                array_push($scripts,$this->fr_library[$matches[1][$i]]);
            }
        }
        $scripts_html = "";
        $attached = [];
        foreach ($scripts as $val){
            if(empty($attached[$val])){
                $scripts_html .= "<script type='text/javascript' src='http://".$_SERVER['SERVER_NAME']."/".REQUEST_JS_PATH."$val'></script>";
                $attached[$val] = 1;
            }
        }
        Env::set("FR_OUTPUT_BUFFER",$content);
        Env::set("FR_SCRIPTS_HTML",$scripts_html);
    }

    private function site(){
        $content = Env::get("FR_OUTPUT_SITE");
        $scripts = [];
        preg_match_all(ATTACH_LIB_REGEX, $content, $matches);
        for ($i = 0 ; $i < count($matches[0]); $i++){
            $content = str_ireplace($matches[0][$i]."\r\n","",$content);
            if(!empty($this->fr_library[$matches[1][$i]])){
                array_push($scripts,$this->fr_library[$matches[1][$i]]);
            }
        }
        $scripts_html = "";
        $attached = [];
        foreach ($scripts as $val){
            if(empty($attached[$val])){
                $scripts_html .= "<script type='text/javascript' src='http://".$_SERVER['SERVER_NAME']."/".REQUEST_JS_PATH."$val'></script>";
                $attached[$val] = 1;
            }
        }
        Env::set("FR_OUTPUT_SITE",$content);
        Env::set("FR_SCRIPTS_HTML",Env::get("FR_SCRIPTS_HTML").$scripts_html);
    }
}
