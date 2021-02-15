<?php
/*
* file to unistall plugin
* @packgage vat-counter
*/


class VatPluginDeactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
