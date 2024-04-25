<?php include 'anndata.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="annc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <title>Announcements</title>
</head>
<body>
<div class="container">
<h2><i class="fas fa-bullhorn announcement-icon"></i> Announcements</h2>
     
    <div class="announcement-options">
        <div class="announcement-option">
            <p>Create New Announcement</p>
            <p>Notify all students of Pansol</p>
        </div>
        
        <div class="add-announcement" id="add-new-button">
            Add New Announcement
        </div>
        
    </div>
    
    <!-- Search Input Field -->
    <div class="search-container">
        <input type="text" id="announcement-search" placeholder="Search announcements...">
        <span class="search-icon">&#128269;</span>
    </div>

    <!-- Add New Announcement Form -->
    <div id="announcement-form" class="popup-form" style="display: none;">
        <h3 id="form-title">Add New Announcement</h3>
        <form id="announcement-form-inner" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" id="announcement-id" name="announcement-id">
            <div class="input-container">
                <label for="announcement-title">Title:</label>
                <input type="text" id="announcement-title" name="announcement-title" required>
            </div>
            <div class="input-container">
                <label for="announcement-content">Content:</label>
                <textarea id="announcement-content" name="announcement-content" required></textarea>
            </div>
            <button type="submit" name="submit" id="submit-button">Submit</button>
            <button type="button" class="cancel-announcement" id="cancel-announcement">Cancel</button>
        </form>
    </div>

    <!-- Update Announcement Form -->
    <div id="update-form" class="popup-form" style="display: none;">
        <h3 id="form-title">Update Announcement</h3>
        <form id="update-form-inner" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" id="update-announcement-id" name="announcement-id">
            <div class="input-container">
                <label for="update-announcement-title">Title:</label>
                <input type="text" id="update-announcement-title" name="announcement-title" required>
            </div>
            <div class="input-container">
                <label for="update-announcement-content">Content:</label>
                <textarea id="update-announcement-content" name="announcement-content" required></textarea>
            </div>
            <button type="submit" name="update" id="update-button">Update</button>
            <button type="button" class="cancel-update" id="cancel-update">Cancel</button>
        </form>
    </div>
    <button class="delete-button" data-id="<?= $announcement['announcementId'] ?>">Delete All</button>

    <div class="announcements">
        <?php if (!empty($announcements)): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($announcements as $announcement): ?>
                        <tr>
                            <td><?= htmlspecialchars($announcement['title']) ?></td>
                            <td><?= htmlspecialchars($announcement['content']) ?></td>
                            <td><?= htmlspecialchars($announcement['date_published']) ?></td>
                            <td>
                            <button class="update-button" data-id="<?= $announcement['announcementId'] ?>">
    <i class="far fa-edit update-icon"></i>Edit
</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-announcements">No announcements yet.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    // Add event listener to the "Add New Announcement" button
    document.getElementById('add-new-button').addEventListener('click', function() {
        document.getElementById('announcement-form').style.display = 'block';
    });

    // Add event listener to the "Cancel" button in the add new announcement form
    document.getElementById('cancel-announcement').addEventListener('click', function() {
        document.getElementById('announcement-form').style.display = 'none';
    });

    // Add event listener to the "Cancel" button in the update form
    document.getElementById('cancel-update').addEventListener('click', function() {
        document.getElementById('update-form').style.display = 'none';
    });

    // Add event listener to the "Update" buttons
    var updateButtons = document.querySelectorAll('.update-button');
    updateButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var title = this.parentNode.parentNode.querySelector('td:nth-child(1)').textContent;
            var content = this.parentNode.parentNode.querySelector('td:nth-child(2)').textContent;

            // Fill the update form with existing data
            document.getElementById('update-announcement-id').value = id;
            document.getElementById('update-announcement-title').value = title;
            document.getElementById('update-announcement-content').value = content;

            // Show the update form
            document.getElementById('update-form').style.display = 'block';
        });
    });
    // Add event listener to the "Delete" buttons
    var deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            
            // Show confirmation dialog before deleting
            if (confirm('Are you sure you want to delete this announcement?')) {
                // Submit the form to delete the announcement
                var form = document.createElement('form');
                form.setAttribute('method', 'post');
                form.setAttribute('action', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                form.innerHTML = '<input type="hidden" name="delete" value="1"><input type="hidden" name="announcement-id" value="' + id + '">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Function to filter announcements based on search query
    function searchAnnouncements() {
        // Get the search query from the input field
        var query = document.getElementById('announcement-search').value.toLowerCase();

        // Get all announcements
        var announcements = document.querySelectorAll('.announcements table tbody tr');

        // Loop through each announcement
        announcements.forEach(function(announcement) {
            // Get the title and content of the announcement
            var title = announcement.querySelector('td:nth-child(1)').textContent.toLowerCase();
            var content = announcement.querySelector('td:nth-child(2)').textContent.toLowerCase();

            // If the title or content contains the search query, show the announcement; otherwise, hide it
            if (title.includes(query) || content.includes(query)) {
                announcement.style.display = 'table-row';
            } else {
                announcement.style.display = 'none';
            }
        });
    }

    // Add event listener to the search input field
    document.getElementById('announcement-search').addEventListener('input', searchAnnouncements);
</script>
</body>
</html>