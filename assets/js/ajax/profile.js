document.addEventListener('DOMContentLoaded', () => {
    console.log('Fetching user profile data...');

    const profileContainer = document.getElementById('profile-container');
    profileContainer.innerHTML = `
        <div class="loading-message" style="text-align: center; padding: 20px;">
            <h2>Loading profile...</h2>
            <p>Please wait while we fetch your profile data.</p>
        </div>
    `;

    fetch('../src/controllers/UserController.php')
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
        .then(userData => {
            if (userData.error) {
                console.error('API error:', userData.error);
                throw new Error(userData.error);
            }

            console.log('Profile data loaded successfully:', userData);

            const bannerImage = '../assets/images/kobeBannerHorizontal.jpg';

            const profileImg = userData.profile_picture ? 
            `${window.location.origin}/cosc360/src/utils/getImage.php?file=${userData.profile_picture}` : 
            '../assets/images/defaultProfilePic.png';

            console.log('Profile image:', profileImg);

            profileContainer.innerHTML = `
                <div class="profile__banner">
                    <img src="${bannerImage}" alt="Banner Image" class="profile__banner-image" />
                    <img src="${profileImg}" alt="Profile Image" class="profile__profile-image" />
                </div>
                <div class="profile-panel">
                <div class="profile-panel__block">
                  <div class="profile-panel__info">
                      <div class="profile-panel__heading">${userData.first_name} ${userData.last_name}</div>
                      <div class="profile-panel__username">@${userData.username}</div>
                  </div>
                  <a href="settings.php" class="profile-panel__edit">EDIT</a>
                </div>
            
                <div class="profile-panel__stats">
                    <div class="profile-panel__stats-item"><span>${userData.followers_count}</span> FOLLOWERS</div>
                    <div class="profile-panel__stats-item"><span>${userData.following_count}</span> FOLLOWING</div>
                    <div class="profile-panel__stats-item"><span>${userData.posts_count}</span> POSTS</div>
                </div>
                <div class="profile-panel__about">
                    About:
                </div>
                <div class="profile-panel__about-text">
                ${userData.bio ? userData.bio : 'No bio available...'}
                </div>
                <div class="profile-panel__communities">
                    <div class="profile-panel__communities-heading">
                        Top Communities:
                    </div>
                    <div class="profile-panel__communities-logos">
                        <img src="../assets/images/nbaLogo.png" alt="NBA Logo" />
                        <img src="../assets/images/nhlLogo.png" alt="NHL Logo" />
                    </div>
                </div>
            </div>
                
                <div id="user-posts">
                    <div class="loading-posts">Loading posts...</div>
                </div>
            `;

            return fetch('../src/controllers/UserPostsController.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error fetching posts! Status: ${response.status}`);
                    }
                    return response.text().then(text => {
                        console.log('Raw posts response:', text);

                        try {
                            if (!text.trim()) {
                                return [];
                            }

                            if (text.trim().indexOf('{') !== 0 && text.trim().indexOf('[') !== 0) {
                                throw new Error(`Invalid JSON response: ${text}`);
                            }

                            return JSON.parse(text);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            throw new Error(`Invalid JSON response: ${text}`);
                        }
                    });
                });
        })
        .then(posts => {
            console.log('User posts loaded:', posts);

            const userPostsContainer = document.getElementById('user-posts');

            if (!posts || posts.length === 0) {
                userPostsContainer.innerHTML = `
                    <div class="no-posts">No posts yet</div>
                `;
                return;
            }

            let postsHTML = '';

            posts.forEach(post => {
                postsHTML += `
                <div class="post">
                    <img class="post__author-logo" src="${typeof getImageUrl === 'function' ? getImageUrl(post.profile_picture) : `${window.location.origin}/cosc360/src/utils/getImage.php?file=${post.profile_picture}`}" />
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
                                <span class="post__action-count">${post.comment_count}</span>
                            </div>
                            <div class="post__action-button">
                                <img src="../assets/svg/heart.svg" class="post__action-icon" />
                                <span class="post__action-count">${post.like_count}</span>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            });

            userPostsContainer.innerHTML = postsHTML;
        })
        .catch(error => {
            console.error('Error loading profile:', error);
        });
});
