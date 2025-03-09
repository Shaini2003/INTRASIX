<?php
// Edit Profile Page - edit_profile.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-end text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-3">IntraSix</div>
            <div class="list-group list-group-flush">
                <a href="main.php" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
                <a href="edit_profile.php" class="list-group-item list-group-item-action bg-dark text-white">Edit Profile</a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100 p-3">
            <!-- Top Navigation Bar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-danger">Logout</button>
                </div>
            </nav>
            
            <div class="container-fluid mt-4">
                <h4>Edit Profile</h4>
                
                <div class="card p-4">
                    <h5 class="card-header bg-primary text-white">Edit Your Profile</h5>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" value="Pankaj Sharma" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" value="admin@pictogram.com" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" value="14721427">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>