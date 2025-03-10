<?php 
//---------------------------------------
	define('TITLE1_HEADER'			, 'Institution Management System');
	define("SITE1_NAME"				, "Login - (ERP)");
	define("COMPANY1_NAME"			, "Institution Management System");
	define("SITE1_ADDRESS"			, "");
	define("COPY1_RIGHTS"			, "Green Professional Technologies");
	define("COPY1_RIGHTS_ORG"		, "&copy; ".date("Y")." - Green Professional Technologies.");
	define("COPY1_RIGHTS_URL"		, "https://gptech.pk/");
//---------------------------------------
	include_once "include/functions/login_func.php";
if(isset($_SESSION['userlogininfo']['LOGINIDA'])) {
	header("Location: dashboard.php");	
} else { 

$login_id = (isset($_POST['login_id']) && $_POST['login_id'] != '') ? $_POST['login_id'] : '';	
	$errorMessage = '';
	if (isset($_POST['login_id'])) {
		$result = cpanelLMSAuserLogin();
		if ($result != '') {
			$errorMessage = $result;
		}
	}
//---------------------------------------	
echo '
<!DOCTYPE html>

<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="login/images/logo.png" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="'.TITLE1_HEADER.'">
        <meta name="keywords" content="'.TITLE1_HEADER.'">
        <meta name="author" content="GPTech">
        <title>Login - '.TITLE1_HEADER.'</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="login/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <img alt="'.TITLE1_HEADER.'" class="w-12" src="login/images/logo.png">
                        <span class="text-white text-lg ml-3 font-medium">IMS</span> 
                    </a>
                    <div class="my-auto">
                        <img alt="'.TITLE1_HEADER.'" class="-intro-x w-1/2 -mt-16" src="login/images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                          '.TITLE1_HEADER.'
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-gray-500 text-center" style="line-height: 1.3em;">Manage all your institution <br>in one gatway</div>
                       
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-dark-1 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                       <form  enctype="multipart/form-data" method="post" accept-charset="utf-8" name="frmLogin" id="frmLogin">
                         <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>';
//---------------------------------------
if($errorMessage) {
	echo '<div style="font-weight:600; text-align:center;margin-top:10px;">
			'.$errorMessage.'
		 </div>';
}
//---------------------------------------
echo '
                        
                        <div class="intro-x mt-8"><b>Email / User Name</b>
                            <input type="text" class="intro-x login__input form-control py-3 px-4 border-gray-300 block" placeholder="Email / User Name" autofocus name="login_id" id="login_id" value="'.$login_id.'" required autocomplete="off">
                           
                        </div>
                        <div class="intro-x mt-4"><b>Password</b>
                            
                            <input type="password" class="intro-x login__input form-control py-3 px-4 border-gray-300 block " placeholder="Password" name="login_pass" value="" id="login_pass" autocomplete="off" required>
                        </div>
                        <div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            <a href="">Forgot Password?</a> 
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" id="btn_submit">Login</button>
                            
                        </div>
                        <div class="intro-x mt-10 xl:mt-24 text-gray-700 dark:text-gray-600 text-center xl:text-left">
                           
                            <br>
                           <a class="text-theme-1 dark:text-theme-10" href="'.COPY1_RIGHTS_URL.'" target="_blank"> '.COPY1_RIGHTS_ORG.'</a> 
                        </div>
                        </form>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
     
        <!-- BEGIN: JS Assets-->
        <script src="login/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>';
}
?>