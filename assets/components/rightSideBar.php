<?php
require_once(__DIR__ . '/../../src/controllers/trendingController.php');
$trendingTags = getTrendingTags();
?>
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
                    src="../assets/images/profile-image-1.jpg" />
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
                    src="../assets/images/profile-image-2.jpg" />
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
                    src="../assets/images/profile-image-3.jpg" />
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
<script src="../assets/js/trending.js"></script>