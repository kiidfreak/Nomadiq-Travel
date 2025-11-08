# Image Upload Limits Configuration

## Current Limits

- **Filament Form Limit**: 10MB (updated)
- **PHP upload_max_filesize**: 2MB ⚠️ (needs to be increased)
- **PHP post_max_size**: 8MB

## The Problem

Your 5.8MB image cannot be uploaded because PHP's `upload_max_filesize` is set to only 2MB, which overrides the Filament limit.

## Solution Options

### Option 1: Increase PHP Upload Limits (Recommended for Development)

#### For Laravel Herd (Windows):

1. **Find PHP Configuration File**:
   - Open Laravel Herd app
   - Go to Settings → PHP
   - Click "Open PHP Config"
   - Or manually locate: `C:\Users\<YourUsername>\.config\herd\config\php84\php.ini` (version may vary)

2. **Update PHP Settings**:
   ```ini
   upload_max_filesize = 20M
   post_max_size = 25M
   memory_limit = 256M
   max_execution_time = 300
   ```

3. **Restart Herd**:
   - Close and restart Laravel Herd application
   - Or restart your web server

#### For Standard PHP Installation:

1. **Find php.ini**:
   ```bash
   php --ini
   ```

2. **Edit php.ini** and update:
   ```ini
   upload_max_filesize = 20M
   post_max_size = 25M
   memory_limit = 256M
   max_execution_time = 300
   ```

3. **Restart PHP/web server**

### Option 2: Compress Your Image (Recommended for Production)

Since images are automatically resized to **1200x900px** anyway, compressing your 5.8MB image is the better approach:

**Benefits:**
- ✅ Faster uploads
- ✅ Less server storage
- ✅ Faster page loads
- ✅ Better user experience

**Tools to Compress:**
- **Online**: [TinyPNG](https://tinypng.com/), [Squoosh](https://squoosh.app/)
- **Desktop**: [ImageOptim](https://imageoptim.com/), [JPEGmini](https://www.jpegmini.com/)
- **Command Line**: `jpegoptim`, `pngquant`

**Target Size**: Aim for 1-3MB before upload (the system will resize to 1200x900px anyway)

### Option 3: Update via .htaccess (If using Apache)

Create or update `public/.htaccess`:

```apache
php_value upload_max_filesize 20M
php_value post_max_size 25M
php_value memory_limit 256M
php_value max_execution_time 300
```

**Note**: This only works if PHP is running as Apache module, not FastCGI.

## Verification

After updating PHP settings, verify with:

```bash
php -i | findstr /i "upload_max_filesize post_max_size"
```

You should see:
```
upload_max_filesize => 20M => 20M
post_max_size => 25M => 25M
```

## Recommended Approach

**For Development**: Increase PHP limits (Option 1)
**For Production**: Compress images before upload (Option 2)

## Current Filament Configuration

The FloatingMemoryResource is now configured to accept up to **10MB** files. Images will be automatically:
- Resized to 1200x900px (4:3 aspect ratio)
- Optimized during upload
- Stored in `storage/app/public/floating-memories/`

## Notes

- Large images (5.8MB+) are unnecessary since they get resized to 1200x900px
- A 1200x900px JPEG at 85% quality should be ~200-500KB
- Compressing before upload saves bandwidth and storage
- Always maintain image quality for your beautiful travel photos! 📸

