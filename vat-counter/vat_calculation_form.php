<?php
/*
* file for calculation
* @package vat-counter
*/

function vat_counter_button_handler()
{
    $content = '';
    $content .= ' <div id="calc" class="calc">';
    $content .= '<form action="" method="POST" id="form">';
    $content .= '<div><label for="prod_title">Nazwa produktu</label><input type="text" id="prod_title" name="prod_title" required></div>';
    $content .= '<div><label for="net_price">Kwota netto</label><div class="input-number-container"><span class="input-number-decrement">–</span><input type="text" id="net_price"  class="input-number"  min="0.01" step="0.01" max="" name="net_price" required><span class="input-number-increment">+</span><input type="text" id="currency" value="PLN" name="currency"  disabled></div></div>';
    $content .= '<div class="select-vat-container"><label for="vat_rate">VAT</label><select name="vat_rate" id="vat_rate">
        <option value="23">23%</option>
        <option value="22">22%</option>
        <option value="8">8%</option>
        <option value="7">7%</option>
        <option value="5">5%</option>
        <option value="3">3%</option>
        <option value="0">0%</option>
        <option value="0">zw.</option>
        <option value="0">np.</option>
        <option value="0">o.o.</option>
    </select></div>';
    $content .= '<div><input type="submit" id="submit_btn" name="submit" value="oblicz"/></div>';
    $content .= '<input type="hidden" name="post_type" id="post_type" value="my_custom_post_type"/>';
    // secure that action is from current side
    wp_nonce_field('vat_calculations_nonce_action', 'vat_calculations_nonce_field');
    $content .= '</form>';
    $content .= '<p id="output"></p>';
    $content .= '</div>';
    return $content;
}
