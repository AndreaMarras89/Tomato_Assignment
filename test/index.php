<?php
require $_SERVER["DOCUMENT_ROOT"] . "/test/bootstrap.php";
$uri = parse_url($_SERVER['REQUEST_URI']);
parse_str($uri["query"], $parameters);
$formato = null; 
$unita_di_misura_formato = null; 
$prezzo_unitario = null; 
$fornitore = null;
if(array_key_exists("formato", $parameters))
{
    $formato = $parameters["formato"];
}
if(array_key_exists("unita_di_misura_formato", $parameters))
{
    $unita_di_misura_formato = $parameters["unita_di_misura_formato"];
}
if(array_key_exists("prezzo_unitario", $parameters))
{
    $prezzo_unitario = $parameters["prezzo_unitario"];
}
if(array_key_exists("fornitore", $parameters))
{
    $fornitore = $parameters["fornitore"];
}
if($formato == null)
{
    echo "formato nullo";
}else
{
    $lista = trovaProdotti($formato, $unita_di_misura_formato, $prezzo_unitario, $fornitore);
    $json = json_encode($lista, JSON_PRETTY_PRINT); 
    echo $json;
}
?>