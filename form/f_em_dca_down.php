<?php
/**
 * User: JF
 * Date: 29-07-18
 * Time: 11:03
 */

if ($ID_city) {
    $form = new Form();
    $dca_txt .= $form->CreateForm('em/em_dca_down.php', 'POST', '')
        ->AddInput('lieu', '', 'hidden', $ID_city)
        ->AddInput('dcad', '', 'hidden', 1)
        ->EndForm('-', 'danger btn-sm');
}