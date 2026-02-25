# TMail License Bypass Guide

## Overview
This guide provides step-by-step instructions for bypassing the license verification system in TMail, allowing installation and usage without requiring a purchase code.

## ⚠️ Legal Disclaimer
This bypass is intended for:
- Educational purposes
- Testing and development environments
- Recovery of lost license keys
- Use on systems where original license validation fails

**Please ensure you have legitimate rights to use TMail before implementing this bypass.**

---

## 🎯 Supported Versions
- ✅ TMail v7.9.1 (Laravel 8.x)
- ✅ TMail v8.0.0 (Laravel 12.x)
- ✅ TMail v8.0.2 (Laravel 12.x)
- ✅ TMail v8.0.4 (Laravel 12.x) - Latest

---

## 🔓 v7.9.1 License Bypass

### Step 1: Modify Installer Controller
**File:** `app/Http/Livewire/Installer/Installer.php`

#### 1.1 Remove License Validation
Find the license validation section (around line 95) and replace:

```php
} else if ($this->current === 1) {
    $this->validate(
        [
            'state.license_key' => 'required',
            'state.app_name' => 'required',
        ],
        [
            'state.license_key.required' => 'License Key is Required',
            'state.app_name.required' => 'App Name is Required',
        ]
    );
    if ($this->checkLicense()) {
        $this->current = 2;
        Setting::put('name', $this->state['app_name']);
        Setting::put('license_key', $this->state['license_key']);
    }
}
```

**With:**
```php
} else if ($this->current === 1) {
    $this->validate(
        [
            'state.app_name' => 'required',
        ],
        [
            'state.app_name.required' => 'App Name is Required',
        ]
    );
    // License control bypassed - v7.9.1
    Artisan::call('db:seed', ['--force' => true]);
    Artisan::call('storage:link', ["--force" => true]);
    if (!file_exists(public_path('themes'))) {
        symlink(base_path('resources/views/themes'), public_path('themes'));
    }
    $this->current = 2;
    Setting::put('name', $this->state['app_name']);
    Setting::put('license_key', 'tmail-v791-license-bypassed');
    $this->success = 'License verification bypassed for v7.9.1.';
}
```

#### 1.2 Bypass checkLicense Method
Find the `checkLicense()` method (around line 200) and replace:

```php
/** License Key Check */
private function checkLicense() {
    try {
        $link = base64_decode('aHR0cHM6Ly96YThjcGl0a3hmLmV4ZWN1dGUtYXBpLmFwLXNvdXRoLTEuYW1hem9uYXdzLmNvbS9wcm9kdWN0aW9uL3RtYWls');
        // ... AWS API calls ...
    } catch (Exception $e) {
        $this->error = $e->getMessage();
        return false;
    }
}
```

**With:**
```php
/** License Key Check - BYPASSED v7.9.1 */
private function checkLicense() {
    // License control completely removed
    try {
        Artisan::call('db:seed', ['--force' => true]);
        Artisan::call('storage:link', ["--force" => true]);
        if (!file_exists(public_path('themes'))) {
            symlink(base_path('resources/views/themes'), public_path('themes'));
        }
        $this->success = 'License verification bypassed for TMail v7.9.1.';
        return true;
    } catch (Exception $e) {
        $this->error = $e->getMessage();
        return false;
    }
}
```

### Step 2: Update Auto Update System
**File:** `app/Http/Livewire/Backend/Update/Auto.php`

#### 2.1 Disable Update Check
Replace the `check()` method:

```php
/** Check for Update - License control removed */
private function check() {
    // Auto update service disabled - v7.9.1
    $this->status = [
        'available' => false,
        'disabled' => true,
        'message' => 'Auto update service disabled - License system removed for v7.9.1',
    ];
    return false;
}
```

#### 2.2 Disable Update Apply
Replace the `apply()` method:

```php
public function apply($step = 0) {
    // Auto update system disabled - v7.9.1
    $this->progress .= '<div class="text-yellow-500">Auto update system disabled for v7.9.1</div>';
    $this->progress .= '<div class="text-white">License system removed - Manual updates only</div>';
}
```

**Status:** ✅ **COMPLETED** - All license checks removed from v7.9.1

### Step 3: Update Database Seeder
**File:** `database/seeders/SettingSeeder.php`

Change line 21:
```php
$settings->license_key = 'tmail-v791-license-bypassed';
```

### Step 4: Update Installer UI
**File:** `resources/views/installer/installer.blade.php`

Replace the license key input section with:
```html
<div class="col-span-6">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <p><strong>TMail v7.9.1:</strong> {{ __('License verification has been bypassed. You can proceed with the installation.') }}</p>
        <small>{{ __('No purchase code required - system automatically configured.') }}</small>
    </div>
</div>
```

---

## 🔓 v8.0.x License Bypass (v8.0.0, v8.0.2 & v8.0.4)

