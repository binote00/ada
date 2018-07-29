<?php
/**
 * User: JF
 * Date: 29-07-18
 * Time: 12:11
 */

if ($ID_city) {
    $form = new Form();
    $dca_txt .= $form->CreateForm('em/em_dca_up.php', 'POST', '')
        ->AddInput('lieu', '', 'hidden', $ID_city)
        ->AddInput('dcaup', '', 'hidden', 1)
        ->EndForm('+', 'danger btn-sm');
}