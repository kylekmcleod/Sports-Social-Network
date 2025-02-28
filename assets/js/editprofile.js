document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.settings-panel__edit');
    editButtons.forEach(button => {
        const editHandler = function() {
            const block = this.closest('.settings-panel__block');
            const metaDiv = block.querySelector('.settings-panel__meta');
            const currentText = metaDiv.textContent;
            
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentText;
            input.className = 'settings-panel__input';
            
            metaDiv.textContent = '';
            metaDiv.appendChild(input);
            input.focus();
            
            this.textContent = 'Save';
            
            this.removeEventListener('click', editHandler);
            
            const saveHandler = () => {
                const newValue = input.value;
                metaDiv.textContent = newValue;
                this.textContent = 'Edit';
                
                this.removeEventListener('click', saveHandler);
                this.addEventListener('click', editHandler);
            };
            
            this.addEventListener('click', saveHandler);
        };
        
        button.addEventListener('click', editHandler);
    });

    const bannerEditButton = document.querySelector('.settings__edit-banner');
    const profileImage = document.querySelector('.settings__profile-image');
    
    const bannerInput = document.createElement('input');
    bannerInput.type = 'file';
    bannerInput.accept = 'image/*';
    bannerInput.style.display = 'none';
    
    const profileInput = document.createElement('input');
    profileInput.type = 'file';
    profileInput.accept = 'image/*';
    profileInput.style.display = 'none';
    
    document.body.appendChild(bannerInput);
    document.body.appendChild(profileInput);

    bannerEditButton.addEventListener('click', () => {
        bannerInput.click();
    });

    profileImage.addEventListener('click', () => {
        profileInput.click();
    });

    bannerInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.querySelector('.settings__banner-image').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    profileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profileImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});