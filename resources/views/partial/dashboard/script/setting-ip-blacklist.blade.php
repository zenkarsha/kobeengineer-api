<script>
  $('body').delegate('.button-delete', 'click', function() {
    var _parent = $(this).parents('tr');
    var id = _parent.data('id');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/setting/ip-blacklist/delete/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        _parent.remove();
      }
    });
  });

  $('.button-forbidden-slider').change(function() {
    var id = $(this).parents('tr').data('id');
    var value = $(this).is(':checked') ? 1 : 0 ;
    $.post('{{ __('/setting/ip-blacklist/update-forbidden/') }}' + id, {
      value: value
    });
  });
</script>
