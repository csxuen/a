<?php
include("config.php");

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = "";
$event = null;

/* Get event details */
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
}

/* Save waste report */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_id = (int)($_POST['event_id'] ?? 0);

    $waste_type = trim($_POST['waste_type'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $collected = trim($_POST['collected'] ?? '');
    $collection_method = trim($_POST['collection_method'] ?? '');
    $disposal_method = trim($_POST['disposal_method'] ?? '');

    if ($event_id <= 0) {
        $error = "Invalid event.";
    } elseif ($waste_type === '' || $quantity === '' || $collected === '' || $collection_method === '' || $disposal_method === '') {
        $error = "Please fill in all fields.";
    } else {
        $waste_report = "Waste Type: " . $waste_type . "\n"
                      . "Quantity: " . $quantity . "\n"
                      . "Collected: " . $collected . "\n"
                      . "Collection Method: " . $collection_method . "\n"
                      . "Disposal Method: " . $disposal_method;

        $stmt = $conn->prepare("UPDATE events SET waste_report = ? WHERE id = ?");
        $stmt->bind_param("si", $waste_report, $event_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Waste report submitted successfully!');
                    window.location.href='event_page.php';
                  </script>";
            exit;
        } else {
            $error = "Failed to save waste report.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Waste Report | EcoEvents</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard page-layout">

<?php include("header.php"); ?>

<div class="waste-page-overlay">
  <div class="waste-modal-box">
    <h2>Create Waste Report</h2>
    <p class="modal-subtitle">
      <?php if ($event): ?>
        Add waste report for <strong><?php echo htmlspecialchars($event['event_name']); ?></strong>
      <?php else: ?>
        Event not found
      <?php endif; ?>
    </p>

    <?php if ($error): ?>
      <div class="waste-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($event): ?>
      <form method="POST" action="WasteReport.php?id=<?php echo $event_id; ?>" class="waste-form">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

        <div class="form-group">
          <label for="waste_type">Waste Type</label>
          <input type="text" name="waste_type" id="waste_type" placeholder="Enter waste type" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="text" name="quantity" id="quantity" placeholder="Enter quantity" required>
          </div>

          <div class="form-group">
            <label for="collected">Collected</label>
            <input type="text" name="collected" id="collected" placeholder="Enter collected amount" required>
          </div>
        </div>

        <div class="form-group">
          <label for="collection_method">Collection Method</label>
          <input type="text" name="collection_method" id="collection_method" placeholder="Enter collection method" required>
        </div>

        <div class="form-group">
          <label for="disposal_method">Disposal Method</label>
          <input type="text" name="disposal_method" id="disposal_method" placeholder="Enter disposal method" required>
        </div>

        <div class="waste-modal-buttons">
          <a href="event_page.php" class="btn-secondary waste-cancel-link">Cancel</a>
          <button type="submit" class="btn-primary">Submit</button>
        </div>
      </form>
    <?php else: ?>
      <div class="waste-modal-buttons">
        <a href="event_page.php" class="btn-secondary waste-cancel-link">Back</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include("footer.php"); ?>

</body>
</html>