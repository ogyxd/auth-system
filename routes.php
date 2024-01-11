<?php 

use Core\Router;
use Core\Middleware;

use Controllers\IndexController;
use Controllers\Auth\RegisterController;
use Controllers\Auth\LoginController;
use Controllers\Auth\LogoutController;

$router = new Router();

$router->set_404_page("404");
$router->set_controller_type(Router::CONTROLLER_TYPE_CLASS_BASED);

$router->route("/", IndexController::class);
$router->route("/register", RegisterController::class);
$router->route("/logout", LogoutController::class);
$router->route("/login", LoginController::class);

$router->resolve();