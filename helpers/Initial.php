<?php

foreach (glob(__dir__ . "/*.php") as $file)
{
    if ($file != __FILE__) {
        $explode = explode('/', $file);
        $filename = end($explode);
        include_once $filename;
    }
}
