document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.tag-filter-button');
    const selectedTags = new Set();
    
    const getProfilePicturePath = (profilePicture) => {
        if (!profilePicture) {
            return '../assets/images/defaultProfilePic.png';
        }
        return profilePicture.startsWith('http') 
            ? profilePicture 
            : `../src/utils/getImage.php?file=${profilePicture}`;
    };

    const fetchPosts = async () => {
        try {
            const queryString = selectedTags.size > 0 
                ? `?tags=${Array.from(selectedTags).join(',')}` 
                : '';
                
            const response = await fetch(`../src/controllers/PostsController.php${queryString}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const posts = await response.json();
            
            const postsContainer = document.getElementById('posts-container');
            if (posts.length === 0) {
                postsContainer.innerHTML = `
                    <div class="no-posts">
                        <p>No posts found for the selected tags.</p>
                    </div>
                `;
                return;
            }
            
            postsContainer.innerHTML = posts.map(post => `
                <div class="post">
                    <img class="post__author-logo" 
                         src="${getProfilePicturePath(post.profile_picture)}" 
                         alt="${post.username}'s profile picture">
                    <div class="post__main">
                        <div class="post__header">
                            <div class="post__author-name">${post.username}</div>
                            <div class="post__create-time">
                                ${new Date(post.created_at).toLocaleDateString()}
                            </div>
                        </div>
                        <div class="post__content">${post.content}</div>
                        ${post.tags ? `
                            <div class="post__tags">
                                ${post.tags.split(',').map(tag => 
                                    `<span class="post__tag">#${tag}</span>`
                                ).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Error fetching posts:', error);
            document.getElementById('posts-container').innerHTML = `
                <div class="error-message">
                    <p>Error loading posts. Please try again later.</p>
                </div>
            `;
        }
    };

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tagValue = button.dataset.value;
            
            if (selectedTags.has(tagValue)) {
                selectedTags.delete(tagValue);
                button.classList.remove('selected');
            } else {
                selectedTags.add(tagValue);
                button.classList.add('selected');
            }
            
            fetchPosts();
        });
    });

    fetchPosts();
});