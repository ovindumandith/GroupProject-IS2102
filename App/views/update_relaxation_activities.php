<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin')) {
    header('Location: login.php');
    exit();
}

require_once '../models/ViewRelaxationActivityModel.php';
require_once '../controller/ViewRelaxationActivityController.php';

// Initialize model and controller
$model = new ViewRelaxationActivityModel();
$controller = new ViewRelaxationActivityController($model);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateActivity();
    exit();
}

// Get activity data
$activityId = $_GET['id'] ?? null;
$activity = $activityId ? $model->getActivityById($activityId) : null;

if (!$activity) {
    $_SESSION['error'] = "Activity not found";
    header("Location: relaxation_activities.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Activity | RelaxU</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/relaxation_activities.css" />
    <link rel="stylesheet" href="../../assets/css/add_relaxation_activities.css" type="text/css" />
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <!-- Your existing header code -->
    </header>

    <!-- Display Error Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Content Section -->
    <div class="content">
        <h1>Update Relaxation Activity</h1>
        
        <form method="post" id="updateform" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($activity['id']) ?>">
            <input type="hidden" name="existing_image_url" value="<?= htmlspecialchars($activity['image_url']) ?>">

            <label for="activity_name">Activity Title:</label>
            <input type="text" id="activity_name" name="activity_name" 
                   value="<?= htmlspecialchars($activity['activity_name']) ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= 
                htmlspecialchars($activity['description']) ?></textarea>

            <label for="playlist_url">Source:</label>
            <input type="text" id="playlist_url" name="playlist_url" 
                   value="<?= htmlspecialchars($activity['playlist_url']) ?>" required>

            <label for="image_url">Image:</label>
            <div>
                <?php if (!empty($activity['image_url'])): ?>
                    <img src="./uploads/<?= htmlspecialchars($activity['image_url']) ?>" 
                         class="image-preview" alt="Current Image">
                    <span class="current-image-label">Current: <?= htmlspecialchars($activity['image_url']) ?></span>
                <?php endif; ?>
                <input type="file" id="image_url" name="image_url" class="file-input" accept="image/*">
                <img id="newImagePreview" class="new-image-preview" src="#" alt="New Image Preview">
            </div>

            <label>Recommended Stress Level:</label>
            <div class="radio-group">
                <label for="low">
                    <input type="radio" value="low" id="low" name="stress_level" 
                        <?= (isset($activity['stress_level']) && $activity['stress_level'] === 'low' ? 'checked' : '') ?>>
                    Low
                </label>
                <label for="moderate">
                    <input type="radio" value="moderate" id="moderate" name="stress_level"
                        <?= (isset($activity['stress_level']) && $activity['stress_level'] === 'moderate' ? 'checked' : '') ?>>
                    Moderate
                </label>
                <label for="high">
                    <input type="radio" value="high" id="high" name="stress_level"
                        <?= (isset($activity['stress_level']) && $activity['stress_level'] === 'high' ? 'checked' : '') ?>>
                    High
                </label>
            </div>

            <input type="submit" name="submit" value="Update Activity">
        </form>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <!-- Your existing footer code -->
    </footer>
            
     

    <script src="../../assets/js/update_relaxation_activities.js"></script>
</body>
</html>