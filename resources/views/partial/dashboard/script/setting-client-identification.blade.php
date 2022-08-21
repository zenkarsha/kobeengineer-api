<script>
  $('.button-forbidden-slider').change(function() {
    var id = $(this).parents('tr').data('id');
    var value = $(this).is(':checked') ? 1 : 0 ;
    $.post('{{ __('/setting/client-identification/update-forbidden/') }}' + id, {
      value: value
    });
  });
</script>
