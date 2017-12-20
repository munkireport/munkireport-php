# Adding Illuminate/Database (Laravel) to MunkiReport PHP #

[Illuminate/Database](https://packagist.org/packages/illuminate/database) is the database package included in the Laravel framework.
It can also be used in a standalone mode, usually called a *Capsule*.


## Features ##

Some of the features that work in Laravel won't work using the Capsule, but we can use the features below.
Generally the console commands arent available to us. Use these reference pages when interacting with the library:

- [Query Builder](https://laravel.com/docs/5.5/queries)
- [Schema Builder](https://laravel.com/docs/5.5/migrations)
- [Eloquent ORM](https://laravel.com/docs/5.5/eloquent)

## Usage in MRPHP ##

A new method has been added to the **KISS_Controller** class in `kissmvc.php` called `connectDB()`. Normally laravel
would load your config.php automatically but we needed to bootstrap it in our own way.

Whenever you want to use the Capsule, call this method first.

## KISS_Model Schema Example: machine_group (deprecated) ##

In the current **KISS_Model** setup, database schema is created in the following way:

- The **Model** class contains a method named `create_table()` which is called in the constructor of a model.
- If the table already exists or migrations are not allowed, nothing happens.
- Your schema is created from the constructor of the model, and the `rs` instance variable.

For example:

        parent::__construct('id', 'machine_group'); //primary key, tablename
        
The KISS framework will automatically use the **id** column as the primary key, and **machine_group** as the table name.
Then, you define your columns as part of the **rs** associative array like this:

        $this->rs['id'] = '';
        $this->rs['groupid'] = 0;
        $this->rs['property'] = '';
        $this->rs['value'] = '';
        
The KISS schema creator infers what type of column you need from the data type of the value set via `get_type()`.

Value types are mapped when they pass these type detection tests:

- `is_int()` maps to `INTEGER`.
- `is_string()` maps to `VARCHAR(255)`.
- `is_float()` maps to `REAL`.
- Everything else is `MEDIUMBLOB`.

Indices are created in a similar way by appending values to an array called **idx**, for example:

        $this->idx[] = array('property');
        $this->idx[] = array('value');
        
Each item in the **idx** array will cause the column indicated to be indexed by an index called:

        <table>_<idx data joined by underscores>
        
In most circumstances `idx data` is the name of the column.


## Migrating machine_group to illuminate/database ##

### Migration Files ###

The first thing to know is that laravel usually creates migration files that are prefixed with the reverse date. This is
because the files will be executed in numerical order.

The current migration file for **machine_group** is called `2017_02_20_085316_machine_group.php`.
This represents 20th February, 2017. 08:53:16. It's not necessary to use the time, so you can start from
`000001` and increment each time.

### Migration Class Structure ###

First, we need to import a few things:

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Capsule\Manager as Capsule;
    
Strictly speaking, **Blueprint** is not required, but your editor might use it to Autocomplete.

Each migration is a class that inherits **Migration**, with two methods: `up()` and `down()` for upgrading and downgrading
the database schema respectively.

**Example**

    class MachineGroup extends Migration
    {
        public function up()
        {
            $capsule = new Capsule(); // <-- this is different to Laravel framework
            $capsule::schema()->create('machine_group', function (Blueprint $table) { // <-- also this
                $table->increments('id');
                $table->integer('groupid')->nullable();
                $table->string('property');
                $table->string('value');                
            });
        }
    
        public function down()
        {
            $capsule = new Capsule();
            $capsule::schema()->dropIfExists('machine_group');
        }
    }

If you read Laravel's schema builder guide, you will notice that almost everything is the same, except two things:

- We need to instantiate the **Capsule** class.
- We have to access the schema builder by calling `$capsule::schema()`.

## Key Differences ##

### 1. Column types are not automatic ###

You need to specify the proper type of data you are storing, laravel doesn't detect this automatically.

### 2. Default values are not automatic ###

If you want a default value (managed by the database) when one isn't specified, you have to specify that. You also need
to append `->nullable()` to make a column nullable, which is the default in KISS_Model.

### 3. Foreign key constraints are explicit ###

**KISS_Model** doesn't truly deal with database constraints (except for `UNIQUE` indexes).
If a column should relate to the primary key of another table, you will have to specify that foreign key relationship.


### 4. Index creation is explicit ###

The migration has to contain separate statements for creating any indexes you want.

## Running migrations ##

Because we don't have access to the `artisan` console command, the database upgrade page will run migrations.
This page is available under the **System** menu.


