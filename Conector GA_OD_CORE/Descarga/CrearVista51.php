<?php
    $vista=51;
    define ("CLAVE_URI", "NOMBRE");
    define ("CLAVE_CARGO", "CARGO");
    include 'comun.php';
    
    $cargos = array ();
    $cargos ["C"] = "CONCEJAL";
    $cargos ["VCPTA"] = "VICEPRESIDENTA";    
    $cargos ["A"] = "ALCALDE";
    $cargos ["P"] = "PRESIDENTE";
    $cargos ["V"] = "VOCAL";   
    
    if ($archivoCSV !== false) {
        fwrite ($archivoCSV, "\n");
        for ($i = 1; $i <= $numeroArchivos; $i ++) {
            $datosArchivo = file_get_contents (RUTA_XML."Vista_".$vista."_$i.xml");
            $xml = simplexml_load_string($datosArchivo);
            
            for ($x = 0; $x < ($xml->count ()); $x++) {
                foreach ($keys as $key) {
                    $elemento = $xml->item[$x]->$key;
                    
                    if ($key == CLAVE_URL) {
                        $elemento = obtenerUrlVinculacion($xml, $x, $vista, CLAVE_URI);
                    }
                    
                    if ($key == CLAVE_CARGO) {
                        $claves = array_keys ($cargos);
                        if (in_array($elemento->__toString (), $claves)){
                            $elemento = $cargos[$elemento->__toString ()];
                        }
                    }
                    
                    editarElemento($elemento);
                    fwrite ($archivoCSV, "\"$elemento\";");
                }
                fwrite ($archivoCSV, "\n");
            }
        }
        
        fclose ($archivoCSV);
    }
?>