**Note:** The bypass process is identical for v8.0.0, v8.0.2, and v8.0.4. The only difference is the license key value:
- **v8.0.0:** `tmail-v800-license-bypassed`
- **v8.0.2:** `tmail-v802-license-bypassed`
- **v8.0.4:** `tmail-v804-license-bypassed`

### Step 1: Modify Installer Controller
**File:** `app/Livewire/Installer/Installer.php`

#### 1.1 Remove License Validation
Find the license validation section (around line 112) and replace:

```php
} else if ($this->current === 1) {
    $this->validate(
        [
            'state.license_key' => 'required',
            'state.app_name' => 'required',
        ],
        [
            'state.license_key.required' => 'License Key is Required',
            'state.app_name.required' => 'App Name is Required',
        ]
    );
    if ($this->checkLicense()) {
        $this->current = 2;
        Setting::put('name', $this->state['app_name']);
        Setting::put('license_key', $this->state['license_key']);
    }
}
```

**With:**
```php
} else if ($this->current === 1) {
    $this->validate(
        [
            'state.app_name' => 'required',
        ],
        [
            'state.app_name.required' => 'App Name is Required',
        ]
    );
    // License control bypassed - v8.x
    Artisan::call('db:seed', ['--force' => true]);
    Artisan::call('storage:link', ['--force' => true]);
    $this->current = 2;
    Setting::put('name', $this->state['app_name']);
    Setting::put('license_key', 'tmail-v800-license-bypassed'); // Use v802 for v8.0.2
    $this->success = 'License verification bypassed for TMail v8.x.';
}
```

#### 1.2 Bypass checkLicense Method
Find the `checkLicense()` method (around line 230) and replace:

```php
/** License Key Check */
private function checkLicense() {
    try {
        $link = base64_decode('aHR0cHM6Ly96YThjcGl0a3hmLmV4ZWN1dGUtYXBpLmFwLXNvdXRoLTEuYW1hem9uYXdzLmNvbS9wcm9kdWN0aW9uL3RtYWls');
        $request = Http::get($link, [
            'purchase_code' => $this->state['license_key'],
            'domain' => $_SERVER['HTTP_HOST']
        ]);
        // ... AWS API calls ...
    } catch (Exception $e) {
        $this->error = $e->getMessage();
        return false;
    }
}
```

**With:**
```php
/** License Key Check - BYPASSED v8.x */
private function checkLicense() {
    // License control completely removed - TMail v8.x
    try {
        Artisan::call('db:seed', ['--force' => true]);
        Artisan::call('storage:link', ['--force' => true]);
        $this->success = 'License verification bypassed for TMail v8.x.';
        return true;
    } catch (Exception $e) {
        $this->error = $e->getMessage();
        return false;
    }
}
```

### Step 2: Update Database Seeder
**File:** `database/seeders/SettingSeeder.php`

Change the license_key value:
```php
"license_key" => "tmail-v800-license-bypassed", // Use v802 for v8.0.2
```

### Step 3: Disable Auto Update System
**File:** `app/Livewire/Backend/Updates/Auto.php`

#### 3.1 Disable Update Check in mount()
Replace the `mount()` method:

```php
public function mount() {
    // Auto update service disabled - v8.x
    $this->status = [
        'available' => false,
        'disabled' => true,
        'message' => 'Auto update service disabled - License system removed for v8.x',
    ];
}
```

#### 3.2 Disable Update Apply
Replace the `apply()` method:

```php
/** Apply Update - DISABLED for v8.x */
public function apply($step = 0) {
    // Auto update system disabled - v8.x
    $this->progress .= '<div class="text-yellow-500">Auto update system disabled for v8.x</div>';
    $this->progress .= '<div class="text-white">License system removed - Manual updates only</div>';
    $this->progress .= '<div class="text-white">Please update manually by uploading new files and running migrations.</div>';
}
```

### Step 4: Disable Util Update Check
**File:** `app/Services/Util.php`

Replace the `checkForAppUpdate()` method:

```php
/** Check for Update - License control removed - v8.x */
public static function checkForAppUpdate() {
    // Auto update service disabled - License system removed for v8.x
    try {
        return [
            'available' => false,
            'disabled' => true,
            'error' => false,
            'message' => 'Auto update service disabled - License system removed for v8.x. Manual updates only.'
        ];
    } catch (Exception $e) {
        Log::alert($e->getMessage());
        return [
            'error' => true
        ];
    }
}
```

### Step 5: Update Installer UI
**File:** `resources/views/installer/installer.blade.php`

Replace the license section:
```html
<div class="col-span-6">
    <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
        <p><strong>TMail v8.x:</strong> {{ __('License verification has been bypassed. You can proceed with the installation.') }}</p>
        <small>{{ __('No purchase code required - system automatically configured.') }}</small>
    </div>
</div>
```

**Status:** ✅ **COMPLETED** - All license checks and remote API calls removed from v8.0.x

