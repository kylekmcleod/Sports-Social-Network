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
    <?php
    include_once('../assets/components/rightSideBar.php');
    ?>
  </div>

  <!-- Mobile nav without logo -->
  <?php
  include_once('../assets/components/mobileNav.php');
  ?>

  <script src="../assets/js/postSomething.js"></script>
  <script src="../assets/js/ajax/addPost.js"></script>
  <script src="../assets/js/explorePage.js"></script>
</body>
</html>