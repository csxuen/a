<!-- Create Event Modal -->
<!-- zayzay start -->
<div id="createEventModal" class="modal">
  <div class="modal-content">

    <span class="close">&times;</span>

    <h2>Create New Event</h2>
    <p class="modal-subtitle">
      Fill in the details for your sustainable event
    </p>

    <form id="createEventForm" method="POST" action="create.php" enctype="multipart/form-data">

      <!-- Event Name -->
      <div class="form-group">
        <label>Event Name *</label>
        <input type="text" name="event_name" required>
      </div>

      <!-- Date & Time Row -->
      <div class="form-row">
        <div class="form-group">
          <label>Date *</label>
          <input type="date" name="event_date" required>
        </div>

        <div class="form-group">
          <label>Time</label>
          <input type="time" name="event_time">
        </div>
      </div>

      <!-- Location -->
      <div class="form-group">
        <label>Location *</label>
        <input type="text" name="event_location" required>
      </div>

      <!-- Event Type text -->
       <label for="eventType">Event Type *</label>
       <input type="text" id="eventType" name="eventType" 
       placeholder="Enter event type" required>

      <!-- Max Participants -->
      <div class="form-group">
        <label>Max Participants</label>
        <input type="number" name="max_participants" min="1">
      </div>

      <!-- Description -->
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" placeholder="Describe your event..."></textarea>
      </div>

      <!-- Sustainability Goals -->
      <div class="form-group">
        <label>Sustainability Goals (one per line)</label>
        <textarea name="sustainability_goals"
          placeholder="E.g. Remove 500kg of plastic"></textarea>
      </div>

      <!-- Event Image Upload -->
      <div class="form-group">
        <label>Event Image</label>
        <input type="file" name="event_image" id="eventImage" accept="image/*">

      <!-- Preview -->
       <div class="image-preview">
         <img id="imagePreview" src="" alt="Image Preview" style="display:none;">
         </div>
      </div>

      <!-- Buttons -->
      <div class="modal-buttons">
        <button type="button" class="btn-secondary" id="cancelBtn">
          Cancel
        </button>

        <button type="reset" class="btn-light">
          Reset
        </button>

        <button type="submit" class="btn-primary">
          Create Event
        </button>
      </div>

    </form>
  </div>
</div>
<!-- zayzay end -->