<?php
require_once('../src/controllers/GetPostWithComments.php');
require_once('../src/controllers/trendingController.php');
$trendingTags = getTrendingTags();

session_start();

$post_id = $_GET['id'] ?? null;
if (!$post_id) {
  header('Location: homepage.php');
  exit;
}

$data = getPostWithComments($post_id);
$post = $data['post'];
$comments = $data['comments'];

if (!$post) {
  header('Location: homepage.php');
  exit;
}

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
          placeholder="Search..." />
        <img
          src="../assets/svg/search.svg"
          class="header__search-icon"
          alt="Search" />
      </div>
    </div>
  </header>

  <div class="layout">
    <?php include_once('../assets/components/leftSideBar.php'); ?>

    <!-- Main content -->
    <div class="layout__main">
      <!-- Original Post -->
      <div class="post">
        <img class="post__author-logo"
          src="<?= $post['profile_picture'] ? '../src/utils/getImage.php?file=' . $post['profile_picture'] : '../assets/images/defaultProfilePic.png' ?>" />
        <div class="post__main">
          <div class="post__header">
            <div class="post__author-name"><?= htmlspecialchars($post['username']) ?></div>
            <div class="post__author-slug">@<?= strtolower(str_replace(' ', '_', $post['username'])) ?></div>
            <div class="post__publish-time"><?= formatTimeAgo($post['created_at']) ?></div>
          </div>
          <div class="post__content"><?= htmlspecialchars($post['content']) ?></div>
        </div>
      </div>

      <!-- Comment Form -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="post-something">
            <img class="post-something__author-logo" src="<?= $userProfilePic ?>" />
            <div class="post-something__content">
                <form action="../src/controllers/AddCommentController.php" method="POST" class="post-something__form">
                    <textarea 
                        class="post-something__input" 
                        placeholder="Post a reply..."
                        name="content"
                        maxlength="280"
                        required
                    ></textarea>
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <div class="post-something__actions">
                        <span class="post-something__char-count">280</span>
                        <button type="submit" class="post-something__button">Reply</button>
                    </div>
                </form>
            </div>
        </div>
      <?php endif; ?>

      <!-- Comments -->
      <div id="comments-container">
        <?php foreach ($comments as $comment): ?>
          <div class="post">
            <img class="post__author-logo"
              src="<?= $comment['profile_picture'] ? '../src/utils/getImage.php?file=' . $comment['profile_picture'] : '../assets/images/defaultProfilePic.png' ?>" />
            <div class="post__main">
              <div class="post__header">
                <div class="post__author-name"><?= htmlspecialchars($comment['username']) ?></div>
                <div class="post__publish-time"><?= formatTimeAgo($comment['created_at']) ?></div>
              </div>
              <div class="post__content"><?= htmlspecialchars($comment['content']) ?></div>
            </div>
          </div>
          <div class="trends-for-you">
            <div class="trends-for-you__block">
              <div class="trends-for-you__heading">
                Trending
              </div>
            </div>
            <?php foreach ($trendingTags as $index => $tag): ?>
            <div class="trends-for-you__block">
              <div class="trends-for-you__meta-information">
                <?php echo $index === 0 ? 'Most Popular' : "#" . ($index + 1) . " Trending"; ?>
              </div>
              <div class="trends-for-you__trend-name">
                #<?php echo htmlspecialchars($tag['tag']); ?>
              </div>
              <div class="trends-for-you__meta-information">
                <?php echo htmlspecialchars($tag['count']); ?> posts
              </div>
            </div>
            <?php endforeach; ?>
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

    <!-- Right sidebar -->
    <?php
    include_once('../assets/components/rightSideBar.php');
    ?>

    <?php include_once('../assets/components/mobileNav.php'); ?>

    <script>
      document.querySelector('.post-something__input')?.addEventListener('input', function() {
        const remaining = 280 - this.value.length;
        const counter = document.querySelector('.post-something__char-count');
        counter.textContent = remaining;
        counter.classList.toggle('post-something__char-count--limit', remaining < 0);
      });
    </script>
    <script src="../assets/js/trending.js"></script>
  </body>
</html>