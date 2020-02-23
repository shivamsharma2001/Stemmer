<?php
$dict=file('resources/dict.txt',FILE_IGNORE_NEW_LINES);
for($i=0;$i<sizeof($dict);$i++)
{
  $hash[$dict[$i]]=1;
}
?>
