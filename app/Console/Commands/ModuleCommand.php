<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use munkireport\lib\Modules as ModuleManager;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ModuleCommand extends Command
{

    use StubTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module
                            {--o|overwrite : Overwrite module if exists}
    ';

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
     * Array holding field types
     * 
     * @var array
     */
    protected $fieldTypes = null;

    /** 
     * Module name
     * 
     * @var array
     */
    protected $moduleName = null;

    /** 
     * Module install path
     * 
     * @var array
     */
    protected $moduleInstallPath = null;

    /** 
     * Module table data
     * 
     * @var array
     */
    protected $moduleTable = null;

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
        $this->fieldTypes = [
            'string' => ['faker' => 'word()'],
            'integer' => ['faker' => 'randomNumber($nbDigits = 4, $strict = false)'],
            'bigInteger' => ['faker' => 'randomNumber($nbDigits = 8, $strict = false)'],
            'boolean' => ['faker' => 'boolean()'],
            'text' => ['faker' => 'text($maxNbChars = 200)'],
        ];
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
        $this->moduleName = Str::slug($moduleFullName, '_');
        if($this->option('overwrite')){
            $this->comment('Overwriting previous module');
            $this->files->deleteDirectory($modulePath.$this->moduleName);
        }
        $this->moduleInstallPath = $this->validateInstall($modulePath, $this->moduleName);
        $numberOfFields = $this->askNumberOfFields( $default = 3 );
        $this->moduleTable = $this->askFieldDetails($numberOfFields, $this->moduleName);

        if($this->choice("Do you need to store more than one row per machine?", ["yes", "no"], 1) == "yes"){
            $this->moduleTable['serial_number']['index'] = 'yes';
        }

        $this->info('Proposed database layout:');
        $headers = ["Column", "Type", "Indexed", "English"];
        $this->table($headers, $this->_toTable($this->moduleTable));

        if ( ! $this->confirm('Do you wish to continue?', 'yes')) {
           throw new RuntimeException("Better next time!");
        }

        $search = $this->getSearchAndReplace();
        $this->createBase($search);
        $this->createScripts($search);
        $this->createViews($search);
        $this->createLocales($moduleFullName);
        $this->createMigrations();

        $this->comment("Your module is ready! It's available here:\n");
        $this->comment($this->moduleInstallPath);

    }

    private function getSearchAndReplace()
    {
        return [
            'MODULE' => $this->moduleName,
            'CLASS' => ucfirst($this->moduleName),
            'LISTING' => $this->tableToListingFields(),
            'FAKER' => $this->tableToFaker(),
            'FILLABLE' => $this->getFillable(),
        ];
    }

    private function getFillable()
    {
        $fillable = '';
        foreach ($this->moduleTable as $field) {
            if( $field['column'] == 'id' || $field['column'] == 'serial_number'){
                continue;
            }
            $fillable .= "      '" . $field['column'] . "',\n";
        }
        return $fillable;
    }

    private function getWidgets()
    {
        $widgets = [];
        foreach($this->moduleTable as $field)
        {
            if(! isset($field['widget']) || $field['widget'] == 'no'){
                continue;
            }
            $name = $this->getWidgetName($field['column']);
            $widgets[$name] = ['view' => $name . '_widget'];
        }
        return ['widgets' => $widgets];
    }

    private function createProvidesYaml()
    {
        $provides = $this->getProvidesBase();
        
        $this->yamlWrite(
            $this->moduleInstallPath.'provides.yml',
            array_merge($this->getProvidesBase(), $this->getWidgets())
        );
    }

    private function getProvidesBase()
    {
        return [
            'client_tabs' => [
                $this->moduleName => [
                    'view' => $this->moduleName.'_tab',
                    'i18n' => $this->moduleName.'.title',
                ],
            ],
            'listings' =>[
                $this->moduleName => [        
                    'view' => $this->moduleName.'_listing',
                    'i18n' => $this->moduleName.'.listing.title',
                ],
            ],
            'reports' =>[
                $this->moduleName => [        
                    'view' => $this->moduleName.'_report',
                    'i18n' => $this->moduleName.'.report.title',
                ],
            ],
        ];
    }

    private function yamlWrite($path, $data)
    {
        $this->saveStub($path, Yaml::dump($data, 3));
    }

    private function tableToFaker()
    {
        $out = '';
        foreach ($this->moduleTable as $field) {
            if( ! isset($field['i18n']) || $field['column'] == 'serial_number'){
                continue;
            }
            $out .= $this->getFakerString($field);            
        }
        return substr($out, 0, -1);
    }

    private function getFakerString($field)
    {
        return "        '".$field['column']."' => \$faker->".$this->fieldTypes[$field['type']]['faker'].",\n";
    }

    private function getFieldTypes()
    {
        return array_keys($this->fieldTypes);
    }

    private function tableToLocales()
    {
        $out = [];
        foreach ($this->moduleTable as $field) {
            if( ! isset($field['en']) || $field['column'] == 'serial_number'){
                continue;
            }
            $out[$field['column']] = $field['en'];
        }
        return $out;
    }

    private function tableToWidgets()
    {
        $out = [];
        foreach ($this->moduleTable as $field) {
            if( ! isset($field['en']) || $field['column'] == 'serial_number'){
                continue;
            }
            $key = $field['column'].'_title';
            $out[$key] = $field['en'];
        }
        return $out;
    }

    private function createMigrations()
    {
        $this->call('make:migration', [
            'path' => $this->moduleInstallPath,
            '--field' => $this->tableToMigrationFields($this->moduleTable),
            '--quiet' => true,
        ]);
    }
    
    private function tableToMigrationFields()
    {
        $out = [];
        foreach ($this->moduleTable as $field) {
            $out[] = implode(',', [
                $field['column'],
                $field['type'],
                $field['index'] ?? '',
            ]);
        }
        return $out;
    }

    private function tableToListingFields()
    {
        $out = '';
        foreach ($this->moduleTable as $field) {
            if( ! isset($field['i18n']) || $field['column'] == 'serial_number'){
                continue;
            }
            $out .= "    -   column: ".$this->moduleName.".".$field['column']."\n";
            $out .= "        i18n_header: ".$field['i18n']."\n";
            if( $field['type'] == 'boolean'){
                $out .= "        formatter: binaryYesNo\n";
            }
        }
        return $out;
    }

    private function createLocales($moduleFullName)
    {
        $localesdir = $this->moduleInstallPath.'locales/';
        $this->files->makeDirectory($localesdir);
        $this->saveStub(
            $localesdir.'en.json',
            json_encode(
                [
                    'column' => $this->tableToLocales($this->moduleTable),
                    'listing' => [
                        'title' => $moduleFullName,
                    ],
                    'report' => [
                        'title' => $moduleFullName,
                    ],
                    'title' => $moduleFullName,
                    'widget' => $this->tableToWidgets($this->moduleTable),
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    private function createViews($search)
    {
        $viewsdir = $this->moduleInstallPath.'views/';
        $this->files->makeDirectory($viewsdir);
        $this->loadReplaceAndSaveStub('listing', $viewsdir.$this->moduleName.'_listing.yml', $search);
        $this->loadReplaceAndSaveStub('client_tab', $viewsdir.$this->moduleName.'_tab.php', $search);
        $this->createWidgets($viewsdir, $search);
        $this->createReportYaml($viewsdir);
    }

    private function createWidgets($viewsdir, $search)
    {
        foreach($this->moduleTable as $field)
        {
            if(! isset($field['widget']) || $field['widget'] == 'no'){
                continue;
            }

            $search['COLUMN'] = $field['column'];
            $this->loadReplaceAndSaveStub(
                $field['widget'].'widget',
                $viewsdir.$this->getWidgetName($field['column']).'_widget.yml',
                $search
            );
        }

    }

    private function createReportYaml($viewsdir)
    {
        $widgets = $this->getWidgets();
        array_walk($widgets['widgets'], function(&$value, $key){
            $value = [];
        });
        
        $this->yamlWrite(
            $viewsdir.$this->moduleName.'_report.yml',
            ['row1' => $widgets['widgets']]
        );
    }

    private function getWidgetName($column)
    {
        return $this->moduleName.'_'.$column;
    }

    private function createBase($search)
    {
        $this->files->makeDirectory($this->moduleInstallPath);
        $this->loadReplaceAndSaveStub('composer', $this->moduleInstallPath.'composer.json', $search);
        $this->loadReplaceAndSaveStub('controller', $this->moduleInstallPath.$this->moduleName.'_controller.php', $search);
        $this->loadReplaceAndSaveStub('model', $this->moduleInstallPath.$this->moduleName.'_model.php', $search);
        $this->loadReplaceAndSaveStub('processor', $this->moduleInstallPath.$this->moduleName.'_processor.php', $search);
        $this->loadReplaceAndSaveStub('factory', $this->moduleInstallPath.$this->moduleName.'_factory.php', $search);
        $this->createProvidesYaml();
    }

    private function createScripts($search)
    {
        $scriptsdir = $this->moduleInstallPath.'scripts/';
        $this->createModuleScriptSearch($search);
        $this->files->makeDirectory($scriptsdir);
        $this->loadReplaceAndSaveStub('install', $scriptsdir.'install.sh', $search);
        $this->loadReplaceAndSaveStub('uninstall', $scriptsdir.'uninstall.sh', $search);
        $this->loadReplaceAndSaveStub('module_script', $scriptsdir.$this->moduleName.'.sh', $search);
    }

    private function createModuleScriptSearch(&$search)
    {
        $logic = "# Replace 'echo' in the following lines with the data collection commands for your module.\n";
        $dataoutput = '';
        $count = 0;
        foreach ($this->moduleTable as $field) {
            if( ! isset($field['i18n']) || $field['column'] == 'serial_number'){
                continue;
            }
            $fieldName = $field['column'];
            $uppercaseField = strtoupper($fieldName);
            $logic .= "$uppercaseField=\$(echo)\n";
            $redirect = $count++ ? '>' : '';
            $dataoutput .= "echo \"$fieldName\${SEPARATOR}\${".$uppercaseField."}\" >$redirect \${OUTPUT_FILE}\n";
        }
        $search['LOGIC'] = $logic;
        $search['DATAOUTPUT'] = $dataoutput;
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
        $field_types = $this->getFieldTypes();
        $field_number = 0;
        while($number_of_fields > $field_number++){
            $field_locale = $this->ask("What is the (short) English description of field $field_number?", "Field $field_number");
            $field_name = $this->ask("What is the name of field $field_number?", Str::slug($field_locale, '_'));
            $field_type = $this->choice("What is the type of field $field_number?", $field_types, 0);
            $field_index = $this->choice("Create index for field $field_number?", ['yes', 'no'], 0);
            $field_widget = $this->askWidget($field_type, $field_number);
            $table[$field_name] = [
                'column' => $field_name,
                'type' => $field_type,
                'index' => $field_index,
                'i18n' => "$name.column.$field_name",
                'en' => $field_locale,
                'widget' => $field_widget,
            ];
            $this->table(
                array_keys( $table[$field_name] ),
                [ array_values( $table[$field_name] ) ]
            );
        }
        return $table;
    }

    private function askWidget($widgetType, $field_number)
    {
        switch($widgetType)
        {
            case 'boolean':
                $choice = ['button', 'no'];
                break;
            case 'string':
                $choice = ['scrollbox', 'bargraph', 'no'];
                break;
            default:
                return 'no';
        }
        return $this->choice("Create widget for field $field_number?", $choice, 0);
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
