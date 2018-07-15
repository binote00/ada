<?php
/**
 * User: JF
 * Date: 15-07-18
 * Time: 08:30
 */

trait Output
{
    /**
     * Texte en alerte
     *
     * @param string $alert
     * @param string $type
     */
    public static function ShowAlert($alert, $type = 'success')
    {
        $_SESSION['alert'] = '<div class="alert alert-' . $type . '">' . $alert . '</div>';
    }

    /**
     * Texte en alerte désactivable
     *
     * @param string $alert
     * @param string $type
     * @param bool $dismiss
     * @return string
     */
    public static function ShowAdvert($alert, $type = 'success', $dismiss = false)
    {
        if ($dismiss) {
            return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              ' . $alert . '
            </div>';
        } else {
            return '<div class="alert alert-' . $type . '">' . $alert . '</div>';
        }
    }
}