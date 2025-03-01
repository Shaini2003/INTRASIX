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
			<div>
			<?php

$logged_user_id = $_SESSION['id'];
$sql = "SELECT stories.story_img, stories.created_at, users.name 
FROM stories 
JOIN users ON stories.user_id = users.id 
ORDER BY stories.created_at DESC";

echo '<div class="story-container">';
echo '<div class="story upload-story">
        <form action="upload_story.php" method="post" enctype="multipart/form-data">
            <label for="storyUpload">
                <div class="upload-icon">+</div>
            </label>
            <input type="file" id="storyUpload" name="story_img" required onchange="this.form.submit();">
        </form>
        <div class="story-name">Upload</div>
      </div>';
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					echo '<div class="story" style="text-align: center;">
                <img src="' . $row['story_img'] . '" alt="Story" style="width: 70px; height: 70px; border-radius: 50%; border: 3px solid white; object-fit: cover; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
                <div class="story-name" style="margin-top: 5px; font-size: 14px; color: #333;">' . $row['name'] . '</div>
              </div>';
				}
			} else {
				echo "<p>No stories found.</p>";
			}
			?>

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
																	<li><span class="views"><i class="fa fa-eye"></i> <ins>1.2k</ins></span></li>
																	<li><span class="comment"><i class="fa fa-comments-o"></i> <ins>52</ins></span></li>
																	<li><span class="like"><i class="ti-heart"></i> <ins>2.2k</ins></span></li>
																	<li><span class="dislike"><i class="ti-heart-broken"></i> <ins>200</ins></span></li>
																	<li class="social-media">
																		<div class="menu">
																			<div class="btn trigger"><i class="fa fa-share-alt"></i></div>
																			<div class="rotater">
																				<div class="btn btn-icon"><a href="#"><i class="fa fa-facebook"></i></a></div>
																			</div>
																			<div class="rotater">
																				<div class="btn btn-icon"><a href="#"><i class="fa fa-twitter"></i></a></div>
																			</div>
																			<div class="rotater">
																				<div class="btn btn-icon"><a href="#"><i class="fa fa-instagram"></i></a></div>
																			</div>
																		</div>
																	</li>
																</ul>
															</div>
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