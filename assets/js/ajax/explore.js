// ...existing code...

function createPostHTML(post) {
    return `
        <div class="post" data-post-id="${post.id}">
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
                        ${post.time_display}
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
                </div>
                
                <div class="post__timestamp">
                    ${post.formatted_date}
                </div>
            </div>
        </div>
    `;
}

// ...existing code...