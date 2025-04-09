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
            placeholder="Search..."
          />
        <img 
            src="../assets/svg/search.svg" 
            class="header__search-icon" 
            alt="Search"
        />
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
      <div id="posts-container"></div>
      </div>

      <!-- Right sidebar -->
      <div class="layout__right-sidebar-container">
        <div class="layout__right-sidebar">
          <div class="who-to-follow">
            <div class="who-to-follow__block">
              <div class="who-to-follow__heading">
                Who to follow
              </div>
            </div>
            
            <div class="who-to-follow__block">
              <img
                class="who-to-follow__author-logo"
                src="../assets/images/profile-image-1.jpg"
              />
              <div class="who-to-follow__content">
                <div>
                  <div class="who-to-follow__author-name">
                    Sports Dude 23
                  </div>
                  <div class="who-to-follow__author-slug">
                    @messilover23
                  </div>
                </div>
                <div class="who-to-follow__button">
                  +
                </div>
              </div>
            </div>
            <div class="who-to-follow__block">
              <img
                class="who-to-follow__author-logo"
                src="../assets/images/profile-image-2.jpg"
              />
              <div class="who-to-follow__content">
                <div>
                  <div class="who-to-follow__author-name">
                    Connor McDavid
                  </div>
                  <div class="who-to-follow__author-slug">
                    @connormcdavid
                  </div>
                </div>
                <div class="who-to-follow__button">
                  +
                </div>
              </div>
            </div>

            <div class="who-to-follow__block">
              <img
                class="who-to-follow__author-logo"
                src="../assets/images/profile-image-3.jpg"
              />
              <div class="who-to-follow__content">
                <div>
                  <div class="who-to-follow__author-name">
                    LaMelo Ball
                  </div>
                  <div class="who-to-follow__author-slug">
                    @melo
                  </div>
                </div>
                <div class="who-to-follow__button">
                  +
                </div>
              </div>
            </div>
          </div>
          
          <div class="trends-for-you">
            <div class="trends-for-you__block">
              <div class="trends-for-you__heading">
                Trending
              </div>
            </div>
            <div class="trends-for-you__block">
              <div class="trends-for-you__meta-information">
                NBA Trending
              </div>
              <div class="trends-for-you__trend-name">
                #LeBron
              </div>
              <div class="trends-for-you__meta-information">
                23k posts
              </div>
            </div>
            <div class="trends-for-you__block">
              <div class="trends-for-you__meta-information">
                NHL Trending
              </div>
              <div class="trends-for-you__trend-name">
                #CanadaVsUSA
              </div>
              <div class="trends-for-you__meta-information">
                43k posts
              </div>
            </div>
            <div class="trends-for-you__block">
              <div class="trends-for-you__meta-information">
                NFL Trending
              </div>
              <div class="trends-for-you__trend-name">
                #eagles
              </div>
              <div class="trends-for-you__meta-information">
                12k posts
              </div>
            </div>
          </div>
          <div class="sports-scores">
            <div class="sports-scores__block">
              <div class="sports-scores__heading">
                Live Scores
              </div>
            </div>
            
            <div class="sports-scores__block">
              <div class="sports-scores__league">NBA</div>
              <div class="sports-scores__game">
                <div class="sports-scores__team">
                  <span class="sports-scores__team-name">LAL</span>
                  <span class="sports-scores__team-score">112</span>
                </div>
                <div class="sports-scores__team">
                  <span class="sports-scores__team-name">GSW</span>
                  <span class="sports-scores__team-score">104</span>
                </div>
              </div>
            </div>

            <div class="sports-scores__block">
              <div class="sports-scores__league">NHL</div>
              <div class="sports-scores__game">
                <div class="sports-scores__team">
                  <span class="sports-scores__team-name">TOR</span>
                  <span class="sports-scores__team-score">3</span>
                </div>
                <div class="sports-scores__team">
                  <span class="sports-scores__team-name">MTL</span>
                  <span class="sports-scores__team-score">2</span>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile nav without logo -->
    <?php
      include_once('../assets/components/mobileNav.php');
    ?>
    <script src="../assets/js/postSomething.js"></script>
    <script src="../assets/js/ajax/posts.js"></script>
    <script src="../assets/js/ajax/addPost.js"></script>
  </body>
</html>
