//********Agrega el nuevo consenso
function nuevo_consenso(medCON){

nvo_consenso_request = Ext.Ajax.request({
            url: urlArgCons=getsdiportal_URI+'modules/sigic/consenso/consensoDiZio_agregaNuevo.php',
			params: { "idPregta": medCON.idPregta,"gidPtoCentral": medCON.gidPtoCentral,
			"the_geomPtoCentral": medCON.the_geomPtoCentral,"idpanel": medCON.idpanel,
			"mediana": medCON.mediana,"areaConsenso": medCON.areaConsenso,
			"ptoCentralConsensoX": medCON.ptoCentralConsensoX,"ptoCentralConsensoY": medCON.ptoCentralConsensoY,
			"ptoCentralConsensoZ": medCON.ptoCentralConsensoZ },
            timeout: 5000,
            failure: function(response)
            {
				var titErrMessage ='Error al enviar datos de nuevo consenso al Servidor';
				var errMessage = 'Estado: ' + response.status + ' | Texto de estado: ' + response.statusText + ' | Respuesta: ' + response.responseText + '<br/>Por favor, intente nuevamente... ';			   
				Ext.Msg.alert(titErrMessage,errMessage);
			},
            success: function(result)
            {
                if ((typeof result.responseText !== "undefined") && (result.responseText.toString() != ""))
                {

                }
            }
	});

}
