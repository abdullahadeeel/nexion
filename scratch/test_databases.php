<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Nexion\Config\Dotenv;
use Nexion\Database\Database;
use Nexion\Database\Model;

// Load env
Dotenv::load(__DIR__ . '/../');

// Test Model class definition
class TestUser extends Model
{
    protected string $table = 'test_users';
}

$passes = 0;
$failures = 0;

function assertTest(string $name, bool $expression)
{
    global $passes, $failures;
    if ($expression) {
        echo "✅ PASS: {$name}\n";
        $passes++;
    } else {
        echo "❌ FAIL: {$name}\n";
        $failures++;
    }
}

echo "=== phpify Dynamic Database Adapter Suite ===\n\n";

// --- TEST 1: SQLite Connection & Basic Operations ---
echo "--- Testing SQLite Driver ---\n";
putenv("DB_CONNECTION=sqlite");
$_ENV['DB_CONNECTION'] = 'sqlite';

// Reset Database singleton instance by reflecting on Database class
$refClass = new ReflectionClass(Database::class);
$refProp = $refClass->getProperty('instance');
$refProp->setAccessible(true);
$refProp->setValue(null, null); // Reset active connection to force reconnect

$sqliteDb = Database::getInstance();
assertTest("SQLite connection instance created", $sqliteDb instanceof \Nexion\Database\Connection\SqliteConnection);

// Run migrations/create table raw command
$sqliteDb->exec("CREATE TABLE IF NOT EXISTS test_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    email VARCHAR(255),
    role VARCHAR(50)
);");

// Clear existing users
$sqliteDb->execute("DELETE FROM test_users");

// Insert a user using direct query
$sqliteDb->execute("INSERT INTO test_users (name, email, role) VALUES (?, ?, ?)", ['Alice Smith', 'alice@example.com', 'admin']);
$lastId = $sqliteDb->lastInsertId();
assertTest("SQLite insert via direct execute & lastInsertId retrieved", is_numeric($lastId) && $lastId > 0);

// Query back
$results = Database::query("SELECT * FROM test_users WHERE email = ? AND role = ?", ['alice@example.com', 'admin']);
assertTest("SQLite query fetches rows effectively with params", count($results) === 1 && $results[0]['name'] === 'Alice Smith');

// Test ORM Model Persistence
$user = new TestUser();
$user->name = 'Bob Jones';
$user->email = 'bob@example.com';
$user->role = 'user';
$saveResult = $user->save();
assertTest("SQLite ORM Model save() returns success status", $saveResult === true);
assertTest("SQLite ORM Model assigned primary key auto-increment id", isset($user->id) && $user->id > 0);

// Test ORM Model Querying
$foundUser = TestUser::find($user->id);
assertTest("SQLite ORM Model find() returns correct Model", $foundUser instanceof TestUser && $foundUser->name === 'Bob Jones');

// Test Model update
$foundUser->name = 'Bob Jones Updated';
$foundUser->save();
$updatedUser = TestUser::find($user->id);
assertTest("SQLite ORM Model update saved successfully", $updatedUser->name === 'Bob Jones Updated');

// Test Model delete
$updatedUser->delete();
assertTest("SQLite ORM Model delete() successfully removes record", TestUser::find($user->id) === null);


// --- TEST 2: MongoDB Connection & Basic Operations ---
echo "\n--- Testing MongoDB Driver ---\n";
putenv("DB_CONNECTION=mongodb");
$_ENV['DB_CONNECTION'] = 'mongodb';
$refProp->setValue(null, null); // Reset Database singleton instance

$mongoDb = Database::getInstance();
assertTest("MongoDB connection instance created", $mongoDb instanceof \Nexion\Database\Connection\MongodbConnection);

// Test raw insert command parsing
$mongoDb->execute("INSERT INTO test_users (name, email, role) VALUES (?, ?, ?)", ['Carol Danvers', 'carol@example.com', 'captain']);
$mongoId = $mongoDb->lastInsertId();
assertTest("MongoDB insert and ID generation successful", !empty($mongoId));

