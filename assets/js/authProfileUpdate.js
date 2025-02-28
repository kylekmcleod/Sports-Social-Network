document.getElementById("uploadProfileBtn").addEventListener("click", () => 
    document.getElementById("profile-upload").click()
);

document.getElementById("profile-upload").addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (!file) return;

    if (!["image/jpeg", "image/png", "image/gif", "image/webp"].includes(file.type)) {
        return alert("Please upload a valid image file (JPEG, PNG, GIF, WebP).");
    }

    if (file.size > 5 * 1024 * 1024) {
        return alert("File size exceeds 5MB limit. Please choose a smaller file.");
    }

    const reader = new FileReader();
    reader.onload = () => document.getElementById("profile-preview").src = reader.result;
    reader.readAsDataURL(file);
});
