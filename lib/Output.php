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

    /**
     * @param string $Path
     * @param string $Replace
     * @param string $Title
     * @param int $Taille
     * @return string
     */
    public static function ShowImage($Path, $Replace = '', $Title = '', $Taille = 100)
    {
        if ($Taille)
            $style = " style='width:" . $Taille . "%;'";
        if (is_file($Path))
            $Image = "<img src='" . $Path . "' title='" . $Title . "'" . $style .">";
        elseif ($Replace && is_file($Replace))
            $Image = "<img src='" . $Replace . "' title='" . $Title . "'" . $style .">";
        else
            $Image = '';
        return $Image;
    }

    /**
     * @param string $link
     * @param string $caption
     * @param string $class
     * @return string
     */
    public static function linkBtn($link, $caption, $class = 'default')
    {
        return '<a href="' . $link . '" class="btn btn-' . $class . '">' . $caption . '</a>';
    }

    /**
     * Bouton Modal bootstrap
     *
     * @param string $target_id
     * @param string $btn_text
     * @param string $btn_class
     * @return string
     */
    public static function btnModal($target_id, $btn_text, $btn_class = 'primary')
    {
        return '<button type="button" class="btn btn-' . $btn_class . '" data-toggle="modal" data-target="#' . $target_id . '">' . $btn_text . '</button>';
    }

    /**
     * Link Modal bootstrap
     *
     * @param string $target_id
     * @param string $caption
     * @param string $class
     * @return string
     */
    public static function linkModal($target_id, $caption, $class = '')
    {
        return '<a href="#" class="link ' . $class . '" data-toggle="modal" data-target="#' . $target_id . '">' . $caption . '</a>';
    }


    /**
     * Générateur de Modal bootstrap
     *
     * @param string $modal_id
     * @param string $modal_title
     * @param string $modal_body
     * @param string $modal_footer
     * @param string $modal_size
     * @return string
     */
    public static function viewModal($modal_id, $modal_title, $modal_body, $modal_footer = '', $modal_size = '')
    {
        if ($modal_size == 'lg') {
            $modal_size = ' modal-lg';
        } elseif ($modal_size == 'hg') {
            $modal_size = ' modal-hg';
        }
        return '<div class="modal fade" tabindex="-1" id="' . $modal_id . '">
          <div class="modal-dialog' . $modal_size . '">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">' . $modal_title . '</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                ' . $modal_body . '
              </div>
              <div class="modal-footer">
                ' . $modal_footer . '
              </div>
            </div>
          </div>
        </div>';
    }

    /**
     * @param string $caption
     * @param string $popuptext
     * @param string $captioncolor [default, primary, info, warning, danger]
     * @return string
     */
    public static function popup($caption, $popuptext, $captioncolor = '')
    {
        if ($captioncolor) {
            $caption = '<i class="text-' . $captioncolor . '">' . $caption . '</i>';
        }
        return '<a href="#" class="popup">' . $caption . '<span>' . $popuptext . '</span></a>';
    }
}