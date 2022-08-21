<script src="//app.kxg.io/js/embed.js?{{ time() }}"></script>
<script>
  $('.ui.checkbox').checkbox();
  var updateEmbedForm = setInterval(function() {
    if ($('#kxgio-embed-2PtsxZcjX .kxgio-form-field').length && $('#kxgio-embed-2qzmW72SO .kxgio-form-field').length)
    {
      $('body').find('.kxgio-form-select').addClass('ui fluid dropdown');
      $('body').find('.kxgio-form-preview').addClass('field');
      $('body').find('.kxgio-form-preview img').addClass('ui centered fluid image');
      $('body').find('.kxgio-form-field').addClass('field');
      clearInterval(updateEmbedForm);
    }
  }, 100);

  $('body').delegate('#type', 'change', function() {
    var type = $(this).val()
    if (type == 'image') {
      $('#image-config-block').show();
      $('#content-block').hide();
      $('#code-block').hide();
    }
    else if (type == 'code') {
      $('#image-config-block').hide();
      $('#content-block').show();
      $('#code-block').show();
    }
    else {
      $('#image-config-block').hide();
      $('#content-block').show();
      $('#code-block').hide();
    }
  });

  $('body').delegate('#kxgio-app', 'change', function() {
    var app = $(this).val()
    $('.kxgio-embed-form').removeClass('on');
    $('#kxgio-embed-' + app).addClass('on');
  });

  $('.kxgio-embed-form').delegate('.kxgio-form-data:first', 'blur change keyup', function() {
    var value = $(this).val();
    $('#content').val(value);
  });

  $('body').delegate('#button-submit', 'click', function() {
    NProgress.start();
    if ($('#type').val() == 'image') {
      var app_key = $('#kxgio-app').val();
      var app = 'KxgioApp_' + app_key;
      $('#image_config').val(JSON.stringify(window[app].getFormData()));
      window[app].getImgurImage(function(action, data, response) {
        $('#image_url').val(response.data.link);
        NProgress.done();
        $('form').submit();
      });
    }
    else {
      NProgress.done();
      $('form').submit();
    }
  });
</script>
