<?php
namespace munkireport\controller;

use \Controller, \View;
use Illuminate\Filesystem\Filesystem;
use munkireport\lib\Database;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;

class system extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        if (! $this->authorized('global')) {
            die('You need to be admin');
        }
    }

    //===============================================================

    /**
     * DataBase
     *
     * Get Database info and status
     *
     */
    public function DataBaseInfo()
    {
        $out = array(
            'db.driver' => '',
            'db.connectable' => false,
            'db.writable' => false,
            'error' => '',
        );
        $config = array(
            'pdo_dsn' => conf('pdo_dsn'),
            'pdo_user' => conf('pdo_user'),
            'pdo_pass' => conf('pdo_pass'),
            'pdo_opts' => conf('pdo_opts'),
        );

        $db = new Database($config);
        //echo '<pre>'; var_dump($db);
        if ($db->connect()) {
            $out['db.connectable'] = true;
            $out['db.driver'] = $db->get_driver();

            if ($db->isWritable()) {
                $out['db.writable'] = true;
            } else {
                $out['error'] = $db->getError();
            }
        } else {
            $out['error'] = $db->getError();
        }
        //echo '<pre>'; var_dump($db);
        // Get engine
        // Get permissions
        // Do a write
        // Do a read
        // Get tables
        // Get size
        $obj = new View();
        $obj->view('json', array('msg' => $out));
    }

    public function migrationsPending()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'username' => conf('pdo_user'),
            'password' => conf('pdo_pass'),
            'driver' => 'sqlite',
            'database' => conf('application_path').'db/db.sqlite'
        ]);
        $capsule->setAsGlobal();
        $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');

        if (!$repository->repositoryExists()) {
            $repository->createRepository();
        }

        $files = new Filesystem();
        $migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);
        $migrationFiles = $migrator->run(APP_ROOT . 'database/migrations', Array('pretend' => true));
        $migrationFilenames = Array();

        foreach ($migrationFiles as $mf) {
            $migrationFilenames[] = basename($mf);
        }


        $obj = new View();
        $obj->view('json', array('msg' => Array(
            'files_pending' => $migrationFilenames,
            'notes' => $migrator->getNotes())
        ));
    }

    public function migrate()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'username' => conf('pdo_user'),
            'password' => conf('pdo_pass'),
            'driver' => 'sqlite',
            'database' => conf('application_path').'db/db.sqlite'
        ]);
        $capsule->setAsGlobal();
        $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');

        if (!$repository->repositoryExists()) {
            $repository->createRepository();
        }

        $files = new Filesystem();
        $migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);
        $migrationFiles = $migrator->run(APP_ROOT . 'database/migrations', Array('pretend' => true));

        $obj = new View();
        $obj->view('json', array('msg' => Array(
            'files' => $migrationFiles,
            'notes' => $migrator->getNotes())
        ));
    }

    //===============================================================

    /**
     * Authentication and Authorization
     *
     * Get Authentication and Authorization data
     *
     */
    public function AuthenticationAndAuthorization()
    {
        # code...
    }
    //===============================================================

    /**
     * php information
     *
     * Retrieve information about php
     *
     */
    public function phpInfo()
    {
        ob_start();
        phpinfo(11);
        $raw = ob_get_clean();
        $phpinfo = array('phpinfo' => array());

        // Remove credits
        $nocreds = preg_replace('#<h1>PHP Credits.*#s', '', $raw);
        if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', $nocreds, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (strlen($match[1])) {
                    $phpinfo[$match[1]] = array();
                } elseif (isset($match[3])) {
                    $keys1 = array_keys($phpinfo);
                    $phpinfo[end($keys1)][$match[2]] = isset($match[4]) ? $match[3] . ' ('.$match[4].')' : str_replace(',', ', ', $match[3]);
                } else {
                    $keys1 = array_keys($phpinfo);
                    $phpinfo[end($keys1)][] = trim(strip_tags($match[2]));
                }
            }
        }
        //echo '<pre>';print_r($phpinfo);return;
        $obj = new View();
        $obj->view('json', array('msg' => $phpinfo));
    }
    //===============================================================
    //===============================================================
    //===============================================================

    //===============================================================

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function show($which = '')
    {
        if ($which) {
            $data['page'] = 'clients';
            $data['scripts'] = array("clients/client_list.js");
            $view = 'system/'.$which;
        } else {
            $data = array('status_code' => 404);
            $view = 'error/client_error';
        }

        $obj = new View();
        $obj->view($view, $data);
    }
}
