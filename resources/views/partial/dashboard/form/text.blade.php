<div class="field">
  @if (isset($label))
    <label for="{{ $name }}">
      {{ $label }}{{ isset($required) && $required ? ' *' : '' }}
    </label>
  @endif
  <input
    type="text"
    id="{{ $name }}" name="{{ $name }}"
    value="{{ old($name, isset($default_value) ? $default_value : '') }}"
    {!! isset($placeholder) ? ' placeholder="'.$placeholder.'"' : ''!!}
    {{ isset($required) && $required ? ' required' : '' }}
  />
  {!! $errors->first($name, '<div class="ui pointing red basic label">:message</div>') !!}
</div>
