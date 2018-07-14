<?php
if(!empty($_POST))
{
    $_SESSION['sauvegarde'] = $_POST;    
    $fichierActuel = $_SERVER['PHP_SELF'];
    if(!empty($_SERVER['QUERY_STRING']))
    {
        $fichierActuel .= '?' . $_SERVER['QUERY_STRING'];
    }    
    header('Location: ' . $fichierActuel);
    exit;
}

if(isset($_SESSION['sauvegarde']))
{
    $_POST = $_SESSION['sauvegarde'];   
    unset($_SESSION['sauvegarde']);
}
?>