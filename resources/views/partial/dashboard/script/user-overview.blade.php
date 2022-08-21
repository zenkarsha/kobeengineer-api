<script>
  $('.flag-slider').click(function() {
    var id = $(this).parents('tr').data('id');
    var parent = $(this).parents('tr');
    var value = $(this).is(':checked') ? 1 : 0 ;
    $.post('{{ __('/user/flag/') }}' + id, {value: value});
  });

  $('.ban-slider').click(function() {
    var id = $(this).parents('tr').data('id');
    var parent = $(this).parents('tr');
    var value = $(this).is(':checked') ? 1 : 0 ;
    $.post('{{ __('/user/ban/') }}' + id, {value: value});
  });
</script>
