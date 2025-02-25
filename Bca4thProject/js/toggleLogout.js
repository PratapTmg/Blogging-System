function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
  }

  // Optionally add click outside to close the dropdown
  document.addEventListener('click', function(event) {
    const avatar = document.getElementById('avatar');
    const dropdown = document.getElementById('dropdownMenu');
    if (!avatar.contains(event.target)) {
      dropdown.style.display = 'none';
    }
  });

  function logout() {
    // Redirect to the PHP logout script
    window.location.href = '../php/loginProcess/logout.php';
  }