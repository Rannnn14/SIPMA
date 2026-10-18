<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::whereIn('role', ['kemahasiswaan', 'admin'])->first();
Illuminate\Support\Facades\Auth::login($user);

$req = Illuminate\Http\Request::create('/kemahasiswaan/dashboard', 'GET');
$controller = app(App\Http\Controllers\DashboardController::class);
$view = $controller->kemahasiswaan($req);

echo "View rendered successfully: " . $view->name() . "\n";
$rendered = $view->render();
echo "Rendered length: " . strlen($rendered) . " bytes\n";
