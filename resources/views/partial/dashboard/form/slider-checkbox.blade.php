{!! isset($use_segment) && $use_segment ? '<div class="ui segment">' : '' !!}
<div class="ui slider checkbox">
  <input type="checkbox" name="{{ $name }}" id="{{ $name }}" class="hidden"{{ isset($default_value) && ((gettype($default_value) == 'string' && ($default_value == 'on' || $default_value == 'true' || $default_value == '1')) || (gettype($default_value) == 'boolean' && $default_value == true) || (gettype($default_value) == 'integer' && $default_value == 1)) ? ' checked' : '' }}>
  <label>{{ $label }}</label>
</div>
{!! isset($use_segment) && $use_segment ? '</div>' : '' !!}
