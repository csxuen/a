<?php
include("session_test.php");
include("config.php");

$query = "
SELECT 
    e.id,
    e.event_name,
    e.description,
    e.event_date,
    e.event_location,
    MIN(g.image_path) AS cover_image,
    COUNT(g.gallery_id) AS total_images
FROM events e
JOIN event_gallery g ON e.id = g.event_id
Where g.status = 'approved'
GROUP BY e.id, e.event_name, e.description, e.event_date, e.event_location
ORDER BY e.id DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoEvents | Event Gallery</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>

<?php include("header.php"); ?>

  <main class="container">

    <h1 class="gallery-title">Event Gallery</h1>
    <hr class="gallery-line" />

    <!-- Toolbar -->
    <div class="gallery-toolbar">
      <label class="gallery-sort">
        Sort By:
        <select id="gallerySort">
          <option value="recent" selected>Most Recent</option>
          <option value="pointsHigh">Points: High to Low</option>
          <option value="pointsLow">Points: Low to High</option>
          <option value="titleAZ">Title: A - Z</option>
          <option value="titleZA">Title: Z - A</option>
        </select>
      </label>
    </div>

    <section class="gallery-grid" id="galleryGrid">
  <?php while ($row = $result->fetch_assoc()): ?>

    <article class="gallery-card">
      <img
        class="gallery-img"
        src="<?= htmlspecialchars($row['cover_image']) ?>"
        alt="<?= htmlspecialchars($row['event_name']) ?>"
        loading="lazy"
      />

      <div class="gallery-info">
        <div class="gallery-row">
          <h3 class="gallery-card-title"><?= htmlspecialchars($row['event_name']) ?></h3>
        </div>

        <p class="gallery-desc">
          <?= htmlspecialchars(mb_strimwidth($row['description'], 0, 90, '...')) ?>
        </p>

        <div class="gallery-row bottom">
          <span><?= date("d M Y", strtotime($row['event_date'])) ?></span>
          <span><?= htmlspecialchars($row['event_location']) ?></span>
        </div>

        <div class="gallery-row bottom">
          <span><?= (int)$row['total_images'] ?> photos</span>
        </div>

        <a href="gallery_view.php?event_id=<?= (int)$row['id'] ?>" class="gallery-btn">
          View Album
        </a>
      </div>
    </article>

  <?php endwhile; ?>
</section>

  </main>

<?php include("footer.php"); ?>

  <script src="js/main.js"></script>
</body>
</html>
