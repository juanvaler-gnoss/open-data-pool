<?php
    $vista=70;
    define ("CLAVE_URI", "CODIGO");
    define ("CLAVE_TIENE", "LOCA_MUN");
    define ("CLAVE_NECESITA", "CODIGO_MUN");
    include 'comun.php';   
    
    $claveComun = "DENOMINACION"; //La clave que tiene en comun todas la vista
    $root = "root"; //El directorio raiz para la consulta xpath
    $item = "item"; //El nombre de cada elemento item del xml
    
    $xmlDepende = simplexml_load_file ("../VistasXml/Vista11/vista_11_1.xml");
    
    if ($archivoCSV !== false) { 
        array_push ($keys, CLAVE_NECESITA);
        fwrite ($archivoCSV, "\"".CLAVE_NECESITA."\";");
        
        fwrite ($archivoCSV, "\n");
        for ($i = 1; $i <= $numeroArchivos; $i ++) {
            $datosArchivo = file_get_contents (RUTA_XML."Vista_".$vista."_$i.xml");
			if (is_string ($datosArchivo) ) {
            $xml = simplexml_load_string($datosArchivo);
            
            for ($x = 0; $x < ($xml->count ()); $x++) {
                foreach ($keys as $key) {
                    $elemento = $xml->item[$x]->$key;
                    
                    if ($key == CLAVE_URL) {
                        $elemento = obtenerUrlVinculacion($xml, $x, $vista, CLAVE_URI);
                    }
                    
                    if ($key == CLAVE_NECESITA) {
                        $nombreMun = $xml->item[$x]->{CLAVE_TIENE};                                                
                        $nombreMun = substr ($nombreMun, 0, -3);
                        
                        $codMun = $xmlDepende->xpath ("/".$root."/".$item."[".$claveComun."= '".$nombreMun."']/".CLAVE_NECESITA);
                        $elemento = $codMun [0];
                    }
                    
                    editarElemento($elemento);
                    fwrite ($archivoCSV, "\"$elemento\";");
                }
                fwrite ($archivoCSV, "\n");
            }
        }
        }
        fclose ($archivoCSV);
    }	
?>