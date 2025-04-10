<?php
include_once('../src/controllers/auth.php');
redirectIfNotLoggedIn();
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
  <link rel="stylesheet" href="../assets/css/settings/setting.css" />
  <link rel="stylesheet" href="../assets/css/profile/profile.css" />
  <script src="../assets/js/ajax/profile.js"></script>
  <script>
    function getImageUrl(imagePath) {
      return `../src/utils/getImage.php?file=${imagePath}`;
    }
  </script>
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
      <div id="profile-container">
        <!-- Profile data will be dynamically injected here -->
      </div>
    </div>

    <!-- Right sidebar -->
    <?php
    include_once('../assets/components/rightSideBar.php');
    ?>

    <!-- Mobile nav without logo -->
    <?php
    include_once('../assets/components/mobileNav.php');
    ?>
  </div>
  <script>
    window.hasTextarea = false;
  </script>
  <script src="../assets/js/postSomething.js"></script>
</body>
</html>