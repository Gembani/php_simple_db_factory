#Simple db Factory
Simple DB Factory will be a framework agnostic way of populating a mysql database for testing purposes.

## Requierements:
- Each Table you define using entity manager requires a auto incremented primary key.
- Only compatible with phps mysqli extention

##Instalation
using composer (https://getcomposer.org/)
composer.json:
```json
{
  ....
    "require-dev": {
        "phpunit/phpunit": "4.0.*",
        "nicholasjstock/simple-db-factory": "dev-master"
    }
    ...

}

```
composer install

then in your script:

```php
<?php
require 'vendor/autoload.php';
$connection = ['host' => 'localhost', 'username' => 'username', 'password' => 'password', 'database' => 'db_name'];
$a = 	new simpleDbFactory\EntityManager($connection);

```
### Simple Example
```php
$credentials = [];
$credentials['host'] = 'localhost';
$credentials['username'] = 'db_username';
$credentials['password'] = 'db_password';
$credentials['database'] = 'db_name';

$em = 	new EntityManager($credentials); // create connection
$em->define('existing_table_name', ['filed_one' => 1, 'field_2' => 2]);

//adds {field_one: 1, field_two : 2} to database
$row = $em->addRow('existing_table_name');

print_r($row)  
// => {prmary_key:1, field_one:1, field_two:2}

//adds {field_one: 1, field_two : 1234}; to database
$row = $em->addRow('existing_table_name', ['field_two' => 1234]);
print_r($row)  
// => {prmary_key:2, field_one:1, field_two:1234}
```
### Using sequences
```php
$int_sequence = new \simpleDbFactory\SequentialNumberGenerator();
$string_sequence = new \simpleDbFactory\StringGenerator('test_string {n}');
$em->define('test_table', ['test_int' => $int_sequence, 'test_string' => $string_sequence]);
$row = $em->addRow('existing_table_name');
$row2 = $em->addRow('existing_table_name');
print_r($row);
//=> {primary_key: 1, test_int: 0, test_string: 'test_string 0'}
print_r($row2);  
//=> {primary_key: 2, test_int: 1, test_string: 'test_string 1'}
```
### Dealing With Foreign Keys
```php
//User has many blog posts:
$em->define('users');
$em->define('blog_posts');

$user = $em->addRow('users', ["name" => "Nick Stock"]);
$em->addRow('blog_posts', ['user_id' => $user['user_id']]);

```
