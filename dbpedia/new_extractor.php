<?php

require_once("../config.php");
set_time_limit(0);

function getTypesResource($resource) {
    try {
        $curl = curl_init();

        $query = "PREFIX dbres: <http://dbpedia.org/resource/>
        PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
        PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

        SELECT ?type WHERE
        { 
            <" . $resource . "> rdf:type ?type.
        }
        ";

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dbpedia.org/sparql",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "query=" . urlencode($query) . "&default-graph-uri=http%3A%2F%2Fdbpedia.org",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "postman-token: 98d3a3cc-71eb-0ae1-9840-4f2055f42ac7"
                ),
            ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        if ($httpcode == 200) {
            return $response;
        } else {
            throw new Exception("Resource " . $resource . " - httpCode= " . $httpcode);
        }
        if ($err) {
            throw new Exception($err);
        }
        curl_close($curl);
    } catch (Exception $e) {
        throw $e;
    }
}

function getResource($url) {
    try {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Postman-Token: b2befc2b-44fc-4c87-8d60-27ea390d2bb8"
          ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        if ($httpcode == 200) {
            return $response;
        } else {
            throw new Exception("Resource " . $resource . " - httpCode= " . $httpcode);
        }
        if ($err) {
            throw new Exception($err);
        }
        curl_close($curl);
    } catch (Exception $e) {
        throw $e;
    }
}

$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND buscaCompleta = 0 LIMIT 50");
foreach (getRows($tweets) as $key => $conceito) {
    $res = $conceito["resource"];
    $ret = getResource($res);    

    $dados = json_decode(trim($ret), true);
    $entidade = $dados[$res];

    $types = $entidade["http://www.w3.org/1999/02/22-rdf-syntax-ns#type"];
    $subjects = $entidade["http://purl.org/dc/terms/subject"];
    $seeAlso = $entidade["http://www.w3.org/2000/01/rdf-schema#seeAlso"];
    // $sameAs = $entidade["http://www.w3.org/2002/07/owl#sameAs"]; //for each idioma
    $abstracts = $entidade["http://dbpedia.org/ontology/abstract"]; //for each por idioma
    $hypernym = $entidade["http://purl.org/linguistics/gold/hypernym"];
    
    $abstract = null;
    if (isset($abstracts)) {
        foreach ($abstracts as $keyAbs => $abs) {
            if ($abs["lang"] == "en") {
                $abstract = $abs["value"];
                break;
            }
        }
    }

    if (isset($subjects)) {
        foreach ($subjects as $keyS => $valueS) {
            $subj = str_replace("http://dbpedia.org/", "", $valueS["value"]);
            insert($conceito["id"], "S", $subj);
        }
    }

    if (isset($types)) {
        foreach ($types as $keyS => $valueS) {
            $subj = str_replace("http://dbpedia.org/", "", $valueS["value"]);
            insert($conceito["id"], "T", $subj);
        }
    }

    if (isset($seeAlso)) {
        foreach ($seeAlso as $keyS => $valueS) {
            $subj = str_replace("http://dbpedia.org/", "", $valueS["value"]);
            insert($conceito["id"], "A", $subj);
        }
    }

    if (isset($hypernym)) {
        foreach ($hypernym as $keyS => $valueS) {
            $subj = str_replace("http://dbpedia.org/", "", $valueS["value"]);
            insert($conceito["id"], "H", $subj);
        }
    }

    $sql = "UPDATE `conceito` SET buscaCompleta = 1, abstract = '" . escape($abstract) . "' WHERE id = " . $conceito["id"];
    query($sql, false);

}

function insert($idConceito, $tipo, $valor) {
    try {
        $sql = "INSERT INTO `conceit_enriquecimento` (idConceito, tipo, valor) VALUES (" . $idConceito . ", '" . escape($tipo) . "', '" . escape($valor) . "');";
        query($sql, false);
    } catch (Exception $e) {}
}
?>