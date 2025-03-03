<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturers</title>
    <link rel="stylesheet" href="../../assets/css/viewlecturer_hous.css"> 
</head>
<body>

    <h2 class="page-title">Lecturers</h2>

    <?php if (!empty($lecturers) && is_array($lecturers)): ?>
        <div class="lecturer-grid">
            <?php foreach ($lecturers as $lecturer): ?>
                <div class="lecturer-card">
                    <img src="../../public/uploads/<?php echo !empty($lecturer['profile_img']) ? htmlspecialchars($lecturer['profile_img']) : 'default.png'; ?>" 
                         alt="Profile Image" class="lecturer-img">
                    
                    <h3><?php echo htmlspecialchars($lecturer['name']); ?></h3>
                    <p><strong>Position:</strong> <?php echo htmlspecialchars($lecturer['position']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($lecturer['phone']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($lecturer['email']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-data">No lecturers found.</p>
    <?php endif; ?>

</body>
</html>
