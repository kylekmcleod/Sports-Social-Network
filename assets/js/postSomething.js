document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('.post-something__input');
    const charCount = document.querySelector('.post-something__char-count');
    const maxLength = 280;

    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        
        const remainingChars = maxLength - this.value.length;
        charCount.textContent = remainingChars;
    });
}); 
