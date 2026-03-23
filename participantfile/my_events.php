    <?php
    include("session_test.php");
    include("config.php");

    if (!isset($_SESSION['user_id'])) {
        header("Location: participant.php");
        exit;
    }

    $participant_id = (int) $_SESSION['user_id'];

    $stmt = $conn->prepare("
    SELECT 
        ep.participation_status,
        e.id AS event_id,
        e.event_name,
        e.description,
        e.event_date,
        e.event_location,
        u.name AS organizer_name
        FROM event_participants ep
        JOIN events e ON ep.event_id = e.id
        LEFT JOIN users u ON e.organizer_id = u.user_id
        WHERE ep.participant_id = ?
        AND ep.participation_status != 'cancelled'
        ORDER BY e.event_date DESC
    ");

    $stmt->bind_param("i", $participant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>My Events</title>
    <link rel="stylesheet" href="css/style.css">
    </head>

    <body>

    <?php include("header.php"); ?>

    <main class="container">

    <h1 class="page-title">My Events</h1>

    <?php if (isset($_GET['upload']) && $_GET['upload'] === 'success'): ?>
    <div class="success-message">
        Photo uploaded successfully!
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['feedback']) && $_GET['feedback'] === 'success'): ?>
    <div class="success-message">
        Feedback submitted successfully!
    </div>
    <?php endif; ?>

    <div class="my-tabs">
    <button class="my-tab active" data-filter="all">All</button>
    <button class="my-tab" data-filter="upcoming">Upcoming</button>
    <button class="my-tab" data-filter="ongoing">Ongoing</button>
    <button class="my-tab" data-filter="completed">Completed</button>
    </div>

    <section class="my-grid">

    <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>

    <?php
    $currentDate = date("Y-m-d");
    $eventDate = $row['event_date'];

    if ($row['participation_status'] === 'completed') {
        $displayStatus = "completed";
    }
    elseif ($eventDate < $currentDate) {
        $displayStatus = "completed";
    }
    elseif ($eventDate === $currentDate) {
        $displayStatus = "ongoing";
    }
    else {
        $displayStatus = "upcoming";
    }
    ?>

    <article class="my-card" data-status="<?= $displayStatus ?>">

    <div class="my-img">
    <span class="my-badge"><?= ucfirst($displayStatus) ?></span>
    </div>

    <div class="my-body">

    <h2><?= htmlspecialchars($row['event_name']) ?></h2>
    <p><?= htmlspecialchars($row['description']) ?></p>

    <ul class="my-meta">
    <li><strong>Date:</strong> <?= date("d M Y", strtotime($row['event_date'])) ?></li>
    <li><strong>Location:</strong> <?= htmlspecialchars($row['event_location']) ?></li>
    </ul>

    <a href="eventdetails.php?id=<?= $row['event_id'] ?>" class="my-btn">View Details</a>

    <?php if ($displayStatus === 'completed'): ?>

    <button class="my-btn open-feedback"
    data-event-id="<?= $row['event_id'] ?>">
    Submit Feedback
    </button>

    <button class="my-btn open-upload" type="button"
    data-event-id="<?= (int)$row['event_id'] ?>"
    data-event-name="<?= htmlspecialchars($row['event_name']) ?>"
    data-event-date="<?= date('d M Y', strtotime($row['event_date'])) ?>"
    data-event-organizer="<?= htmlspecialchars($row['organizer_name']) ?>">
    Upload Photo
    </button>

    <?php else: ?>

    <button class="my-btn disabled-btn" disabled>Submit Feedback</button>
    <button class="my-btn disabled-btn" disabled>Upload Photo</button>

    <?php endif; ?>

    <a href="gallery_view.php?event_id=<?= $row['event_id'] ?>" class="my-btn">
    Gallery
    </a>

    </div>
    </article>

    <?php endwhile; ?>
    <?php else: ?>
    <p>No events found.</p>
    <?php endif; ?>

    </section>

    </main>

    <!-- ================= FEEDBACK MODAL ================= -->
    <div class="modal-overlay" id="feedbackModal">
    <div class="feedback-modal-box">
        <button class="modal-close feedback-close" id="closeModalBtn" type="button">✕</button>

        <h2 class="feedback-title">Submit Event Feedback &amp; Rating</h2>
        <p class="feedback-subtitle">Share your experience and earn 15 Green Points!</p>

        <form action="submit_feedback.php" method="POST" id="feedbackForm">
        <input type="hidden" id="eventIdInput" name="event_id">
        <input type="hidden" id="ratingValue" name="rating" value="0">

        <label class="feedback-label">Rating</label>
        <div class="rating-stars" id="ratingStars">
            <button type="button" class="star" data-value="1">★</button>
            <button type="button" class="star" data-value="2">★</button>
            <button type="button" class="star" data-value="3">★</button>
            <button type="button" class="star" data-value="4">★</button>
            <button type="button" class="star" data-value="5">★</button>
        </div>

        <label class="feedback-label" for="feedbackText">Your Feedback</label>
        <textarea
            id="feedbackText"
            name="feedback"
            class="feedback-textarea"
            placeholder="Share your experience...."
            required
        ></textarea>

        <div class="feedback-actions">
            <button type="button" id="cancelModalBtn" class="feedback-btn cancel-btn">Cancel</button>
            <button type="submit" class="feedback-btn submit-btn">Submit Feedback</button>
        </div>
        </form>
    </div>
    </div>

<!-- UPLOAD PHOTO MODAL -->
<div class="modal-overlay" id="uploadModal">
  <div class="upload-modal-box">
    <button class="modal-close upload-close" id="closeUploadBtn" type="button">✕</button>

    <h2 class="upload-title">Upload Event Photo</h2>

    <div class="upload-event-info">
      <div><strong>Event Name:</strong> <span id="uEventName">Event Name</span></div>
      <div><strong>Event Date:</strong> <span id="uEventDate">Event Date</span></div>
      <div><strong>Organizer:</strong> <span id="uEventOrg">Organizer</span></div>
    </div>

    <form action="upload_event_photo.php" method="POST" enctype="multipart/form-data" id="uploadForm">
      <input type="hidden" id="uploadEventId" name="event_id">

      <label class="upload-label">Upload your photo here</label>

      <div class="upload-dropzone" id="dropZone">
        <p class="upload-drop-text">Drag and drop your image here or click Browse</p>
        <button type="button" id="browseBtn" class="browse-btn">Browse</button>
        <input type="file" id="imageFile" name="image" accept="image/*" hidden>
        <p id="fileName" class="file-name">No file selected</p>
      </div>

      <div class="upload-field">
        <label for="photoTitle">Photo Title</label>
        <input type="text" id="photoTitle" name="title" placeholder="Enter photo title">
      </div>

      <div class="upload-field">
        <label for="photoDescription">Description</label>
        <textarea id="photoDescription" name="description" placeholder="Write a short description"></textarea>
      </div>

      <div class="upload-actions">
        <button type="submit" class="upload-btn primary">Upload Photo</button>
        <button type="button" id="cancelUploadBtn" class="upload-btn secondary">Cancel</button>
      </div>
    </form>
  </div>
</div>

<?php include("footer.php"); ?>

<script src="js/main.js"></script>

    </body>
    </html>