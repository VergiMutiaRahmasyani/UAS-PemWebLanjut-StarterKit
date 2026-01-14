<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

// Check user with ID 2
$user = \App\Models\User::with('roles')->find(2);

if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    
    if ($user->roles->isNotEmpty()) {
        echo "Roles: " . $user->getRoleNames()->implode(', ') . "\n";
        echo "Has role 'editor': " . ($user->hasRole('editor') ? 'Yes' : 'No') . "\n";
    } else {
        echo "No roles assigned.\n";
    }
} else {
    echo "User with ID 2 not found.\n";
}
