<script>
  $('body').delegate('.button-delete', 'click', function() {
    var _parent = $(this).parents('tr');
    var id = _parent.data('id');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/setting/domain-whitelist/delete/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        _parent.remove();
      }
    });
  });
</script>
