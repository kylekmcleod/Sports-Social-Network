// This script fetches and displays posts from a server when the page loads.
//
// It is used on the home page to display all the posts taht have been made by users.

document.addEventListener('DOMContentLoaded', function() {
    const postsContainer = document.getElementById('posts-container');

    if (postsContainer) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://localhost/COSC360/src/controllers/PostsController.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                const posts = JSON.parse(xhr.responseText);
                if (posts.length > 0) {
                    posts.forEach(function(post) {
                        const postElement = document.createElement('div');
                        postElement.classList.add('post');
                        postElement.innerHTML = `
                            <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
                            <div class="post__main">
                                <div class="post__header">
                                    <div class="post__author-name">${post.username}</div>
                                    <div class="post__author-slug">@${post.username}</div>
                                    <div class="post__publish-time">${new Date(post.created_at).toLocaleString()}</div>
                                </div>
                                <div class="post__content">
                                    ${post.content}
                                </div>
                                <div class="post__actions">
                                    <div class="post__action-button">
                                        <img src="../assets/svg/comment.svg" class="post__action-icon" />
                                        <span class="post__action-count">24</span>
                                    </div>
                                    <div class="post__action-button">
                                        <img src="../assets/svg/heart.svg" class="post__action-icon" />
                                        <span class="post__action-count">482</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        postsContainer.appendChild(postElement);
                    });
                } else {
                    const noPostsMessage = document.createElement('div');
                    noPostsMessage.textContent = "No posts found.";
                    postsContainer.appendChild(noPostsMessage);
                }
            } else {
                console.error('Error fetching posts:', xhr.status, xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Request failed');
        };

        xhr.send();
    } else {
        console.error('Error: posts-container element not found.');
    }
});
