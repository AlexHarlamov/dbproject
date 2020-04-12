<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\util\App;
use app\core\util\Env;
use Exception;

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
        // TODO: Implement fr_defaultAction() method.
        try {
            echo App::call(TEMPLATE_WORKER, "simpleInjecting", [
                "template" => "@1 @2 ",
                "vars" => [
                    "1" => "max",
                    "2" => "pleasure"
                ]
            ]);

            $parser = App::getApp(TEMPLATE_WORKER);

            echo $parser->simpleInjecting([
                "template" => "@1 @2 ",
                "vars" => [
                    "1" => "max",
                    "2" => "pleasure"
                ]
            ]);

            echo implode(", ", Env::get("LastSelect")[0]);

        } catch (Exception $exception){
            echo $exception;
        }

        return $this->fr_DefaultNextCoreStateAfterParseTemplate;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}