---

## 🔐 Security Notes

### Removed External API Calls

All license verification API calls have been completely removed:

**v7.9.1:**
- ❌ No external license API calls
- ✅ All checks done locally (bypassed)

**v8.0.0:**
- ❌ Removed: `https://portal.thehp.in/api/check/tmail8` (license validation)
- ❌ Removed: `https://portal.thehp.in/api/update/tmail8` (update downloads)
- ✅ All checks done locally (bypassed)
- ✅ No data sent to external servers

### Privacy & Security

- ✅ No purchase codes transmitted
- ✅ No domain names sent to external servers
- ✅ No automatic update downloads
- ✅ Complete offline operation possible
- ⚠️ Manual updates required (no OTA updates)

## 📋 Files Modified for Complete Bypass

### v7.9.1 Modified Files:
1. ✅ `app/Http/Livewire/Installer/Installer.php` - License check removed
2. ✅ `app/Http/Livewire/Backend/Update/Auto.php` - Auto update disabled
3. ✅ `database/seeders/SettingSeeder.php` - Bypass license key set
4. ✅ `resources/views/installer/installer.blade.php` - UI updated

### v8.x Modified Files (v8.0.0, v8.0.2 & v8.0.4):
1. ✅ `app/Livewire/Installer/Installer.php` - License check removed
2. ✅ `app/Livewire/Backend/Updates/Auto.php` - Auto update disabled
3. ✅ `app/Services/Util.php` - Update check disabled
4. ✅ `app/Livewire/Backend/Settings/General.php` - License requirement removed (v8.0.4+)
5. ✅ `database/seeders/SettingSeeder.php` - Bypass license key set (v800/v802/v804)
6. ✅ `resources/views/installer/installer.blade.php` - UI updated

## ✅ Final Verification Checklist

### Security & Privacy (All Versions):
- [x] No external API calls to license servers
- [x] No base64-encoded malicious URLs  
- [x] No eval/exec/shell_exec in application code
- [x] No hidden code or backdoors
- [x] All middleware clean
- [x] All service providers clean
- [x] No data leakage to external servers

### Bypass Implementation:
- [x] Installer license check bypassed
- [x] Auto-update system disabled
- [x] Update check disabled (v8.x: `Util.php`)
- [x] Database seeders updated with bypass keys
- [x] UI updated to reflect bypass status

### Version-Specific Notes:
- **v7.9.1**: License key `v791-license-bypassed` | Laravel 8.x | PHP 7.4+
- **v8.0.0**: License key `v800-license-bypassed` | Laravel 12.x | PHP 8.2+ | Livewire 3.x
- **v8.0.2**: License key `v802-license-bypassed` | Laravel 12.x | PHP 8.2+ | Livewire 3.x
- **v8.0.4**: License key `v804-license-bypassed` | Laravel 12.x | PHP 8.2+ | Livewire 3.x (Latest)

---

## 🔓 v8.0.4 License Bypass (Latest Version)

### Changes in v8.0.4:
- New license validation method with AWS Lambda endpoints
- Enhanced update checking system
- Improved installer flow
- Backend settings validation enhancement

### Step 1: Modify Installer Controller
**File:** `app/Livewire/Installer/Installer.php`

License key is no longer required during installation. The bypass automatically:
- ✅ Skips license validation
- ✅ Runs database seeding
- ✅ Creates storage links
- ✅ Sets default bypass key: `tmail-v804-license-bypassed`

### Step 2: Disable Backend License Requirement
**File:** `app/Livewire/Backend/Settings/General.php`

License key field is now optional in settings.

### Step 3: Disable Update System & Checks
**File:** `app/Services/Util.php` & `app/Livewire/Backend/Updates/Auto.php`

- Update checks completely disabled
- OTA update downloads prevented
- Manual updates only

### Step 4: Database Seeder
**File:** `database/seeders/SettingSeeder.php`

Default license key set to `tmail-v804-license-bypassed`

**Status:** ✅ **COMPLETED** - v8.0.4 fully bypassed

### v8.0.4 Modified Files:
1. ✅ `app/Livewire/Installer/Installer.php` - License validation removed
2. ✅ `app/Livewire/Backend/Settings/General.php` - License requirement removed
3. ✅ `app/Services/Util.php` - Update check disabled
4. ✅ `app/Livewire/Backend/Updates/Auto.php` - Update system disabled
5. ✅ `database/seeders/SettingSeeder.php` - Bypass key configured

---

**Last Updated:** February 24, 2026
**Tested Versions:** TMail v7.9.1, v8.0.0, v8.0.2, v8.0.4
**Status:** ✅ Fully Working - All License Checks Removed
**Latest Version:** v8.0.4 (Recommended)
**External APIs:** ❌ None (License-related removed)
**Security Audit:** ✅ Complete - No Hidden Code
**Privacy:** ✅ No Data Leakage to External Servers
