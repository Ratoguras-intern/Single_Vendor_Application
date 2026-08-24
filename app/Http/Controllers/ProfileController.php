<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->boolean('remove_avatar')) {
            $this->deleteAvatar($user);
            $user->avatar_path = null;
        } elseif ($request->hasFile('avatar')) {
            $path = $this->storeAvatar($request->file('avatar'));
            $oldPath = $user->avatar_path;
            $user->avatar_path = $path;

            if ($oldPath && $oldPath !== $path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
        }

        $user->save();

        if ($request->expectsJson()) {
            $fresh = $user->fresh();

            return response()->json([
                'status' => 'profile-updated',
                'message' => 'Profile updated.',
                'avatar_url' => $fresh->avatarUrl(),
                'initials' => $fresh->initials(),
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    protected function deleteAvatar($user): void
    {
        if ($user->avatar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_path);
        }
    }

    private function storeAvatar($file): string
    {
        $mime = $file->getMimeType();

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'image/png' => @imagecreatefrompng($file->getRealPath()),
            'image/webp' => @imagecreatefromwebp($file->getRealPath()),
            default => false,
        };

        if (! $src) {
            abort(422, 'The uploaded file could not be processed as an image.');
        }

        if ($mime === 'image/jpeg') {
            $src = $this->applyExifOrientation($src, @exif_read_data($file->getRealPath()));
        }

        $width = imagesx($src);
        $height = imagesy($src);
        $side = min($width, $height);
        $sx = (int) (($width - $side) / 2);
        $sy = (int) (($height - $side) / 2);

        $canvas = imagecreatetruecolor(512, 512);
        imagecopyresampled($canvas, $src, 0, 0, $sx, $sy, 512, 512, $side, $side);
        imagedestroy($src);

        $extension = function_exists('imagewebp') ? 'webp' : 'jpg';
        $name = 'avatars/' . \Illuminate\Support\Str::random(40) . '.' . $extension;

        if ($extension === 'webp') {
            imagewebp($canvas, \Illuminate\Support\Facades\Storage::disk('public')->path($name), 82);
        } else {
            imagejpeg($canvas, \Illuminate\Support\Facades\Storage::disk('public')->path($name), 85);
        }

        imagedestroy($canvas);

        return $name;
    }

    private function applyExifOrientation($image, $exif)
    {
        return match ((int) ($exif['Orientation'] ?? 1)) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
