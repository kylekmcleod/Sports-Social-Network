document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('.post-something__input');
    const charCount = document.querySelector('.post-something__char-count');
    const maxLength = 280;

    // Handle textarea auto-height
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        
        // Update character count
        const remainingChars = maxLength - this.value.length;
        charCount.textContent = remainingChars;
    });
}); 