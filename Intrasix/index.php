<?php
session_start();
include 'includes/dbh.php'; // Include database connection
include 'includes/functions.inc.php';
include 'ajax.php';

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
	<!--ICONSCOUT CDN-->
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.6/css/unicons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>


<body>

	<div class="theme-layout">
		<div class="responsive-header">


			<div class="search-bar">
				<i class="uil uil-search"></i>
				<input type="search" placeholder="search" style="border: none;">
			</div>


		</div><!-- responsive header -->

		<div class="topbar stick">
			<div class="logo">
				<a title="" href="#"><img src="images/intrasix.png" alt="" width="70px" height="70px"></a>
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

					// ** Function to fetch grouped stories **
					function getGroupedStories($logged_user_id)
					{
						global $conn;

						if (!$conn) {
							die("❌ Error: MySQL connection is closed!");
						}

						// Fetch stories grouped by user
						$sql = "SELECT users.id as user_id, users.name, GROUP_CONCAT(stories.story_img ORDER BY stories.created_at DESC) AS story_imgs 
            FROM stories 
            JOIN users ON stories.user_id = users.id 
            GROUP BY users.id 
            ORDER BY MAX(stories.created_at) DESC";

						$result = $conn->query($sql);

						return $result;
					}

					// Fix: Start session only if it's not already active
					if (session_status() === PHP_SESSION_NONE) {
						session_start();
					}

					// ** Get logged-in user's ID from session **
					$logged_user_id = $_SESSION['id'] ?? null;

					if (!$logged_user_id) {
						die("❌ Error: User not logged in.");
					}

					// ** Fetch grouped stories **
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

					// ** Display Stories (One per User) **
					if ($stories_result && $stories_result->num_rows > 0) {
						while ($row = $stories_result->fetch_assoc()) {
							// Convert the concatenated story images into an array
							$story_images = explode(",", $row['story_imgs']);
							$first_story = htmlspecialchars($story_images[0]); // Show only the latest story as thumbnail

							echo '<div class="story" onclick="openUserStories(' . htmlspecialchars(json_encode($story_images)) . ', \'' . htmlspecialchars($row['name']) . '\')">
                <img src="' . $first_story . '" alt="Story">
                <div class="story-name">' . htmlspecialchars($row['name']) . ($row['user_id'] == $logged_user_id ? " (You)" : "") . '</div>
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
					<div class="story-controls">
						<button onclick="prevStory()">&#10094; Prev</button>
						<button onclick="nextStory()">Next &#10095;</button>
					</div>
				</div>

				<!-- CSS for Modal -->
				<style>
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

				<!-- JavaScript for Story Navigation -->
				<script>
					let storyImages = [];
					let currentStoryIndex = 0;

					function openUserStories(images, userName) {
						storyImages = images;
						currentStoryIndex = 0;
						showStory(userName);
					}

					function showStory(userName) {
						if (storyImages.length > 0) {
							document.getElementById("storyImage").src = storyImages[currentStoryIndex];
							document.getElementById("storyCaption").innerHTML = userName;
							document.getElementById("storyModal").style.display = "block";
						}
					}

					function closeStory() {
						document.getElementById("storyModal").style.display = "none";
					}

					function nextStory() {
						if (currentStoryIndex < storyImages.length - 1) {
							currentStoryIndex++;
							document.getElementById("storyImage").src = storyImages[currentStoryIndex];
						}
					}

					function prevStory() {
						if (currentStoryIndex > 0) {
							currentStoryIndex--;
							document.getElementById("storyImage").src = storyImages[currentStoryIndex];
						}
					}
				</script>

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
													<a href="inbox.html" title="">Inbox</a>
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
													<a href="timeline-friends.html" title="">friends</a>
												</li>
												<li>
													<i class="ti-image"></i>
													<a href="timeline-photos.html" title="">images</a>
												</li>
												<li>
													<i class="ti-video-camera"></i>
													<a href="timeline-videos.html" title="">videos</a>
												</li>
												<li>
													<i class="ti-comments-smiley"></i>
													<a href="messages.html" title="">Messages</a>
												</li>
												<li>
													<i class="ti-bell"></i>
													<a href="notifications.html" title="">Notifications</a>
												</li>

												<li>
													<i class="ti-power-off"></i>
													<a href="landing.html" title="">Logout</a>
												</li>
											</ul>
										</div><!-- Shortcuts -->

										<div class="widget stick-widget">
											<h4 class="widget-title">Friends Suggest</h4>
											<ul class="followers">
												<?php

												if (!isset($_SESSION['id'])) {
													echo "<li>Please log in to see follow suggestions.</li>";
												} else {
													// Fetch follow suggestions
													$follow_suggestions = filterFollowSuggestion(getFollowSuggestions());

													// Display users or a message if no suggestions are available
													if (!empty($follow_suggestions)) {
														foreach ($follow_suggestions as $suser) {
												?>
															<li>
																<figure>
																	<img src="<?php echo htmlspecialchars($suser['profile_pic'] ?? 'default.jpg'); ?>" alt="Profile Picture">
																</figure>
																<div class="friend-meta">
																	<h4>
																		<a href="'?u=<?= $suser['name'] ?>'<?= htmlspecialchars($suser['id']) ?>" title=""><?= htmlspecialchars($suser['name']) ?></a>
																	</h4>
																	<button class="btn btn-sm btn-primary followbtn" data-user-id='<?= htmlspecialchars($suser['id']) ?>'>Follow</button>
																</div>
															</li>
												<?php
														}
													} else {
														echo "<li>No follow suggestions available.</li>";
													}
												}
												?>
											</ul>
										</div>

										<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
										<script>
											// Follow user AJAX
											$(".followbtn").click(function() {
												var user_id_v = $(this).data('user-id');
												var button = this;
												$(button).attr('disabled', true);

												$.ajax({
													url: 'ajax.php?follow',
													method: 'POST',
													dataType: 'json',
													data: {
														user_id: user_id_v
													},
													success: function(response) {
														if (response.status) {
															$(button).html('<i class="bi bi-check-circle"></i> Followed');
														} else {
															$(button).attr('disabled', false);
															alert("Failed to follow. Try again.");
														}
													}
												});
											});
										</script>




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
																<a href="user_profile.php?id=<?php echo $post['user_id']; ?>" title="<?php echo htmlspecialchars($post['name']); ?>">
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
																		<form action="like_post.php" method="POST">
																			<input type="hidden" name="post_id" value="<?= $post['id']; ?>">
																			<button type="submit" class="like <?= userLikedPost($post['id'], $_SESSION['id']) ? 'liked' : ''; ?>">
																				<i class="ti-heart"></i>
																				<ins><?= getLikeCount($post['id']); ?></ins>
																			</button>
																		</form>
																	</li>
																	<script>
																		document.addEventListener("DOMContentLoaded", function() {
																			document.querySelectorAll(".like").forEach(button => {
																				button.addEventListener("click", function() {
																					this.classList.toggle("liked");
																				});
																			});
																		});
																	</script>


																	<li>
																		<!-- Click event triggers loading comments -->
																		<span class="comment-toggle" onclick="loadComments(<?= $post['id']; ?>)">
																			<i class="fa-regular fa-comment"></i><ins id="comment-count-<?= $post['id']; ?>">0</ins>
																		</span>
																	</li>
																</ul>
															</div>

															<!-- Comments will be loaded dynamically here -->
															<div class="comment-section" id="comments-section-<?= $post['id']; ?>"></div>

															<!-- Comment Form -->
															<form class="comment-form" data-post-id="<?= $post['id']; ?>">
																<input type="hidden" name="post_id" value="<?= $post['id']; ?>">
																<input type="text" class="comment-text" name="comment_text" placeholder="Write a comment...">
																<button type="submit">Comment</button>
															</form>

															<?php
															include 'includes/dbh.php';

															// Ensure user is logged in before allowing comments
															if (!isset($_SESSION['id'])) {
																echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
																exit;
															}

															// Handle the comment submission
															if ($_SERVER['REQUEST_METHOD'] === 'POST') {
																$user_id = $_SESSION['id']; // Get logged-in user ID
																$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
																$comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';

																// Validate input
																if (!$post_id || empty($comment_text)) {
																	echo json_encode(['status' => 'error', 'message' => 'Invalid comment or post ID.']);
																	exit;
																}

																// Prevent duplicate comments (Check if the exact same comment exists)
																$checkQuery = "SELECT id FROM comments WHERE post_id = ? AND user_id = ? AND comment_text = ?";
																$checkStmt = mysqli_prepare($conn, $checkQuery);
																mysqli_stmt_bind_param($checkStmt, "iis", $post_id, $user_id, $comment_text);
																mysqli_stmt_execute($checkStmt);
																mysqli_stmt_store_result($checkStmt);

																if (mysqli_stmt_num_rows($checkStmt) > 0) {
																	echo json_encode(['status' => 'error', 'message' => 'Duplicate comment detected.']);
																	exit;
																}

																// Insert new comment into database
																$query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
																$stmt = mysqli_prepare($conn, $query);
																mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $comment_text);
																mysqli_stmt_execute($stmt);

																echo json_encode(['status' => 'success']);
																exit;
															}
															?>

															<script>
																// Ensure script runs only after DOM is fully loaded
																document.addEventListener("DOMContentLoaded", function() {
																	document.querySelectorAll('.comment-form').forEach(form => {
																		form.addEventListener('submit', function(event) {
																			event.preventDefault(); // Prevent default form submission

																			let postId = this.getAttribute('data-post-id'); // Get post ID
																			let commentText = this.querySelector('.comment-text').value.trim(); // Get comment text

																			if (!commentText) {
																				alert("Comment cannot be empty"); // Validate input
																				return;
																			}

																			// Send comment data to the backend
																			fetch('add_comment.php', {
																					method: 'POST',
																					headers: {
																						'Content-Type': 'application/x-www-form-urlencoded'
																					},
																					body: `post_id=${postId}&comment_text=${encodeURIComponent(commentText)}`
																				})
																				.then(response => response.json()) // Parse JSON response
																				.then(data => {
																					if (data.status === 'success') {
																						loadComments(postId); // Reload comments after successful submission
																						this.querySelector('.comment-text').value = ''; // Clear input field
																					} else {
																						alert(data.message); // Show error message
																					}
																				});
																		});
																	});
																});

																// Function to load comments dynamically
																function loadComments(postId) {
																	var commentsSection = document.getElementById("comments-section-" + postId);
																	commentsSection.innerHTML = "Loading comments..."; // Show loading text

																	fetch('get_comments.php?post_id=' + postId)
																		.then(response => response.json()) // Parse JSON response
																		.then(comments => {
																			commentsSection.innerHTML = ""; // Clear previous comments
																			// Render each comment
																			commentsSection.innerHTML = comments.map(comment => `
                    <div class='comment'>
                        <img src='${comment.profile_pic}' alt='Profile'>
                        <div>
                            <strong>${comment.name}</strong>
                            <p>${comment.comment_text}</p>
                        </div>
                    </div>
                `).join('');
																			updateCommentCount(postId); // Update comment count
																		});
																}

																// Function to update the comment count
																function updateCommentCount(postId) {
																	fetch('get_comment_count.php?post_id=' + postId)
																		.then(response => response.json())
																		.then(data => {
																			document.getElementById("comment-count-" + postId).innerText = data.comment_count;
																		});
																}
															</script>

															<style>
																/* Styling for comment section */
																.comment-section {
																	margin-top: 10px;
																	padding: 10px;
																	border-top: 1px solid #ccc;
																}

																/* Styling for individual comments */
																.comment {
																	display: flex;
																	align-items: center;
																	margin-bottom: 10px;
																}

																/* Profile picture styling */
																.comment img {
																	width: 40px;
																	height: 40px;
																	border-radius: 50%;
																	margin-right: 10px;
																}

																/* Comment text container */
																.comment div {
																	background: #f1f1f1;
																	padding: 8px;
																	border-radius: 5px;
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


								if (!isset($_SESSION['id'])) {
									die("Session ID not set. Please log in.");
								}

								$user_id = $_SESSION['id'];

								// Function to get followers
								function getFollowers($user_id, $conn)
								{
									$stmt = $conn->prepare("
								SELECT u.id, u.name, u.profile_pic
								FROM follow_list f
								JOIN users u ON f.follower_id = u.id
								WHERE f.user_id = ?
								");
									$stmt->bind_param("i", $user_id);
									$stmt->execute();
									$result = $stmt->get_result();

									return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
								}

								$followers = getFollowers($user_id, $conn);
								?>

								<div class="col-lg-3">
									<aside class="sidebar static">
										<div class="widget friend-list stick-widget">
											<h4 class="widget-title">Followers</h4>
											<ul id="follower-list" class="friendz-list">
												<?php if (!empty($followers)) : ?>
													<?php foreach ($followers as $follower) : ?>
														<li>
															<figure>
																<img src="<?= htmlspecialchars(!empty($follower['profile_pic']) ? $follower['profile_pic'] : 'images/default.jpg') ?>" alt="Profile Picture">
																<span class="status f-online"></span>
															</figure>
															<div class="friendz-meta">
																<a href="profile.php?id=<?= htmlspecialchars($follower['id']) ?>">
																	<?= htmlspecialchars($follower['name']) ?>
																</a>
															</div>
														</li>
													<?php endforeach; ?>
												<?php else : ?>
													<li><strong>No followers yet.</strong></li>
												<?php endif; ?>
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