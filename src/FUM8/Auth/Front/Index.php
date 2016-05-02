<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace FUM8\Auth\Front;

class Index
{
    public static function indexAction()
    {
        $content = 'LEL';
        \Flight::render('front/index.html', ['body_content' => $content]);
    }
}