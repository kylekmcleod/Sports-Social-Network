/*
    Prevent empty posts from being submitted. There is also a check in AddPostController.php that will not
    allow an empty post to be submitted. The controller will simply redirect back without adding a post. 
*/

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.post-something');
    const textarea = document.querySelector('.post-something__input');
    const charCount = document.querySelector('.post-something__char-count');
    const tagButtons = document.querySelectorAll('.tag-button');
    const selectedTagsInput = document.getElementById('selected-tags');
    const selectedTags = new Set();

    textarea.addEventListener('input', function() {
        const remaining = 280 - this.value.length;
        charCount.textContent = remaining;
        charCount.style.color = remaining < 0 ? 'red' : 'inherit';
    });

    tagButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tagValue = button.dataset.value;
            
            if (selectedTags.has(tagValue)) {
                selectedTags.delete(tagValue);
                button.classList.remove('selected');
            } else {
                selectedTags.add(tagValue);
                button.classList.add('selected');
            }

            selectedTagsInput.value = Array.from(selectedTags).join(',');
        });
    });

    form.addEventListener('submit', function(event) {
        const content = textarea.value.trim();
        
        if (content === "") {
            alert("Post cannot be empty!");
            event.preventDefault();
            return;
        }

        if (content.length > 280) {
            alert("Post exceeds maximum character limit of 280!");
            event.preventDefault();
            return;
        }
    });
});