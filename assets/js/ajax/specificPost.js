document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    
    if (!postId) {
        console.error("No post ID provided in URL");
        displayErrorMessage("No post ID provided");
        return;
    }

    function formatTimeDisplay(timestamp) {
        const now = new Date();
        const postTime = new Date(timestamp);
        const timeDiff = Math.floor((now - postTime) / 1000);
        
        if (timeDiff < 60) {
            return `${timeDiff}s`;
        } else if (timeDiff < 3600) {
            return `${Math.floor(timeDiff / 60)}m`;
        } else if (timeDiff < 86400) {
            return `${Math.floor(timeDiff / 3600)}h`;
        } else {
            const options = { month: 'short', day: 'numeric' };
            return postTime.toLocaleDateString('en-US', options);
        }
    }

    function displayErrorMessage(message) {
        const mainContent = document.querySelector('.layout__main');
        mainContent.innerHTML = `
            <div class="error-message">
                <h2>${message}</h2>
                <p>The post you're looking for couldn't be found.</p>
                <a href="homepage.php" class="error-link">Return to homepage</a>
            </div>
        `;
    }
    fetch(`../src/controllers/GetSpecificPost.php?id=${postId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.post) {
                throw new Error('Post data not found');
            }
            updatePage(data.post);
        })
        .catch(error => {
            console.error('Error fetching post:', error);
            displayErrorMessage("Error loading post");
        });

    function updatePage(post) {
        document.title = `${post.username}'s Post - Sport Page`;
        const mainContent = document.querySelector('.layout__main');
        let newContent = `
            <div class="post">
                <img class="post__author-logo" src="${post.profile_picture ? '../src/utils/getImage.php?file=' + post.profile_picture : '../assets/images/defaultProfilePic.png'}" />
                <div class="post__main">
                    <div class="post__header">
                        <div class="post__author-name">
                            ${post.username}
                        </div>
                        <div class="post__author-slug">
                            @${post.username.toLowerCase().replace(/\s+/g, '_')}
                        </div>
                        <div class="post__publish-time">
                            ${formatTimeDisplay(post.created_at)}
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
            </div>`;

        newContent += `
            <div class="post-something">
                <img class="post-something__author-logo" src="../assets/images/defaultProfilePic.png" />
                <div class="post-something__content">
                    <form id="replyForm" class="post-something__form">
                        <textarea 
                            class="post-something__input" 
                            placeholder="Post a reply..."
                            name="content"
                            maxlength="280"
                            required
                        ></textarea>
                        <input type="hidden" name="post_id" value="${post.post_id}">
                        <div class="post-something__actions">
                            <span class="post-something__char-count">280</span>
                            <button type="submit" class="post-something__button">Reply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="comments-container"></div>`;

        mainContent.innerHTML = newContent;
        loadComments(post.post_id);
        setupReplyForm();
    }

    function loadComments(postId) {
        fetch(`../src/controllers/GetCommentsController.php?post_id=${postId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayComments(data.comments);
                }
            })
            .catch(error => console.error('Error loading comments:', error));
    }

    function displayComments(comments) {
        const commentsContainer = document.getElementById('comments-container');
        commentsContainer.innerHTML = comments.map(comment => `
            <div class="post">
                <img class="post__author-logo" src="${comment.profile_picture ? '../src/utils/getImage.php?file=' + comment.profile_picture : '../assets/images/defaultProfilePic.png'}" />
                <div class="post__main">
                    <div class="post__header">
                        <div class="post__author-name">${comment.username}</div>
                        <div class="post__publish-time">${formatTimeDisplay(comment.created_at)}</div>
                    </div>
                    <div class="post__content">${comment.content}</div>
                </div>
            </div>
        `).join('');
    }

    function setupReplyForm() {
        const form = document.getElementById('replyForm');
        const textarea = form.querySelector('.post-something__input');
        const charCount = form.querySelector('.post-something__char-count');

        textarea.addEventListener('input', function() {
            const remaining = 280 - this.value.length;
            charCount.textContent = remaining;
            
            if (remaining < 0) {
                charCount.classList.add('post-something__char-count--limit');
            } else {
                charCount.classList.remove('post-something__char-count--limit');
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../src/controllers/AddCommentController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    this.reset();
                    loadComments(formData.get('post_id'));
                    charCount.textContent = '280';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add reply');
            });
        });
    }
});