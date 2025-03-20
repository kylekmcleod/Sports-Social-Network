document.addEventListener('DOMContentLoaded', function() {

    console.log('postSomething.js loaded');
    
    const currentPage = window.location.pathname;
    if (!currentPage.includes('profile.php')) {

        const textarea = document.querySelector('.post-something__input') || 
                       document.querySelector('.post-something__content');
        
        if (textarea) {
            console.log('Textarea found, setting up handlers');
            
            const charCount = document.querySelector('.post-something__char-count');
            const maxLength = 280;

            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';

                if (charCount) {
                    const remainingChars = maxLength - this.value.length;
                    charCount.textContent = remainingChars;
                }
            });

            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        } else {
            console.log('No textarea found on this page');
        }
    } else {
        console.log('On profile page - not looking for textarea');
    }
});
