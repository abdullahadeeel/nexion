<?php

require_once __DIR__ . '/../core/Autoloader.php';

use Nexion\Core\Autoloader;
use Nexion\Core\Config\Dotenv;
use Nexion\Core\Http\Request;
use Nexion\Core\Http\Response;
use Nexion\Core\Http\ValidationException;
use Nexion\Core\Routing\Router;
use Nexion\Core\Database\QueryBuilder;
use Nexion\Core\View\Engine;

Autoloader::register();

$errors = 0;
$passes = 0;

function assertTest(string $name, bool $expression) {
    global $errors, $passes;
    if ($expression) {
        echo "✅ PASS: {$name}\n";
        $passes++;
    } else {
        echo "❌ FAIL: {$name}\n";
        $errors++;
    }
}

echo "=== nexion Framework Verification Suite ===\n\n";

// 1. Dotenv Loader Verification
$envFile = __DIR__ . '/.env';
file_put_contents($envFile, "TEST_KEY=test_value\nTEST_BOOL=true\n");
Dotenv::load(__DIR__); // loads .env in scratch directory
assertTest("Dotenv loaded TEST_KEY", env('TEST_KEY') === 'test_value');
assertTest("Dotenv casted boolean true", env('TEST_BOOL') === true);
unlink($envFile);

// 2. Global Helpers Verification
assertTest("Global helper env() fallback", env('TEST_KEY_FALLBACK', 'default') === 'default');

// 3. HTTP Request Validation
$_GET = ['email' => 'invalid-email', 'age' => 'twenty'];
$req = new Request();
try {
    $req->validate([
        'email' => 'required|email',
        'age' => 'required|numeric',
        'name' => 'required'
    ]);
    assertTest("Request validation threw no exception (should fail)", false);
} catch (ValidationException $e) {
    $validationErrors = $e->getErrors();
    assertTest("Request validation caught email format error", isset($validationErrors['email']));
    assertTest("Request validation caught numeric type error", isset($validationErrors['age']));
    assertTest("Request validation caught required field missing error", isset($validationErrors['name']));
}

// 4. Advanced Routing and Route Groups
$router = new Router();
$router->group(['prefix' => '/api/v1', 'middleware' => ['AuthMiddleware']], function($r) {
    $r->get('/users/{id}', function() {})->name('api.users.show');
});

assertTest("Route Group Prefix applied correctly", $router->route('api.users.show', ['id' => 10]) === '/api/v1/users/10');

// Mock a request to resolve the group route
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/api/v1/users/10';
$routeReq = new Request();
$resolved = $router->resolve($routeReq);
assertTest("Router resolved route correct dynamic parameter", $resolved !== null);
if ($resolved) {
    [$route, $params] = $resolved;
    assertTest("Router matched dynamic parameter ID", $params['id'] === '10');
    assertTest("Router inherited middleware from group stack", in_array('AuthMiddleware', $route->getMiddleware()));
}

// 5. ORM Query Builder (Pre-execution SQL compiler check)
class MockModel extends \Nexion\Core\Database\Model {
    protected string $table = 'mock_table';
}

$qb = new QueryBuilder('mock_table', MockModel::class);
$qb->where('status', 'active')->where('role', 'admin')->orderBy('id', 'DESC')->limit(5);

// Let's use reflection to inspect QueryBuilder parameters
$ref = new ReflectionClass($qb);
$wheresProp = $ref->getProperty('wheres');
$wheresProp->setAccessible(true);
$wheres = $wheresProp->getValue($qb);

$bindingsProp = $ref->getProperty('bindings');
$bindingsProp->setAccessible(true);
$bindings = $bindingsProp->getValue($qb);

assertTest("QueryBuilder compiled status where clause", $wheres[0] === 'status = ?');
assertTest("QueryBuilder compiled role where clause", $wheres[1] === 'role = ?');
assertTest("QueryBuilder stored first binding value", $bindings[0] === 'active');
assertTest("QueryBuilder stored second binding value", $bindings[1] === 'admin');

// 6. Template Engine Layout & Buffering Stack
$viewDir = __DIR__ . '/views';
$cacheDir = __DIR__ . '/cache';
if (!is_dir($viewDir)) mkdir($viewDir, 0777, true);
if (!is_dir($viewDir . '/layouts')) mkdir($viewDir . '/layouts', 0777, true);

file_put_contents($viewDir . '/layouts/app.php', "<html><body>@yield('main')</body></html>");
file_put_contents($viewDir . '/child.php', "@extends('layouts.app')\n@section('main')<h1>Hello {{ \$name }}</h1>@endsection");

$engine = new Engine($viewDir, $cacheDir);
$output = $engine->render('child', ['name' => 'Antigravity']);

assertTest("Engine resolved layout extends and rendered output buffered section", str_contains($output, '<html><body><h1>Hello Antigravity</h1></body></html>'));

// Clean up scratch view files
unlink($viewDir . '/child.php');
unlink($viewDir . '/layouts/app.php');
rmdir($viewDir . '/layouts');
rmdir($viewDir);
// Clean up cache
array_map('unlink', glob("$cacheDir/*"));
rmdir($cacheDir);

echo "\n============================================\n";
echo "Verification results: {$passes} Passed, {$errors} Failed\n";
if ($errors === 0) {
    echo "🎉 Framework completely validated successfully!\n";
} else {
    echo "🚨 Verification failed. Please inspect logs.\n";
    exit(1);
}
