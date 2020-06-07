<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use munkireport\lib\Modules as ModuleManager;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

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
        $moduleFullName = $this->askModuleName( $default = 'I ❤️ MunkiReport' );
        $moduleName = Str::slug($moduleFullName, '_');
        $moduleClassName = ucfirst($moduleName);
        $this->files->deleteDirectory($modulePath.$moduleName);
        $moduleInstallPath = $this->validateInstall($modulePath, $moduleName);
        $numberOfFields = $this->askNumberOfFields( $default = 3 );
        $moduleTable = $this->askFieldDetails($numberOfFields, $moduleName);

        if($this->choice("Do you need to store more than one row per machine?", ["yes", "no"], 1) == "yes"){
            $moduleTable['serial_number']['index'] = 'yes';
        }

        $headers = ["Column", "Type", "Indexed", "English"];
        $this->table($headers, $this->_toTable($moduleTable));

        // if ( ! $this->confirm('Do you wish to continue?')) {
        //    throw new RuntimeException("Well that's too bad!");
        // }        
        $search = [
            'MODULE' => $moduleName,
            'CLASS' => ucfirst($moduleName),
            'LISTING' => $this->tableToListingFields($moduleName, $moduleTable),
        ];
        $this->createBase($moduleInstallPath, $moduleName, $search);
        $this->createScripts($moduleInstallPath, $moduleName, $search);
        $this->createViews($moduleInstallPath, $moduleName, $search);
        $this->createLocales($moduleInstallPath, $moduleFullName, $moduleTable);
        $this->createMigrations($moduleInstallPath, $moduleName, $moduleTable);

    }

    private function tableToLocales($moduleTable)
    {
        $out = [];
        foreach ($moduleTable as $field) {
            if( ! isset($field['en']) || $field['column'] == 'serial_number'){
                continue;
            }
            $out[$field['column']] = $field['en'];
        }
        return $out;
    }

    private function createMigrations($moduleInstallPath, $module, $moduleTable)
    {
        $this->call('make:migration', [
            'path' => $moduleInstallPath,
            '--field' => $this->tableToMigrationFields($moduleTable)
        ]);
    }
    
    private function tableToMigrationFields($moduleTable)
    {
        $out = [];
        foreach ($moduleTable as $field) {
            $out[] = implode(',', [
                $field['column'],
                $field['type'],
                $field['index'] ?? '',
            ]);
        }
        return $out;
    }

    private function tableToListingFields($module, $moduleTable)
    {
        $out = '';
        foreach ($moduleTable as $field) {
            if( ! isset($field['i18n']) || $field['column'] == 'serial_number'){
                continue;
            }
            $out .= "    -   column: ".$module.".".$field['column']."\n";
            $out .= "        i18n_header: ".$field['i18n']."\n";
            if( $field['type'] == 'boolean'){
                $out .= "        formatter: binaryYesNo\n";
            }
        }
        return $out;
    }

    private function createLocales($moduleInstallPath, $moduleFullName, $moduleTable)
    {
        $localesdir = $moduleInstallPath.'locales/';
        $this->files->makeDirectory($localesdir);
        $this->saveStub(
            $localesdir.'en.json',
            json_encode(
                [
                    'column' => $this->tableToLocales($moduleTable),
                    'listing' => [
                        'title' => $moduleFullName,
                    ],
                    'report' => [
                        'title' => $moduleFullName,
                    ],
                    'title' => $moduleFullName,
                    'widget' => [
                        'title' => $moduleFullName . ' Widget',
                    ]
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    private function createViews($moduleInstallPath, $module, $search)
    {
        $viewsdir = $moduleInstallPath.'views/';
        $this->files->makeDirectory($viewsdir);
        $this->loadReplaceAndSaveStub('listing', $viewsdir.$module.'_listing.yml', $search);
        $this->loadReplaceAndSaveStub('report', $viewsdir.$module.'_report.yml', $search);
        $this->loadReplaceAndSaveStub('widget', $viewsdir.$module.'_widget.yml', $search);
        $this->loadReplaceAndSaveStub('client_tab', $viewsdir.$module.'_tab.php', $search);
    }

    private function createBase($moduleInstallPath, $module, $search)
    {
        $this->files->makeDirectory($moduleInstallPath);
        $this->loadReplaceAndSaveStub('provides', $moduleInstallPath.'provides.yml', $search);
        $this->loadReplaceAndSaveStub('controller', $moduleInstallPath.$module.'_controller.php', $search);
        $this->loadReplaceAndSaveStub('model', $moduleInstallPath.$module.'_model.php', $search);
        $this->loadReplaceAndSaveStub('processor', $moduleInstallPath.$module.'_processor.php', $search);
        $this->loadReplaceAndSaveStub('factory', $moduleInstallPath.$module.'_factory.php', $search);
        $this->loadReplaceAndSaveStub('composer', $moduleInstallPath.'composer.json', $search);
    }

    private function createScripts($moduleInstallPath, $module, $search)
    {
        $scriptsdir = $moduleInstallPath.'scripts/';
        $this->files->makeDirectory($scriptsdir);
        $this->loadReplaceAndSaveStub('install', $scriptsdir.'install.sh', $search);
        $this->loadReplaceAndSaveStub('uninstall', $scriptsdir.'uninstall.sh', $search);
        $this->loadReplaceAndSaveStub('module_script', $scriptsdir.$module.'.sh', $search);
    }

    private function loadReplaceAndSaveStub($stub, $target, $search = [])
    {
        $this->saveStub(
            $target,
            $this->populateStub(
                $this->getStub($stub),
                $search
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
            $field_locale = $this->ask("What is the (short) English description of field $field_number?", "Field $field_number");
            $field_name = $this->ask("What is the name of field $field_number?", Str::slug($field_locale, '_'));
            $field_type = $this->choice("What is the type of field $field_number?", $field_types, 0);
            $field_index = $this->choice("Create index for field $field_number?", ['yes', 'no'], 0);
            $table[$field_name] = [
                'column' => $field_name,
                'type' => $field_type,
                'index' => $field_index,
                'i18n' => "$name.column.$field_name",
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
