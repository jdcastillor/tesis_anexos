<?php
include_once("/proxies/required.php");

$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
$cuestionario= cambia($_COOKIE["cookie_cuestionarios0"], true);
$idUser=cambia($_COOKIE["cookie_id"], true);
$posicio = -1;
$mediana = 0;
$fech=strtotime("now");
$matrizSalidaMed = array();
$matrizDistanciasT = array();

//*************************  Conectando la base de datos
	$conbd = pg_connect($strCnx) or die('No se ha podido conectar: ' . pg_last_error());

	$query_Idpreguntas = "SELECT * FROM pregunta WHERE activa = 1 AND idcuestionario = '$cuestionario' AND geoconsenso = 1 ORDER BY idpregunta ASC";
	$result = pg_query($query_Idpreguntas) or die('La consulta fallo: ' . pg_last_error());
		
		for ($i=0;$i<pg_num_rows($result);++$i)
		{
			$row = pg_fetch_array( $result,$i );
			$matrizidpreguntas[] = $row["idpregunta"];
			
			$matrizPgtas[] = array( 
				'idpregunta' => $row["idpregunta"],
				'idcuestionario' => $row["idcuestionario"],
				'tiporesp' => $row["tiporesp"],
				'geoconsenso' => $row["geoconsenso"],
				'activa' => $row["activa"],
				'x_cen' => $row["x_cen"],
				'y_cen' => $row["y_cen"],
				'dist_ini' => $row["dist_ini"]
			);
			
		}
		
		$matrizGids = array();
		$idP=0;
		
		for ($ii=0;$ii<count($matrizPgtas); $ii++){
			$idPregta = $matrizPgtas[$ii]["idpregunta"];
			$idP = $idP + 1;
			$query = "SELECT a.gid as gida, b.gid as gidb, ST_Distance(ST_Transform(a.the_geom,900913), ST_Transform(b.the_geom,900913)) 
			as dist, a.the_geom as the_geom, b.the_geom as the_geomb, a.x as x_a, a.y as y_a, a.z as z_a, b.x as x_b, b.y as y_b, b.z as z_b, a.idpanel as idpanel
			FROM respuesta a INNER JOIN respuesta b ON a.gid != b.gid WHERE a.idpregunta='$idPregta' AND a.estado=1 AND b.idpregunta='$idPregta' AND b.estado=1 
			ORDER BY gida ASC";
			
			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
			
			$matrizDistancias1p = array();
			
			if (pg_num_rows($result) >= 3){
				// crea un array por cada pregunta con todas los vectores de distancias, es decir selecciona todas
				// las opiniones o respuestas a una solo prgunta
				for ($i=0; $i<pg_num_rows($result); $i++){
				
					$row = pg_fetch_array ( $result,$i );
					$rowSiguiente = pg_fetch_array ( $result,$i+1 );
					
					$matrizDistancias1p[] = array( 
						'gidb' => $row["gidb"],
						'dist' => $row["dist"],
						'the_geom' => $row["the_geom"],
						'the_geomb' => $row["the_geomb"],
						'idpanel' => $idP,
						'x_a' => $row["x_a"],
						'y_a' => $row["y_a"],
						'z_a' => $row["z_a"],
						'x_b' => $row["x_b"],
						'y_b' => $row["y_b"],
						'z_b' => $row["z_b"],
					);
					
					// Aqui se tienen los arrays de distancias de cada respuesta
					if ($row["gida"] != $rowSiguiente["gida"] AND $row["gida"] != pg_num_rows($result) - 1) {
						$matrizGids[]= [
							'gida' => $row["gida"],
							'datos' => $matrizDistancias1p,
						];
						unset($matrizDistancias1p);
						$matrizDistancias1p = array();
					}
				}
			}else{
			
				$dis = $matrizPgtas[$ii]["dist_ini"];
				$xa=$matrizPgtas[$ii]["x_cen"];
				$ya=$matrizPgtas[$ii]["y_cen"];
				
					for ($j=0; $j<1; $j++){
						
						$matrizDistancias1p[] = array( 
							'gidb' => $j+1,
							'dist' => $dis,
							'the_geom' => "",
							'the_geomb' => "",
							'idpanel' => $idP,
							'x_a' => $xa,
							'y_a' => $ya,
							'z_a' => "",
							'x_b' => $xa,
							'y_b' => $ya,
							'z_b' => "",
						);
						
							$matrizGids[]= [
								'gida' =>  $j,
								'datos' => $matrizDistancias1p,
							];
							unset($matrizDistancias1p);
							$matrizDistancias1p = array();

						for ($i=0; $i<pg_num_rows($result3); $i++){
				
							$row = pg_fetch_array ( $result3,$i );
							$rowSiguiente = pg_fetch_array ( $result3,$i+1 );
							
							$matrizDistancias1p[] = array( 
								'gidb' => $row["gidb"],
								'dist' => $row["dist"],
								'the_geom' => $row["the_geom"],
								'the_geomb' => $row["the_geomb"],
								'idpanel' => $idP,
								'x_a' => $row["x_a"],
								'y_a' => $row["y_a"],
								'z_a' => $row["z_a"],
								'x_b' => $row["x_b"],
								'y_b' => $row["y_b"],
								'z_b' => $row["z_b"],
							);
							
							// Aqui se tienen los arrays de distancias de cada respuesta
							if ($row["gida"] != $rowSiguiente["gida"] AND $row["gida"] != pg_num_rows($result3) - 1) {
								$matrizGids[]= [
									'gida' => $row["gida"],
									'datos' => $matrizDistancias1p,
								];
								unset($matrizDistancias1p);
								$matrizDistancias1p = array();
							}
						}
						
					}
				
			}
			
			$matrizDistanciasT[] = array(
				'idpregta'=> $idPregta,
				'respuesta'=>$matrizGids,
			);	
			unset($matrizGids);
			$matrizGids = array();
		}

	echo json_encode($matrizDistanciasT);
	
	pg_free_result($result);  

	pg_close($conbd); 
?>
