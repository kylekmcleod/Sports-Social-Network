<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sport Page</title>
  <link rel="stylesheet" href="../assets/css/globals.css" />
  <link rel="stylesheet" href="../assets/css/homepage/brand.css" />
  <link rel="stylesheet" href="../assets/css/homepage/layout.css" />
  <link rel="stylesheet" href="../assets/css/nav/sidebar-menu.css" />
  <link rel="stylesheet" href="../assets/css/homepage/trends-for-you.css" />
  <link rel="stylesheet" href="../assets/css/homepage/post.css" />
  <link rel="stylesheet" href="../assets/css/homepage/postSomething.css" />
  <link rel="stylesheet" href="../assets/css/homepage/who-to-follow.css" />
  <link rel="stylesheet" href="../assets/css/homepage/header.css" />
  <link rel="stylesheet" href="../assets/css/homepage/sports-scores.css" />
  <link rel="stylesheet" href="../assets/css/explore/explore.css" />
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="header__content">
      <div class="header__search-container">
        <input
          type="text"
          class="header__search-input"
          placeholder="Search..." />
        <img
          src="../assets/svg/search.svg"
          class="header__search-icon"
          alt="Search" />
      </div>
    </div>
  </header>

  <div class="layout">
    <?php
    include_once('../assets/components/leftSideBar.php');
    ?>

    <!-- Main content -->
    <div class="layout__main">
      <div class="explore-tags">
        <h2 class="explore-tags__heading">Explore posts by tag</h2>
        <div class="explore-tags__container">
          <button type="button" class="tag-filter-button" data-value="football">Football</button>
          <button type="button" class="tag-filter-button" data-value="basketball">Basketball</button>
          <button type="button" class="tag-filter-button" data-value="soccer">Soccer</button>
          <button type="button" class="tag-filter-button" data-value="tennis">Tennis</button>
          <button type="button" class="tag-filter-button" data-value="baseball">Baseball</button>
          <button type="button" class="tag-filter-button" data-value="hockey">Hockey</button>
        </div>
      </div>
      <div id="posts-container"></div>
    </div>

    <!-- Right sidebar -->
    <div class="layout__right-sidebar-container">
      <?php
      include_once('../assets/components/rightSideBar.php');
      ?>
    </div>
  </div>

  <!-- Mobile nav without logo -->
  <?php
  include_once('../assets/components/mobileNav.php');
  ?>

  <script src="../assets/js/postSomething.js"></script>
  <script src="../assets/js/ajax/addPost.js"></script>
  <script src="../assets/js/explorePage.js"></script>
  <script src="../assets/js/ajax/explore.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tag filtering
        const tagButtons = document.querySelectorAll('.tag-filter-button');
        tagButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tag = this.dataset.value;
                loadPostsByTag(tag);
                
                // Update active button state
                tagButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Load all posts initially
        loadPosts();
        
        // Post click event delegation for navigation
        document.getElementById('posts-container').addEventListener('click', function(e) {
            const postElement = e.target.closest('.post');
            if (postElement) {
                const postId = postElement.dataset.postId;
                if (postId) {
                    window.location.href = `post.php?id=${postId}`;
                }
            }
        });
    });
    
    function loadPosts() {
        fetch('../src/controllers/PostsController.php')
            .then(response => response.json())
            .then(posts => {
                displayPosts(posts);
            })
            .catch(error => console.error('Error loading posts:', error));
    }
    
    function loadPostsByTag(tag) {
        fetch(`../src/controllers/PostsController.php?tags=${tag}`)
            .then(response => response.json())
            .then(posts => {
                displayPosts(posts);
            })
            .catch(error => console.error('Error loading posts by tag:', error));
    }
    
    function displayPosts(posts) {
        const container = document.getElementById('posts-container');
        if (posts.length === 0) {
            container.innerHTML = '<div class="no-posts">No posts found</div>';
            return;
        }
        
        let postsHTML = '';
        posts.forEach(post => {
            postsHTML += createPostHTML(post);
        });
        
        container.innerHTML = postsHTML;
    }
    
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
  </script>
</body>
</html>