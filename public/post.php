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
    <link rel="stylesheet" href="../assets/css/post/postReply.css" />
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
        
        <!-- posts -->
        <div class="post">
          <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
          <div class="post__main">
            <div class="post__header">
              <div class="post__author-name">
                Sports Dude 23
              </div>
              <div class="post__author-slug">
                @messilover23
              </div>
              <div class="post__publish-time">
                38m
              </div>
            </div>
            <div class="post__content">
              Messi is the GOAT. Mind-boggling stats aside, just watch him play on the field. Its like he plays the game watching from above. Moves on the pitch going past multiple players as if they are not there.
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
        </div>

        <div class="post-something">
            <img class="post-something__author-logo" src="../assets/images/profile-image-1.jpg" />
            <div class="post-something__content">
              <textarea 
                class="post-something__input" 
                placeholder="Post a reply..."
                maxlength="280"
              ></textarea>
              <div class="post-something__actions">
                <span class="post-something__char-count">280</span>
                <button class="post-something__button">Reply</button>
              </div>
            </div>
          </div>

          <div class="post">
            <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
            <div class="post__main">
              <div class="post__header">
                <div class="post__author-name">
                  Sports Dude 23
                </div>
                <div class="post__author-slug">
                  @messilover23
                </div>
                <div class="post__publish-time">
                  38m
                </div>
              </div>
              <div class="post__content">
                100% agree. Messilover23 out...
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
          </div>
          <div class="post">
            <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
            <div class="post__main">
              <div class="post__header">
                <div class="post__author-name">
                  Sports Dude 23
                </div>
                <div class="post__author-slug">
                  @messilover23
                </div>
                <div class="post__publish-time">
                  38m
                </div>
              </div>
              <div class="post__content">
                100% agree. Messilover23 out...
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
          </div>
          <div class="post">
            <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
            <div class="post__main">
              <div class="post__header">
                <div class="post__author-name">
                  Sports Dude 23
                </div>
                <div class="post__author-slug">
                  @messilover23
                </div>
                <div class="post__publish-time">
                  38m
                </div>
              </div>
              <div class="post__content">
                100% agree. Messilover23 out...
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
          </div>
          <div class="post">
            <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
            <div class="post__main">
              <div class="post__header">
                <div class="post__author-name">
                  Sports Dude 23
                </div>
                <div class="post__author-slug">
                  @messilover23
                </div>
                <div class="post__publish-time">
                  38m
                </div>
              </div>
              <div class="post__content">
                100% agree. Messilover23 out...
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
          </div>
          <div class="post">
            <img class="post__author-logo" src="../assets/images/profile-image-1.jpg" />
            <div class="post__main">
              <div class="post__header">
                <div class="post__author-name">
                  Sports Dude 23
                  
                </div>
                <div class="post__author-slug">
                  @messilover23
                </div>
                <div class="post__publish-time">
                  38m
                </div>
              </div>
              <div class="post__content">
                100% agree. Messilover23 out...
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
          </div>

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
    <nav class="mobile-nav">
      <div class="sidebar-menu">
        <div class="sidebar-menu__item sidebar-menu__item--active">
          <img src="../assets/svg/home.svg" class="sidebar-menu__item-icon" />
        </div>
        <div class="sidebar-menu__item">
          <img src="../assets/svg/explore.svg" class="sidebar-menu__item-icon" />
        </div>
        <div class="sidebar-menu__item">
          <img src="../assets/svg/notifications.svg" class="sidebar-menu__item-icon" />
        </div>
        <div class="sidebar-menu__item">
          <img src="../assets/svg/profile.svg" class="sidebar-menu__item-icon" />
        </div>
      </div>
    </nav>
    <script src="../assets/js/postSomething.js"></script>
  </body>
</html>
