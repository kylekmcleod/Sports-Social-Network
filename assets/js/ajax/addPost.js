/*
    Prevent empty posts from being submitted. There is also a check in AddPostController.php that will not
    allow an empty post to be submitted. The controller will simply redirect back without adding a post. 
*/
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.post-something');
    const textarea = document.querySelector('.post-something__input');

    form.addEventListener('submit', function(event) {
        const content = textarea.value.trim();
        
        if (content === "") {
            alert("Post cannot be empty!");
            event.preventDefault();
        }
    });
});
