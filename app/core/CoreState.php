<?php


namespace app\core;


interface CoreState
{
    function fr_defaultAction() : CoreState;

    function fr_executeHookCallbacks($state_option = null);
}
