// Wait until the page is fully loaded
document.addEventListener("DOMContentLoaded", () => {

  // Select all forms with class 'delete-form'
  const deleteForms = document.querySelectorAll('.delete-form');

  // Add a submit event listener to each form
  deleteForms.forEach(form => {
    form.addEventListener('submit', (e) => {

      // Show a confirmation popup
      const confirmed = confirm("Are you sure you want to delete this club?");

      // If user clicks "Cancel", prevent form submission
      if (!confirmed) {
        e.preventDefault();
      }
    });
  });
});
