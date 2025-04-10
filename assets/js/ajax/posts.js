// This script fetches and displays posts from a server when the page loads.
// It is used on the home page to display all the posts that have been made by users.

document.addEventListener('DOMContentLoaded', function () {
    const postsContainer = document.getElementById('posts-container');
    let existingPostIds = new Set();
    let lastUpdateTime = new Date().getTime();

    if (!postsContainer) {
        console.error('Error: posts-container element not found.');
        return;
    }

    // Event delegation for post clicks
    document.getElementById('posts-container').addEventListener('click', function(e) {
        const postElement = e.target.closest('.post');
        if (postElement) {
            const postId = postElement.dataset.postId;
            if (postId) {
                window.location.href = `post.php?id=${postId}`;
            }
        }
    });

    function fetchAndDisplayPosts() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../src/controllers/PostsController.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const posts = JSON.parse(xhr.responseText);

                if (posts.length === 0) {
                    if (!document.getElementById("no-posts-message")) {
                        postsContainer.innerHTML = '<div id="no-posts-message">No posts found.</div>';
                    }
                    return;
                }

                const noPostsMessage = document.getElementById("no-posts-message");
                if (noPostsMessage) {
                    postsContainer.removeChild(noPostsMessage);
                }

                posts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                
                // Track only new posts
                const newPosts = posts.filter(post => !existingPostIds.has(post.id));
                
                // Only show alert if we've already loaded posts before and there are genuinely new posts
                if (newPosts.length > 0 && existingPostIds.size > 0) {
                    showNewPostAlert(newPosts.length);
                    
                    // Add new posts to the beginning without removing existing ones
                    let fragment = document.createDocumentFragment();
                    newPosts.forEach(function (post) {
                        existingPostIds.add(post.id);
                        const postElement = createPostElement(post);
                        postElement.classList.add('new-post');
                        fragment.appendChild(postElement);
                    });
                    
                    // Insert at the beginning
                    if (postsContainer.firstChild) {
                        postsContainer.insertBefore(fragment, postsContainer.firstChild);
                    } else {
                        postsContainer.appendChild(fragment);
                    }
                    
                    // Highlight new posts briefly
                    setTimeout(() => {
                        document.querySelectorAll('.new-post').forEach(el => {
                            el.classList.remove('new-post');
                        });
                    }, 3000);
                } else if (existingPostIds.size === 0) {
                    // First load - add all posts
                    posts.forEach(function (post) {
                        existingPostIds.add(post.id);
                        const postElement = createPostElement(post);
                        postsContainer.appendChild(postElement);
                    });
                }
                
                // Update timestamp after successful refresh
                lastUpdateTime = new Date().getTime();
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
        
        // Only show alert if it's not already visible
        if (alertElement.style.display !== 'block') {
            let message = count === 1 ? '1 new post available' : `${count} new posts available`;
            alertElement.textContent = message;
            alertElement.style.display = 'block';
            
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, 5000);
        }
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

    // Update the post creation function to include timestamp
    function createPostElement(post) {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.dataset.postId = post.id;

        // Format the date for display
        const formattedDate = post.formatted_date || 
            new Date(post.created_at).toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

        postElement.innerHTML = `
            <img class="post__author-logo" 
                src="${post.profile_picture ? '../src/utils/getImage.php?file=' + post.profile_picture : '../assets/images/defaultProfilePic.png'}" 
                alt="Profile Picture" />
            <div class="post__main">
                <div class="post__header">
                    <div class="post__author-name">
                        ${post.username}
                    </div>
                    <div class="post__author-slug">
                        @${post.username}
                    </div>
                    <div class="post__publish-time">
                        ${post.time_display || ''}
                    </div>
                </div>
                <div class="post__content">
                    ${post.content}
                </div>
                <div class="post__timestamp">
                    ${formattedDate}
                </div>
            </div>
        `;

        return postElement;
    }

    // Initial load
    fetchAndDisplayPosts();
    
    // Poll for new posts with a reasonable interval
    setInterval(fetchAndDisplayPosts, 30000); // Poll every 30 seconds
});