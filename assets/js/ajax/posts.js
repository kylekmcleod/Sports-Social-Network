// This script fetches and displays posts from a server when the page loads.
// It is used on the home page to display all the posts that have been made by users.

document.addEventListener('DOMContentLoaded', function () {
    const postsContainer = document.getElementById('posts-container');
    let existingPostIds = new Set();

    if (!postsContainer) {
        console.error('Error: posts-container element not found.');
        return;
    }

function fetchAndDisplayPosts() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../src/controllers/PostsController.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const posts = JSON.parse(xhr.responseText);

            if (posts.length === 0) {
                if (!document.getElementById("no-posts-message")) {
                    postsContainer.innerHTML = '';
                    const noPostsMessage = document.createElement('div');
                    noPostsMessage.textContent = "No posts found.";
                    noPostsMessage.id = "no-posts-message";
                    postsContainer.appendChild(noPostsMessage);
                }
                return;
            }

            const noPostsMessage = document.getElementById("no-posts-message");
            if (noPostsMessage) {
                postsContainer.removeChild(noPostsMessage);
            }

            posts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            
            const newPosts = posts.filter(post => !existingPostIds.has(post.id));
            if (newPosts.length > 0 && existingPostIds.size > 0) {
                showNewPostAlert(newPosts.length);
            }
            
            postsContainer.innerHTML = '';
            
            posts.forEach(function (post) {
                existingPostIds.add(post.id);

                const postElement = document.createElement('div');
                postElement.classList.add('post');
                postElement.dataset.postId = post.id;

                const profilePicSrc = post.profile_picture
                    ? `../src/utils/getImage.php?file=${post.profile_picture}`
                    : '../assets/images/defaultProfilePic.png';

                postElement.innerHTML = `
                    <img class="post__author-logo" src="${profilePicSrc}" />
                    <div class="post__main">
                        <div class="post__header">
                            <div class="post__author-name">@${post.username}&nbsp;&nbsp;&#8226;</div>
                            <div class="post__publish-time">
                                ${(() => {
                                    const postDate = new Date(post.created_at);
                                    const currentYear = new Date().getFullYear();

                                    const options = {
                                        month: 'short',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                    };

                                    if (postDate.getFullYear() !== currentYear) {
                                        options.year = 'numeric';
                                    }

                                    return postDate.toLocaleString('en-US', options);
                                })()}
                            </div>
                        </div>
                        <div class="post__content">
                            ${post.content}
                        </div>
                        <div class="post__actions">
                            <div class="post__action-button">
                                <img src="../assets/svg/comment.svg" class="post__action-icon" />
                                <span class="post__action-count">0</span>
                            </div>
                            <div class="post__action-button">
                                <img src="../assets/svg/heart.svg" class="post__action-icon" />
                                <span class="post__action-count">0</span>
                            </div>
                        </div>
                    </div>
                `;

                postElement.addEventListener('click', () => {
                    window.location.href = `../public/post.php?id=${post.id}`;
                });

                postElement.style.cursor = 'pointer';
                postsContainer.appendChild(postElement);
            });
        } else {
            console.error('Error fetching posts:', xhr.status, xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error('Request failed');
    };

    xhr.send();
}

function showNewPostAlert(count) {
    let alertElement = document.getElementById('new-post-alert');
    if (!alertElement) {
        alertElement = document.createElement('div');
        alertElement.id = 'new-post-alert';
        alertElement.style.position = 'fixed';
        alertElement.style.top = '30px';
        alertElement.style.right = '30px';
        alertElement.style.backgroundColor = '#1DA1F2';
        alertElement.style.color = 'white';
        alertElement.style.padding = '10px 15px';
        alertElement.style.borderRadius = '5px';
        alertElement.style.zIndex = '1000';
        alertElement.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
        alertElement.style.cursor = 'pointer';
        document.body.appendChild(alertElement);
        
        alertElement.addEventListener('click', () => {
            alertElement.style.display = 'none';
        });
    }
    
    let message;
    if (count === 1) {
        message = '1 new post available!';
    } else {
        message = `${count} new posts available!`;
    }
    alertElement.textContent = message;
    alertElement.style.display = 'block';
    
    setTimeout(() => {
        alertElement.style.display = 'none';
    }, 5000);
}

    fetchAndDisplayPosts();
    setInterval(fetchAndDisplayPosts, 2000);
});