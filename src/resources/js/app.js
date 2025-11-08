import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// Toast notification auto-hide
document.addEventListener("DOMContentLoaded", function () {
  const toasts = document.querySelectorAll("[x-data]");
  toasts.forEach((toast) => {
    if (toast.getAttribute("x-show") === "show") {
      setTimeout(() => {
        toast.style.display = "none";
      }, 3000);
    }
  });
});

// File upload preview
window.handleFileUpload = function (event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      // Handle file preview if needed
      console.log("File loaded:", file.name);
    };
    reader.readAsDataURL(file);
  }
};

// Confirm delete actions
window.confirmDelete = function (message) {
  return confirm(message || "Are you sure you want to delete this item?");
};

// Auto-submit forms with delay
window.debounce = function (func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
};

// Mark notification as read
window.markNotificationRead = function (notificationId) {
  fetch(`/notifications/${notificationId}/read`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  }).then((response) => {
    if (response.ok) {
      document
        .querySelector(`[data-notification-id="${notificationId}"]`)
        ?.remove();
    }
  });
};

// Load subjects based on branch and year
window.loadSubjects = function (branchId, year) {
  if (!branchId || !year) return;

  fetch(`/api/subjects?branch_id=${branchId}&year=${year}`)
    .then((response) => response.json())
    .then((data) => {
      const select = document.getElementById("subject_id");
      if (select) {
        select.innerHTML = '<option value="">Select Subject</option>';
        data.forEach((subject) => {
          select.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
        });
      }
    })
    .catch((error) => console.error("Error loading subjects:", error));
};
