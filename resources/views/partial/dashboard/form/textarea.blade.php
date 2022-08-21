@if (isset($label))
  <label for="{{ $name }}">
    {{ $label }}{{ isset($required) && $required ? ' *' : '' }}
  </label>
@endif
<textarea
  id="{{ $name }}" name="{{ $name }}"
  {{ isset($short) && $short ? ' rows="2"' : ''}}
  {!! isset($placeholder) ? ' placeholder="'.$placeholder.'"' : ''!!}
  {{ isset($required) && $required ? ' required' : '' }}
>{{ old($name, isset($default_value) ? $default_value : '') }}</textarea>
{!! $errors->first($name, '<div class="ui pointing red basic label">:message</div>') !!}
