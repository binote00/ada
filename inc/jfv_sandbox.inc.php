<?php

function GetRank($Rating, $Type = 1)
{
    if ($Type == 2) {
        $Rank = floor(($Rating - 10000) / 1000);
        if ($Rank < 0)
            $Rank = 1;
    } elseif ($Type == 4) {
        if ($Rating < 15650)
            $Rank = 1;
        elseif ($Rating < 16500)
            $Rank = 2;
        elseif ($Rating < 17250)
            $Rank = 3;
        elseif ($Rating < 17500)
            $Rank = 4;
        elseif ($Rating < 18000)
            $Rank = 5;
        elseif ($Rating < 18500)
            $Rank = 6;
        elseif ($Rating < 25000)
            $Rank = 7;
    } elseif ($Type == 1 or $Type == 12) {
        if ($Rating < 16390)
            $Rank = 1;
        elseif ($Rating < 17000)
            $Rank = 2;
        elseif ($Rating < 17399)
            $Rank = 3;
        elseif ($Rating < 18100)
            $Rank = 4;
        elseif ($Rating < 18500)
            $Rank = 5;
        elseif ($Rating < 18900)
            $Rank = 6;
        elseif ($Rating < 19700)
            $Rank = 7;
        elseif ($Rating < 20000)
            $Rank = 8;
        elseif ($Rating < 22000)
            $Rank = 9;
        else
            $Rank = 10;
    }
    return $Rank;
}

function GetRankLevel($Level, $Type = 1)
{
    if ($Type == 4) {
        if ($Level == 1) {
            $Rank1 = 0;
            $Rank2 = 15650;
        } elseif ($Level == 2) {
            $Rank1 = 15650;
            $Rank2 = 16500;
        } elseif ($Level == 3) {
            $Rank1 = 16500;
            $Rank2 = 17250;
        } elseif ($Level == 4) {
            $Rank1 = 17250;
            $Rank2 = 17500;
        } elseif ($Level == 5) {
            $Rank1 = 17500;
            $Rank2 = 18000;
        } elseif ($Level == 6) {
            $Rank1 = 18000;
            $Rank2 = 18500;
        } elseif ($Level == 7) {
            $Rank1 = 18500;
            $Rank2 = 25000;
        }
    } elseif ($Type == 1 or $Type == 12) {
        if ($Level == 1) {
            $Rank1 = 0;
            $Rank2 = 16390;
        } elseif ($Level == 2) {
            $Rank1 = 16390;
            $Rank2 = 17000;
        } elseif ($Level == 3) {
            $Rank1 = 17000;
            $Rank2 = 17399;
        } elseif ($Level == 4) {
            $Rank1 = 17399;
            $Rank2 = 18100;
        } elseif ($Level == 5) {
            $Rank1 = 18100;
            $Rank2 = 18500;
        } elseif ($Level == 6) {
            $Rank1 = 18500;
            $Rank2 = 18900;
        } elseif ($Level == 7) {
            $Rank1 = 18900;
            $Rank2 = 19700;
        } elseif ($Level == 8) {
            $Rank1 = 19700;
            $Rank2 = 20000;
        } elseif ($Level == 9) {
            $Rank1 = 20000;
            $Rank2 = 22000;
        } else {
            $Rank1 = 22000;
            $Rank2 = 25000;
        }
    }
    return array($Rank1, $Rank2);
}