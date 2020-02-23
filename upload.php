<!DOCTYPE html>
<html>
 <head>
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <style>
   h1 {
  color: blue;
  text-align: center;
}
  .footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   background-color: #c21b28 ;
   color: white;
   text-align: center;
}
  </style>

 </head>
 <body>
 
  <nav class="navbar navbar-expand-sm bg-danger navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" >STEMMER</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.html">Home</a>
    </li>
   
  </ul>
</nav>
<?php
$uploaddir = 'inputs/';
$uploadfile = $uploaddir . basename($_FILES['fileToUpload']['name']);
echo '<pre>';
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
print "</pre>";
$filename=$uploadfile;
$uploadedfile=fopen($filename,"r") or die("Unable to open Input file!");
$suffix_file=fopen("resources/suffix.txt","r") or die("Unable to open Suffix file!");
$dir='outputs/';
$nameo=$dir . basename($_FILES['fileToUpload']['name']);
$out_file=fopen($nameo,"w") or die("Unable to open Output file!");
function find_in_dict($string)
{
  $handle = fopen('resources/dict.txt', 'r') or die("Unable to open dictionary");
  $valid = false; 
  while (($buffer = fgets($handle)) !== false) 
  {
   // echo "Matchin " .$string . " with " .$buffer ."<br>";
    //if($buffer==$string)
    $buffer=trim($buffer);
    if(strcmp($buffer,$string)==0) 
    {
        $valid = TRUE;
        break;
    }  
  }
  fclose($handle);
  return $valid;  
}
function endsWith($wordex,$suffix)
{
  $wordex = trim($wordex);
  $suffix = trim($suffix);
  $len=strlen($suffix);
  if($len==0)
  {
    return true;
  }
  trim($wordex);
  trim($suffix);
  return (substr($wordex,-$len)===$suffix);
}
function matched($wordex,$suffix)
{
  $wordex = trim($wordex);
  $suffix = trim($suffix);
  $len=strlen($suffix);//echo "I'm called". "<br>";
  $lenw=strlen($wordex);
  //echo "<br>". substr($wordex,0,$lenw-$len) . " + " .$suffix  . " # " . $wordex . "<br>". "<br>";
  $text=substr($wordex,0,$lenw-$len)." + ".$suffix."\n\n";
  return $text;
}
$count=0;
while(!feof($uploadedfile))
{
  $line=fgets($uploadedfile);
  //echo $line ." : ".$count++."<br>";
  $word_arr=explode(" ",$line);
  //print_r($word_arr);
  
  foreach($word_arr as $string)
  {
    $found=0;
    $string=trim($string," ");
    if(!empty($string)){
    fwrite($out_file,"  Extracted Word : ".$string."<br>");
    if(find_in_dict($string))
    {
      fwrite($out_file,"  Stammed Word : ".$string."<br>"."<br>");
    }
    else
    {
      $words=file('resources/suffix.txt',FILE_IGNORE_NEW_LINES);
      for($i=0;$i<sizeof($words);$i++)
      {
        $suffix=trim($words[$i]," ");
        if(endsWith($string,$suffix))
        {
          //echo $suffix."<br>";
          fwrite($out_file,"  Stammed Word : ".matched($string,$suffix));
          $found=1;
          break;
        }
      }
      if($found==0)
      {
        //echo $string ."<br>";
        fwrite($out_file,"  Stammed Word :  "."\t"." OOV  "."<br>"."<br>");
      }
    }
   }
  }
}
fclose($uploadedfile);
fclose($suffix_file);
fclose($out_file);
$out_file=fopen($nameo,"r") or die("Unable to open Output file!");
while(!feof($out_file))
{
  echo " ".fgets($out_file) . "<br>";
}
fclose($out_file);
?>
 <div class="footer page-header">
            <hr>
            <p class="mute">&copy; 2019 Stammer</p>
        </div>
</body>
</html>
