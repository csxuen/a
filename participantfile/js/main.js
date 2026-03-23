// js/main.js

// --------------------------
// Helpers
// --------------------------
function qs(sel, root = document) {
  return root.querySelector(sel);
}
function qsa(sel, root = document) {
  return Array.from(root.querySelectorAll(sel));
}
function getQueryParam(key) {
  return new URLSearchParams(window.location.search).get(key);
}

// --------------------------
// Demo data for Event Details
// --------------------------
const EVENTS = {
  1: {
    name: "Tree Planting Day",
    desc: "Join us to plant trees and help make the city greener. Gloves and tools provided.",
    points: 50,
    date: "12 Feb 2026",
    time: "09:00",
    location: "Bukit Jalil, Kuala Lumpur",
    organizer: "John",
    category: "Planting",
    joined: 24,
    capacity: 100,
    goals: ["Plant 100 saplings", "Educate 100 volunteers"]
  },
  2: {
    name: "Community Clean-Up",
    desc: "Work together to clean public areas and sort recyclables properly.",
    points: 40,
    date: "18 Feb 2026",
    time: "08:30",
    location: "Kuala Lumpur",
    organizer: "Amina",
    category: "Clean-up",
    joined: 57,
    capacity: 120,
    goals: ["Collect 50kg of waste", "Separate recyclables correctly"]
  },
  3: {
    name: "Recycling Workshop",
    desc: "Learn easy ways to reduce waste and recycle correctly at home.",
    points: 30,
    date: "20 Feb 2026",
    time: "10:00",
    location: "Zoom",
    organizer: "Daniel",
    category: "Workshop",
    joined: 159,
    capacity: 200,
    goals: ["Train 200 participants", "Increase awareness"]
  },
  4: {
    name: "Beach Clean-Up Drive",
    desc: "Help clean coastal areas and protect marine life from plastic pollution.",
    points: 40,
    date: "15 Feb 2026",
    time: "08:00",
    location: "Port Dickson",
    organizer: "Jonathan",
    category: "Clean-up",
    joined: 20,
    capacity: 50,
    goals: [
      "Collect and properly dispose of at least 20kg of beach waste",
      "Raise public awareness about marine pollution"
    ]
  },
  5: {
    name: "Urban Gardening Workshop",
    desc: "Learn how to grow vegetables and herbs in small urban spaces using sustainable methods.",
    points: 35,
    date: "22 Feb 2026",
    time: "10:00",
    location: "APU Campus, Bukit Jalil",
    organizer: "Eco Team",
    category: "Workshop",
    joined: 15,
    capacity: 40,
    goals: ["Teach balcony gardening basics", "Reduce food waste", "Promote composting"]
  },
  6: {
    name: "River Clean Up",
    desc: "Learn how to protect the water ecosystem, keep the river clean, and prevent waste from flowing into the sea.",
    points: 50,
    date: "12 Feb 2026",
    time: "11:00",
    location: "Klang River",
    organizer: "APU Recycle Organization",
    category: "Clean-up",
    joined: 13,
    capacity: 30,
    goals: ["Collect at least 20kg of river waste", "Prevent plastic waste from entering the sea"]
  }
};

// --------------------------
// Modal open/close (reusable)
// --------------------------
function openOverlay(overlayEl) {
  if (!overlayEl) return;
  overlayEl.classList.add("show");
  overlayEl.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}
