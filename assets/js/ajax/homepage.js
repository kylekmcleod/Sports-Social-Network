// ...existing code...

function createPostHTML(post) {
    // Format the date
    const postDate = new Date(post.created_at);
    const formattedDate = postDate.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    return `
        <div class="post" data-post-id="${post.post_id}">
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
                
                <div class="post__actions">
                    <div class="post__action-button">
                        <img src="../assets/svg/comment.svg" class="post__action-icon" />
                        <span class="post__action-count">${post.comment_count || 0}</span>
                    </div>
                    <div class="post__action-button">
                        <img src="../assets/svg/heart.svg" class="post__action-icon" />
                        <span class="post__action-count">${post.like_count || 0}</span>
                    </div>
                </div>
                
                <div class="post__timestamp">
                    ${formattedDate}
                </div>
            </div>
        </div>
    `;
}

// ...existing code...