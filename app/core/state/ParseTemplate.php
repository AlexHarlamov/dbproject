<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\util\Env;

class ParseTemplate implements CoreState
{
    /**
     * @var CoreState $fr_DefaultNextCoreStateAfterParseTemplate
     *
     * required return CoreState type variable of lifecycle core state.
     *
     */

    private CoreState $fr_DefaultNextCoreStateAfterParseTemplate;

    public function __construct()
    {
        $this->fr_DefaultNextCoreStateAfterParseTemplate = new Terminate();
    }


    function fr_defaultAction(): CoreState
    {
        if(Env::get("WRAPPER") == 1) {
            $template = Env::get("SITE_TEMPLATE");
            $data = Env::get("SITE_DATA");
            $site = recursionParseLoop($template, $data); //Can be converted into application call (return value available)
            Env::set("FR_OUTPUT_SITE", $site);
        }

        $template = Env::get("VIEW_TEMPLATE");
        $data = Env::get("VIEW_DATA");
        $html = recursionParseLoop($template,$data); //Can be converted into application call (return value available)

        Env::set("FR_OUTPUT_BUFFER",$html);
        return $this->fr_DefaultNextCoreStateAfterParseTemplate;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
