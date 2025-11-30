-- Tabel admin untuk login dashboard
CREATE TABLE admin (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_admin VARCHAR(100) NOT NULL
);

-- Data admin awal
INSERT INTO admin (username, password, nama_admin)
VALUES ('admin', md5('123456'), 'Administrator Portal');

-- Tabel profil laboratorium
CREATE TABLE profil (
    id SERIAL PRIMARY KEY,
    visi TEXT,
    misi TEXT,
    struktur TEXT, 
    kontak TEXT
);

-- Data default profil (boleh diupdate nanti dari CMS)
INSERT INTO profil (visi, misi, struktur, kontak)
VALUES (
    'Memposisikan Lab Multimedia & Game sebagai pusat keunggulan yang responsif terhadap kebutuhan industri dan pendidikan tinggi.',
    'Menyelenggarakan pendidikan praktikum dan penelitian berkualitas di bidang teknologi immersive, mobile, multimedia, serta interaktif. Mengembangkan riset dan inovasi berbasis mobile computing, multimedia, VR/AR, dan sensor interaktif. Menyediakan fasilitas laboratorium modern dan relevan dengan perkembangan industri. Mendorong kolaborasi antara mahasiswa, dosen, industri, dan masyarakat. Menghasilkan karya inovatif berupa aplikasi dan publikasi ilmiah yang bermanfaat.',
    'Struktur organisasi laboratorium dalam bentuk bagan hierarki.',
    'Laboratorium Multimedia & Mobile Tech, JTI Polinema. Email: lab.mmt@polinema.ac.id'
);

-- Tabel karya / proyekcompany_tita
CREATE TABLE karya (
    id SERIAL PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    kategori VARCHAR(50),
    tahun INT,
    gambar VARCHAR(255)
);

-- Tabel berita
CREATE TABLE berita (
    id SERIAL PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    isi TEXT,
    tanggal DATE DEFAULT CURRENT_DATE,
    gambar VARCHAR(255)
);

-- Tabel galeri
CREATE TABLE galeri (
    id SERIAL PRIMARY KEY,
    judul VARCHAR(150),
    file_path VARCHAR(255),
    jenis VARCHAR(20)  -- foto / video
);

-- Tambahkan kolom admin_id di tabel karya
ALTER TABLE karya
ADD COLUMN admin_id INT;

-- Baru tambahkan foreign key ke tabel admin
ALTER TABLE karya
ADD CONSTRAINT fk_karya_admin
FOREIGN KEY (admin_id) REFERENCES admin(id)
ON DELETE CASCADE;

-- Tambahkan kolom admin_id di tabel berita
ALTER TABLE berita
ADD COLUMN admin_id INT;

ALTER TABLE berita
ADD CONSTRAINT fk_berita_admin
FOREIGN KEY (admin_id) REFERENCES admin(id)
ON DELETE CASCADE;

-- Tambahkan kolom admin_id di tabel gakALTER TABLE galeri
ADD COLUMN admin_id INT;

ALTER TABLE galeri
ADD CONSTRAINT fk_galeri_admin
FOREIGN KEY (admin_id) REFERENCES admin(id)
ON DELETE CASCADE;

-- 1. Tabel kontak_detail (CRUD untuk info kontak di profil)
CREATE TABLE kontak_detail (
    id SERIAL PRIMARY KEY,
    icon VARCHAR(100),        -- ikon seperti 'bi bi-envelope', 'bi bi-geo-alt'
    link TEXT,                -- bisa isi 'mailto:lab.mmt@polinema.ac.id' atau URL maps
    keterangan VARCHAR(255),  -- misal: 'Email', 'Alamat', 'Telepon'
    admin_id INT,
    CONSTRAINT fk_kontak_admin FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE CASCADE
);

-- 2. Tabel galeri_terbaru (gambar terbaru untuk ditampilkan di halaman utama)
CREATE TABLE galeri_terbaru (
    id SERIAL PRIMARY KEY,
    gambar VARCHAR(255) NOT NULL,
    admin_id INT,
    CONSTRAINT fk_galeri_terbaru_admin FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE CASCADE
);

-- 3. Tabel kategori_galeri (kategori utama galeri)
CREATE TABLE kategori_galeri (
    id SERIAL PRIMARY KEY,
    judul VARCHAR(100) NOT NULL,
    admin_id INT,
    CONSTRAINT fk_kategori_admin FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE CASCADE
);

-- 4. Tabel detail_kategori_galeri (isi tiap kategori)
CREATE TABLE detail_kategori_galeri (
    id SERIAL PRIMARY KEY,
    kategori_id INT NOT NULL,         -- relasi ke kategori_galeri
    gambar VARCHAR(255) NOT NULL,
    judul VARCHAR(150),
    CONSTRAINT fk_detail_kategori FOREIGN KEY (kategori_id) REFERENCES kategori_galeri(id) ON DELETE CASCADE
);

ALTER TABLE detail_kategori_galeri
ADD COLUMN tanggal DATE DEFAULT CURRENT_DATE;

ALTER TABLE karya DROP COLUMN tahun;
ALTER TABLE karya ADD COLUMN tanggal DATE DEFAULT CURRENT_DATE;

ALTER TABLE galeri
ADD COLUMN kategori VARCHAR(100),
ADD COLUMN tanggal DATE DEFAULT CURRENT_DATE;

ALTER TABLE galeri ADD COLUMN kategori_id INT;
ALTER TABLE galeri
ADD CONSTRAINT fk_galeri_kategori
FOREIGN KEY (kategori_id) REFERENCES kategori_galeri(id)
ON DELETE SET NULL;

INSERT INTO kategori_galeri (judul, admin_id)
VALUES
('Kompetisi & Acara internal', 1),
('Kegiatan Akademik ', 1),
('Prestasi', 1);

ALTER TABLE admin ADD COLUMN temp_password VARCHAR(255);