$(document).ready(function($) 
{
	$('#liens_infos').html('Veuillez sélectionner un lieu...');
	$('#a_lieuo').change(function(e) 
	{
		var selectvalue=$(this).val();	 
		$('#liens_infos').html('Loading...');	 
		$('#a_lieud').html('<option value="">Loading...</option>');
		if(selectvalue =="") 
		{
			$('#liens_infos').html('Aucune info');
			$('#a_lieud').html('<option value="0">Aucun</option>');
		}
		else 
		{
			$.ajax({url: 'db_lien_infos.php?id='+selectvalue,
			 success: function(output) 
			{
				$('#liens_infos').html(output);
			},
			error: function (xhr,ajaxOptions,thrownError) {
			alert(xhr.status + " "+ thrownError);
			}});
			$.ajax({url: 'db_lien_lieu2.php?id='+selectvalue,
			 contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
			 success: function(output) 
			{
				$('#a_lieud').html(output);
			},
			error: function (xhr,ajaxOptions,thrownError) {
			alert(xhr.status + " "+ thrownError);
			}});
		}
	});
});