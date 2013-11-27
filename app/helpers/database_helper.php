<?php

/**
 * Migrate database table 
 * 
 * Partly borrowed from codeigniter 
 *
 * @return mixed
 **/
function migrate($model_obj)
{
	$model_name = get_class($model_obj);
	$migration_dir = conf('application_path').'migrations/'.strtolower($model_name);
	$target_version = $model_obj->get_version();
	$current_version = $model_obj->get_schema_version();

	// Check if directory exists
	if( ! is_dir($migration_dir))
	{
		error('Migration error: no migrations found in '.$migration_dir);
		return FALSE;
	}

	$migration_list = array();

	// Get migration files
	foreach (glob($migration_dir.'/*_*.php') as $file)
	{
		$name = basename($file, '.php');
		$number = intval(strtok($name, '_'));
		
		$migration_list[$number] = $file;
	}

	if( $target_version > 0 && ! isset($migration_list[$target_version]))
	{
		echo('Migration error: migration '.$target_version.' not found');
		return FALSE;
	}

	if( $target_version > $current_version )
	{
		ksort($migration_list);
		$method = 'up';
	}
	else
	{
		krsort($migration_list);
		$method = 'down';
	}
	$migration_obj = new Migration($model_obj->get_table_name());
			
	foreach ($migration_list as $number => $file)
	{
		include_once $file;

		$name = basename($file, '.php');
		$class = 'Migration'.substr($name, strpos($name, '_'));

		if ( ! class_exists($class, FALSE))
        {
            error('Migration error: migration class '.$class.' not found');
            return FALSE;
        }

		if (($method === 'up'   && $number > $current_version && $number <= $target_version) OR
			($method === 'down' && $number <= $current_version && $number > $target_version))
		{
			$instance = new $class();
			if(call_user_func(array($instance, $method)) === FALSE )
			{
				error('Migration error: '.$instance->get_errors());
	            return FALSE;
			}

			$migration_obj->version = $number;
			$migration_obj->save();
		}
	}

	if ($current_version != $target_version)
	{
		$migration_obj->version = $target_version;
		$migration_obj->save();
	}
	alert('Migrated '.$model_name.' to version '.$migration_obj->version);
}