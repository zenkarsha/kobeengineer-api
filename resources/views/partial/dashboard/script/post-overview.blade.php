<script>
  function postCardInitial() {
    $('.post-content').linkify();
    $('.ui.dropdown').dropdown();
    $('.post-content').readmore({
      moreLink: '<a class="fluid ui mini bottom attached button see-more-button" href="#">See more</a>',
      lessLink: '',
      collapsedHeight: 300
    });
  }

  postCardInitial();

  $('body').delegate('.button-show-post-detail', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    var data = parent.data('datas');
    var html = `
      <table class="ui unstackable table">
        <thead>
          <tr>
            <th>#${ data.id }</th>
            <th class="right aligned"><small>Created at: ${ data.created_at }</small></th>
          </tr>
        </thead>
        <tbody>
          <tr><td>key</td><td>${ data.key }</td></tr>
          <tr><td>true_id</td><td>${ data.true_id }</td></tr>
          <tr><td>pending_votes</td><td>${ data.pending_votes }</td></tr>
          <tr><td>pending_votes_goal</td><td>${ data.pending_votes_goal }</td></tr>
          <tr><td>priority</td><td>${ data.priority }</td></tr>
          <tr><td>published_at</td><td>${ data.published_at }</td></tr>
          <tr><td>report</td><td>${ data.report }</td></tr>
        </tbody>
      </table>
    `;
    $('#modal-post-detail').html(html);
    parent.find('.post-labels').clone().appendTo('#modal-post-detail');
    $('#post-detail-modal').modal({
      closable  : false,
      onDeny    : function(){
        $('#post-detail-modal').modal('hide');
      }
    }).modal('show');
  });

  $('body').delegate('.action-search-user', 'click', function() {
    var datas = $(this).parents('.post-item').data('datas');
    var user_id = datas.user_id;
    $('#keyword').val('user:' + user_id);
    $('#post-search-form').submit();
  });

  $('body').delegate('.action-search-ip', 'click', function() {
    var datas = $(this).parents('.post-item').data('datas');
    var client_ip = datas.client_ip;
    $('#keyword').val('ip:' + client_ip);
    $('#post-search-form').submit();
  });

  $('body').delegate('.action-search-client-identification', 'click', function() {
    var datas = $(this).parents('.post-item').data('datas');
    var client_identification = datas.client_identification;
    $('#keyword').val('ci:' + client_identification);
    $('#post-search-form').submit();
  });

  $('body').delegate('.action-allow', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    var _this = $(this);
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/allow/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          if (_this.hasClass('action-remove-dom') == false) {
            parent.replaceWith(r.data.dom);
            postCardInitial();
          }
          else {
            parent.remove();
          }
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-deny', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    var _this = $(this);
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/deny/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          if (_this.hasClass('action-remove-dom') == false) {
            parent.replaceWith(r.data.dom);
            postCardInitial();
          }
          else {
            parent.remove();
          }
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-delete', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/delete/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.remove();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-cancel_queuing', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/cancel_queuing/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-set_priority', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/set_priority/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unpublish', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unpublish/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-republish', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/republish/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-ban_all', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/ban/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-flag_user', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/flag_user/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-ban_user', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/ban_user/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-ban_ip', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/ban_ip/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-ban_ip_forbidden', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/ban_ip_forbidden/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-ban_client_identification', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/ban_client_identification/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unban_all', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unban/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unflag_user', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unflag_user/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unban_user', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unban_user/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unban_ip', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unban_ip/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unban_ip_forbidden', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unban_ip_forbidden/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });

  $('body').delegate('.action-unban_client_identification', 'click', function() {
    var id = $(this).parents('.post-item').data('id');
    var parent = $(this).parents('.post-item');
    $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      url: '{{ __('/post/action/unban_client_identification/') }}' + id,
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.success) {
          parent.replaceWith(r.data.dom);
          postCardInitial();
          notification(r.message);
        }
        else {
          notification(r.message, 'error');
        }
      }
    });
  });
</script>
