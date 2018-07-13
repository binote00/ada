$(document).ready(function($)
{
    //$('#liens_infos').html('?');
    $('#a_pays').change(function(e){
        var pays=$(this).val();
        if(pays != ''){
            $.ajax({url: 'admin_cibles_get.php?pays='+pays,
                contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                success: function(data)
                {
                    console.log(data);
                    $('#a_lieuo').html(data);
                },
                error: function (xhr,ajaxOptions,thrownError) {
                    alert(xhr.status + " "+ thrownError);
                }});
        }
    });
    $('#a_lieuo').change(function(e)
    {
        var selectvalue=$(this).val();
        $('#cible_infos').html('Loading...');
        console.log(selectvalue);
        if(selectvalue ==''){
            $('#cible_infos').html('Aucune info');
        }
        else
        {
            $.ajax({url: 'admin_cible_get.php?id='+selectvalue,
                contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                success: function(data)
                {
                    console.log(data);
                    $('#cible_infos').html(data);
                },
                error: function (xhr,ajaxOptions,thrownError) {
                    alert(xhr.status + " "+ thrownError);
                }});
        }
    });
});