<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all();
foreach ($users as $user) {
    if (strpos(strtolower($user->role), 'admin') !== false) {
        echo "Found Admin - ID: {$user->id}, Email: {$user->email}, Role: {$user->role}\n";
        $user->password = Hash::make('password');
        $user->save();
        echo "Password reset to: password\n\n";
    }
}
