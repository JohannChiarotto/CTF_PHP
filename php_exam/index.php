<?php
// index.php - front controller

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Router.php';

Session::start();

$router = new Router();

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/ChallengeController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/AccountController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/SubmissionController.php';
require_once __DIR__ . '/controllers/ScoreboardController.php';
require_once __DIR__ . '/controllers/InvoiceController.php';

// Routes GET
$router->get('/', [new HomeController(), 'index']);
$router->get('/detail', [new HomeController(), 'detail']);

$router->get('/login', [new AuthController(), 'showLogin']);
$router->get('/register', [new AuthController(), 'showRegister']);
$router->get('/logout', [new AuthController(), 'logout']);

$router->get('/sell', [new ChallengeController(), 'showSell']);
$router->get('/edit', [new ChallengeController(), 'showEdit']);

$router->get('/cart', [new CartController(), 'index']);
$router->get('/cart/validate', [new CartController(), 'showValidate']);

$router->get('/account', [new AccountController(), 'index']);
$router->get('/invoice', [new InvoiceController(), 'show']);

$router->get('/admin', [new AdminController(), 'index']);
$router->get('/admin/users', [new AdminController(), 'users']);
$router->get('/admin/challenges', [new AdminController(), 'challenges']);

$router->get('/scoreboard', [new ScoreboardController(), 'index']);

// Routes POST
$router->post('/login', [new AuthController(), 'login']);
$router->post('/register', [new AuthController(), 'register']);

$router->post('/sell', [new ChallengeController(), 'store']);
$router->post('/edit', [new ChallengeController(), 'update']);
$router->post('/delete-challenge', [new ChallengeController(), 'delete']);

$router->post('/cart/add', [new CartController(), 'add']);
$router->post('/cart/update', [new CartController(), 'update']);
$router->post('/cart/remove', [new CartController(), 'remove']);
$router->post('/cart/validate', [new CartController(), 'validate']);

$router->post('/account/update', [new AccountController(), 'update']);
$router->post('/account/add-balance', [new AccountController(), 'addBalance']);

$router->post('/submit-flag', [new SubmissionController(), 'submit']);

$router->post('/admin/user/role', [new AdminController(), 'updateRole']);
$router->post('/admin/user/ban', [new AdminController(), 'banUser']);
$router->post('/admin/user/reset', [new AdminController(), 'resetUser']);
$router->post('/admin/challenge/toggle', [new AdminController(), 'toggleChallenge']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

