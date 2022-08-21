@if ($is_issue == false)
# #{{ $facebook_page_name }}{{ $true_id }}


@endif
@if (isset($reply_to))
RE: #{{ $facebook_page_name }}{{ $reply_to }}
@endif
{{ $message }}
@if (isset($hashtag))


{{ $hashtag }}
@endif