function closeOverlay(overlayEl) {
  if (!overlayEl) return;
  overlayEl.classList.remove("show");
  overlayEl.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
}
// --------------------------
// Points Service (Frontend-only, backend-friendly later)
// --------------------------
const PointsService = (() => {
  const KEY = "greenPoints";

  function get() {
    // MUST return a NUMBER
    return Number(localStorage.getItem(KEY) || 0);
  }

  function set(points) {
    const safe = Math.max(0, Number(points) || 0);
    localStorage.setItem(KEY, String(safe));

    // notify all pages/components in same tab
    window.dispatchEvent(
      new CustomEvent("points:changed", { detail: { points: safe } })
    );

    return safe;
  }

  function add(amount) {
    return set(get() + (Number(amount) || 0));
  }

  function subtract(amount) {
    return set(get() - (Number(amount) || 0));
  }

  // only set initial points once (from DOM), then keep localStorage as source of truth
  function initFromDom(selector = "#userPoints") {
    if (localStorage.getItem(KEY) !== null) return get(); // already initialized
    const domVal = Number(document.querySelector(selector)?.textContent.trim() || 0);
    return set(domVal);
  }

  return { get, set, add, subtract, initFromDom };
})();


// MAIN
document.addEventListener("DOMContentLoaded", () => {

  // Navbar height -> body padding-top
  const nav = qs(".navbar");
  if (nav) document.documentElement.style.setProperty("--nav-h", nav.offsetHeight + "px");

  // EVENTS PAGE: Search + Filter (events.php)
// EVENTS PAGE: Search + Filter (events.php)
const searchInput = qs("#eventSearch");
const filterSelect = qs("#eventFilter");
const eventCards = qsa(".events-grid .event-card");

if (eventCards.length) {
  const applyFilters = () => {
    const searchValue = (searchInput?.value || "").toLowerCase().trim();
    const filterValue = (filterSelect?.value || "all").toLowerCase();

    eventCards.forEach(card => {
      const name = (card.dataset.name || "").toLowerCase();
      const location = (card.dataset.location || "").toLowerCase();
      const category = (card.dataset.category || "").toLowerCase();

      const matchesSearch =
        !searchValue ||
        name.includes(searchValue) ||
        location.includes(searchValue);

      const matchesFilter =
        filterValue === "all" || category === filterValue;

      card.style.display = (matchesSearch && matchesFilter) ? "" : "none";
    });
  };

  searchInput?.addEventListener("input", applyFilters);
  filterSelect?.addEventListener("change", applyFilters);
  applyFilters();
}

  // MY EVENTS: Tabs filter + disable rules
  const tabs = qsa(".my-tab");
  const myCards = qsa(".my-card");
  if (tabs.length && myCards.length) {
    myCards.forEach(card => {
      const status = (card.dataset.status || "").toLowerCase();
      const isUpcoming = status === "upcoming";
      const isOngoing = status === "ongoing";
      const isCompleted = status === "completed";

      qsa(".open-feedback", card).forEach(btn => {
        if (!isCompleted) {
          btn.disabled = true;
          btn.classList.add("disabled-btn");
          btn.title = "Feedback is only available after the event is completed.";
        }
      });

      qsa(".open-upload", card).forEach(btn => {
        if (!(isOngoing || isCompleted)) {
          btn.disabled = true;
          btn.classList.add("disabled-btn");
          btn.title = "Image upload is available during or after the event.";
        }
      });
    });

    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        tabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");

        const filter = (tab.dataset.filter || "all").toLowerCase();
        myCards.forEach(card => {
          const status = (card.dataset.status || "").toLowerCase();
          const show = (filter === "all") || (status === filter);
          card.style.display = show ? "" : "none";
        });
      });
    });
  }


// FEEDBACK MODAL (my_events.php)

