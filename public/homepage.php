<?php
session_start();
require_once('../config/config.php');
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
        <?php
        if(checkIfLoggedIn()) {
            $userProfilePic = '../assets/images/defaultProfilePic.png';
            if (isset($_SESSION['user_id'])) {
                $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $userProfilePic = $row['profile_picture'] ? '../src/utils/getImage.php?file=' . $row['profile_picture'] : '../assets/images/defaultProfilePic.png';
                }
            }
        ?>
          <form class="post-something" method="POST" action="../src/controllers/AddPostController.php">
            <img class="post-something__author-logo" src="<?php echo $userProfilePic; ?>" alt="Author Logo" />
            <div class="post-something__content">
              <textarea 
                class="post-something__input" 
                placeholder="What's happening in sports?"
                maxlength="280"
                name="content"
              ></textarea>
              <div class="post-something__tags">
                <div class="post-something__tags-container">
                  <button type="button" class="tag-button" data-value="football">Football</button>
                  <button type="button" class="tag-button" data-value="basketball">Basketball</button>
                  <button type="button" class="tag-button" data-value="soccer">Soccer</button>
                  <button type="button" class="tag-button" data-value="tennis">Tennis</button>
                  <button type="button" class="tag-button" data-value="baseball">Baseball</button>
                  <button type="button" class="tag-button" data-value="hockey">Hockey</button>
                </div>
                <input type="hidden" name="tags[]" id="selected-tags" value="">
              </div>
              <div class="post-something__actions">
                <span class="post-something__char-count">280</span>
                <button type="submit" class="post-something__button">Post</button>
              </div>
            </div>
          </form>
        <?php
        } else {
        ?>
        <div class="post-something__login-container">
          <p class="post-something__login-message">You must be logged in to post something.</p>
          <button class="post-something__login-button" onclick="window.location.href='login.php'">Login</button>
          </div>
        <?php
        }
        ?>
        
      <!-- posts container -->
      <!-- Post template (will be cloned by JavaScript) -->
      <template id="post-template">
        <div class="post">
          <img class="post__author-logo" src="" alt="Profile Picture" />
          <div class="post__main">
            <div class="post__header">
              <div class="post__author-name"></div>
              <div class="post__author-slug"></div>
              <div class="post__publish-time"></div>
            </div>
            <div class="post__content"></div>
            <div class="post__actions">
              <div class="post__action-button">
                <img src="../assets/svg/comment.svg" class="post__action-icon" />
                <span class="post__action-count">0</span>
              </div>
              <div class="post__action-button like-button">
                <img src="../assets/svg/heart.svg" class="post__action-icon" />
                <span class="post__action-count">0</span>
              </div>
            </div>
            <div class="post__timestamp"></div>
          </div>
        </div>
      </template>

      <div id="posts-container"></div>
    </div>

    <!-- Right sidebar -->
    <?php
    include_once('../assets/components/rightSideBar.php');
    ?>

  <!-- Mobile nav without logo -->
  <?php
  include_once('../assets/components/mobileNav.php');
  ?>
  <script src="../assets/js/postSomething.js"></script>
  <script src="../assets/js/ajax/posts.js"></script>
  <script src="../assets/js/ajax/addPost.js"></script>
  <script>
    // Load posts when the page loads
    document.addEventListener('DOMContentLoaded', function() {
      loadPosts();
    });
    
    function loadPosts() {
      fetch('../src/controllers/PostsController.php')
        .then(response => response.json())
        .then(posts => {
          displayPosts(posts);
        })
        .catch(error => console.error('Error loading posts:', error));
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