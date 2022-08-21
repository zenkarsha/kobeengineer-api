<div class="ui segment">
  <h3 class="ui header">
    {{ $title }}
  </h3>
  <div class="field">
    <div id="{{ $name }}-editor" class="code-editor"></div>
    <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="" />
  </div>
</div>
<script>
  $(function() {
    var {{ $name }} = ace.edit('{{ $name }}-editor');
    var default_value = '{!! $default_value or '' !!}';
    {{ $name }}.setTheme("ace/theme/terminal");
    {{ $name }}.getSession().setMode("ace/mode/html");
    {{ $name }}.getSession().setTabSize(2);
    {{ $name }}.getSession().setUseSoftTabs(true);
    {{ $name }}.getSession().setUseWrapMode(true);
    {{ $name }}.setValue(default_value);
    $('#{{ $name }}').val({{ $name }}.getValue());

    {{ $name }}.getSession().on('change', function(e) {
      var html = {{ $name }}.getValue();
      $('#{{ $name }}').val(html);
    });
  });
</script>
