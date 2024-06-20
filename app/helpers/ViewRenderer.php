<?php
namespace helpers;

class ViewRenderer
{
    public function render($layout, $template, $parameters = [])
    {
        extract($parameters);
        ob_start();
        require_once "app/views/$template";
        $content = ob_get_clean();
        ob_start();
        require_once "app/views/$layout";
        return ob_end_flush();
    }

    public function render2($layout, $template, $parameters = [])
    {
        extract($parameters);
        ob_start();
        require_once "app/views/$template";
        $content = ob_get_clean();
        ob_start();
        require_once "app/views/$layout";
        return ob_end_flush();
    }
}


?>