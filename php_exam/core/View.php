<?php
// core/View.php

class View
{
    public static function render(string $template, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        $baseUrl = BASE_URL;

        $viewFile = __DIR__ . '/../views/' . $template . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$template}");
        }

        include __DIR__ . '/../views/layout/header.php';
        include $viewFile;
        include __DIR__ . '/../views/layout/footer.php';
    }
}

