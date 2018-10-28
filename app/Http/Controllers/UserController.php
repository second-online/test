<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Sizes of profile pictures to create.
     *
     * @var array
     */
    private $sizes = [
        'large' => 600,
        'small' => 88,
        'tiny' => 66
    ];

    /**
     * Upload users profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePicture(Request $request)
    {
        // Validation!
        if ($request->hasFile('picture')) {
            $user = $request->user();
            $oldFilename = $user->getOriginal('profile_picture');
            $random = Str::random(40);
            $filename = $random . '.jpg';
            $img = Image::make($request->picture);

            foreach ($this->sizes as $key => $size) {
                $imgVariation = $img->fit($size)->save();

                Storage::disk('public')->put(
                    '/users/' . $user->id . '/profile_pictures/' . $key . '/' . $filename, $imgVariation
                );
            }

            $user->profile_picture = $filename;
            
            if ($user->save()) {
                $this->removeProfilePictureFiles($user->id, $oldFilename);
            }
        }

        return response()->json($user);
    }

    /**
     * Delete users profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyProfilePicture(Request $request)
    {
        $user = $request->user();

        if (!is_null($user->profile_picture)) {
            $user->profile_picture = null;

            if ($user->save()) {
                $this->removeProfilePictureDirectory($user->id);
            }
        }

        return response()->json($user);
    }

    /**
     * Remove the pictures from storage.
     *
     * @param  int    $userId
     * @param  string $filename
     * @return bool
     */
    private function removeProfilePictureFiles($userId, $filename)
    {
        $files = [];
        
        foreach ($this->sizes as $key => $size) {
            $files[] = '/users/' . $userId . '/profile_pictures/' . $key . '/' . $filename; 
        }

        return Storage::disk('public')->delete($files);
    }

    /**
     * Remove the users directory from storage.
     *
     * @param  int    $userId
     * @return bool
     */
    private function removeProfilePictureDirectory($userId)
    {
        return Storage::disk('public')->deleteDirectory('/users/' . $userId);
    }
}
