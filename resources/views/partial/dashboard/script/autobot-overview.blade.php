<script>
  $('.button-edit').click(function() {
    var id = $(this).parents('tr').data('id');
    window.location.href = '{{ __('/autobot/edit/') }}' + id;
  });

  $('.button-delete').click(function() {
    var id = $(this).parents('tr').data('id');
    var parent = $(this).parents('tr');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/autobot/delete/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        parent.remove();
      }
    });
  });

  $('.boot-slider').click(function() {
    var id = $(this).parents('tr').data('id');
    var parent = $(this).parents('tr');
    var value = $(this).is(':checked') ? 1 : 0 ;
    $.post('{{ __('/autobot/boot/') }}' + id, {value: value});
  });

  $('.button-reboot').click(function() {
    var id = $(this).parents('tr').data('id');
    var parent = $(this).parents('tr');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/autobot/reboot/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        console.log(r);
        parent.find('.session').text(r.session);
      }
    });
  });
</script>
