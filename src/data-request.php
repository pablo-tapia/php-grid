<?php
/**
 * @file
 * Creates a query that retrieves specific data from the database to create a grid
 * the file uses ZF2 database module and uses TableGateway to access the data layer.
 */

require_once 'vendor/autoload.php';

use Zend\Db\Adapter\Adapter;

$adapter = new Adapter([
    'driver' => 'pdo_mysql',
    'driver_options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''],
    'database' => 'php-grid-site',
    'username' => 'homestead',
    'password' => 'secret'
]);

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;

/**
 * Retrieve the data from the $_GET array if any
 */
if (!empty($_GET)) {
    foreach ($_GET as $index => $value) {
        $$index = $value;
    } // end foreach
}
/**
 * Build the query using the Select object and pass that to the Table Gateway to generate the result set
 * @link https://stackoverflow.com/questions/21920963/how-to-add-join-columns-to-tablegateway-in-zendframework-2
 */
$select = new Select(['e' => 'grid_events']);
$select->columns(['name', 'price', 'web_page', 'end_date', 'for', 'convener']);
$select->join(['c' => 'grid_categories'], 'e.category = c.category_id', ['category' => 'category_name']);
$select->join(['s' => 'grid_status'], 'e.status = s.status_id', ['status' => 'status_name']);
$select->join(['si' => 'grid_situations'], 'e.situation = si.situation_id', ['situation' => 'situation_name']);
if (isset($name) && !empty($name)) {
    $select->where->like('e.name', "%{$name}%");
} // end if
if (isset($end_date) && !empty($end_date)) {
    $select->where->like('e.end_date', "%{$end_date}%");
} // end if
if (isset($convener) && !empty($convener)) {
    $select->where->like('e.convener', "%{$convener}%");
} // end if

$gridEvents = new TableGateway('grid_events', $adapter, NULL, new HydratingResultSet());
$rowset = $gridEvents->selectWith($select);

header("Content-Type: application/json");
echo json_encode($rowset->toArray());