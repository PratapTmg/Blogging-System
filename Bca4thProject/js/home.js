//description trimer
document.querySelectorAll(".blog-description").forEach((description) => {
  const maxLength = 60;
  let text = description.textContent;

  if (text.length > maxLength) {
    description.textContent = text.slice(0, maxLength) + "...";
  }
});

document.querySelectorAll(".blog-title").forEach((title) => {
  const maxLength = 25;
  let text = title.textContent;

  if (text.length > maxLength) {
    title.textContent = text.slice(0, maxLength) + "...";
  }
});

document.getElementById("avatar").addEventListener("click", function () {
  var content = document.getElementById("dropdownContent");

  // Toggle the 'show' class to make the dropdown visible or hidden
  content.classList.toggle("show");
});
