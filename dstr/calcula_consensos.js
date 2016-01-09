function calculaConsensos(datos,dibujar,nvoCons,idPt){

medianasPgtas.length = 0;

var medianNvoCons = new Array();

for (var p=0; p<datos.length; p++){ // por tantas preguntas como hayan en el array:

	var preguntaNo = datos[p].idpregta;
	var arraySoloMedianas = new Array();
	var gIdyMedianas = new Array();		
	
	for (var gIdres=0; gIdres<datos[p].respuesta.length; gIdres++){
		
		var gidaNo = datos[p].respuesta[gIdres].gida;
		var matDist = new Array();
		var idP = -1;
		var dis = -1;
		var geom = -1;
		var la = -1;
		var lo = -1;
		var el = -1;
		
		for ( var cantDatos=0; cantDatos<datos[p].respuesta[gIdres].datos.length; cantDatos++ ){
			matDist.push(parseFloat(datos[p].respuesta[gIdres].datos[cantDatos].dist));
			idP =  parseInt(datos[p].respuesta[gIdres].datos[cantDatos].idpanel);
			dis =  parseFloat(datos[p].respuesta[gIdres].datos[cantDatos].dist);
			geom = datos[p].respuesta[gIdres].datos[cantDatos].the_geom;
			la =  parseFloat(datos[p].respuesta[gIdres].datos[cantDatos].y_a);
			lo =  parseFloat(datos[p].respuesta[gIdres].datos[cantDatos].x_a);
			el =  parseFloat(datos[p].respuesta[gIdres].datos[cantDatos].z_a);
		}
		
		var mediana = ss.median(matDist);
		
		arraySoloMedianas.push(mediana);
		
		var con = Math.PI * Math.pow(mediana, 2);
		
		gIdyMedianas.push({
		'idPregta':preguntaNo,
		'gidPtoCentral':gidaNo,
		'the_geomPtoCentral':geom,
		'idpanel':idP,
		'ptoCentralConsensoX':lo,
		'ptoCentralConsensoY':la,
		'ptoCentralConsensoZ':el,
		'mediana':mediana,
		'areaConsenso': con
		});
	}
	
	min = ss.min(arraySoloMedianas);
	
	for (var gm=0; gm<gIdyMedianas.length; gm++){
		if (gIdyMedianas[gm].mediana === min){
			medianasPgtas.push(gIdyMedianas[gm]);
			if (gIdyMedianas[gm].idPregta == idPt) {medianNvoCons = gIdyMedianas[gm];}
		}
	}

}

if (nvoCons == true ){nuevo_consenso(medianNvoCons);}
if (dibujar == true) {dibujaConsenso(medianasPgtas);}
}