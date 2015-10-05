<?php

namespace spartaksun\addresses\components;


use spartaksun\addresses\form\Form;

/**
 * Html helper
 * @package spartaksun\addresses\components
 */
class Html
{
    /**
     * Create URL from route and params
     * @param string $route
     * @param array $params
     * @return string
     */
    public static function createUrl($route, $params = array())
    {
        return "/" . trim($route, "/") . "?" . http_build_query($params);
    }

    /**
     * Safely encode string
     * @param $text
     * @return string
     */
    public static function encode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }

    /**
     * Html block with form errors
     * @param Form $form
     * @return string
     */
    public static function errorSummary(Form $form)
    {
        $errors = $form->getErrors();
        if(empty($errors)) {
            return '';
        }

        $html = '<div class="errors"><h3>Please fix the following input errors:</h3>';
        foreach ($form->getErrors() as $attribute => $error) {
            $attr = ucfirst($attribute);
            $html .= "<b>{$attr}</b><ul>";
            foreach ($error as $row) {
                $html .= "<li>{$row}</l>";
            }
            $html .= '</ul>';
        }
        $html .= '</div>';

        return $html;
    }
}
