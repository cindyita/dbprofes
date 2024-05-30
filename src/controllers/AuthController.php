<?php
namespace ControllersNS;
use ModelsNS\QueryModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Throwable;

class AuthController
{

    public static function auth($data){

        try{
    
            if (!empty($data) && count($data)>0) {
                $db = new QueryModel();
                $username = $data['username'];
                $pass = $data['pass'];
                $row = $db->queryUnique("SELECT u.id,u.username,u.password,u.email,u.biography,u.id_role,r.name role,u.timestamp_create FROM SYS_USER u LEFT JOIN SYS_ROLE r ON u.id_role = r.id WHERE u.username = :username",[':username' => $username]);
                if($row && $row != [] && password_verify($pass, $row['password'])){

                    session_start();

                    foreach ($row as $key => $value) {
                        if($key != "password"){
                            $_SESSION['PSESSION'][$key] = $value;
                        }
                    }

                    $id = $row['id'];
                    $_SESSION['PSESSION']['userid'] = $id;

                    $token = self::generateToken($id);

                    setcookie('AuthToken', $token, [
                        'expires' => time() + 60*60*24*30, // 30 días
                        'path' => '/',
                        // 'domain' => '',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);

                    $db->close();
                    if($token){
                        return 1;
                    }else{
                        return "Error in token";
                    }
                    
                }else{
                    $db->close();
                    return 2;
                }
                
            } else {
                return json_encode(['error'=>'Invalid format or no info']);
            }
            
        }catch(exception $e){
            return json_encode('error: '.$e->getMessage());
        }

    }

    public static function generateToken($id){
        $now = strtotime("now");
        $key = $_ENV['KEY_AUTH'];
        $expiration_time = $now + (30 * 24 * 60 * 60); //30 días

        $payload = [
            'exp' => $expiration_time,
            'data' => $id
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        $res = ["token" => $jwt];

        return json_encode($res);
    }

    public static function getToken(){
        try{
            if(isset($_COOKIE['AuthToken'])){
                $token = json_decode($_COOKIE['AuthToken'], true);
                $token = $token['token'];
                $decodedToken = JWT::decode($token, new Key($_ENV['KEY_AUTH'], 'HS256'));
                return $decodedToken;
            }else{
                return false;
            }
        }catch(\Throwable $e){
            if($e->getMessage() == "Expired token"){
                return "expired";
            }else{
                return false;
            }
        }
    }

    public static function validateAuthToken(){
        $info = self::getToken();
        if(!$info){
            return false;
        }
        if($info == "expired"){
            return "expired";
        }
        $id = $info->data;
        $db = new QueryModel();
        $row = $db->value("SYS_USER","id = $id","id");
        return $row;
    }

    public static function clearAuthCookie() {
        if (isset($_COOKIE["AuthToken"])) {
            setcookie("AuthToken", "");
        }
    }

    public static function checkSession() {
        if (!self::validateAuthToken()) {
            self::logout();
        }
    }

    public static function logout() {
        unset($_SESSION['PSESSION']);
        setcookie("AuthToken", "", time() - 3600, '/');
        session_destroy();
        redirect('login');
    }


}