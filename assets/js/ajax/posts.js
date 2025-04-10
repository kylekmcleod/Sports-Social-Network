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
                postElement.innerHTML = renderPost(post);
                postElement.addEventListener('click', () => {
                    window.location.href = `../public/post.php?id=${post.id}`;
                });
                postElement.style.cursor = 'pointer';
                postsContainer.appendChild(postElement);
            });

            if (window.setupCollapsibleThreads) {
                window.setupCollapsibleThreads();
            }
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

// Add a new function or modify the existing renderPost function to include collapsible threads
function renderPost(post) {
    // Escape HTML characters in user-generated content
    const safeUsername = escapeHtml(post.username);
    const safeContent = escapeHtml(post.content);
    
    // Create post HTML
    let postHtml = `
        <div class="post" data-post-id="${post.id}">
            <a href="./profile.php?user_id=${post.user_id || ''}">
                <img class="post__author-logo" src="${post.profile_picture ? '../src/utils/getImage.php?file=' + post.profile_picture : '../assets/images/defaultProfilePic.png'}" />
            </a>
            <div class="post__main">
                <div class="post__header">
                    <a href="./profile.php?user_id=${post.user_id || ''}" class="post__author-link">
                        <div class="post__author-name">${safeUsername}</div>
                    </a>
                    <div class="post__author-slug">@${safeUsername.toLowerCase().replace(/\s+/g, '_')}</div>
                    <div class="post__publish-time">${formatTime(post.created_at)}</div>
                </div>
                <a href="./post.php?id=${post.id}" class="post__content-link">
                    <div class="post__content">${safeContent}</div>
                </a>
                <div class="post__tags">
                    ${renderTags(post.tags)}
                </div>
                <div class="post__actions">
                    <div class="post__action">
                        <img src="../assets/svg/comment.svg" class="post__action-icon" />
                        <span>${post.comment_count || 0}</span>
                    </div>
                    <div class="post__action">
                        <img src="../assets/svg/like.svg" class="post__action-icon" />
                        <span>${post.like_count || 0}</span>
                    </div>
                </div>`;
    
    // Add collapsible thread toggle if post has comments
    if (post.comment_count && post.comment_count > 0) {
        postHtml += `
                <div class="post__thread-toggle" data-post-id="${post.id}">
                    <span class="thread-arrow"></span>
                    Show ${post.comment_count} ${post.comment_count === 1 ? 'reply' : 'replies'}
                </div>
                <div class="post__thread-container" data-post-id="${post.id}">
                    <!-- Comments will be loaded here -->
                </div>`;
    }
    
    postHtml += `
            </div>
        </div>`;
    
    return postHtml;
}

// Add or modify the existing function to set up collapsible threads after posts are loaded
function loadPosts(tags = []) {
    // ...existing code...
    
    // Call setupCollapsibleThreads after posts are rendered
    if (window.setupCollapsibleThreads) {
        window.setupCollapsibleThreads();
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

    fetchAndDisplayPosts();
    setInterval(fetchAndDisplayPosts, 2000);
});