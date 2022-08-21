<div class="field">
  @include('partial.dashboard.form.slider-checkbox', [
    'name' => $name,
    'label' => $label,
    'default_value' => isset($default_value) ? $default_value : false,
  ])
</div>
