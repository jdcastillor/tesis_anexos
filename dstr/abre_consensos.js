
function abre_consenso(dibuja,agrega_nuevo,idpreg){

consenso_request = Ext.Ajax.request({
            url: urlArgCons=getsdiportal_URI+'modules/sigic/consenso/consensoDiZio_soloDatos.php',
            timeout: 5000,
            failure: function(response)
            {
				var titErrMessage ='Error al obtener datos de consenso desde el Servidor'; // request.url es la variable del URL de petición 
				var errMessage = 'Estado: ' + response.status + ' | Texto de estado: ' + response.statusText + ' | Respuesta: ' + response.responseText + '<br/>Por favor, intente nuevamente... ';			   
				Ext.Msg.alert(titErrMessage,errMessage);
			},
            success: function(result)
            {
                if ((typeof result.responseText !== "undefined") && (result.responseText.toString() != ""))
                {
					mePgtas = Ext.util.JSON.decode(result.responseText);

					calculaConsensos(mePgtas,dibuja,agrega_nuevo,idpreg);
                }
            }
	});
}