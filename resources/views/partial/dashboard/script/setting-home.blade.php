<script>
  $('select.dropdown').dropdown();
  $('.ui.checkbox').checkbox();

  $(function() {
    $('.accordion').accordion({
      selector: {
        trigger: '.title'
      }
    });
  });

  $('body').delegate('#button-save-setting', 'click', function() {
    $('form')[0].submit();
  });

  function loginToFacebook()
  {
    FB.login((function(response) {
      if (response.status === 'connected') {
        return getPageToken(response.authResponse.accessToken);
      } else {
        alert('Failed!');
      }
    }), {
      scope: 'pages_show_list,pages_read_engagement,pages_manage_posts',
      return_scopes: true
    });
  }

  function getPageToken(access_token)
  {
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      data: {
        access_token: access_token
      },
      url: '{{ __('/setting/get-page-token') }}',
      error: function(r) {
        console.log(r);
      },
      success: function(r) {
        $('#facebook_page_token').val(r.data.access_token);
      }
    });
  }

  $('body').delegate('#button-get-page-token', 'click', function() {
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        getPageToken(response.authResponse.accessToken);
      }
      else {
        $('#login-facebook-modal').modal({
          closable  : false,
          onDeny    : function(){
            $('#login-facebook-modal').modal('hide');
          },
          onApprove : function() {
            loginToFacebook();
          }
        }).modal('show');
      }
    });
  });
</script>
