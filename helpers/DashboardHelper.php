<?php

function __($path) {
    return '/' . env('DASHBOARD_PATH') . $path;
}
