<?php
namespace App\Helpers;
class Helper
{
    public function getUser($profiles)
    {
        //get follower/following
        $data = [];
        foreach ($profiles as $profile) {
            if (auth()->id() == $profile->id) {
                $profile->is_user = true;
            }
            if (
                auth()
                    ->user()
                    ->requestFollow->contains($profile->id)
            ) {
                $profile->is_requested = true;
            }
            if (
                auth()
                    ->user()
                    ->following->contains($profile->id)
            ) {
                //add attribute
                $profile->is_following = true;
                $data[] = $profile;
            } else {
                $profile->is_following = false;
                $data[] = $profile;
            }
        }
        return $data;
    }
}
