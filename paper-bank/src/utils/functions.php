<?php

/**
 * Redirect the User to any route
 * @param {string} $route - The route where you want to redirect the user
 * @param {array} $flash - Flash message Array if provided then the redirect will happen with flash message
 *
 * @return - Instance of the Utils::redirect($route, $flash) function
 */
function redirect($route, $flash = null)
{
    if ($flash) {
        return Utils::redirect($route, $flash);
    } else {
        return Utils::redirect($route);
    }
}

/**
 * Print the Data in the pretty formate
 * @param {array} $data - data you want to print
 */
function dump($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * Check if the given value is empty or not
 * @param {array} $data
 *
 * @return bool
 *
 */
function is_empty($data) : bool
{
    if (gettype($data) == "integer") {
        return $data <= 0 ? true : false;
    }

    if (gettype($data) == "double") {
        return $data <= 0 ? true : false;
    }

    if (gettype($data) == "string") {
        return $data == "" || $data == NULL ? true : false;
    }
}


/**
 * Include the component from inc folder
 * @param $data - Data / Props you want to pass to the data
 * @param $componentName - ComponentName you want to include
 *
 */
function component($componentName, $data)
{
    require(APPROOT . '/views/inc/' . $componentName . ".php");
}

/**
 * Include the assets from the assets folder inside public folder
 * @param $location - Location inside public/assets folder or name of file.
 *
 */
function assets($location)
{
    echo URLROOT . "/assets/" .$location;
}


/**
 * Converts a number to its roman presentation.
 **/
function numberToRoman($num)
{
  // Be sure to convert the given parameter into an integer
  $n = intval($num);
  $result = '';

  // Declare a lookup array that we will use to traverse the number:
  $lookup = array(
    'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
    'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
    'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
  );

  foreach ($lookup as $roman => $value)
  {
    // Look for number of matches
    $matches = intval($n / $value);

    // Concatenate characters
    $result .= str_repeat($roman, $matches);

    // Substract that from the number
    $n = $n % $value;
  }

  return strtolower($result);
}

/**
 * Route
 *
 * @param null | string $path
 * @desc Provide the path not using the prefixing slash "your path"
 * Returns the absolute path using the URLROOT variable
 *
 */
function route($path = null){
  if (!$path)
    return URLROOT;

  return URLROOT . "/" . $path;
}


/**
 * Ip Address of User
 *
 * @desc Returns the ip address of the user
 */
function user_ip_address(){

  $ip = getenv('HTTP_CLIENT_IP')?: getenv('HTTP_X_FORWARDED_FOR')?: getenv('HTTP_X_FORWARDED')?: getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
          getenv('REMOTE_ADDR');

  return $ip == "::1" ? "127.0.0.1" : $ip;
}

/**
 * question typpes array
 *
 * @desc Returns the array of question type 
*/
function getQuestionType($id = ''){
  $questionType = array (
    array('id'=>'subjective', 'name'=>'Subjective')	,
    array('id'=>'objective', 'name'=>'Objective')
  );
  $quesT = array (
    'subjective'=>'Subjective'	,
    'objective'=>'Objective'
  );
  if(!empty($id)){
    return $quesT[$id];
  } else{
    return $questionType;
  }
}

/**
 * question typpes array
 *
 * @desc Returns the array of question type 
*/
function getQType($id = ''){
  $qType = array (
    array('id'=>'1'  , 'name'=>'Long Question')	      ,
    array('id'=>'2' , 'name'=>'Short Question')       ,
    array('id'=>'3'  , 'name'=>'MCQS')                ,
    array('id'=>'4'  , 'name'=>'True/False')          ,
    array('id'=>'5'  , 'name'=>'Fill in the Blanks')  ,
  );
  $qT = array (
    '1'=>'Long Question'	,
    '2'=>'Short Question' ,
    '3'=>'MCQS'           ,
    '4'=>'True/False'     ,
    '5'=>'Fill in the Blanks'
  );
  if(!empty($id)){
    return $qT[$id];
  } else{
    return $qType;
  }
}
/**
 * yes no array
 *
 * @desc Returns the array of Yes no 
*/
function getYesno($id = ''){
  $Yesno = array (
    array('id'=>'1' , 'name'=>'Yes')	      ,
    array('id'=>'2' , 'name'=>'No')         ,
 
  );
  $yn = array ('1' =>'Yes','2' =>'No');
  if(!empty($id)){
    return  $yn[$id];
  } else{
    return $Yesno;
  }
}

/**
 * Get the respectively layout for the addition of the questions
 *
 *
 * @desc Return the layout name according to the question type
 */
function getQuestionLayout($id){
  $layouts =[
    1 => 'question',
    2 => 'question',
    3 => 'mcqs'
  ];

  return $layouts[$id];
}


/**
 * Upload the file on the server
 *
 * @param $pathname - name of the input filed with the file attached
 * @param $storage_path - name of the destination where you want to save your file
 *
 * @desc Return the layout name according to the question type
 */
function upload($pathname, $storage_path, $options = [
  'max_size' => 10 * 1024 * 1024,
  'name_prefix' => ''
]){
  // Check if file was uploaded without errors
  if (isset($_FILES[$pathname]) && $_FILES[$pathname]["error"] == 0) {
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
    $filename = $_FILES[$pathname]["name"];
    $filetype = $_FILES[$pathname]["type"];
    $filesize = $_FILES[$pathname]["size"];

    // Verify file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

    // Verify file size - 5MB maximum
    $maxsize = $options['max_size'];
    if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

    $fileExtention = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = $options['name_prefix'] . '_' . date('Y-m-d-H-i-s') . "." . $fileExtention;

    move_uploaded_file($_FILES[$pathname]["tmp_name"], $storage_path . "/" . $filename);

    return $filename;
  }

  return false;
}