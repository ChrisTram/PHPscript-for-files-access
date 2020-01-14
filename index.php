<?php
exec("stat --format=\"%s\" \"$file\"");

echo "<html>

<head>
      <!--Import Google Icon Font-->
      <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
      <!--Import materialize.css-->
      <link type='text/css' rel='stylesheet' href='css/materialize.min.css'  media='screen,projection'/>

      <!--Let browser know website is optimized for mobile-->
      <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
</head>

<body>


      <script type='text/javascript' src='js/materialize.min.js'></script>

<table class='bordered highlight'>
        <thead>
          <tr>
              <th>Nom</th>
              <th>Télécharger</th>
              <th>Poid</th>
              <th>Date</th>
          </tr>
        </thead>

        <tbody>

";
$path = ".";



$blacklist = array('somedir','index.php', "css", "js", "LICENSE", "README.md");
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
function realFileSize($path) {
  if(@is_dir($path)) {
    return 0;
  }
  $sizeInBytes = @filesize($path);
  if($sizeInBytes===false) {
    $command = "/bin/ls -l ".escapeshellarg($path);
    $ret = trim(exec($command)); 
    if(!substr_count($ret, "ls:") && !substr_count($ret, "Aucun fichier")) {
      $ret = str_replace("\t", " ", $ret);
      $ret = str_replace("  ", " ", $ret);
      $ret = str_replace("  ", " ", $ret);
      $ret = str_replace("  ", " ", $ret);
      $arr = explode(" ", $ret);
      $sizeInBytes = $arr[4];
    }
  }
  return $sizeInBytes;
}
// get everything except hidden files
$files = preg_grep('/^([^.])/', scandir($path)); 

$replaceBy = array(" ", " ", " ", " ", " ");
$toReplace = array(".", "#", "mp4", "mkv");


foreach ($files as $file) { 
    if (!in_array($file, $blacklist)) {
        echo "<tr>
        <td><a href='$path/$file' target='_blank'>" .  str_replace($toReplace, $replaceBy, $file) . "</a></td>
        <td><a class='btn-floating btn-medium waves-effect waves-light red' href='$path/$file' target='_blank' download><i class='material-icons'>cloud_download</i></a></td>
        <td>" .  human_filesize(realFileSize($file)) . "</td>
        <td>" .  date ("F d Y H:i:s.", filemtime($file)) . "</td>


        </tr>";
    }
}
echo "        </tbody>
      </table></body></html>";

?> 