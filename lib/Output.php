<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 31-10-17
 * Time: 10:41
 */

trait Output
{
    public function Render($text)
    {
        return '    
        <div id="def-header"><?=$def_header?></div>
        <div id="def-body">
            <div id="def-nav"><?=$def_nav?></div>
            <h1><?=$def_title?></h1>
            <div class="row">
                <div class="col-12 col-lg-6" id="def-left"><?=$def_left?></div>
                <div class="col-12 col-lg-6" id="def-right"><?=$def_right?></div>
            </div>
            <div id="def-content"><?=$def_content?></div>
            <div id="def-footer"><?=$def_footer?></div>
        </div>';
    }

    /**
     * @param string $text
     * @param int $level [0=warning, 1=danger, 2=success]
     * @return string
     */
    public static function Alert($text, $level=0)
    {
        if($level == 1){
            $class = 'danger';
        }elseif($level == 2){
            $class = 'success';
        }else{
            $class = 'warning';
        }
        return '<div class="alert alert-'.$class.'">'.$text.'</div>';
    }
}