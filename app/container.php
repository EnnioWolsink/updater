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
    
    $c['db_config'] = $config['database'];
    $c['db'] = $c->share(function($c) {
        $db = $c['db_config'];
        $dsn = "mysql:dbname={$db['database']};host={$db['host']}";
        
        $pdo = new PDO($dsn, $db['username'], $db['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    });
    
    $c['logger_class'] = $ns . '\\Util\\Logger';
    $c['logger'] = $c->share(function($c) {
        return new $c['logger_class']();
    });
    
    $c['update_mapper_class'] = $ns . '\\DataMapper\\UpdateMapper';
    $c['updater_mapper'] = $c->share(function($c) {
        return new $c['update_mapper_class'](
            $c['db']
        );
    });
    
    $c['update_class'] = $ns . '\\Updater\\Update';
    $c['update'] = $c->share(function($c) {
        return new $c['update_class'](
            $c['logger']
        );
    });
    
    $c['rollback_class'] = $ns . '\\Updater\\Rollback';
    $c['rollback'] = $c->share(function($c) {
        return new $c['rollback_class'](
            $c['logger']
        );
    });
    
    return $c;
};

return $_c();
