<?php

/**
 * The Base Controller and every controller should extends this 
 * Controller
 */


class Controller
{
    public function model($model)
    {

        // Require the model 
        require_once("src/models/$model.php");

        // Instantiate the new model
        return new $model;
    }

    public function view($view, $data = [])
    {

        require_once(APPROOT . "/views/inc/header.php");


        $isTrue = file_exists("src/views/$view.php");

        if ($isTrue) {
            require_once("src/views/$view.php");
        } else {
            die("Somthing went wrong please contect your web master");
        }

        require_once(APPROOT . "/views/inc/footer.php");
    }

    public function dispatcher($callback, $args)
    {
        try {
            call_user_func_array(array($this, $callback), $args);
            return true;
        } catch (Exception $ex) {
          if ($_ENV['ENV'] == 'production')
            return redirect("dashboard", [
              'type' => 'error',
              'message' => "Somthing went wrong please contact your webmaster"
            ]);

          dump($ex);
          new ErrorHandler($ex);

        }
    }

    public function viewWithOutHeaderAndFooter($view, $data = [])
    {
        $isTrue = file_exists("src/views/$view.php");

        if ($isTrue) {
            require_once("src/views/$view.php");
        } else {
            die("Somthing went wrong please contect your web master");
        }
    }

    public function authMiddleware()
    {
        if (!Auth::isLogin()){
            $rediretUrl = $_ENV['LOGOUT_REDIRECT'];
            return header("Location: $rediretUrl");
        } 
    }
}
