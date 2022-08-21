@if ($is_issue == false)
# #{{ $facebook_page_name }}{{ $true_id }}


@endif
@if (isset($reply_to))
RE: #{{ $facebook_page_name }}{{ $reply_to }}
@endif
@if ($is_issue == false)
![image](image{{ $ext }})
@else
![image]({{ $image }})
@endif
@if (isset($hashtag))


{{ $hashtag }}
@endif
