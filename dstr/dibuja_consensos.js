var vectorLayer_Opiniones;
var vectorLayer_Consenso;
var consenso_request;
var marcas_request;

var vectorLayer_histMisOps;

function seleccionaColor(num)
{
	switch(num)
	{
	case 1:
		colorLinea='#0000FF';
		 break;
	case 2:
		colorLinea="#FFCC00";
		 break;
	case 3:
		colorLinea="#00FF00";
		 break;
	case 4:
		colorLinea="#FFBAD2";
		 break;
	case 5:
		colorLinea="#FF0000";
		 break;
	case 6:
		colorLinea="#01B6AD";
		 break;
	case 7:
		colorLinea="#CEEBFB";
		 break;
	case 8:
		colorLinea="#FFCC99";
		 break;
	case 9:
		colorLinea="#FFFF00";
		 break;
	case 10:
		colorLinea="#669966";
		 break;
	case 11:
		colorLinea="#FF006F";
		 break;
	case 12:
		colorLinea="#C9CACE";
		 break;
	case 13:
		colorLinea="#996699";
		 break;
	case 14:
		colorLinea="#6600CC";
		 break;
	case 15:
		colorLinea="#AAF200";
		 break;
	case 16:
		colorLinea="#F20056";
		 break;
	case 17:
		colorLinea="#663300";
		 break;
	case 18:
		colorLinea="#BDD8DA";
		 break;
	case 19:
		colorLinea="#B1B1B1";
		 break;
	case 20:
		colorLinea="#FFFFBE";
		 break;
	}
	
	return colorLinea;
}

function dibujaConsenso(medianasPgtasB){

	removeMarkersFromMap("areasConsenso");
	
	vectorLayer_Consenso = new OpenLayers.Layer.Vector("areasConsenso");
	
	var numPregt = -1;
	
	var respondidas = new Array();
	
		for (noPan=0;noPan < opinionesUsr.length;noPan++)
		{
			respondidas[noPan] = parseInt(opinionesUsr[noPan].idpanel);
		}

	for (ic=0;ic < medianasPgtasB.length;ic++)
	{
			var panelNo;
			panelNo = parseInt(medianasPgtasB[ic].idpanel);

			var idCuestionx = parseInt(medianasPgtasB[ic].idpanel);
			var colorLinea;
			var longitudx = medianasPgtasB[ic].ptoCentralConsensoX;
			var latitudy = medianasPgtasB[ic].ptoCentralConsensoY;
			var radio = medianasPgtasB[ic].mediana;
			var area = medianasPgtasB[ic].areaConsenso;
			var areaC;
			if (parseFloat(area) >= 10000) { 
				A = parseFloat(area * 0.000001).toFixed(2);
				areaC = A + ' km^2.';
			}else{
				areaC = parseFloat(area).toFixed(2) + ' m^2.';
			}
			
			var diametroC;
			if ((parseFloat(radio) * 2) >= 1000){
				A = parseFloat(radio * 2).toFixed(2);
				diametroC = 'd= ' + (A / 1000).toFixed(2) + ' km.';
			}else{
				diametroC = 'd= ' + parseFloat(radio * 2).toFixed(2) + ' m.';
			}
			
			var pto = new OpenLayers.Geometry.Point(longitudx, latitudy).transform(new OpenLayers.Projection('EPSG:4326'), new OpenLayers.Projection(map_currentMapProjection));
			
			colorLinea=seleccionaColor(idCuestionx);
				
			var activa;
			if (Ext.getCmp('chk_visualiza_'+panelNo).checked == false){
				activa = 'none';
			}else{
				activa = '';
			}
			
			var styleAreaConsenso =
				{
					display: activa,
					strokeColor: colorLinea,
					strokeOpacity: 1,
					strokeWidth: 2,
					fillColor: "#FFFFFF",
					fillOpacity: 0.0,
					fontSize: "11px",
					fontFamily: "Courier New, monospace",
					fontColor: colorLinea,
					labelAlign:"ct",
					label: diametroC    +  
				};

			var circulito = new OpenLayers.Geometry.Polygon.createGeodesicPolygon(pto, radio, 40, 0, map.getProjectionObject());
			var metaCons = {
				name: "consenso_" + ic,
				description:'consenso de la pregunta ' + medianasPgtasB[ic].idPregta,
				pregunta: medianasPgtasB[ic].idPregta};
			var lineFeature_AreaConsenso = new OpenLayers.Feature.Vector(circulito, metaCons, styleAreaConsenso);
			map.addLayer(vectorLayer_Consenso);

			vectorLayer_Consenso.addFeatures([lineFeature_AreaConsenso]);
		}
	}
}
