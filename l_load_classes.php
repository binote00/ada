<?
function LoadClass($classe)
{
  require_once $classe.'_class.php';
}
spl_autoload_register('LoadClass');
if(is_array($classes))
{
	foreach($classes as $param => $classe) 
	{
		LoadClass($classe);
	}
}
?>