<?php

function countFolderFiles($path)
{
    return count(glob(base_path($path . '*')));
}

function font($font)
{
    return base_path('resources/font/' . $font . '.ttf');
}

function resourceImage($path)
{
    return base_path('resources/images' . $path);
}

function getAppImagePath($app_key, $filename)
{
    return resourceImage('/app/'.$app_key.'/'.$filename);
}
