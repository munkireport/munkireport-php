<?php

use munkireport\models\Migration;

/**
 * undocumented function
 *
 * @return void
 * @author
 **/
function create_table($model)
{
    // Get columns
    $columns = array();
    foreach ($model->rs as $name => $val) {
    // Determine type automagically
        $type = $model->get_type($val);

        // Or set type from type array
        $columns[$name] = isset($model->rt[$name]) ? $model->rt[$name] : $type;
    }

    // Set primary key
    $columns[$model->pkname] = 'INTEGER PRIMARY KEY';

    // Table options, override per driver
    $tbl_options = '';

    // Driver specific options
    switch ($model->get_driver()) {
        case 'sqlite':
            $columns[$model->pkname] .= ' AUTOINCREMENT';
            break;
        case 'mysql':
            $columns[$model->pkname] .= ' AUTO_INCREMENT';
            $tbl_options = conf('mysql_create_tbl_opts');
            break;
    }

    // Compile columns sql
    $sql = '';
    foreach ($columns as $name => $type) {
        $sql .= $model->enquote($name) . " $type,";
    }
    $sql = rtrim($sql, ',');

    $dbh = $model->getdbh();
    $dbh->exec(sprintf("CREATE TABLE %s (%s) %s", $model->enquote($model->tablename), $sql, $tbl_options));

    // Set indexes
    $model->set_indexes();
}

/**
 * Migrate database table
 *
 * Partly borrowed from codeigniter
 *
 * @return mixed
 **/
function migrate($model_obj)
{
    $ctl = new Controller;
    if (! $ctl->authorized('global')) {
        throw new Exception("Only migrate in admin session", 1);
    }
    
    $model_name = get_class($model_obj);
    $module_name = str_replace('_model', '', strtolower($model_name));
    $target_version = $model_obj->get_version();
    $current_version = $model_obj->get_schema_version();

    // Try to get migration path from moduleManager
    if( ! getMrModuleObj()->getModuleMigrationPath($module_name, $migration_dir))
    {
        $migration_dir = conf('application_path').'migrations/'.strtolower($model_name);
    }

    // Check if directory exists
    if (! is_dir($migration_dir)) {
        throw new Exception('no migrations found in '.$migration_dir);
    }

    $migration_list = array();

    // Get migration files
    foreach (glob($migration_dir.'/*_*.php') as $file) {
        $name = basename($file, '.php');
        $number = intval(strtok($name, '_'));

        $migration_list[$number] = $file;
    }
    
    if ($current_version > 0 && ! isset($migration_list[$current_version])) {
        throw new Exception($model_name.' migration '.$current_version.' not found');
    }

    if ($target_version > 0 && ! isset($migration_list[$target_version])) {
        throw new Exception($model_name.' migration '.$target_version.' not found');
    }

    if ($target_version > $current_version) {
        ksort($migration_list);
        $method = 'up';
    } else {
        krsort($migration_list);
        $method = 'down';
    }
    $migration_obj = new Migration($model_obj->get_table_name());

    foreach ($migration_list as $number => $file) {
        include_once $file;

        $name = basename($file, '.php');
        $class = 'Migration'.substr($name, strpos($name, '_'));

        if (! class_exists($class, false)) {
            throw new Exception('migration class '.$class.' not found');
        }

        if (($method === 'up'   && $number > $current_version && $number <= $target_version) or
            ($method === 'down' && $number <= $current_version && $number > $target_version)) {
            $instance = new $class();

            // Check if we have up and down
            if (! method_exists($instance, 'up') || ! method_exists($instance, 'down')) {
                throw new Exception("up() or down() missing from $class");
            }

            if (call_user_func(array($instance, $method)) === false) {
                throw new Exception($instance->get_errors());
            }

            $migration_obj->version = $number;
            $migration_obj->save();
        }
    }

    if ($current_version != $target_version) {
        $migration_obj->version = $target_version;
        $migration_obj->save();
    }
}