const feedbackModal = qs("#feedbackModal");
if (feedbackModal) {
  const closeBtn = qs("#closeModalBtn");
  const cancelBtn = qs("#cancelModalBtn");
  const form = qs("#feedbackForm");

  const eventIdInput = qs("#eventIdInput");
  const ratingValue = qs("#ratingValue");
  const feedbackText = qs("#feedbackText");
  const stars = qsa("#ratingStars .star");

  const setRating = (val) => {
    if (ratingValue) {
      ratingValue.value = String(val);
    }

    stars.forEach(star => {
      const starValue = Number(star.dataset.value);
      star.classList.toggle("active", starValue <= val);
    });
  };

  const resetFeedbackModal = () => {
    form?.reset();
    setRating(0);
    if (eventIdInput) {
      eventIdInput.value = "";
    }
  };

  const openFeedbackModal = () => openOverlay(feedbackModal);

  const closeFeedbackModal = () => {
    closeOverlay(feedbackModal);
    resetFeedbackModal();
  };

  qsa(".open-feedback").forEach(btn => {
    btn.addEventListener("click", () => {
      if (btn.disabled) return;

      if (eventIdInput) {
        eventIdInput.value = btn.dataset.eventId || "";
      }

      openFeedbackModal();
    });
  });

  closeBtn?.addEventListener("click", closeFeedbackModal);
  cancelBtn?.addEventListener("click", closeFeedbackModal);

  feedbackModal.addEventListener("click", (e) => {
    if (e.target === feedbackModal) {
      closeFeedbackModal();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && feedbackModal.classList.contains("show")) {
      closeFeedbackModal();
    }
  });

  stars.forEach(star => {
    star.addEventListener("click", () => {
      setRating(Number(star.dataset.value));
    });
  });

  form?.addEventListener("submit", (e) => {
    const rating = Number(ratingValue?.value || 0);
    const feedback = (feedbackText?.value || "").trim();

    if (rating === 0) {
      e.preventDefault();
      alert("Please select a rating.");
      return;
    }

    if (!feedback) {
      e.preventDefault();
      alert("Please write your feedback.");
    }
  });
}

// -------------------------------------------------------
// UPLOAD MODAL (Enhanced UI)
// -------------------------------------------------------
const uploadModal = qs("#uploadModal");

if (uploadModal) {
  const closeBtn = qs("#closeUploadBtn");
  const cancelBtn = qs("#cancelUploadBtn");
  const form = qs("#uploadForm");

  const browseBtn = qs("#browseBtn");
  const fileInput = qs("#imageFile");
  const fileName = qs("#fileName");
  const dropZone = qs("#dropZone");

  const uploadEventId = qs("#uploadEventId");

  const open = (eventId) => {
    uploadEventId.value = eventId || "";
    openOverlay(uploadModal);
  };

  const close = () => {
    closeOverlay(uploadModal);
    form.reset();
    fileName.textContent = "No file selected";
  };

  // Open modal
  qsa(".open-upload").forEach(btn => {
    btn.addEventListener("click", () => {
      if (btn.disabled) return;
      open(btn.dataset.eventId);
    });
  });

  // Close modal
  closeBtn?.addEventListener("click", close);
  cancelBtn?.addEventListener("click", close);

  // Click outside
  uploadModal.addEventListener("click", (e) => {
    if (e.target === uploadModal) close();
  });

  // Browse button
  browseBtn?.addEventListener("click", () => fileInput.click());

  // File selected
  fileInput?.addEventListener("change", () => {
    fileName.textContent = fileInput.files[0]?.name || "No file selected";
  });

  // Drag & Drop
  dropZone?.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("dragover");
  });

  dropZone?.addEventListener("dragleave", () => {
    dropZone.classList.remove("dragover");
  });

  dropZone?.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("dragover");

    if (e.dataTransfer.files.length) {
      fileInput.files = e.dataTransfer.files;
      fileName.textContent = e.dataTransfer.files[0].name;
    }
  });

  // Validation
  form?.addEventListener("submit", (e) => {
    if (!fileInput.files.length) {
      e.preventDefault();
      alert("Please select an image.");
    }
  });
}

  // -------------------------------------------------------
  // GALLERY SORT (gallery.php)
  // -------------------------------------------------------
  const sort = qs("#gallerySort");
  const grid = qs("#galleryGrid");
  if (sort && grid) {
    const sortCards = (mode) => {
      const cards = qsa(".gallery-card", grid);

      cards.sort((a, b) => {
        const titleA = (a.dataset.title || "").toLowerCase();
        const titleB = (b.dataset.title || "").toLowerCase();
        const pointsA = Number(a.dataset.points || 0);
        const pointsB = Number(b.dataset.points || 0);
        const dateA = new Date(a.dataset.date || "2000-01-01").getTime();
        const dateB = new Date(b.dataset.date || "2000-01-01").getTime();

        if (mode === "recent") return dateB - dateA;
        if (mode === "pointsHigh") return pointsB - pointsA;
        if (mode === "pointsLow") return pointsA - pointsB;
        if (mode === "titleAZ") return titleA.localeCompare(titleB);
        if (mode === "titleZA") return titleB.localeCompare(titleA);
        return 0;
      });

      cards.forEach(card => grid.appendChild(card));
    };

    sort.addEventListener("change", () => sortCards(sort.value));
    sortCards(sort.value);
  }

  // -------------------------------------------------------
  // REWARDS: Redeem modal + Progress (rewards.php)
  // -------------------------------------------------------
  //  const redeemModal = qs("#redeemModal");
