document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit profile script loaded');

    const statusMessage = document.querySelector('.settings-panel__status-message');
    fetch('../src/controllers/settingsController.php')
        .then(response => {
            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            return response.text().then(text => {
                try {
                    console.log('Raw response:', text);

                    if (text.trim().indexOf('{') !== 0 && text.trim().indexOf('[') !== 0) {
                        throw new Error(`Invalid JSON response: ${text}`);
                    }

                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error(`Invalid JSON response: ${text}`);
                }
            });
        })

    function showStatusMessage(message, isError = false) {
        console.log(`Status message: ${message} (${isError ? 'error' : 'success'})`);
        
        statusMessage.textContent = '';
        statusMessage.className = 'settings-panel__status-message';
        
        if (isError) {
            statusMessage.classList.add('settings-panel__status-message--error');
        } else {
            statusMessage.classList.add('settings-panel__status-message--success');
        }
        
        statusMessage.textContent = message;
        
        setTimeout(() => {
            statusMessage.textContent = '';
            statusMessage.className = 'settings-panel__status-message';
        }, 5000);
    }
    
    document.querySelector('.settings-panel').addEventListener('click', (event) => {
        if (event.target.classList.contains('settings-panel__edit')) {
            handleEditClick(event.target);
        }
    });
    
    function handleEditClick(button) {
        const block = button.closest('.settings-panel__block');
        const field = block.getAttribute('data-field');
        const metaDiv = block.querySelector('.settings-panel__meta');
        const currentText = metaDiv.textContent;

        console.log(`Edit button clicked for field: ${field}, current value: "${currentText}"`);

        const input = document.createElement('input');
        input.type = 'text';
        input.value = currentText;
        input.className = 'settings-panel__input';

        metaDiv.textContent = '';
        metaDiv.appendChild(input);
        input.focus();

        button.textContent = 'Save';
        console.log('Changed button text to "Save"');

        button.setAttribute('data-original-text', 'Edit');
        button.setAttribute('data-original-field', field);
        button.setAttribute('data-original-value', currentText);
        button.setAttribute('data-editing', 'true');

        button.addEventListener('click', () => {
            const newValue = input.value.trim();

            if (!newValue) {
                console.log('Empty value detected, showing error');
                showStatusMessage(`${field} cannot be empty`, true);
                return;
            }

            console.log(`Saving new value for field: ${field}, value: "${newValue}"`);

            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('field', field);
            formData.append('value', newValue);

            fetch('../src/controllers/settingsController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Field ${field} updated successfully`);
                    metaDiv.textContent = newValue;
                    showStatusMessage(`${field} updated successfully!`);
                } else {
                    console.error(`Error updating field ${field}: ${data.message}`);
                    showStatusMessage(`Error: ${data.message}`, true);
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showStatusMessage('Network error occurred', true);
            });

            button.textContent = button.getAttribute('data-original-text');
            button.removeAttribute('data-editing');
            button.removeAttribute('data-original-text');
            button.removeAttribute('data-original-value');
            button.removeAttribute('data-original-field');
        }, { once: true });
    }
    document.querySelector('.settings-panel').addEventListener('click', (event) => {
        const button = event.target;
        
        if (button.classList.contains('settings-panel__edit') && 
            button.getAttribute('data-editing') === 'true' && 
            button.textContent === 'Save') {
            
            const block = button.closest('.settings-panel__block');
            const field = block.getAttribute('data-field');
            const metaDiv = block.querySelector('.settings-panel__meta');
            const input = metaDiv.querySelector('.settings-panel__input');
            const currentText = button.getAttribute('data-original-value');
            const newValue = input.value;
            
            console.log(`Save button clicked for field: ${field}, new value: "${newValue}"`);
            
            if (!newValue.trim()) {
                console.log('Empty value detected, showing error');
                showStatusMessage(`${field} cannot be empty`, true);
                return;
            }
            
            metaDiv.textContent = newValue;
            changedFields[field] = newValue; 
            console.log(`Field ${field} set to "${newValue}" (tracked for bulk update)`);
            console.log('Current tracked changes:', changedFields);
            
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('field', field);
            formData.append('value', newValue);
            
            console.log(`Sending immediate update for field: ${field}`);
            
            fetch('../src/controllers/settingsController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log(`AJAX response status: ${response.status}`);
                return response.text().then(text => {
                    console.log('Raw AJAX response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        throw new Error('Invalid response: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Parsed response data:', data);
                if (data.success) {
                    showPopupNotification(`${field} updated successfully!`);
                } else {
                    showStatusMessage(`Error: ${data.message}`, true);
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
                showStatusMessage('Network error occurred', true);
            });
            
            button.textContent = button.getAttribute('data-original-text');
            button.removeAttribute('data-editing');
            button.removeAttribute('data-original-text');
            button.removeAttribute('data-original-value');
            button.removeAttribute('data-original-field');
        }
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

    function uploadImage(file, imageType, imgElement) {
        const reader = new FileReader();
        
        if (!file.type.match('image.*')) {
            showStatusMessage('Please select an image file (JPEG, PNG, etc.)', true);
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            showStatusMessage('File is too large. Maximum size is 5MB.', true);
            return;
        }
        
        showStatusMessage('Uploading image...', false);
        
        reader.onload = (e) => {
            const imageData = e.target.result;
            
            imgElement.src = imageData;
            
            console.log(`Uploading ${imageType} image...`);
            
            const formData = new FormData();
            formData.append('action', 'update_image');
            formData.append('image_type', imageType);
            formData.append('image_data', imageData);
            
            fetch('../src/controllers/settingsController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Image upload response:', data);
                if (data.success) {
                    showStatusMessage('Image updated successfully!');
                } else {
                    showStatusMessage(`Error: ${data.message}`, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusMessage('Network error occurred', true);
            });
        };
        
        reader.readAsDataURL(file);
    }

    bannerInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            uploadImage(file, 'banner_image', document.querySelector('.settings__banner-image'));
        }
    });

    profileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            uploadImage(file, 'profile_image', profileImage);
        }
    });

});