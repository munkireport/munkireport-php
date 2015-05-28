# About Business Units

Business Units are a way to use one munkireport instance for more than one business/group. Business Units can have:

* One or more Machine Groups (which contain Machines)
* One or more Users (who can visit the unit pages)
* One or more Managers (who can add/delete users, add/delete Machine Groups)

## Technical Reference

The way Business Units (BU) are implemented is by adding restrictions on database queries for the current user. On login, the appropriate BU and Machine Groups are added to the user's $_SESSION variables:

* `$_SESSION['business_unit']` contains the numerical id of the BU
* `$_SESSION['role']` contains the role of the current user (user, manager or admin)
* `$_SESSION['machine_groups']` contains an array of Machine Group id's that belong to the BU
* `$_SESSION['filter']` contains an array of Machine Group id's that should be retrieved, the user can change this. You can only add group id's to the filter that are in `$_SESSION['machine_groups']`

### Model methods

#### `$this->retrieve_record($serial_number)`

Retrieve record for `$serial_number` if in allowed Machine Groups, otherwise returns FALSE.
If $GLOBALS['auth'] == 'report', this method will return the record;

#### `$this->retrieve_records($serial_number)`

Retrieve multiple records for `$serial_number` if in allowed Machine Groups, otherwise returns an empty array.


### Helper functions

#### `authorized_for_serial($serial_number)`

Returns `TRUE` if the current user should be allowed to access the data for this machine.
If $GLOBALS['auth'] == 'report', this method will return TRUE as well;

#### `get_machine_group_filter($prefix = 'WHERE', $machine_table_alias = 'machine')`

Retrieve a 'WHERE' clause that contains a filter for the current user. You need to add a join to machine in your database query for this to work.

Example:

```php
$sql = "SELECT COUNT(1) as total,
		COUNT(CASE WHEN (which_directory_service LIKE 'Active Directory'
		OR which_directory_service LIKE 'LDAPv3') THEN 1 END) AS arebound
		FROM directoryservice";
```

should become

```php
$sql = "SELECT COUNT(1) as total,
		COUNT(CASE WHEN (which_directory_service LIKE 'Active Directory'
		OR which_directory_service LIKE 'LDAPv3') THEN 1 END) AS arebound
		FROM directoryservice
		LEFT JOIN machine USING(serial_number)
		".get_machine_group_filter();
```

**NOTE:** If you already have a `WHERE` clause in your query, you can replace the `$prefix` with 'AND'. If you use a table alias in your query, you can replace `$machine_table_alias` with the alias name.				


### API Calls

If you want to retrieve data in JSON format, you can use these API calls:

#### `baseUrl + 'unit/get_data'`

This will return information about the current Business Unit.

**Example:**

```js
$.getJSON(baseUrl + 'unit/get_data')
	.done(function(data){
		console.log(data)
	})
```

could return

```js
{
	unitid: 2,
	name: "Company X",
	address: "Amsterdam, the Netherlands",
	machine_groups: {1, 12},
	managers: {"Anna"},
	users: {"John", "Jane", "Betty"}
}

#### `baseUrl + 'unit/get_machine_groups'`

This call returns information about the machine_groups available to the current user.

**Example:**

```js
$.getJSON(baseUrl + 'unit/get_data')
	.done(function(data){
		console.log(data)
	})
```

could return

```js
{
	groupid: 2,
	name: "Sales People",
	passphrase: "some_long_random_string"
}
```
