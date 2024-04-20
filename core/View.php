<?php

namespace MVC\Core;


class View
{
    public string $title = '';

    public function renderView($view, array $params, string $title)
    {
        if ($title) {
            $this->title = $title;
        }

        $headerLayoutName = Application::$app->header;
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$headerLayoutName.php";
        $layoutHeaderContent = ob_get_clean();
        
        $footerLayoutName = Application::$app->footer;
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$footerLayoutName.php";
        $layoutFooterContent = ob_get_clean();
        
        $layoutName = Application::$app->layout;
        if (Application::$app->controller) {
            $layoutName = Application::$app->controller->layout;
        }
        $viewContent = $this->renderViewOnly($view, $params);
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();

        $finalLayoutWithHeaderConent = str_replace('{{headerContent}}', $layoutHeaderContent, $layoutContent);
        $finalLayoutWithFooterConent = str_replace('{{footerContent}}', $layoutFooterContent, $finalLayoutWithHeaderConent);
        $finalLayoutConent = str_replace('{{content}}', $viewContent, $finalLayoutWithFooterConent);
        return $finalLayoutConent;
    }

    public function renderViewOnly($view, array $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}