<?php
/*
 * Service container
 */

// Use a function to minimize modifying the scope
$_container = function()
{
    $name_space = 'Rimote\\Updater';
    $container = new Pimple();
    
    $container['logger_class'] = $name_space . '\\Util\\Logger';
    $container['logger'] = $container->factory(function($container) {
        return new $container['logger_class']();
    });
    
    $container['update_class'] = $name_space . '\\Updater\\Update';
    $container['update'] = $container->factory(function($container) {
        return new $container['update_class'](
            $container['logger']
        );
    });
    
    $container['rollback_class'] = $name_space . '\\Updater\\Rollback';
    $container['rollback'] = $container->factory(function($container) {
        return new $container['rollback_class'](
            $container['logger']
        );
    });
    
    return $container;
};

return $_container();
