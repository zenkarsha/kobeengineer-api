<script>
  $('.button-edit').click(function() {
    var id = $(this).parents('tr').data('id');
    window.location.href = '{{ __('/setting/edit/') }}' + id;
  });

  $('body').delegate('.button-delete', 'click', function() {
    var _parent = $(this).parents('tr');
    var id = _parent.data('id');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/setting/delete/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        _parent.remove();
      }
    });
  });
</script>
