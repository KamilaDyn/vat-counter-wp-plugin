<?php
/*
* file to uninstall plugin
* @package vat-counter
*/

class VatPluginActivate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
