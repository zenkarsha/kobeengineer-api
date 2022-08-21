<div class="field">
  @if (isset($label))
    <label for="{{ $name }}">
      {{ $label }}{{ isset($required) && $required ? ' *' : '' }}
    </label>
  @endif
  @include('partial.dashboard.form.select-dropdown', [
    'name' => $name,
    'placeholder' => isset($placeholder) ? $placeholder : false,
    'options' => $options,
    'default_value' => isset($default_value) ? $default_value : false,
  ])
</div>
