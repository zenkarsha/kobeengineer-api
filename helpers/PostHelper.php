<?php

function convertPostTypeToText($type)
{
    $type = (int) $type;
    switch($type)
    {
        case 1:
            return \Lang::get('post.label-type-text');
        case 2:
            return \Lang::get('post.label-type-link');
        case 3:
            return \Lang::get('post.label-type-image');
        case 4:
            return \Lang::get('post.label-type-code');
        default:
            return \Lang::get('post.label-type-text');
    }
}

function convertPostTypeToName($type)
{
    $type = (int) $type;
    switch($type)
    {
        case 1:
            return 'text';
        case 2:
            return 'link';
        case 3:
            return 'image';
        case 4:
            return 'code';
        default:
            return 'text';
    }
}

function getPostState($post)
{
    $post = (object) $post;
    if ((int) $post->denied == 1)
        return 'denied';
    elseif ((int) $post->pending == 1)
        return 'pending';
    elseif ((int) $post->queuing == 1)
        return 'queuing';
    elseif ((int) $post->published > 0)
        return 'published';
    elseif ((int) $post->unpublished == 1)
        return 'unpublished';
    elseif ((int) $post->analysed == 0)
        return 'analysing';
    else
        return 'failed';
}
