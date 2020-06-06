<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use munkireport\lib\Modules as ModuleManager;

class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create module';

    /** @var instance $module_manager Instance of Module Manager */
    protected $module_manager = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->module_manager = new ModuleManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                'i18n' => 'reportdata.serial_number'
            ],
        ];
        $field_types = [
            'string',
            'integer',
            'bigInteger',
            'text',
        ];
        $this->comment('Creating a module is cool!');

        $path = $this->choice("Where do you want to generate the module?", $this->module_manager->getModuleSearchPaths());        
        $name = $this->ask('What is the name of the module?', 'my_cool_module');
        $number_of_fields = intval($this->ask('How many database fields do you need? (apart from id and serial_number)', 3));
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

        if($this->choice("Do you need to store more than one row per machine?", ["yes", "no"], 1) == "yes"){
            $table['serial_number']['index'] = 'yes';
        }

        $headers = ["Column", "Type", "Indexed", "English"];
        $this->table($headers, $this->_toTable($table));

        if ( ! $this->confirm('Do you wish to continue?')) {
           $this->error("Well that's too bad!");
           die();
        }

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
