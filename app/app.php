<?php
/**
* Main application file
*/
$app->get('/', function($request, $params) {
    template('powerstack/powerstack.tpl');
});
?>
