<?php
session_start();
include 'includes/dbh.php'; // Include database connection
include 'includes/functions.inc.php';


global $posts;
if (!isset($_SESSION['id'])) {
	header("Location: otp.php"); // Redirect to OTP page if not logged in
	exit();
}

$id = $_SESSION['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>Intrasix</title>
	<link rel="icon" href="images/wink.png" type="image/png" sizes="16x16">

	<link rel="stylesheet" href="css/main.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/color.css">
	<link rel="stylesheet" href="css/responsive.css">
	<link rel="stylesheet" href="styles.css">
	<!-- <link rel="stylesheet" href="colorstyle.php"> -->


	<!--ICONSCOUT CDN-->
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.6/css/unicons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>


<body>

	<div class="theme-layout">
		<div class="responsive-header">


			<div class="search-bar">
				<i class="uil uil-search"></i>
				<input type="search" id="searchInput" placeholder="Search by username" style="border: none;">
				<div id="searchResults" class="search-results"></div>
			</div>

			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script>
				$(document).ready(function() {
					$('#searchInput').on('input', function() {
						const query = $(this).val().trim();
						const $resultsContainer = $('#searchResults');

						if (query.length < 1) {
							$resultsContainer.hide();
							return;
						}

						$.ajax({
							url: 'search.php',
							method: 'GET',
							data: {
								q: query
							},
							dataType: 'json',
							success: function(data) {
								$resultsContainer.empty();
								if (data.length > 0) {
									data.forEach(user => {
										const resultItem = `
                                    <div class="result-item">
                                        <img src="${user.profile_pic || 'default.jpg'}" alt="Profile">
                                        <a href="profile.php?id=${user.id}">${user.username}</a>
                                    </div>
                                `;
										$resultsContainer.append(resultItem);
									});
									$resultsContainer.show();
								} else {
									$resultsContainer.hide();
								}
							},
							error: function(xhr, status, error) {
								console.error('AJAX Error:', status, error);
							}
						});
					});

					// Hide results when clicking outside
					$(document).on('click', function(event) {
						if (!$(event.target).closest('.search-bar').length) {
							$('#searchResults').hide();
						}
					});
				});
			</script>


		</div><!-- responsive header -->

		<div class="topbar stick">
			<div class="logo">
				<a title="" href="#"><img src="images/intrasix-logo.png" alt="" width="70px" height="70px"></a>
				<a title="" href="#"><img src="images/Name.png" alt="" width="70px" height="70px"></a>
			</div>

			<div class="top-area">
				<ul class="main-menu">


					<?php if (isset($_SESSION["id"])): ?>
						<li><a href="view_profile.php" title="View Profile"><?php echo $_SESSION['name']; ?></a></li>
						<li><a href="logout.php" title="Logout">Logout</a></li>
					<?php else: ?>
						<li><a href="login.php" title="Login">Login</a></li>
					<?php endif; ?>
				</ul>

				<ul>
					<li>
						<div class="search-bar">
							<i class="uil uil-search"></i>
							<input type="search" placeholder="search" style="border: none;">
						</div>
					</li>
				</ul>
				<ul class="setting-area">

					<li><a href="#" title="Home" data-ripple=""><i class="ti-home"></i></a></li>
					<li>
						<a href="#" title="Notification" data-ripple="">
							<i class="ti-bell"></i><span>20</span>
						</a>

						<div class="dropdowns">
							<span>4 New Notifications</span>
							<ul class="drops-menu">
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-1.jpg" alt="">
										<div class="mesg-meta">
											<h6>sarah Loren</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag green">New</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-2.jpg" alt="">
										<div class="mesg-meta">
											<h6>Jhon doe</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag red">Reply</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-3.jpg" alt="">
										<div class="mesg-meta">
											<h6>Andrew</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag blue">Unseen</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-4.jpg" alt="">
										<div class="mesg-meta">
											<h6>Tom cruse</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag">New</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-5.jpg" alt="">
										<div class="mesg-meta">
											<h6>Amy</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag">New</span>
								</li>
							</ul>
							<a href="notifications.php" title="" class="more-mesg">view more</a>
						</div>
					</li>
					<li>
						<a href="#" title="Messages" data-ripple=""><i class="ti-comment"></i><span>12</span></a>
						<div class="dropdowns">
							<span>5 New Messages</span>
							<ul class="drops-menu">
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-1.jpg" alt="">
										<div class="mesg-meta">
											<h6>sarah Loren</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag green">New</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-2.jpg" alt="">
										<div class="mesg-meta">
											<h6>Jhon doe</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag red">Reply</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-3.jpg" alt="">
										<div class="mesg-meta">
											<h6>Andrew</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag blue">Unseen</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-4.jpg" alt="">
										<div class="mesg-meta">
											<h6>Tom cruse</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag">New</span>
								</li>
								<li>
									<a href="notifications.html" title="">
										<img src="images/resources/thumb-5.jpg" alt="">
										<div class="mesg-meta">
											<h6>Amy</h6>
											<span>Hi, how r u dear ...?</span>
											<i>2 min ago</i>
										</div>
									</a>
									<span class="tag">New</span>
								</li>
							</ul>
							<a href="messages.html" title="" class="more-mesg">view more</a>
						</div>
					</li>


				</ul>

				<span class="ti-menu main-menu" data-ripple=""></span>
			</div>
		</div><!-- topbar -->

		<section>
			<div><?php
					include 'includes/dbh.php'; // Include database connection

					// ** Function to fetch all stories grouped by users (except logged-in user) **
					function getGroupedStories($logged_user_id)
					{
						global $conn;

						if (!$conn) {
							die("❌ Error: MySQL connection is closed!");
						}

						// Fetch stories grouped by user (excluding logged-in user)
						$sql = "SELECT users.id as user_id, users.name, 
                   GROUP_CONCAT(stories.story_img ORDER BY stories.created_at DESC) AS story_imgs 
            FROM stories 
            JOIN users ON stories.user_id = users.id 
            WHERE users.id != ?
            GROUP BY users.id 
            ORDER BY MAX(stories.created_at) DESC";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param("i", $logged_user_id);
						$stmt->execute();
						return $stmt->get_result();
					}

					// ** Function to fetch the logged-in user's stories **
					function getUserStories($logged_user_id)
					{
						global $conn;

						if (!$conn) {
							die("❌ Error: MySQL connection is closed!");
						}

						$sql = "SELECT id, story_img FROM stories WHERE user_id = ? ORDER BY created_at DESC";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("i", $logged_user_id);
						$stmt->execute();
						return $stmt->get_result();
					}

					// Start session if not started
					if (session_status() === PHP_SESSION_NONE) {
						session_start();
					}

					// ** Get logged-in user's ID from session **
					$logged_user_id = $_SESSION['id'] ?? null;

					if (!$logged_user_id) {
						die("❌ Error: User not logged in.");
					}

					// ** Fetch user's stories & other users' stories **
					$user_stories_result = getUserStories($logged_user_id);
					$stories_result = getGroupedStories($logged_user_id);

					// ** HTML Output **
					echo '<div class="story-container">';

					// ** Upload Story Button **
					echo '<div class="story upload-story">
        <form action="upload_story.php" method="post" enctype="multipart/form-data">
            <label for="storyUpload">
                <div class="upload-icon">+</div>
            </label>
            <input type="file" id="storyUpload" name="story_img" required onchange="this.form.submit();">
        </form>
        <div class="story-name">Upload</div>
      </div>';

					// ** Display Logged-in User's Stories (With Delete Option) **
					if ($user_stories_result->num_rows > 0) {
						$story_images = [];
						$story_ids = [];

						while ($row = $user_stories_result->fetch_assoc()) {
							$story_ids[] = $row['id'];
							$story_images[] = $row['story_img'];
						}

						echo '<div class="story" onclick="openUserStories(' . htmlspecialchars(json_encode($story_images)) . ', ' . htmlspecialchars(json_encode($story_ids)) . ', true)">
            <img src="' . htmlspecialchars($story_images[0]) . '" alt="Story">
            <div class="story-name">(Your Stories)</div>
          </div>';
					}

					// ** Display Other Users' Stories (Without Delete Option) **
					if ($stories_result && $stories_result->num_rows > 0) {
						while ($row = $stories_result->fetch_assoc()) {
							$story_images = explode(",", $row['story_imgs']);
							$first_story = htmlspecialchars($story_images[0]); // Show only the latest story as thumbnail

							echo '<div class="story" onclick="openUserStories(' . htmlspecialchars(json_encode($story_images)) . ', null, false)">
                <img src="' . $first_story . '" alt="Story">
                <div class="story-name">' . htmlspecialchars($row['name']) . '</div>
              </div>';
						}
					} else {
						echo "<p>⚠ No stories found.</p>";
					}

					echo '</div>'; // Close story-container div
					?>

				<!-- Story Modal -->
				<div id="storyModal" class="modal">
					<span class="close" onclick="closeStory()">&times;</span>
					<img class="modal-content" id="storyImage">
					<div id="storyCaption"></div>
					<form id="deleteStoryForm" action="delete_story.php" method="post">
						<input type="hidden" name="story_id" id="storyIdInput">
						<button type="submit" id="deleteBtn" class="delete-btn">🗑 Delete</button>
					</form>
					<div class="story-controls">
						<button onclick="prevStory()">&#10094; Prev</button>
						<button onclick="nextStory()">Next &#10095;</button>
					</div>
				</div>

				<!-- JavaScript for Story Navigation & Deletion -->
				<script>
					let storyImages = [];
					let storyIds = [];
					let currentStoryIndex = 0;
					let isUserStory = false;

					function openUserStories(images, ids, userStory) {
						storyImages = images;
						storyIds = ids;
						currentStoryIndex = 0;
						isUserStory = userStory;
						showStory();
					}

					function showStory() {
						if (storyImages.length > 0) {
							document.getElementById("storyImage").src = storyImages[currentStoryIndex];
							document.getElementById("storyModal").style.display = "block";

							if (isUserStory) {
								document.getElementById("deleteBtn").style.display = "block";
								document.getElementById("storyIdInput").value = storyIds[currentStoryIndex];
							} else {
								document.getElementById("deleteBtn").style.display = "none";
							}
						}
					}

					function closeStory() {
						document.getElementById("storyModal").style.display = "none";
					}

					function nextStory() {
						if (currentStoryIndex < storyImages.length - 1) {
							currentStoryIndex++;
							showStory();
						}
					}

					function prevStory() {
						if (currentStoryIndex > 0) {
							currentStoryIndex--;
							showStory();
						}
					}
				</script>

				<!-- CSS for Delete Button -->
				<style>
					.delete-btn {
						background: red;
						color: white;
						border: none;
						padding: 10px;
						cursor: pointer;
						border-radius: 5px;
						font-size: 14px;
						display: none;
						/* Hide by default */
						margin: 10px auto;
					}

					.modal {
						display: none;
						position: fixed;
						z-index: 1000;
						left: 0;
						top: 0;
						width: 100%;
						height: 100%;
						background-color: rgba(0, 0, 0, 0.8);
						text-align: center;
					}

					.modal-content {
						display: block;
						margin: auto;
						max-width: 50%;
						max-height: 100%;
						border-radius: 10px;
					}

					.close {
						position: absolute;
						top: 15px;
						right: 35px;
						color: white;
						font-size: 40px;
						font-weight: bold;
						cursor: pointer;
					}

					#storyCaption {
						text-align: center;
						color: white;
						font-size: 20px;
						margin-top: 10px;
					}

					.story-controls {
						position: absolute;
						width: 100%;
						top: 50%;
						display: flex;
						justify-content: space-between;
						transform: translateY(-50%);
					}

					.story-controls button {
						background: rgba(255, 255, 255, 0.5);
						border: none;
						padding: 10px 20px;
						font-size: 18px;
						cursor: pointer;
						border-radius: 5px;
					}
				</style>
			</div>

			<div class="gap gray-bg">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="row" id="page-contents">
								<div class="col-lg-3">
									<aside class="sidebar static">

										<div class="widget">
											<h4 class="widget-title">Shortcuts</h4>
											<ul class="naves">
												<li>
													<i class="ti-clipboard"></i>
													<a href="post.php" title="">Create Post</a>
												</li>
												<li>
													<i class="ti-mouse-alt"></i>
													<a href="inbox.php" title="">Inbox</a>
												</li>
												<li>
													<i class="ti-user"></i>
													<a href="view_profile.php" title="">View Profile</a>
												</li>
												<li>
													<i class="ti-user"></i>
													<a href="edit_profile.php" title="">Edit Profile</a>
												</li>
												<li>
													<i class="ti-user"></i>
													<a href="friends.php" title="">friends</a>
												</li>
												<li>
													<i class="ti-image"></i>
													<a href="timeline-photos.html" title="">images</a>
												</li>
												<li>
													<i class="ti-video-camera"></i>
													<a href="video.php" title="">videos</a>
												</li>
												<li>
													<i class="fa-solid fa-palette"></i>
													<a href="themes.php" title="">Themes</a>
												</li>
												<li>
													<i class="ti-bell"></i>
													<a href="notifications.html" title="">Notifications</a>
												</li>
												<li>
													<i class="ti-bell"></i>
													<a href="review.php" title="">Reviews</a>
												</li>

												<li>
													<i class="ti-power-off"></i>
													<a href="landing.html" title="">Logout</a>
												</li>
											</ul>
										</div><!-- Shortcuts -->

										<!-- HTML/PHP Widget Section (index.php or your main file) -->
										<div class="widget stick-widget">
											<h4 class="widget-title">Friends Suggest</h4>
											<ul class="followers" id="suggestionList">
												<?php
												$follow_suggestions = filterFollowSuggestion(getFollowSuggestions());

												if (empty($follow_suggestions)) {
													echo "<li>No suggestions available at the moment.</li>";
												} else {
													foreach ($follow_suggestions as $suser) {
												?>
														<li data-user-id="<?= htmlspecialchars($suser['id']) ?>">
															<figure>
																<img src="<?= htmlspecialchars($suser['profile_pic'] ?? 'default.jpg') ?>"
																	alt="Profile Picture">
															</figure>
															<div class="friend-meta">
																<h4>
																	<a href="?u=<?= htmlspecialchars($suser['name']) ?>"
																		title=""><?= htmlspecialchars($suser['name']) ?></a>
																</h4>
																<?php if (isset($_SESSION['id'])) {
																	$isFollowing = checkFollowStatus($suser['id']);
																?>
																	<button class="btn"
																		data-user-id="<?= htmlspecialchars($suser['id']) ?>"
																		<?= $isFollowing ? 'disabled' : '' ?>>
																		<?= $isFollowing ? 'Following' : 'Follow' ?>
																	</button>
																<?php } ?>
															</div>
														</li>
												<?php
													}
												}
												?>
											</ul>
										</div>

										<?php
										function getFollowSuggestions()
										{
											global $conn;

											$query = "SELECT id, name, profile_pic FROM users ORDER BY RAND() LIMIT 10";
											$stmt = $conn->prepare($query);
											$stmt->execute();
											$result = $stmt->get_result();
											return $result->fetch_all(MYSQLI_ASSOC);
										}

										function filterFollowSuggestion($list)
										{
											if (!isset($_SESSION['id'])) {
												return $list;
											}

											$filtered_list = [];
											$current_user = $_SESSION['id'];

											foreach ($list as $user) {
												if ($user['id'] != $current_user && !checkFollowStatus($user['id'])) {
													$filtered_list[] = $user;
												}
											}
											return $filtered_list;
										}

										function checkFollowStatus($user_id)
										{
											global $conn;

											if (!isset($_SESSION['id'])) {
												return false;
											}

											$current_user = $_SESSION['id'];

											$stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM follow_list 
        WHERE follower_id = ? AND following_id = ?
    ");
											$stmt->bind_param("ii", $current_user, $user_id);
											$stmt->execute();
											$result = $stmt->get_result()->fetch_assoc();

											return $result['count'] > 0;
										}

										function followUser($user_id)
										{
											global $conn;

											if (!isset($_SESSION['id'])) {
												return false;
											}

											$current_user = $_SESSION['id'];

											if (checkFollowStatus($user_id) || $current_user == $user_id) {
												return false;
											}

											$stmt = $conn->prepare("
        INSERT INTO follow_list (follower_id, following_id, created_at) 
        VALUES (?, ?, NOW())
    ");
											$stmt->bind_param("ii", $current_user, $user_id);
											return $stmt->execute();
										}
										?>

										<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
										<script>
											document.addEventListener('DOMContentLoaded', function() {
												const followButtons = document.querySelectorAll('.follow-btn');

												followButtons.forEach(button => {
													button.addEventListener('click', function() {
														const userId = this.getAttribute('data-user-id');
														const button = this;

														fetch('follow.php', {
																method: 'POST',
																headers: {
																	'Content-Type': 'application/x-www-form-urlencoded',
																},
																body: 'following_id=' + encodeURIComponent(userId)
															})
															.then(response => {
																if (!response.ok) {
																	throw new Error('Network response was not ok: ' + response.statusText);
																}
																return response.json();
															})
															.then(data => {
																if (data.success) {
																	button.textContent = 'Following';
																	button.disabled = true;
																	button.classList.remove('btn-primary');
																	button.classList.add('btn-secondary');
																} else {
																	alert(data.message);
																}
															})
															.catch(error => {
																console.error('Error:', error);
																alert('An error occurred while following the user: ' + error.message);
															});
													});
												});
											});
										</script>
										<style>
											.btn {
												background-color: #9b59b6;
												color: white;
											}

											.follow-btn {
												transition: all 0.3s ease;
											}

											.follow-btn:disabled {
												opacity: 0.7;
												cursor: not-allowed;
											}

											.widget.stick-widget {
												background: #fff;
												padding: 20px;
												border-radius: 5px;
											}

											.followers li {
												display: flex;
												align-items: center;
												padding: 10px 0;
												border-bottom: 1px solid #eee;
											}

											.followers li figure {
												margin-right: 10px;
											}

											.followers li img {
												width: 40px;
												height: 40px;
												border-radius: 50%;
												object-fit: cover;
											}

											.friend-meta h4 {
												margin: 0;
												font-size: 14px;
											}
										</style>



									</aside>
								</div><!-- sidebar -->

								<?php
								include 'includes/dbh.php'; // Ensure the database connection is included

								function getUser($user_id)
								{
									global $conn;
									if (!$conn) {
										die("Error: MySQL connection is closed!");
									}

									$query = "SELECT * FROM users WHERE id = ?";
									$stmt = mysqli_prepare($conn, $query);
									mysqli_stmt_bind_param($stmt, "i", $user_id);
									mysqli_stmt_execute($stmt);
									$result = mysqli_stmt_get_result($stmt);
									return mysqli_fetch_assoc($result);
								}

								function getPosts()
								{
									global $conn;
									if (!$conn) {
										die("Error: MySQL connection is closed!");
									}

									$query = "SELECT posts.id, posts.user_id, posts.post_img, posts.post_text, posts.created_at, users.name, users.profile_pic 
                                              FROM posts 
                                              JOIN users ON users.id = posts.user_id 
                                              ORDER BY posts.created_at DESC";

									$result = mysqli_query($conn, $query);
									return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
								}

								$posts = getPosts();
								?>

								<div class="col-lg-6">
									<div class="loadMore">
										<?php foreach ($posts as $post): ?>
											<div class="central-meta item">
												<div class="user-post">
													<div class="friend-info">
														<figure>
															<img src="<?php echo htmlspecialchars($post['profile_pic'] ?? 'default.jpg'); ?>" alt="Profile Picture">
														</figure>
														<div class="friend-name">
															<ins>
																<a href="profile.php?id=<?php echo $post['user_id']; ?>" title="<?php echo htmlspecialchars($post['name']); ?>">
																	<?php echo htmlspecialchars($post['name']); ?>
																</a>
															</ins>
															<span>published: <?php echo date('F d, Y H:i', strtotime($post['created_at'])); ?></span>
														</div>
														<div class="post-meta">
															<?php if (!empty($post['post_img'])): ?>
																<img src="images/posts/<?php echo htmlspecialchars($post['post_img']); ?>" alt="Post Image">
															<?php endif; ?>
															<p><?php echo nl2br(htmlspecialchars($post['post_text'])); ?></p>
															<div class="we-video-info">
																<ul>
																	<li>
																		<!-- Like button form with onsubmit event -->
																		<form class="like-form" action="like_post.php" method="POST" onsubmit="return handleLikeSubmit(event, <?= $post['id']; ?>)">
																			<input type="hidden" name="post_id" value="<?= $post['id']; ?>">
																			<button type="submit" class="like <?= userLikedPost($post['id'], $_SESSION['id']) ? 'liked' : ''; ?>">
																				<i class="ti-heart"></i>
																				<ins id="like-count-<?= $post['id']; ?>"><?= getLikeCount($post['id']); ?></ins>
																			</button>
																		</form>
																	</li>
																	<script>
																		// Handle like form submission without AJAX
																		function handleLikeSubmit(event, postId) {
																			event.preventDefault(); // Prevent default form submission and page refresh

																			const form = event.target;
																			const likeButton = form.querySelector('.like');
																			const likeCountElement = document.getElementById(`like-count-${postId}`);
																			const isLiked = likeButton.classList.contains('liked');
																			const userId = <?= isset($_SESSION['id']) ? $_SESSION['id'] : 'null' ?>; // Ensure user is logged in

																			if (!userId) {
																				alert("Please log in to like posts.");
																				return false;
																			}

																			// Store current scroll position
																			const scrollPosition = window.scrollY || window.pageYOffset;

																			// Submit the form programmatically
																			fetch(form.action, {
																					method: form.method,
																					body: new FormData(form)
																				})
																				.then(response => response.text()) // Get the server response (HTML)
																				.then(html => {
																					// Parse the response to update the like count and state
																					const parser = new DOMParser();
																					const doc = parser.parseFromString(html, 'text/html');
																					const newLikeCount = doc.getElementById(`like-count-${postId}`)?.innerText || likeCountElement.innerText;
																					const newIsLiked = doc.querySelector(`.like[data-post-id="${postId}"]`)?.classList.contains('liked') || false;

																					// Update UI
																					likeCountElement.innerText = newLikeCount;
																					likeButton.classList.toggle('liked', newIsLiked);

																					// Restore scroll position after the page reloads
																					window.scrollTo(0, scrollPosition);
																				})
																				.catch(error => {
																					console.error('Error:', error);
																					alert('An error occurred while liking the post.');
																				});

																			return false; // Prevent default form submission
																		}

																		// Function to update like count and state after page load (optional for consistency)
																		document.addEventListener("DOMContentLoaded", function() {
																			const urlParams = new URLSearchParams(window.location.search);
																			if (urlParams.get('post_id')) {
																				const postId = urlParams.get('post_id');
																				updateLikeUI(postId);
																			}
																		});

																		function updateLikeUI(postId) {
																			const likeButton = document.querySelector(`.like-form[data-post-id="${postId}"] .like`);
																			const likeCountElement = document.getElementById(`like-count-${postId}`);

																			if (likeButton && likeCountElement) {
																				likeCountElement.innerText = getLikeCount(postId); // Update with server-side count
																				likeButton.classList.toggle('liked', userLikedPost(postId, <?= isset($_SESSION['id']) ? $_SESSION['id'] : 'null' ?>));
																			}
																		}
																	</script>

																	<li>
																		<!-- Click event triggers loading comments -->
																		<span class="comment-toggle" onclick="toggleComments(<?= $post['id']; ?>)">
																			<i class="fa-regular fa-comment" style="color: #9b59b6;"></i>
																			<ins id="comment-count-<?= $post['id']; ?>">0</ins>
																		</span>
																	</li>
																</ul>
															</div>

															<!-- Comments will be loaded dynamically here -->
															<div class="comment-section" id="comments-section-<?= $post['id']; ?>" style="display: none;"></div>

															<!-- Comment Form -->
															<?php if (isset($_SESSION['id'])): ?>
																<form class="comment-form" data-post-id="<?= $post['id']; ?>">
																	<input type="hidden" name="post_id" value="<?= $post['id']; ?>">
																	<input type="text" class="comment-text" name="comment_text" placeholder="Write a comment...">
																	<button type="submit" class="comment-submit">Comment</button>
																</form>
															<?php else: ?>
																<p>Please log in to comment.</p>
															<?php endif; ?>

															<script>
																document.addEventListener("DOMContentLoaded", function() {
																	// Load initial comment count for all posts
																	updateCommentCount(<?= $post['id']; ?>);

																	// Handle comment form submission
																	document.querySelectorAll('.comment-form').forEach(form => {
																		form.addEventListener('submit', function(event) {
																			event.preventDefault();

																			const postId = this.getAttribute('data-post-id');
																			const commentText = this.querySelector('.comment-text').value.trim();
																			const submitButton = this.querySelector('.comment-submit');

																			if (!commentText) {
																				alert("Comment cannot be empty");
																				return;
																			}

																			if (form.classList.contains('submitting')) return;
																			form.classList.add('submitting');
																			submitButton.disabled = true;

																			fetch('add_comment.php', {
																					method: 'POST',
																					headers: {
																						'Content-Type': 'application/x-www-form-urlencoded'
																					},
																					body: `post_id=${postId}&comment_text=${encodeURIComponent(commentText)}`
																				})
																				.then(response => response.json())
																				.then(data => {
																					if (data.status === 'success') {
																						loadComments(postId); // Reload comments after submission
																						this.querySelector('.comment-text').value = '';
																						document.getElementById(`comments-section-${postId}`).style.display = 'block'; // Show comments
																						updateCommentCount(postId); // Update comment count
																					} else {
																						alert(data.message);
																					}
																				})
																				.catch(error => {
																					console.error('Error:', error);
																					alert('An error occurred.');
																				})
																				.finally(() => {
																					form.classList.remove('submitting');
																					submitButton.disabled = false;
																				});
																		});
																	});
																});

																// Toggle comments visibility and load them if not already loaded
																function toggleComments(postId) {
																	const commentsSection = document.getElementById(`comments-section-${postId}`);
																	if (commentsSection.style.display === 'none' || commentsSection.innerHTML === '') {
																		loadComments(postId); // Load comments if not already loaded
																		commentsSection.style.display = 'block'; // Show comments
																	} else {
																		commentsSection.style.display = 'none'; // Hide comments
																	}
																}

																// Load comments dynamically
																function loadComments(postId) {
																	const commentsSection = document.getElementById(`comments-section-${postId}`);
																	commentsSection.innerHTML = "Loading comments...";

																	fetch(`get_comments.php?post_id=${postId}`)
																		.then(response => response.json())
																		.then(comments => {
																			if (comments.length === 0) {
																				commentsSection.innerHTML = "<p>No comments yet.</p>";
																			} else {
																				commentsSection.innerHTML = comments.map(comment => `
                            <div class="comment">
                                <img src="${comment.profile_pic}" alt="Profile">
                                <div>
                                    <strong>${comment.name}</strong>
                                    <p>${comment.comment_text}</p>
                                </div>
                            </div>
                        `).join('');
																			}
																			updateCommentCount(postId); // Update comment count after loading comments
																		})
																		.catch(error => {
																			console.error('Error:', error);
																			commentsSection.innerHTML = "Failed to load comments.";
																		});
																}

																// Update comment count
																function updateCommentCount(postId) {
																	fetch(`get_comment_count.php?post_id=${postId}`)
																		.then(response => response.json())
																		.then(data => {
																			document.getElementById(`comment-count-${postId}`).innerText = data.comment_count;
																		})
																		.catch(error => {
																			console.error('Error updating comment count:', error);
																		});
																}
															</script>

															<style>
																.container {
																	max-width: 1200px;
																	margin: 0 auto;
																	padding: 20px;
																}

																.comment-section {
																	margin-top: 10px;
																	padding: 10px;
																	border-top: 1px solid #ccc;
																	width: 100%;
																	box-sizing: border-box;
																}

																.comment {
																	display: flex;
																	align-items: flex-start;
																	/* Align items at the top for better stacking on small screens */
																	margin-bottom: 10px;
																	width: 100%;
																}

																.comment img {
																	width: 40px;
																	height: 40px;
																	border-radius: 50%;
																	margin-right: 10px;
																	flex-shrink: 0;
																	/* Prevent image from shrinking */
																}

																.comment div {
																	background: #f1f1f1;
																	padding: 8px;
																	border-radius: 5px;
																	flex-grow: 1;
																	/* Allow text to grow and fill available space */
																	word-wrap: break-word;
																	/* Ensure long text wraps */
																	max-width: calc(100% - 50px);
																	/* Adjust for image and margin */
																}

																.comment-form {
																	display: flex;
																	gap: 10px;
																	margin-top: 10px;
																	width: 100%;
																	flex-wrap: wrap;
																	/* Allow wrapping on small screens */
																}

																.comment-text {
																	flex-grow: 1;
																	padding: 8px;
																	border: 1px solid #ccc;
																	border-radius: 4px;
																	min-width: 200px;
																	/* Minimum width for input on small screens */
																}

																.comment-submit {
																	padding: 8px 16px;

																	color: white;
																	border: none;
																	border-radius: 4px;
																	cursor: pointer;
																}



																.comment-toggle {
																	cursor: pointer;
																	display: flex;
																	align-items: center;
																}

																.comment-toggle i {
																	margin-right: 5px;
																	color: #6f42c1;
																}

																.comment-toggle ins {
																	text-decoration: none;
																	font-weight: bold;
																}

																/* Media Queries for Responsiveness */
																@media screen and (max-width: 768px) {
																	.comment {
																		flex-direction: column;
																		/* Stack elements vertically on smaller screens */
																		align-items: flex-start;
																	}

																	.comment img {
																		margin-bottom: 10px;
																		/* Space below image when stacked */
																		margin-right: 0;
																		/* Remove right margin */
																	}

																	.comment div {
																		max-width: 100%;
																		/* Full width for text on small screens */
																	}

																	.comment-form {
																		flex-direction: column;
																		/* Stack form elements vertically */
																	}

																	.comment-text {
																		width: 100%;
																		/* Full width on small screens */
																		min-width: 0;
																		/* Override min-width for full responsiveness */
																	}

																	.comment-submit {
																		width: 100%;
																		/* Full width button on small screens */
																	}
																}

																@media screen and (max-width: 480px) {
																	.container {
																		padding: 10px;
																		/* Reduce padding on very small screens */
																	}

																	.comment img {
																		width: 30px;
																		/* Smaller profile images on tiny screens */
																		height: 30px;
																	}

																	.comment-text {
																		font-size: 14px;
																		/* Smaller font for input on tiny screens */
																	}

																	.comment-submit {
																		font-size: 14px;
																		/* Smaller font for button on tiny screens */
																	}
																}
															</style>


														</div>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
								<?php
								function getFollowers()
								{
									global $conn;

									if (!isset($_SESSION['id'])) {
										return []; // Return empty array if not logged in
									}

									$current_user = $_SESSION['id'];

									$query = "
        SELECT u.id, u.name, u.profile_pic
        FROM users u
        INNER JOIN follow_list fl ON u.id = fl.follower_id
        WHERE fl.following_id = ?
        ORDER BY fl.created_at DESC
    ";

									$stmt = $conn->prepare($query);
									$stmt->bind_param("i", $current_user);
									$stmt->execute();
									$result = $stmt->get_result();

									return $result->fetch_all(MYSQLI_ASSOC);
								}
								?>


								<div class="col-lg-3">
									<aside class="sidebar static">
										<div class="widget friend-list stick-widget">
											<h4 class="widget-title">Followers</h4>
											<ul id="follower-list" class="friendz-list">
												<?php
												// Fetch followers
												$followers = getFollowers();

												if (!empty($followers)) {
													foreach ($followers as $follower) {
												?>
														<li>
															<figure>
																<img src="<?= htmlspecialchars(!empty($follower['profile_pic']) ? $follower['profile_pic'] : 'images/default.jpg') ?>"
																	alt="Profile Picture">
																<span class="status f-online"></span>
															</figure>
															<div class="friendz-meta">
																<a href="profile.php?id=<?= htmlspecialchars($follower['id']) ?>">
																	<?= htmlspecialchars($follower['name']) ?>
																</a>
															</div>
														</li>
													<?php
													}
												} else {
													?>
													<li><strong>No followers yet.</strong></li>
												<?php
												}
												?>
											</ul>
										</div>
									</aside>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>



	</div>
	<div class="side-panel">
		<h4 class="panel-title"> Settings</h4>
		<form method="post">
			<div class="setting-row">
				<span>use night mode</span>
				<input type="checkbox" id="nightmode1" />
				<label for="nightmode1" data-on-label="ON" data-off-label="OFF"></label>
			</div>
		</form>


	</div><!-- side panel -->

	<script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script src="js/main.min.js"></script>
	<script src="js/script.js"></script>
	<script src="js/map-init.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8c55_YHLvDHGACkQscgbGLtLRdxBDCfI"></script>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="app.js"></script>

</body>

</html>