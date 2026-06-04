<div class="flex items-center gap-2">
    @php
        $logo = get_setting('SITE_LOGO');
        $logoPath = $logo ? asset('storage/' . $logo) : asset('assets/images/logo.png');
        $siteName = get_setting('SITE_NAME', config('app.name', 'DigiCart'));
    @endphp
    <img src="{{ $logoPath }}" alt="{{ $siteName }}" class="w-50">
</div>