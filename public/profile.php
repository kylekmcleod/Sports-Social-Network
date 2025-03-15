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
    <link rel="stylesheet" href="../assets/css/settings/setting.css" />
    <link rel="stylesheet" href="../assets/css/profile/profile.css" />
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
            <div class="profile__banner">
                <img src="../assets/images/kobeBannerHorizontal.jpg" alt="Banner Image" class="profile__banner-image" />
                <img src="../assets/images/profile-image-4.jpg" alt="Profile Image" class="profile__profile-image" />
            </div>
            <div class="profile-panel">
                <div class="profile-panel__block">
                  <div class="profile-panel__info">
                      <div class="profile-panel__heading">Kevin Durant</div>
                      <div class="profile-panel__username">@easyMoneySniper</div>
                  </div>
                  <a href="settings.php" class="profile-panel__edit">EDIT</a>
                </div>
            
                <div class="profile-panel__stats">
                    <div class="profile-panel__stats-item"><span>230</span> FOLLOWERS</div>
                    <div class="profile-panel__stats-item"><span>180</span> FOLLOWING</div>
                    <div class="profile-panel__stats-item"><span>8</span> POSTS</div>
                </div>
                <div class="profile-panel__about">
                    About:
                </div>
                <div class="profile-panel__about-text">
                    #35 Phoenix Suns, Soon to be #7 for Minnesota Timberwolves
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

            <div class="post">
                <img class="post__author-logo" src="../assets/images/profile-image-4.jpg" />
                <div class="post__main">
                  <div class="post__header">
                    <div class="post__author-name">
                      Kevin Durant
                    </div>
                    <div class="post__author-slug">
                      @easyMoneySniper
                    </div>
                    <div class="post__publish-time">
                      38m
                    </div>
                  </div>
                  <div class="post__content">
                    My bad you guys Cade is going to be the face of the league for sure!
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
                <img class="post__author-logo" src="../assets/images/profile-image-4.jpg" />
                <div class="post__main">
                  <div class="post__header">
                    <div class="post__author-name">
                      Kevin Durant
                    </div>
                    <div class="post__author-slug">
                      @easyMoneySniper
                    </div>
                    <div class="post__publish-time">
                      55m
                    </div>
                  </div>

                  <div class="post__content">
                    Stephon Castle is the future of the NBA, mark my words
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
                <img class="post__author-logo" src="../assets/images/profile-image-4.jpg" />
                <div class="post__main">
                  <div class="post__header">
                    <div class="post__author-name">
                      Kevin Durant
                    </div>
                    <div class="post__author-slug">
                      @easyMoneySniper
                    </div>
                    <div class="post__publish-time">
                      2h 35m
                    </div>
                  </div>
                  <div class="post__content">
                    Whew! thank god Canada won tonight, I was worried there for a second #noMoTarrifs
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
                <img class="post__author-logo" src="../assets/images/profile-image-4.jpg" />
                <div class="post__main">
                  <div class="post__header">
                    <div class="post__author-name">
                      Kevin Durant
                    </div>
                    <div class="post__author-slug">
                      @easyMoneySniper
                    </div>
                    <div class="post__publish-time">
                      4h 21m
                    </div>
                  </div>
                  <div class="post__content">
                    Heard rumours people are saying Bronny could beat me 1v1, can't be waisting my time on small fries #easyMoney
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
                <img class="post__author-logo" src="../assets/images/profile-image-4.jpg" />
                <div class="post__main">
                  <div class="post__header">
                    <div class="post__author-name">
                      Kevin Durant
                    </div>
                    <div class="post__author-slug">
                      @easyMoneySniper
                    </div>
                    <div class="post__publish-time">
                      6h 12m
                    </div>
                  </div>
                  <div class="post__content">
                    I should probably put the phone down and start training soon, don't want to end up like PodcastP #LOL
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
    <?php
      include_once('../assets/components/mobileNav.php');
    ?>
    <script src="../assets/js/postSomething.js"></script>
  </body>
</html>