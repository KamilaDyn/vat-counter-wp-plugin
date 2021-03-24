<?php
/*
* file to uninstall plugin
* @package vat-counter
*/

class VatPluginDeactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
