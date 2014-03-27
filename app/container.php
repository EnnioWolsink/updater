<?php
/*
 * Service container
 */

// Use a function to minimize modifying the scope
$_c = function()
{
    $ns = 'Rimote\\Updater';
    
    $config = require __DIR__ . '/config/config.php';
    
    $c = new Pimple();
    
    $c['logger_class'] = $ns . '\\Util\\Logger';
    $c['logger'] = $c->factory(function($c) {
        return new $c['logger_class']();
    });
    
    $c['update_class'] = $ns . '\\Updater\\Update';
    $c['update'] = $c->factory(function($c) {
        return new $c['update_class'](
            $c['logger']
        );
    });
    
    $c['rollback_class'] = $ns . '\\Updater\\Rollback';
    $c['rollback'] = $c->factory(function($c) {
        return new $c['rollback_class'](
            $c['logger']
        );
    });
    
    return $c;
};

return $_c();
