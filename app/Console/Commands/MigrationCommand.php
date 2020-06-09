<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\RuntimeException;


class MigrationCommand extends Command
{
    use StubTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration
                            {path : The path to the module}
                            {name=init : Name of the migration}
                            {--t|table= : Table name (if not set, name of the module)}
                            {--f|field=* : Field description (name,type,indexed)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $columns = '';
        $indexes = '';

        $module_path = $this->argument('path');
        $module = basename($module_path);
        $migration_name = $module . '_' . $this->argument('name');
        $migrations_dir = $module_path . '/migrations/';
        $filename = date('Y_m_d_His_') . $migration_name .'.php';
        $classname = str_replace('_', '', ucwords($migration_name, '_'));
        $tablename = $this->option('table') ?? $module;

        $this->validateModulePath($module_path);
        $this->ensureMigrationDirectoryExists($migrations_dir);
        $this->validateMigrationName($migrations_dir, $migration_name, $module);
        
        if($this->argument('name') == 'init'){
            $stub = $this->getStub('init_migration');
        }else{
            $stub = $this->getStub('migration');
        }

        $this->parseFieldData($this->option('field'), $columns, $indexes);

        $this->saveStub(
            "${migrations_dir}${filename}",
            $this->populateStub(
                $stub,
                [
                    'CLASSNAME' => $classname,
                    'TABLENAME' => $tablename,
                    '//COLUMNS' => $columns,
                    '//INDEXES' => $indexes
                ]
            )
        );
        if( ! $this->option('quiet') ){
            $this->info("Created migration at ${migrations_dir}${filename}");
        }
    }

    public function validateMigrationName($migrations_dir, $migration_name, $module)
    {
        $initial_migration_found = false;
        foreach(scandir($migrations_dir) as $migration_filename){
          $regex = '/' . $module . '(_init)?\.php/';
          if (preg_match($regex, $migration_filename)){
            $initial_migration_found = $migration_filename;
          }elseif (strpos($migration_filename, "${migration_name}.php")){
            throw new RuntimeException("Found migration ending in $migration_name, please provide another migration name\n");
          }
        }
        if ($initial_migration_found && $migration_name == $module . '_init'){
            throw new RuntimeException("Found initial migration: $migration_filename, please provide a migration name\n");
        }          
    }

    public function validateModulePath($module_path)
    {
        if ( ! file_exists($module_path)) {
            throw new RuntimeException("Module does not exist, please provide an existing path to a module directory.\n");
        }
    }

    public function ensureMigrationDirectoryExists($migrations_dir)
    {
        if ( ! file_exists($migrations_dir)) {
            mkdir($migrations_dir, 0777, true);
        }
    }

    private function parseFieldData($columnArray, &$columns, &$indexes)
    {
        $serial_number_found = false;
        foreach ($columnArray as $key => $value) {
            // Split $values
            list($name, $type, $indexed) = explode(',', $value);
            if($name == 'id'){
                continue;
            }
            if($name == 'serial_number'){
                if($indexed == 'unique'){
                    $indexes .= $this->renderUniqueDefinition($name);
                }
                else{
                    $indexes .= $this->renderIndexDefinition($name);
                }
                $serial_number_found = true;
                continue;
            }
            $columns .= $this->renderColumnDefinition($name, $type);
            if($indexed == 'yes'){
                $indexes .= $this->renderIndexDefinition($name);
            }
        }
        // If serial_number is not defined, assume it needs to be unique
        if(! $serial_number_found ){
            $indexes .= $this->renderUniqueDefinition('serial_number');
        }
    }

    public function renderColumnDefinition($name, $type)
    {
        return $this->renderDefinition($name, $type, $postfix = "->nullable()");
    }

    public function renderIndexDefinition($name)
    {
        return $this->renderDefinition($name, 'index');
    }

    public function renderUniqueDefinition($name)
    {
        return $this->renderDefinition($name, 'unique');
    }

    public function renderDefinition($name, $type, $postfix = "")
    {
        return "            \$table->${type}('${name}')${postfix};\n";
    }
}
