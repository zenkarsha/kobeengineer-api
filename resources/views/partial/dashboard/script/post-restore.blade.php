<script>
  $('body').delegate('#button-deny', 'click', function() {
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/old/deny/' . $post->id) }}',
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        window.location.reload();
      }
    });
  });

  $('body').delegate('#button-skip', 'click', function() {
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/old/skip/' . $post->id) }}',
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        window.location.reload();
      }
    });
  });

  $('body').delegate('#button-allow', 'click', function() {
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/old/allow/' . $post->id) }}',
      data: {
        content: $('#content').val()
      },
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        window.location.reload();
      }
    });
  });
</script>
