<?php
include 'db.php';
$con = getdb();
if(isset($_POST["Import"])){
  $filename = $_FILES["file"]["tmp_name"];
  if($_FILES["file"]["size"] > 0){
    $file = fopen($filename, "r");
    $file_data= file($filename);
    foreach($file_data as $k){
        $csv[] = explode(',',$k);

        
    }
    //echo "<pre>";
    //print_r($csv);
         $n = count($csv);
         $flag = 0;
        for($i=0;$i<$n;$i++){
        $saveTo = "images/" . $i+1 .".png";
        $url = $csv[$i][1];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        curl_close($ch);
        $sql = "INSERT INTO images (image) VALUES ('$saveTo')";
        if(mysqli_query($con, $sql)){
          $flag += 1;
        };
        if(file_exists($saveTo)){
          unlink($saveTo);
        }
        $fp = fopen($saveTo, 'x');
        fwrite($fp,$raw);
        fclose($fp);
        }
//
  }
  if($flag == $n){
    mail($_POST["email"],"Product Images updated","Images have been Updated to the Server from your Csv file");
  }else{
    echo "Unable To Upload all the Images from CSV file";
  }

}
?>