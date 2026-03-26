<?php
include("session_test.php");
include("config.php");

/* Fetch About Page Content */
$aboutData = [];

$query = "SELECT section_key, title, content, image_path FROM about_content";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $aboutData[$row['section_key']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcoEvents | About Us</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include("header.php"); ?>

<main class="about-page">

<!-- HERO -->
<section class="about-hero">
<div class="container">

<div class="about-hero-content">

<div class="about-hero-icon"></div>

<h1>
<?= htmlspecialchars($aboutData['hero']['title'] ?? 'About Sustainable Events') ?>
</h1>

<p>
<?= htmlspecialchars($aboutData['hero']['content'] ?? '') ?>
</p>

</div>

</div>
</section>


<!-- OUR PLATFORM -->
<section class="about-section">
<div class="container about-row">

<div class="about-text-card">

<h2>
<?= htmlspecialchars($aboutData['platform']['title'] ?? 'Our Platform') ?>
</h2>

<p>
<?= htmlspecialchars($aboutData['platform']['content'] ?? '') ?>
</p>

</div>

<?php if (!empty($aboutData['platform']['image_path'])): ?>
<img 
src="<?= htmlspecialchars($aboutData['platform']['image_path']) ?>" 
alt="Our Platform" 
class="about-section-image"
>
<?php else: ?>
<div class="about-image-box"></div>
<?php endif; ?>

</div>
</section>


<!-- OUR MISSION -->
<section class="about-section">
<div class="container about-row reverse">

<?php if (!empty($aboutData['mission']['image_path'])): ?>
<img 
src="<?= htmlspecialchars($aboutData['mission']['image_path']) ?>" 
alt="Our Mission" 
class="about-section-image"
>
<?php else: ?>
<div class="about-image-box"></div>
<?php endif; ?>

<div class="about-text-card">

<h2>
<?= htmlspecialchars($aboutData['mission']['title'] ?? 'Our Mission') ?>
</h2>

<p>
<?= htmlspecialchars($aboutData['mission']['content'] ?? '') ?>
</p>

</div>

</div>
</section>


<!-- COMMITMENT -->
<section class="about-section">
<div class="container about-row">

<div class="about-text-card">

<h2>
<?= htmlspecialchars($aboutData['commitment']['title'] ?? 'Sustainability Commitment') ?>
</h2>

<p>
<?= htmlspecialchars($aboutData['commitment']['content'] ?? '') ?>
</p>

</div>

<?php if (!empty($aboutData['commitment']['image_path'])): ?>
<img 
src="<?= htmlspecialchars($aboutData['commitment']['image_path']) ?>" 
alt="Sustainability Commitment" 
class="about-section-image"
>
<?php else: ?>
<div class="about-image-box"></div>
<?php endif; ?>

</div>
</section>


<!-- GREEN POINTS -->
<section class="about-points">
<div class="container">

<h2>
<?= htmlspecialchars($aboutData['green_points']['title'] ?? 'About Green Points System') ?>
</h2>

<p class="about-points-sub">
<?= htmlspecialchars($aboutData['green_points']['content'] ?? '') ?>
</p>

<div class="points-grid two-cards">

  <div class="points-card">
    <div class="points-circle"></div>
    <h2>+10</h2>
    <p>Attend Event</p>
  </div>

  <div class="points-card">
    <div class="points-circle"></div>
    <h2>+5</h2>
    <p>Submit Feedback</p>
  </div>

</div>

</div>
</section>


<!-- REDEEM SECTION -->
<section class="about-redeem">
<div class="container">

<h2>
<?= htmlspecialchars($aboutData['redeem_intro']['title'] ?? 'Redeem Your Points For:') ?>
</h2>

<div class="redeem-preview-grid">

<a href="rewards.php" class="reward-link">
  <div class="redeem-preview-card">
    <div class="redeem-preview-img"></div>
    <h3>Reusable Bag</h3>
    <p>Reduce single-use plastic in daily life.</p>
  </div>
</a>

<a href="rewards.php" class="reward-link">
  <div class="redeem-preview-card">
    <div class="redeem-preview-img"></div>
    <h3>Plant / Seed Packets</h3>
    <p>Grow your own plants and support the environment.</p>
  </div>
</a>

<a href="rewards.php" class="reward-link">
  <div class="redeem-preview-card">
    <div class="redeem-preview-img"></div>
    <h3>Certificate</h3>
    <p>Official acknowledgment for participation.</p>
  </div>
</a>

<a href="rewards.php" class="reward-link">
  <div class="redeem-preview-card">
    <div class="redeem-preview-img"></div>
    <h3>Voucher</h3>
    <p>Redeemable at campus locations.</p>
  </div>
</a>

</div>

</div>
</section>

</main>

<?php include("footer.php"); ?>

<script src="js/main.js"></script>

</body>
</html>

<!-- UPDATE about_content
SET content = 'New updated about text'
WHERE section_key = 'hero'; -->