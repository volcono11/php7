<?php
session_start();
require "connection.php";
date_default_timezone_set('Asia/Dubai');
$encrypted = '';
$decrypted = '';

$chars = array(
    'a' => 'd',
    'b' => 'j',
    'c' => 'p',
    'd' => 'a',
    'e' => 'o',
    'f' => 'c',
    'g' => 'q',
    'h' => 'i',
    'i' => 'n',
    'j' => 's',
    'k' => 'r',
    'l' => 'b',
    'm' => 'm',
    'n' => 'z',
    'o' => 'x',
    'p' => 'e',
    'q' => 't',
    'r' => 'y',
    's' => 'g',
    't' => 'k',
    'u' => 'u',
    'v' => 'v',
    'w' => 'l',
    'x' => 'f',
    'y' => 'w',
    'z' => 'h',

    '1' => '2',
    '2' => '4',
    '3' => '6',
    '4' => '8',
    '5' => '0',
    '6' => '9',
    '7' => '7',
    '8' => '5',
    '9' => '3',
    '0' => '1',

    'A' => 'D',
    'B' => 'J',
    'C' => 'P',
    'D' => 'A',
    'E' => 'O',
    'F' => 'C',
    'G' => 'Q',
    'H' => 'I',
    'I' => 'N',
    'J' => 'S',
    'K' => 'R',
    'L' => 'B',
    'M' => 'M',
    'N' => 'Z',
    'O' => 'X',
    'P' => 'E',
    'Q' => 'T',
    'R' => 'Y',
    'S' => 'G',
    'T' => 'K',
    'U' => 'U',
    'V' => 'V',
    'W' => 'L',
    'X' => 'F',
    'Y' => 'W',
    'Z' => 'H',

);



$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($con,$sql);

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$sqlScript = "";
foreach ($tables as $table) {

    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($con,$query);
    $row = mysqli_fetch_row($result);

    $sqlScript .= "\n\n" . encryptData($row[1],$chars) . ";\n\n";


    $query = "SELECT * FROM $table";
    $result = mysqli_query($con,$query);

    $columnCount = mysqli_num_fields($result);

    // Prepare SQLscript for dumping data for each table
    for ($i = 0; $i < $columnCount; $i ++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "NZGOYK NZKX ".encryptData($table,$chars)." VDBUOG(";
            for ($j = 0; $j < $columnCount; $j ++) {
                $row[$j] = $row[$j];

                if (isset($row[$j])) {
                    $sqlScript .= '"' . encryptData(trim($row[$j]),$chars) . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }

    $sqlScript .= "\n";
}

if(!empty($sqlScript)){

    $backup_file_name = $database . '_backup_' . date("d-m-Y-h-i-a"). '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler);

    if(gzcompressfile($backup_file_name,1)==false){
                echo '<br/>Gzipped file could not be created.';
                return false;
    }else{
                $file_name= $backup_file_name.".zip";
                unlink($backup_file_name);
                output_file($file_name, ''.$file_name.'', 'text/plain');
    }
}

function gzcompressfile($source,$level=false){
        $dest=$source.'.zip';
        $mode='wb'.$level;
        $error=false;
        if($fp_out=gzopen($dest,$mode))
        {
                if($fp_in=fopen($source,'rb'))
                {
                        while(!feof($fp_in))gzwrite($fp_out,fread($fp_in,4096));
                        fclose($fp_in);
                }
                else $error=true;
                gzclose($fp_out);
        }
        else $error=true;
        if($error) return false;
        else return $dest;
}


function output_file($file, $name, $mime_type=''){
 if(!is_readable($file)) die('File not found or inaccessible!');
 $size = filesize($file);
 $name = rawurldecode($name);
 $known_mime_types=array(
    "htm" => "text/html",
    "exe" => "application/octet-stream",
    "zip" => "application/zip",
    "doc" => "application/msword",
    "jpg" => "image/jpg",
    "php" => "text/plain",
    "xls" => "application/vnd.ms-excel",
    "ppt" => "application/vnd.ms-powerpoint",
    "gif" => "image/gif",
    "pdf" => "application/pdf",
    "txt" => "text/plain",
    "html"=> "text/html",
    "png" => "image/png",
    "jpeg"=> "image/jpg"
 );

 if($mime_type==''){
     $file_extension = strtolower(substr(strrchr($file,"."),1));
     if(array_key_exists($file_extension, $known_mime_types)){
        $mime_type=$known_mime_types[$file_extension];
     } else {
        $mime_type="application/force-download";
     };
 };


 @ob_end_clean();


 if(ini_get('zlib.output_compression'))
 ini_set('zlib.output_compression', 'Off');
 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');


 if(isset($_SERVER['HTTP_RANGE']))
 {
    list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
    list($range) = explode(",",$range,2);
    list($range, $range_end) = explode("-", $range);
    $range=intval($range);
    if(!$range_end) {
        $range_end=$size-1;
    } else {
        $range_end=intval($range_end);
    }

    $new_length = $range_end-$range+1;
    header("HTTP/1.1 206 Partial Content");
    header("Content-Length: $new_length");
    header("Content-Range: bytes $range-$range_end/$size");
 } else {
    $new_length=$size;
    header("Content-Length: ".$size);
 }

 /* Will output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r'))
 {
    if(isset($_SERVER['HTTP_RANGE']))
    fseek($file, $range);

    while(!feof($file) &&
        (!connection_aborted()) &&
        ($bytes_send<$new_length)
          )
    {
        $buffer = fread($file, $chunksize);
        echo($buffer);
        flush();
        $bytes_send += strlen($buffer);
    }
 fclose($file);
 unlink($name);
 } else
 //If no permissiion
 die('Error - can not open file.');
 //die
die();
}
function encryptData($original,$chars){
   $encrypted = "";
   for ($i = 0; $i < strlen($original); $i++) {
    $encrypted .= array_key_exists($original[$i], $chars)
        ? $chars[$original[$i]]
        : $original[$i];
   }
   return $encrypted;
}

function decryptData($encrypted,$chars){
   for ($i = 0; $i < strlen($encrypted); $i++) {
    $key = array_search($encrypted[$i], $chars);
    $decrypted .= $key !== false
        ? $key
        : $encrypted[$i];
   }
   return trim($decrypted);
}

?>
