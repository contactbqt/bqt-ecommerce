<?php

namespace App\Livewire\Admin\Setting;

use App\Models\SettingGroup;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin-app')]
class SettingManage extends Component
{
    use WithFileUploads;

    public $slug;
    public $group;
    public $settings_data = [];
    
    // File properties
    public $site_logo;
    public $favicon;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->group = SettingGroup::where('slug_name', $slug)->firstOrFail();
        
        $settings = Setting::where('setting_group_id', $this->group->id)->get();
        foreach ($settings as $setting) {
            $key = trim($setting->key);
            $val = $setting->value;
            if ($key === 'MAINTENANCE_MODE' || $key === 'SOCIAL_ENABLE' || $key === 'ENABLE_REVIEWS' || $key === 'VERIFIED_PURCHASE_ONLY') {
                $val = (bool)$val;
            }
            $this->settings_data[$key] = $val;
        }
    }

    public function save()
    {
        $settings = Setting::where('setting_group_id', $this->group->id)->get();

        foreach ($settings as $setting) {
            $key = trim($setting->key);
            $value = $this->settings_data[$key] ?? $setting->value;

            // Handle Site Logo
            if ($key === 'SITE_LOGO' && $this->site_logo) {
                $this->validate(['site_logo' => 'image|max:2048']);
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
                $value = $this->site_logo->store('settings', 'public');
                $this->site_logo = null;
            }

            // Handle Favicon
            if ($key === 'FAVICON' && $this->favicon) {
                $this->validate(['favicon' => 'image|mimes:ico,png,jpg|dimensions:width=32,height=32|max:512']);
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
                $value = $this->favicon->store('settings', 'public');
                $this->favicon = null;
            }

            // Handle Toggles (Livewire toggle sends true/false)
            if (in_array($key, ['MAINTENANCE_MODE', 'SOCIAL_ENABLE', 'ENABLE_REVIEWS', 'VERIFIED_PURCHASE_ONLY'])) {
                $value = $value ? '1' : '0';
            }

            $setting->update(['value' => $value]);

            // Cast back to boolean for checkboxes to prevent UI glitches
            if (in_array($key, ['MAINTENANCE_MODE', 'SOCIAL_ENABLE', 'ENABLE_REVIEWS', 'VERIFIED_PURCHASE_ONLY'])) {
                $this->settings_data[$key] = (bool)$value;
            } else {
                $this->settings_data[$key] = $value;
            }
        }

        session()->flash('message', $this->group->group_name . ' updated successfully!');
    }

    public function getTimezones()
    {
        return \DateTimeZone::listIdentifiers();
    }

    public function getLanguages()
    {
        return [
            'en' => 'English',
        ];
    }

    public function getProductTypes()
    {
        return [
            'simple' => 'Simple Product',
            'variant' => 'Variant Product',
            'both' => 'Both Product and Variant',
        ];
    }

    public function getCurrencies()
    {
        return [
            'INR' => 'Indian Rupee (₹)',
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
        ];
    }

    public function getEcommerceOptions()
    {
        return [
            'CURRENCY_POSITION' => [
                'left' => 'Left',
                'right' => 'Right',
            ],
            'TAX_TYPE' => [
                'inclusive' => 'Inclusive',
                'exclusive' => 'Exclusive',
            ],
            'COD_ENABLE' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'PRODUCT_WISHLIST' => [
                '1' => 'Show',
                '0' => 'Hide',
            ],
            'SHIPPING_METHOD' => [
                'flat_rate' => 'Flat Rate',
                'free_shipping' => 'Free Shipping',
                'local_pickup' => 'Local Pickup',
            ],
            'MAIL_MAILER' => [
                'smtp' => 'SMTP',
                'sendmail' => 'Sendmail',
                'mailgun' => 'Mailgun',
                'ses' => 'Amazon SES',
                'postmark' => 'Postmark',
            ],
            'SMTP_ENCRYPTION' => [
                'tls' => 'TLS',
                'ssl' => 'SSL',
                'none' => 'None',
            ],
        ];
    }

    public function render()
    {
        $settings = Setting::where('setting_group_id', $this->group->id)->get();
        return view('livewire.admin.setting.setting-manage', [
            'settings' => $settings,
            'timezones' => $this->getTimezones(),
            'languages' => $this->getLanguages(),
            'currencies' => $this->getCurrencies(),
            'ecommerceOptions' => $this->getEcommerceOptions(),
            'productTypes' => $this->getProductTypes(),
        ]);
    }
}
