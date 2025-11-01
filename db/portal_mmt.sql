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

-- Tabel karya / proyek
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