document.addEventListener("DOMContentLoaded", () => {

  const correctCode = "1234"; // <-- set your 4-digit code here
  const modal = document.getElementById('code-modal');
  const input = document.getElementById('access-code');
  const unlockBtn = document.getElementById('unlock-btn');
  const errorMsg = document.getElementById('error-msg');

  // Initially disable all delete buttons
  const deleteButtons = document.querySelectorAll('.delete');
  deleteButtons.forEach(btn => btn.disabled = true);

  // Unlock button event
  unlockBtn.addEventListener('click', () => {
    if (input.value === correctCode) {
      // Hide modal
      modal.style.display = 'none';
      // Enable delete buttons
      deleteButtons.forEach(btn => btn.disabled = false);
    } else {
      errorMsg.textContent = "Incorrect code. Try again!";
      input.value = "";
      input.focus();
    }
  });

  // DELETE CONFIRMATION (still works after unlocking)
  const deleteForms = document.querySelectorAll('.delete-form');
  deleteForms.forEach(form => {
    form.addEventListener('submit', (e) => {
      const confirmed = confirm("Are you sure you want to delete this club?");
      if (!confirmed) {
        e.preventDefault();
      }
    });
  });

});
