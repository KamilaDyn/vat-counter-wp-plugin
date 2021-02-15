<?php
/*
* file to unistall plugin
* @packgage vat-counter
*/


class VatPluginActivate
{
    public static function activate()
    {

        flush_rewrite_rules();
    }
}