//   if (redeemModal) {
//     const closeBtn = qs("#redeemCloseBtn");
//     const cancelBtn = qs("#redeemCancelBtn");
//     const yesBtn = qs("#redeemYesBtn");
//     const redeemCost = qs("#redeemCost");
//     const redeemName = qs("#redeemName");

//     const open = () => openOverlay(redeemModal);
//     const close = () => closeOverlay(redeemModal);

//     const pointsEl = qs("#userPoints");
//     const progressText = qs("#progressText");
//     const progressFill = qs("#progressFill");
//     const nextRewardText = qs("#nextRewardText");

//     function updateRewardsProgress(points) {
//       const target = Math.ceil((points + 1) / 100) * 100; // next hundred
//       const percent = Math.max(0, Math.min(100, Math.round((points / target) * 100)));

//       if (pointsEl) pointsEl.textContent = points;
//       if (progressFill) progressFill.style.width = percent + "%";
//       if (progressText) progressText.textContent = `${points}/${target}`;
//       if (nextRewardText) nextRewardText.textContent = `Next reward at ${target} green points`;
//     }

//     // init points once from DOM (first run only), then use localStorage
//     PointsService.initFromDom("#userPoints");
//     updateRewardsProgress(PointsService.get());

//     // open modal
//     qsa(".redeem-btn").forEach(btn => {
//       btn.addEventListener("click", () => {
//         const card = btn.closest(".redeem-card");
//         const cost = card?.dataset.cost || "0";
//         const name = card?.querySelector("h3")?.textContent || "Reward";

//         if (redeemCost) redeemCost.value = cost;
//         if (redeemName) redeemName.value = name;
//         open();
//       });
//     });

//     closeBtn?.addEventListener("click", close);
//     cancelBtn?.addEventListener("click", close);

//     redeemModal.addEventListener("click", (e) => {
//       if (e.target === redeemModal) close();
//     });

//     document.addEventListener("keydown", (e) => {
//       if (e.key === "Escape" && redeemModal.classList.contains("show")) close();
//     });

//     // redeem
//     yesBtn?.addEventListener("click", () => {
//       const historyBody = qs("#redeemHistoryBody");

//       const currentPoints = PointsService.get();
//       const cost = Number(redeemCost?.value || 0);
//       const name = redeemName?.value || "Reward";

//       if (currentPoints < cost) {
//         alert("Not enough Green Points to redeem this item.");
//         close();
//         return;
//       }

//       const newPoints = PointsService.subtract(cost);
//       updateRewardsProgress(newPoints);

//       const today = new Date();
//       const dateStr = today.toLocaleDateString("en-GB", {
//         day: "2-digit",
//         month: "long",
//         year: "numeric"
//       });

//       const tr = document.createElement("tr");
//       tr.innerHTML = `
//         <td>${name}</td>
//         <td>${cost} points</td>
//         <td>${dateStr}</td>
//       `;
//       historyBody?.prepend(tr);

//       alert("Redeemed successfully!");
//       close();
//     });
//   }
});