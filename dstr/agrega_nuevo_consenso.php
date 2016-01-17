<?php
include_once("proxies/required.php");

$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
$cuestionario= cambia($_COOKIE["cookie_cuestionarios0"], true);
$idUser=cambia($_COOKIE["cookie_id"], true);
$fech=strtotime("now");

	$idPregtaA = $_POST['idPregta'];
	$vMA = $_POST['mediana'];
	$vAcA  = $_POST['areaConsenso'];
	$idPanA = $_POST['idpanel'];
	$gidPcA = $_POST['gidPtoCentral'];
	$vxA = $_POST['ptoCentralConsensoX'];
	$vyA = $_POST['ptoCentralConsensoY'];
	$vzA = $_POST['ptoCentralConsensoZ'];

	
	$idPregta = intval($idPregtaA);
	$vM = floatval ($vMA);
	$vAc  = floatval ($vAcA);
	$idPan = intval($idPanA);
	$gidPc = intval($gidPcA);
	$vx = floatval ($vxA);
	$vy = floatval ($vyA);
	$vz = floatval ($vzA);
	
	$conbd = pg_connect($strCnx) or die('No se ha podido conectar: ' . pg_last_error());
	$query = "INSERT INTO consensos(idcuestionario,idpregunta,idpanel,gidpto,x,y,z,mediana,areacons,fecha,iduser) 
	VALUES($cuestionario, $idPregta, $idPan, $gidPc, $vx, $vy, $vz, $vM, $vAc, $fech, $idUser)";
	$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());							
	
	pg_free_result($result);  

	pg_close($conbd);
?>
