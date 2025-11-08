# Image Upload Troubleshooting Guide

## Issue: "The photo/Video field must not be greater than 10240 kilobytes"

### Understanding the Error

- **Limit**: 10MB (10,240 KB)
- **Your file**: 6MB (should be acceptable)
- **Why it might fail**: File might actually be larger, or browser/client validation issue

### Solutions

#### 1. Check Actual File Size
- Right-click the file on your computer
- Check "Properties" (Windows) or "Get Info" (Mac)
- Verify the actual file size in bytes/KB/MB
- **Note**: Sometimes file managers show rounded sizes

#### 2. Compress the Image
**Online Tools:**
- **TinyPNG**: https://tinypng.com/ (Best for PNG)
- **Squoosh**: https://squoosh.app/ (Google's tool)
- **Compressor.io**: https://compressor.io/

**Desktop Tools:**
- **ImageOptim** (Mac)
- **JPEGmini** (Windows/Mac)
- **GIMP** (Free, all platforms)

#### 3. Resize the Image
- Use an image editor to reduce dimensions
- Target: Max 2000px width or height
- The system will resize automatically, but smaller files upload faster

#### 4. Convert Format
- PNG files are often larger than JPEG
- Try converting PNG → JPEG (with quality 85-90%)
- WebP format is also supported and often smaller

#### 5. Clear Browser Cache
- Sometimes cached validation rules cause issues
- Hard refresh: Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)
- Clear browser cache and cookies

#### 6. Try Different Browser
- Some browsers handle file uploads differently
- Try Chrome, Firefox, or Edge

### Quick Fix Steps

1. **Download the image** from your computer
2. **Open TinyPNG.com**
3. **Upload and compress** the image
4. **Download the compressed version**
5. **Upload the compressed version** to the admin panel

### Expected Results

After compression, a 6MB image should become:
- **PNG**: ~2-3MB (60-70% reduction)
- **JPEG**: ~1-2MB (70-80% reduction)
- **WebP**: ~800KB-1.5MB (75-85% reduction)

### Still Having Issues?

If the file is definitely under 10MB and still failing:

1. **Check PHP limits** (already set to 20MB - should be fine)
2. **Try a smaller test file** first (1-2MB)
3. **Check browser console** for detailed error messages
4. **Contact support** with:
   - File size (in bytes)
   - File format
   - Browser used
   - Error message screenshot

### Pro Tips

- **Always compress images** before uploading for best performance
- **Use JPEG for photos** (smaller file size)
- **Use PNG for graphics** with transparency
- **Use WebP** for modern browsers (smallest file size)
- **Resize before uploading** if dimensions are very large (>4000px)

---

## Current System Limits

- **PHP upload_max_filesize**: 20MB ✅
- **PHP post_max_size**: 25MB ✅
- **Filament maxSize**: 10MB (10,240 KB) ✅
- **Auto-resize**: Images resized to 2000px max width/height ✅

Your 6MB file should work! If it doesn't, try the solutions above.

