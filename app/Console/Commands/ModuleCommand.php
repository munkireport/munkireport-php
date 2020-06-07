<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use munkireport\lib\Modules as ModuleManager;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Filesystem\Filesystem;

class ModuleCommand extends Command
{

    use StubTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

    /** 
     * Instance of Module Manager
     * 
     * @var object
     */
    protected $module_manager = null;

    /** 
     * Instance of Filesystem
     * 
     * @var object
     */
    protected $files = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->module_manager = new ModuleManager;
        $this->files = new Filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Creating a module is cool!');

        $modulePath = $this->askInstallPath();        
        $moduleName = $this->askModuleName( $default = 'my_cool_module' );
        $this->files->deleteDirectory($modulePath.$moduleName);
        $moduleInstallPath = $this->validateInstall($modulePath, $moduleName);
        // $numberOfFields = $this->askNumberOfFields( $default = 3 );
        // $moduleTable = $this->askFieldDetails($numberOfFields, $moduleName);

        // if($this->choice("Do you need to store more than one row per machine?", ["yes", "no"], 1) == "yes"){
        //     $moduleTable['serial_number']['index'] = 'yes';
        // }

        // $headers = ["Column", "Type", "Indexed", "English"];
        // $this->table($headers, $this->_toTable($moduleTable));

        // if ( ! $this->confirm('Do you wish to continue?')) {
        //    throw new RuntimeException("Well that's too bad!");
        // }

        $this->files->makeDirectory($moduleInstallPath);
        $this->files->makeDirectory($moduleInstallPath.'scripts/');
        $this->files->makeDirectory($moduleInstallPath.'views/');
        $this->files->makeDirectory($moduleInstallPath.'locales/');
        $this->files->makeDirectory($moduleInstallPath.'migrations/');
        
        
        $this->saveStub(
            $moduleInstallPath.'provides.yml',
            $this->populateStub(
                $this->getStub('provides'),
                ['MODULE'],
                [$moduleName]
            )
        );
        $this->getStub('provides');
        //cat "${DIR}/templates/provides.yml" | sed "s/MODULE/${MODULE}/g" > "${MODULE_PATH}/provides.yml"


        // $this->call('email:send', [
        //     'user' => 1, '--queue' => 'default'
        // ]);

    }

    private function loadReplaceAndSaveStub($stub, $target, $search = [])
    {
        $this->saveStub(
            $target,
            $this->populateStub(
                $this->getStub($stub),
                array_keys($search),
                array_values($search)
            )
        );
    }

    private function askInstallPath()
    {
        return $this->choice(
            "Where do you want to generate the module?",
            $this->module_manager->getModuleSearchPaths(),
            0
        );
    }

    private function askModuleName( $default )
    {
        return $this->ask( 'What is the name of the module?', $default );
    }

    private function validateInstall($path, $name)
    {
        if(file_exists($path.$name)){
            throw new RuntimeException("There is aleady a module at " . $path.$name, 1);
        }
        return $path.$name.'/';
    }

    private function askNumberOfFields($default)
    {
        return intval($this->ask('How many database fields do you need? (apart from id and serial_number)', $default));
    }

    private function askFieldDetails($number_of_fields, $name)
    {
        $table = [
            'id' => [
                'column' => 'id',
                'type' => 'increments',
                'hidden' => 'true',
            ],
            'serial_number' => [
                'column' => 'serial_number',
                'type' => 'string',
                'index' => 'unique',
                'en' => 'Serial Number',
                'i18n' => 'reportdata.serial_number',
            ],
        ];
        $field_types = [
            'string',
            'integer',
            'bigInteger',
            'boolean',
            'text',
        ];
        $field_number = 0;
        while($number_of_fields > $field_number++){
            $field_name = $this->ask("What is the name of field $field_number?", "field$field_number");
            $field_type = $this->choice("What is the type of field $field_number?", $field_types, 0);
            $field_index = $this->choice("Create index for field $field_number?", ['yes', 'no'], 0);
            $field_locale = $this->ask("What is the english description of field $field_number?", "Field $field_number");
            $table[$field_name] = [
                'column' => $field_name,
                'type' => $field_type,
                'index' => $field_index,
                'i18n' => "$name.$field_name",
                'en' => $field_locale,
            ];
            $this->table(
                array_keys( $table[$field_name] ),
                [ array_values( $table[$field_name] ) ]
            );
        }
        return $table;
    }

    private function _createMigration($table, $path)
    {
        # code...
    }

    private function _toTable($table)
    {
       $out = [];
        foreach ($table as $key => $value) {
           $out[] = [
               $value['column'],
               $value['type'],
               $value['index'] ?? '',
               $value['en'] ?? '',
           ];
       }
       return $out;
    }
}
