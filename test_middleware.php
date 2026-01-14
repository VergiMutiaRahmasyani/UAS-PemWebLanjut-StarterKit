<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Create a request instance first
$request = Request::create('/test', 'GET');

// Set the request instance
$app->instance('request', $request);

// Bootstrap the application
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Set up the authenticated user
$user = \App\Models\User::find(2); // User dengan role editor
if (!$user) {
    die("User with ID 2 not found.\n");
}

// Authenticate the user
Auth::login($user);

// Create middleware instance
$middleware = new \App\Http\Middleware\CheckRole();

try {
    // Test with editor role (should pass)
    echo "\n=== Testing middleware with role 'editor' ===\n";
    $response = $middleware->handle($request, function($req) {
        return response('Middleware passed!');
    }, 'editor');
    
    if ($response->getContent() === 'Middleware passed!') {
        echo "✅ PASSED: Middleware allowed access for editor role.\n";
    } else {
        echo "❌ FAILED: Middleware did not allow access for editor role.\n";
        echo "Response status: " . $response->getStatusCode() . "\n";
        if ($response->getStatusCode() === 302) {
            echo "Redirecting to: " . $response->getTargetUrl() . "\n";
        }
    }
    
    // Test with admin role (should fail)
    echo "\n=== Testing middleware with role 'admin' ===\n";
    $response = $middleware->handle($request, function($req) {
        return response('Should not reach here');
    }, 'admin');
    
    if ($response->getStatusCode() === 302) {
        echo "✅ PASSED: Middleware redirected for admin role (as expected).\n";
        echo "   Redirecting to: " . $response->getTargetUrl() . "\n";
    } else {
        echo "❌ FAILED: Middleware did not redirect for admin role.\n";
        echo "   Status code: " . $response->getStatusCode() . "\n";
        echo "   Response: " . $response->getContent() . "\n";
    }
    
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    if (method_exists($e, 'getTraceAsString')) {
        echo "Trace: \n" . $e->getTraceAsString() . "\n";
    }
}
