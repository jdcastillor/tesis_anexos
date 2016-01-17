<?php  

include("proxies/required.php");

$idPgta = isset($_GET['idPgta']) ? $_GET['idPgta']:"";
$idPanel = isset($_GET['idPgtaPanel']) ? $_GET['idPgtaPanel']:"";
$cuestionario=cambia($_COOKIE["cookie_cuestionarios0"], true);

	$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";

	$conbd = pg_connect($strCnx) or die('No se ha podido conectar: ' . pg_last_error());

	$query = "SELECT DISTINCT idpregunta, idpanel, mediana, the_geom, x, y, z, areacons, fecha, idcons,	idcuestionario,	iduser 
	FROM consensos WHERE idcuestionario = '$cuestionario' AND idpregunta = '$idPgta' ORDER BY idpanel ASC, fecha ASC";
	$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
	$registros= pg_num_rows($result);
	
		if ($registros > 0 ){
			$arr0 = array();
				
			for ($i=0;$i<$registros;$i++)
			{
				$row = pg_fetch_array ( $result,$i );
				
				$arr0 = array(
							'idpregunta' => $row["idpregunta"],
							'idpanel' => $row["idpanel"],
							'mediana' => $row["mediana"],
							'the_geom' => $row["the_geom"],
							'x' => $row["x"],
							'y' => $row["y"],
							'z' => $row["z"],
							'areacons' => $row["areacons"],
							'idcons' => $row["idcons"],
							'idcuestionario' => $row["idcuestionario"],
							'iduser' => $row["iduser"],
							'fecha' => date("d M Y, H:m:s", $row["fecha"]),
				);
			}
			
			$ar = $arr0["mediana"];
			$xa= $arr0["x"];
			$ya = $arr0["y"];
			$arCon = $arr0["areacons"];
			
			pg_free_result($result);
			
			$query = "SELECT * FROM respuesta as A WHERE ST_DWithin(ST_GeomFromText('POINT(" . $xa . " " . $ya . ")', 4326)::geography, A.the_geom::geography," . $ar . ") AND idpregunta= " . $idPgta . " AND estado=1 ORDER BY fecha DESC";
			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
			$registros= pg_num_rows($result);
			
			$arr1 = array();

			for ($i=0;$i<$registros;$i++)
			{
				$row = pg_fetch_array ( $result,$i );
				
				$area=0;
				$idUser = $row["iduser"];
				$query2 = "SELECT areacons, mediana FROM consensos WHERE idpregunta = $idPgta AND iduser = $idUser ORDER BY fecha DESC LIMIT 1";
				$result2 = pg_query($query2) or die('La consulta fallo: ' . pg_last_error());
				$registros2= pg_num_rows($result2);
				
				for ($ii=0;$ii<$registros2;$ii++)
				{
					$row2 = pg_fetch_array ( $result2,$ii );
					$area = $row2['areacons'];
					$mediana = $row2['mediana'];
				}
				
				$arr1[] = array(
							'IdU' => $row["iduser"],
							'Argto' => $row["argumento"],
							'valor'=> $row["valor"],
							'G' => $row["x"]. ', ' . $row["y"],
							'F' => date("d M Y, H:m:s, T", $row["fecha"]),
							'F2' => $row["fecha"],
							'IdPBd' => $idPgta,
							'IdPanel' => $idPanel,
							'ultCons' => $area,
							'mediana' => $mediana,
							'E_Cons' => 1,
				);
			}

			pg_free_result($result); 
			
			$query = "SELECT * FROM respuesta as A WHERE NOT ST_DWithin(ST_GeomFromText('POINT(" . $xa . " " . $ya . ")', 4326)::geography, A.the_geom::geography," . $ar . ") AND idpregunta= " . $idPgta . " AND estado=1 ORDER BY fecha DESC";
			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
			$registros= pg_num_rows($result);

			for ($i=0;$i<$registros;$i++)
			{
				$row = pg_fetch_array ( $result,$i );
				
				$area=0;
				$idUser = $row["iduser"];
				$query2 = "SELECT areacons, mediana FROM consensos WHERE idpregunta = $idPgta AND iduser = $idUser ORDER BY fecha DESC LIMIT 1";
				$result2 = pg_query($query2) or die('La consulta fallo: ' . pg_last_error());
				$registros2= pg_num_rows($result2);
				
				for ($ii=0;$ii<$registros2;$ii++)
				{
					$row2 = pg_fetch_array ( $result2,$ii );
					$area = $row2['areacons'];
					$mediana = $row2['mediana'];
				}
				
				$arr1[] = array(
							'IdU' => $row["iduser"],
							'Argto' => $row["argumento"],
							'valor'=> $row["valor"],
							'G' => $row["x"]. ', ' . $row["y"],
							'F' => date("d M Y, H:m:s, T", $row["fecha"]),
							'F2' => $row["fecha"],
							'IdPBd' => $idPgta,
							'IdPanel' => $idPanel,
							'ultCons' => $area,
							'mediana' => $mediana,
							'E_Cons' => 0,
				);
			}
		}else{
		
			$query = "SELECT * FROM respuesta WHERE idpregunta= " . $idPgta . " AND estado=1 ORDER BY fecha DESC";
			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
			$registros= pg_num_rows($result);
			
			if ($registros>0){
				for ($i=0;$i<$registros;$i++)
				{
					$row = pg_fetch_array ( $result,$i );
					
					$area=0;
					$mediana = 0;
					$idUser = $row["iduser"];
					
					$arr1[] = array(
								'IdU' => $row["iduser"],
								'Argto' => $row["argumento"],
								'valor'=> $row["valor"],
								'G' => $row["x"]. ', ' . $row["y"],
								'F' => date("d M Y, H:m:s, T", $row["fecha"]),
								'F2' => $row["fecha"],
								'IdPBd' => $idPgta,
								'IdPanel' => $idPanel,
								'ultCons' => $area,
								'mediana' => $mediana,
								'E_Cons' => 1,
					);
				}
			}
		}
	
	
	echo json_encode($arr1);
	
	pg_free_result($result);  

	pg_close($conbd);  

?> 
