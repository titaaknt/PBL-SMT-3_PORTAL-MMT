// Efek scroll halus ke atas
const backToTopButton = document.createElement("button");
backToTopButton.textContent = "↑";
backToTopButton.className = "btn btn-primary position-fixed bottom-0 end-0 m-4";
backToTopButton.style.display = "none";
document.body.appendChild(backToTopButton);

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    backToTopButton.style.display = "block";
  } else {
    backToTopButton.style.display = "none";
  }
});

backToTopButton.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

// Pesan notifikasi sukses di dashboard
function showAlert(message, type = "success") {
  const alertBox = document.createElement("div");
  alertBox.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
  alertBox.style.zIndex = "1050";
  alertBox.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  document.body.appendChild(alertBox);
  setTimeout(() => alertBox.remove(), 3000);
}