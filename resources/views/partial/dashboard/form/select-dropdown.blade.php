<select class="ui fluid search dropdown" name="{{ $name }}" id="{{ $name }}">
  @if (isset($placeholder) && $placeholder)
    <option value="">{{ $placeholder }}</option>
  @endif
  @foreach ($options as $key => $value)
    <option value="{{ $key }}"{{ isset($default_value) && ($default_value == $key) ? ' selected' : '' }}>{{ $value }}</option>
  @endforeach
</select>
