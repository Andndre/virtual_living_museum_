# AGENTS.md - Debug Mode

This file provides guidance to agents when debugging issues in this repository.

## Debug Logging

**Laravel Pail**: Use `php artisan pail` for real-time log viewing (already running in dev mode via `composer run dev`)

**Log Files**: Check `storage/logs/laravel.log` for detailed errors

**Queue Worker**: `php artisan queue:listen --tries=1` runs concurrently in dev mode

## Common Debugging Scenarios

**AR Marker Not Detected**:

- Verify `.patt` file exists in `/storage/` and URL is correct
- Check browser console for AR.js initialization errors
- Ensure `gesture-detector.js` and `gesture-handler.js` are loaded

**Progress Not Unlocking**:

- Check if `APP_DEMO_MODE=true` in `.env`
- Verify `shouldIncrementProgress()` returns correct boolean
- Check user has required `jawaban_user` records

**3D Model Not Loading**:

- Verify DRACO compression is enabled
- Check model exists in `/storage/app/public/{path_obj}`
- Enable WebXR in Chrome: `chrome://flags/#webxr`
- Test on Android device (iOS has limited WebXR support)

**Database Foreign Key Errors**:

- Missing `onDelete('cascade')` on foreign key
- Wrong PK name in constrained() call

**Token Authentication Fails**:

- Token may be expired (check timestamp)
- HMAC signature mismatch (verify secret key in .env)
- Use `TokenHelper::verify($token)` to debug

## Demo Mode Debugging

```bash
# Check if demo mode is active
grep APP_DEMO_MODE .env
# Temporarily disable for testing progression
APP_DEMO_MODE=false php artisan serve
```

## Useful Debug Commands

```bash
php artisan route:list                        # List all routes
php artisan migrate:fresh --seed              # Fresh database
php artisan cache:clear && php artisan config:clear  # Clear caches
```
