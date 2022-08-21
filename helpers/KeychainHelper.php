<?php

function createUniqueIdentifyKey()
{
    return md5(microtime().rand() . randString());
}

function createUniqueKey()
{
    return base62(strrev(time())) . randString(3);
}
