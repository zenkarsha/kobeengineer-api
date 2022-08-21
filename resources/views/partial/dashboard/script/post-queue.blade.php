<script>
  $('.button-delete').click(function() {
    var id = $(this).parents('tr').data('id');
    window.location.href = '{{ __('/post/queue/delete/') }}' + id;
  });
</script>
