<?php
// error_reporting(0);
require_once __DIR__ . '/router.php';
include "src/Tokenizer.php";
include "src/Database.php";
// ##################################################
// ##################################################
// ##################################################

get('/autorize', function () {
    $db = new Database();
    if ($db->isset($_GET)) {
        $tokenizer = new Tokenizer();
        echo $tokenizer->generateToken(["profile" => $_GET['profile'], "email" => $_GET['email'], "isAdmin" => $db->isAdmin($_GET['email'])]);
    } else {
        echo json_encode(["message" => "Access Denied"]);
    }
});

get('/callback', function () {
    $tokenizer = new Tokenizer();
    try{
        echo $tokenizer->checkToken($_GET['jwt']);
    } catch (Exception $e){
        echo json_encode(["message" => "Access Denied"]);
    }
});

get('/.well-known/jwks.json', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo file_get_contents(".well-known/jwks.json");
});

post('/registration', function () {

    $tokenizer = new Tokenizer();
    $params = json_decode( file_get_contents('php://input'), TRUE );
    $user = json_decode($tokenizer->checkToken(getallheaders()['token'] ?? ""));
    $db = new Database();
    if ($db->isAdmin($user->email)) {
        if(!$db->isset($params) && $message = $db->save($params)){
            http_response_code(201);
            echo json_encode(["message" => $message]);
        } else {
            http_response_code(206);
            echo json_encode(["message" => "data isnt validate. fields 'email' and 'profile' is required, 'email' is unique"]);
        }
    } else {
        http_response_code(403);
        echo json_encode(["message" => "403 Forbidden"]);
    }

});
// проверка работоспособности сайта
get('/manage/health', 'src/health.php');


// -- Example:

/*// Static GET
// In the URL -> http://localhost
// The output -> Index
get('/', 'views/index.php');

// Dynamic GET. Example with 1 variable
// The $id will be available in user.php
get('/user/$id', 'views/user');

// Dynamic GET. Example with 2 variables
// The $name will be available in full_name.php
// The $last_name will be available in full_name.php
// In the browser point to: localhost/user/X/Y
get('/user/$name/$last_name', 'views/full_name.php');

// Dynamic GET. Example with 2 variables with static
// In the URL -> http://localhost/product/shoes/color/blue
// The $type will be available in product.php
// The $color will be available in product.php
get('/product/$type/color/$color', 'product.php');

// A route with a callback
get('/callback', function(){
  echo 'Callback executed';
});

// A route with a callback passing a variable
// To run this route, in the browser type:
// http://localhost/user/A
get('/callback/$name', function($name){
  echo "Callback executed. The name is $name";
});

// A route with a callback passing 2 variables
// To run this route, in the browser type:
// http://localhost/callback/A/B
get('/callback/$name/$last_name', function($name, $last_name){
  echo "Callback executed. The full name is $name $last_name";
});

// ##################################################
// ##################################################
// ##################################################
// any can be used for GETs or POSTs

// For GET or POST
// The 404.php which is inside the views folder will be called
// The 404.php has access to $_GET and $_POST
any('/404','views/404.php');*/
