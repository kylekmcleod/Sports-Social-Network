<?php
include_once('../src/controllers/auth.php');
include_once(__DIR__ . '/../src/controllers/settingsController.php');
redirectIfNotLoggedIn();

$userData = getUserData();

$profileImage = "../assets/images/profile-image-4.jpg";
$bannerImage = "../assets/images/kobeBannerHorizontal.jpg";

if ($userData && !isset($userData['error'])) {
    if (isset($userData['profile_image']) && !empty($userData['profile_image'])) {
        $profileImage = "../" . $userData['profile_image'];
    }
    
    if (isset($userData['banner_image']) && !empty($userData['banner_image'])) {
        $bannerImage = "../" . $userData['banner_image'];
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Sport Page</title>
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
    <script>
      const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
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
      <?php include_once('../assets/components/leftSideBar.php'); ?>
        
      <!-- Main content -->
      <div class="layout__main">
        <h1 class="settings-panel__header">Settings</h1>
        
        <div class="settings__banner">
          <img src="<?php echo htmlspecialchars($bannerImage); ?>" alt="Banner Image" class="settings__banner-image" />
          <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="settings__profile-image" />
          <img src="../assets/svg/editImage.svg" alt="Edit Banner" class="settings__edit-banner" />
        </div>

        <div class="settings-panel">
          <div class="settings-panel__block" data-field="first_name">
              <div class="settings-panel__content">
                <div class="settings-panel__label">First Name</div>
                <div class="settings-panel__meta" id="user-first-name"><?php echo htmlspecialchars($userData['first_name'] ?? ''); ?></div>
              </div>
              <div class="settings-panel__edit" onclick="editField('first_name')">Edit</div>
          </div>

          <div class="settings-panel__block" data-field="last_name">
              <div class="settings-panel__content">
                <div class="settings-panel__label">Last Name</div>
                <div class="settings-panel__meta" id="user-last-name"><?php echo htmlspecialchars($userData['last_name'] ?? ''); ?></div>
              </div>
              <div class="settings-panel__edit" onclick="editField('last_name')">Edit</div>
          </div>
          <div class="settings-panel__block" data-field="username">
              <div class="settings-panel__content">
                <div class="settings-panel__label">Username</div>
                <div class="settings-panel__meta" id="user-username"><?php echo htmlspecialchars($userData['username'] ?? ''); ?></div>
              </div>
              <div class="settings-panel__edit" onclick="editField('username')">Edit</div>
          </div>

          <div class="settings-panel__block" data-field="email">
              <div class="settings-panel__content">
                <div class="settings-panel__label">Email</div>
                <div class="settings-panel__meta" id="user-email"><?php echo htmlspecialchars($userData['email'] ?? ''); ?></div>
              </div>
              <div class="settings-panel__edit" onclick="editField('email')">Edit</div>
          </div>

          <div class="settings-panel__block" data-field="about">
              <div class="settings-panel__content">
                <div class="settings-panel__label">About me</div>
                <div class="settings-panel__meta" id="user-about"><?php echo htmlspecialchars($userData['about'] ?? ''); ?></div>
              </div>
              <div class="settings-panel__edit" onclick="editField('about')">Edit</div>
          </div>
          
          <div class="settings-panel__status-message"></div>
        </div>
      </div>

      <script>
        function editField(field) {
          const block = document.querySelector(`.settings-panel__block[data-field="${field}"]`);
          const metaDiv = block.querySelector(`.settings-panel__meta`);
          const editBtn = block.querySelector(`.settings-panel__edit`);
          
          if (editBtn.textContent === 'Save') {
            const newValue = metaDiv.querySelector('input, textarea').value;
            saveField(field, newValue, metaDiv, editBtn);
          } else {
            const currentValue = metaDiv.textContent.trim();
            
            let inputElement;
            if (field === 'about') {
              inputElement = document.createElement('textarea');
              inputElement.style.width = '100%';
              inputElement.style.minHeight = '100px';
            } else {
              inputElement = document.createElement('input');
              inputElement.type = 'text';
              inputElement.style.width = '100%';
            }
            
            inputElement.value = currentValue;
            inputElement.id = `edit-${field}`;
            
            metaDiv.textContent = '';
            metaDiv.appendChild(inputElement);
            
            editBtn.textContent = 'Save';
            
            inputElement.focus();
            
            if (field !== 'about') {
              inputElement.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                  saveField(field, inputElement.value, metaDiv, editBtn);
                }
              });
            }
          }
        }
        
        function saveField(field, newValue, metaDiv, editBtn) {
          const statusMsg = document.querySelector('.settings-panel__status-message');
          statusMsg.textContent = 'Saving...';
          statusMsg.style.color = '#888';

          const formData = new FormData();
          formData.append('action', 'update_' + field);
          formData.append('value', newValue);

          fetch('../src/controllers/settingsController.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error(`Server returned ${response.status}: ${response.statusText}`);
            }
            return response.json(); 
          })
          .then(data => {
            console.log('Server response:', data); 

            if (data.success) {
              metaDiv.textContent = newValue;
              editBtn.textContent = 'Edit';
              statusMsg.textContent = `${field.replace('_', ' ')} updated successfully!`;
              statusMsg.style.color = 'green';

              setTimeout(() => { statusMsg.textContent = ''; }, 3000);
            } else {
              statusMsg.textContent = `Error: ${data.error || 'Unknown error occurred'}`;
              statusMsg.style.color = 'red';
            }
          })
          .catch(error => {
            console.error('Error updating field:', error); // Debugging: Log the error
            statusMsg.textContent = `An error occurred: ${error.message}`;
            statusMsg.style.color = 'red';
          });
        }
      </script>

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

     <!-- Mobile nav without logo -->
     <?php
      include_once('../assets/components/mobileNav.php');
    ?>
    <script src="../assets/js/postSomething.js"></script>
    <script src="../assets/js/editprofile.js"></script>
  </body>
</html>