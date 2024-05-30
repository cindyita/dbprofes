<?php
namespace ControllersNS;
use ModelsNS\QueryModel;
use ControllersNS\AuthController;

class PagesController
{
    public static $styles;
    public static $scripts;

    public function __construct()
    {
        $this->styles = [];
        $this->scripts = [];
    }

    public static function headerLayout() {
        $styles = self::$styles;
        require_once "./src/views/layouts/headerLayout.php";
    }

    public static function menuLayout() {
        require_once "./src/views/layouts/menuLayout.php";
    }

    public static function footerLayout() {
        $scripts = self::$scripts;
        require_once "./src/views/layouts/footerLayout.php";
    }

    private static function addScript($path) {
        self::$scripts[] = $path;
    }

    private static function addStyle($path) {
        self::$styles[] = $path;
    }

    private static function pageScript($page) {
        self::addScript('./assets/js/pages/'.$page.'.js');
    }

    private static function pageStyle($page) {
        self::addStyle('./assets/css/pages/'.$page.'.css');
    }

    public static function checkSession(){
        if(!isset($_SESSION["PSESSION"])){
            header('Location: login');
            exit();
        }
        $validate = AuthController::validateAuthToken();
        if(!$validate){
            self::unAuth();
            exit();
        }
        if($validate == "expired"){
            header('Location: login');
            exit();
        }
    }

    public static function checkSessionToHome(){
        $validate = AuthController::validateAuthToken();
        if($validate && isset($_SESSION["PSESSION"]) && $validate != "expired"){
            header('Location: home');
        }
    }

    // PÁGINA NO AUTORIZADO
    public static function unAuth(){
        require_once "./src/views/pages/unauthorized.php";
    }

    // PÁGINA HOME
    public static function home() {
        self::pageScript('home');
        $db = new QueryModel();
        $form_grading = $db->select("REG_FORM_GRADING");
        $time_grading = $db->select("REG_TIME_GRADING");
        $accessibility = $db->select("REG_ACCESSIBILITY");
        require_once "./src/views/pages/home.php";
    }

    // PÁGINA LOGIN
    public static function login() {
        self::checkSessionToHome();
        self::pageScript('login');
        require_once "./src/views/pages/login.php";
    }

    public static function logout(){
        AuthController::logout();
        self::checkSession();
    }

    // PÁGINA REGISTRO
    public static function register() {
        self::pageScript('register');
        require_once "./src/views/pages/register.php";
    }

    // PÁGINA PERFIL
    public static function profile() {
        self::checkSession();
        self::pageScript('profile');
        require_once "./src/views/pages/profile.php";
    }

    // PÁGINA OPINIONES
    public static function myopinions() {
        self::checkSession();
        self::pageScript('myopinions');
        require_once "./src/views/pages/myopinions.php";
    }

    // PÁGINA ERROR 404
    public static function error404() {
        require_once "./src/views/pages/error404.php";
    }

}