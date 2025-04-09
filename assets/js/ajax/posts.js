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
                                <span class="post__action-count">24</span>
                            </div>
                            <div class="post__action-button">
                                <img src="../assets/svg/heart.svg" class="post__action-icon" />
                                <span class="post__action-count">482</span>
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

    fetchAndDisplayPosts();
    setInterval(fetchAndDisplayPosts, 2000);
});