$(document).ready(function($)
{
    //$('#liens_infos').html('?');
    $('#a_lieuo').change(function(e)
    {
        var selectvalue=$(this).val();
        $('#liens_infos').html('Loading...');
        console.log(selectvalue);
        if(selectvalue =="")
        {
            $('#liens_infos').html('Aucune info');
        }
        else
        {
            $.ajax({url: 'db_lien_infos.php?id='+selectvalue,
                success: function(output)
                {
                    console.log(output);
                    $('#liens_infos').html(output);
                },
                error: function (xhr,ajaxOptions,thrownError) {
                    alert(xhr.status + " "+ thrownError);
                }});
            $.ajax({url: 'admin_lieux_get.php?id='+selectvalue,
                contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                success: function(data)
                {
                    console.log(data);
                    $('#lieu_infos').html(data);
                },
                error: function (xhr,ajaxOptions,thrownError) {
                    alert(xhr.status + " "+ thrownError);
                }});
        }
    });
});