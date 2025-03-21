<?php
class ProfileImageHelper {

    public static function getProfileImageUrl($profileImagePath) {
        $baseUrl = '/COSC360/';
        
        if ($profileImagePath === null || empty($profileImagePath)) {
            return $baseUrl . 'assets/images/defaultProfilePic.png';
        }
        
        return $baseUrl . $profileImagePath;
    }
}
?>
