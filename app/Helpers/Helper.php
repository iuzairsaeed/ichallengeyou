<?php

function get_setting_option($field)
{
    return optional(DB::table('settings')->where('field', $field)->first())->value;
}
