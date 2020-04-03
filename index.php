<?php
/**
 * @virtual_host_entry_point index.php
 *
 * !!!RED ZONE!!!
 * !!!ATTENTION!!!
 *
 * Override this section if you 100% sure what are you doing and for what reason
 *
 */

use app\core\CoreController;

define("ROOT_DIR",__DIR__.'/');

require_once ROOT_DIR."/settings/fr_namespace_resolver/default.php";

$app = new CoreController();
$app->execute();
