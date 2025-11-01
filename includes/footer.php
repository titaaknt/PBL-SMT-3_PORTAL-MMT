</div> <!-- end container -->

<footer class="footer text-white mt-5">
  <div class="container py-4">
    <div class="row align-items-start text-center text-md-start">
      
      <!-- Kolom 1: Nama Lab -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold text-warning">Laboratorium Multimedia & Mobile Tech</h5>
        <p class="mb-1">Jurusan Teknologi Informasi</p>
        <p>Politeknik Negeri Malang</p>
        <div class="mt-3 d-flex justify-content-center justify-content-md-start gap-3">
          <img src="assets/img/jti.png" alt="Logo JTI" class="footer-logo">
          <img src="assets/img/polinema.png" alt="Logo Polinema" class="footer-logo">
        </div>
      </div>

      <!-- Kolom 2: Kontak -->
      <div class="col-md-4 mb-4">
        <h6 class="fw-bold text-warning mb-3">Kontak Kami</h6>
        <p><i class="bi bi-geo-alt-fill"></i> Jalan Soekarno Hatta No.9, Malang</p>
        <p><i class="bi bi-envelope-fill"></i> lab.mmt@polinema.ac.id</p>
        <p><i class="bi bi-telephone-fill"></i> (0341) 404424 ext. 231</p>
      </div>

      <!-- Kolom 3: Lokasi -->
      <div class="col-md-4 mb-4">
        <h6 class="fw-bold text-warning mb-3">Lokasi Kami</h6>
        <div class="map-container">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.506868340879!2d112.61507317500345!3d-7.948468279184024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd629d81a4327b9%3A0x1fcbf6a686c2b431!2sPoliteknik%20Negeri%20Malang!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
            allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>

    </div>
  </div>

  <div class="footer-bottom text-center py-2">
    <small>© 2025 Laboratorium Multimedia & Mobile Tech - Politeknik Negeri Malang</small>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<style>
.footer {
  background: linear-gradient(180deg, #021324ff 0%, #021324ff 100%);
  padding-top: 40px;
  padding-bottom: 20px;
}

.footer h5, .footer h6 {
  color: #FFD43B;
  font-weight: 700;
}

.footer p, .footer small {
  color: #f1f1f1;
  font-size: 0.95rem;
  margin-bottom: 0.5rem;
}

.footer-logo {
  width: 65px;
  height: auto;
  transition: transform 0.3s ease;
}
.footer-logo:hover {
  transform: scale(1.08);
}

.map-container iframe {
  border: 0;
  width: 100%;
  height: 200px;
  border-radius: 10px;
  box-shadow: 0 0 8px rgba(0,0,0,0.3);
}

.footer-bottom {
  background-color: rgba(0,0,0,0.35);
  margin-top: 15px;
}

@media (max-width: 768px) {
  .footer {
    text-align: center;
  }
  .footer-logo {
    width: 55px;
  }
  .map-container {
    margin-top: 1rem;
  }
}
</style>