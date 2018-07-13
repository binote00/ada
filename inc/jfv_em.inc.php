<?php
function Get_CT_Discount($Avancement)
{
    if ($Avancement > 9999999)
        $Discount = 8;
    elseif ($Avancement > 4999999)
        $Discount = 7;
    elseif ($Avancement > 999999)
        $Discount = 6;
    elseif ($Avancement > 499999)
        $Discount = 5;
    elseif ($Avancement > 199999)
        $Discount = 4;
    elseif ($Avancement > 99999)
        $Discount = 3;
    elseif ($Avancement > 49999)
        $Discount = 2;
    elseif ($Avancement > 25000)
        $Discount = 1;
    else
        $Discount = 0;
    return $Discount;
}