// Test query translation
$mongoResults = Database::query("SELECT * FROM test_users WHERE email = ? AND role = ?", ['carol@example.com', 'captain']);
assertTest("MongoDB translated SQL SELECT and fetched results effectively", count($mongoResults) === 1 && $mongoResults[0]['name'] === 'Carol Danvers');

// Test Model via MongoDB
$mongoUser = new TestUser();
$mongoUser->name = 'Peter Parker';
$mongoUser->email = 'peter@example.com';
$mongoUser->role = 'hero';
assertTest("MongoDB Model save() returns success status", $mongoUser->save() === true);
assertTest("MongoDB Model assigned generated ID", !empty($mongoUser->id));

// Find MongoDB Model
$foundMongoUser = TestUser::find($mongoUser->id);
assertTest("MongoDB Model find() successfully matches and retrieves document", $foundMongoUser instanceof TestUser && $foundMongoUser->name === 'Peter Parker');

// Update MongoDB Model
$foundMongoUser->name = 'Spider-Man';
$foundMongoUser->save();
$updatedMongoUser = TestUser::find($mongoUser->id);
assertTest("MongoDB Model update executed correctly", $updatedMongoUser->name === 'Spider-Man');

// Delete MongoDB Model
$updatedMongoUser->delete();
assertTest("MongoDB Model delete() executed correctly", TestUser::find($mongoUser->id) === null);


// --- TEST 3: AWS DynamoDB Connection & Basic Operations ---
echo "\n--- Testing AWS DynamoDB Driver ---\n";
putenv("DB_CONNECTION=dynamodb");
$_ENV['DB_CONNECTION'] = 'dynamodb';
$refProp->setValue(null, null); // Reset Database singleton instance

$awsDb = Database::getInstance();
assertTest("AWS DynamoDB connection instance created", $awsDb instanceof \Nexion\Database\Connection\AwsDynamoDbConnection);

// Test raw insert command parsing
$awsDb->execute("INSERT INTO test_users (name, email, role) VALUES (?, ?, ?)", ['Bruce Banner', 'bruce@example.com', 'hulk']);
$awsId = $awsDb->lastInsertId();
assertTest("AWS DynamoDB insert and ID generation successful", !empty($awsId));

// Test query translation
$awsResults = Database::query("SELECT * FROM test_users WHERE email = ? AND role = ?", ['bruce@example.com', 'hulk']);
assertTest("AWS DynamoDB translated SQL SELECT and fetched results effectively", count($awsResults) === 1 && $awsResults[0]['name'] === 'Bruce Banner');

// Test Model via AWS DynamoDB
$awsUser = new TestUser();
$awsUser->name = 'Tony Stark';
$awsUser->email = 'tony@example.com';
$awsUser->role = 'ironman';
assertTest("AWS DynamoDB Model save() returns success status", $awsUser->save() === true);
assertTest("AWS DynamoDB Model assigned generated ID", !empty($awsUser->id));

// Find AWS DynamoDB Model
$foundAwsUser = TestUser::find($awsUser->id);
assertTest("AWS DynamoDB Model find() successfully matches and retrieves document", $foundAwsUser instanceof TestUser && $foundAwsUser->name === 'Tony Stark');

// Update AWS DynamoDB Model
$foundAwsUser->name = 'Iron Man';
$foundAwsUser->save();
$updatedAwsUser = TestUser::find($awsUser->id);
assertTest("AWS DynamoDB Model update executed correctly", $updatedAwsUser->name === 'Iron Man');

// Delete AWS DynamoDB Model
$updatedAwsUser->delete();
assertTest("AWS DynamoDB Model delete() executed correctly", TestUser::find($awsUser->id) === null);


// --- FINAL VERIFICATION ---
echo "\n============================================\n";
echo "Database verification: {$passes} Passed, {$failures} Failed\n";
if ($failures === 0) {
    echo "🎉 All database drivers and abstraction layers successfully verified!\n";
} else {
    echo "🚨 Verification failed! Please check driver logs.\n";
    exit(1);
}
