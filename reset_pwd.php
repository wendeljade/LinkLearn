<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$u = App\Models\User::where('email','buksu@gmail.com')->first();
if($u){
    $u->password = bcrypt('password');
    $u->save();
    echo "Password reset to: password\n";
} else {
    echo "User not found\n";
}
