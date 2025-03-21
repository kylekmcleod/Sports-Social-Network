document.addEventListener('DOMContentLoaded', function() {
    const profileUpload = document.getElementById('profile-upload');
    const profilePreview = document.getElementById('profile-preview');
    const uploadProfileBtn = document.getElementById('uploadProfileBtn');

    profileUpload.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    uploadProfileBtn.addEventListener('click', function() {
        profileUpload.click();
    });
});
