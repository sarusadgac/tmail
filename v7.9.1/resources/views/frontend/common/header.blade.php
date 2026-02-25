<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', config('app.settings.font_family.head', 'Poppins')) }}:wght@400;600;700&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', config('app.settings.font_family.body', 'Poppins')) }}:wght@400;600&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
<style>
:root {
  --head-font: "{{ config('app.settings.font_family.head', 'Poppins') }}";
  --body-font: "{{ config('app.settings.font_family.body', 'Poppins') }}";
  --primary: {{ config('app.settings.colors.primary', '#0155b5') }};
  --secondary: {{ config('app.settings.colors.secondary', '#2fc10a') }};
  --tertiary: {{ config('app.settings.colors.tertiary', '#d2ab3e') }};
}
</style>
<script>
  let captcha_name = "{{ config('app.settings.captcha', 'off') }}";
  let site_key = "";
  if(captcha_name && captcha_name !== "off") {
    site_key = "{{ config('app.settings.' . config('app.settings.captcha') . '.site_key', '') }}";
  }
  let strings = {!! json_encode(\Lang::get('*')); !!}
  const __ = (string) => {
    if(strings[string] !== undefined) {
      return strings[string];
    } else {
      return string;
    }
  }
</script>
@foreach(['success', 'error'] as $type)
@if(Session::has($type))
<script defer>
  document.addEventListener("DOMContentLoaded", () => {
    document.dispatchEvent(new CustomEvent("showAlert", {
      bubbles: true,
      detail: {
        type: "{{ $type }}",
        message: "{{ Session::get($type) }}",
      },
    }));
  });
</script>
@endif
@endforeach
@if(config('app.settings.enable_ad_block_detector'))
<script>
fetch("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js").catch((error) => {
document.querySelector('[class*="-theme"]').remove();
  document.querySelector('body > div').insertAdjacentHTML('beforebegin', `
    <div class="fixed w-screen h-screen bg-red-800 flex flex-col justify-center items-center gap-5 z-50 text-white">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
      </svg>
      <h1 class="text-4xl font-bold">{{ __('Ad Blocker Detected') }}</h1>
      <h2>{{ __('Disable the Ad Blocker to use ') . config('app.settings.name') }}</h2>
    </div>
  `)
});
</script>
@endif