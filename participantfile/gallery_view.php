<?php
include("session_test.php");
include("config.php");

if(!isset($_GET['event_id'])){
    die("Event not specified.");
}

$event_id = (int) $_GET['event_id'];

$stmt = $conn->prepare("
SELECT e.event_name, g.image_path
FROM event_gallery g
JOIN events e ON g.event_id = e.id
WHERE g.event_id = ?
AND g.status = 'approved'
ORDER BY g.gallery_id ASC
");

$stmt->bind_param("i",$event_id);
$stmt->execute();

$result = $stmt->get_result();

$images = [];
$event_name = "";

while($row = $result->fetch_assoc()){
    $images[] = $row['image_path'];
    $event_name = $row['event_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($event_name) ?> | Gallery</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php include("header.php"); ?>

<main class="gallery-view-page">
  <div class="container">

    <div class="gallery-view-topbar">
      <h1 class="gallery-view-title"><?= htmlspecialchars($event_name) ?></h1>
    </div>

    <div class="gallery-viewer">

      <div class="gallery-image-wrapper">

        <button class="gallery-arrow left" onclick="prevImage()" aria-label="Previous image">
          &#10094;
        </button>

        <img id="mainGalleryImage"
             class="gallery-view-image"
             src="<?= htmlspecialchars($images[0]) ?>"
             alt="<?= htmlspecialchars($event_name) ?>">

        <button class="gallery-arrow right" onclick="nextImage()" aria-label="Next image">
          &#10095;
        </button>

      </div>

    </div>

    <div class="gallery-meta">
      <span class="gallery-counter" id="imageCounter">
        1 / <?= count($images) ?>
      </span>
    </div>

    <div class="gallery-bottom">
    <a href="gallery.php" class="gallery-back-btn">Back to Gallery</a>
    </div>

  </div>
</main>

<script>
const images = <?= json_encode($images) ?>;
let currentIndex = 0;

function showImage(index){
  const mainImage = document.getElementById("mainGalleryImage");
  const counter = document.getElementById("imageCounter");

  mainImage.src = images[index];
  counter.textContent = (index + 1) + " / " + images.length;
}

function nextImage(){
  currentIndex = (currentIndex + 1) % images.length;
  showImage(currentIndex);
}

function prevImage(){
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  showImage(currentIndex);
}

document.addEventListener("keydown", function(e){
  if(e.key === "ArrowRight"){
    nextImage();
  }
  if(e.key === "ArrowLeft"){
    prevImage();
  }
});
</script>

</body>
</html>