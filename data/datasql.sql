CREATE TABLE users (
    id_users INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    kontak VARCHAR(15),
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    reset_token VARCHAR(64),
    reset_token_expiry DATETIME,
    role ENUM('pemilik', 'staff', 'penghuni') NOT NULL
);

CREATE TABLE lokasi_kamar (
  id_lokasi INT PRIMARY KEY AUTO_INCREMENT,
  nama_lokasi VARCHAR(50)
);

CREATE TABLE kamar (
  id_kamar INT PRIMARY KEY AUTO_INCREMENT,
  id_lokasi INT,
  nomor_kamar VARCHAR(10),
  harga_kamar INT,
  status_kamar VARCHAR(50),
  FOREIGN KEY (id_lokasi) REFERENCES lokasi_kamar(id_lokasi)
);

CREATE TABLE penghuni (
  id_penghuni INT PRIMARY KEY AUTO_INCREMENT,
  id_users INT NOT NULL,
  id_kamar INT NOT NULL,
  tanggal_registrasi_penghuni DATE DEFAULT CURRENT_DATE,
  tanggal_masuk_penghuni DATE,
  tanggal_keluar_penghuni DATE,
  identitas_penghuni VARCHAR(255),
  keterangan_penghuni TEXT,
  status_penghuni ENUM('aktif', 'tidak aktif') DEFAULT 'aktif',
  FOREIGN KEY (id_users) REFERENCES users(id_users),
  FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar)
);

CREATE TABLE tagihan (
  id_tagihan INT PRIMARY KEY AUTO_INCREMENT,
  id_penghuni INT,
  no_tagihan VARCHAR(50),
  kategori_tagihan VARCHAR(255),
  deskripsi_tagihan VARCHAR(255),
  jumlah_tagihan INT,
  jumlah_bayar_tagihan INT,
  jumlah_sisa_tagihan INT,
  tanggal_tagihan DATE,
  status_tagihan ENUM('Lunas', 'Belum Lunas', 'Belum Bayar') DEFAULT 'Belum Bayar';
  keterangan_tagihan VARCHAR(255),
  FOREIGN KEY (id_penghuni) REFERENCES penghuni(id_penghuni)
);

CREATE TABLE penghuni_in_house (
  id_pih INT PRIMARY KEY AUTO_INCREMENT,
  id_penghuni INT,
  tanggal_masuk_pih DATE,
  tanggal_keluar_pih DATE,
  durasi_sewa_pih VARCHAR(50),
  FOREIGN KEY (id_penghuni) REFERENCES penghuni(id_penghuni)
);

CREATE TABLE keluhan (
  id_keluhan INT PRIMARY KEY,
  id_penghuni INT,
  tanggal_keluhan DATETIME,
  gambar_keluhan VARCHAR(255),
  isi_keluhan TEXT,
  status_keluhan ENUM('Belum Ditanggapi', 'Sedang Ditanggapi', 'Sudah Ditanggapi') NOT NULL DEFAULT 'Belum Ditanggapi',
  FOREIGN KEY (id_penghuni) REFERENCES penghuni(id_penghuni)
);

CREATE TABLE pengeluaran (
  id_pengeluaran INT(11) NOT NULL AUTO_INCREMENT,
  keterangan_pengeluaran VARCHAR(100) NOT NULL,
  nomimal_pengeluaran INT(11) NOT NULL,
  tanggal_pengeluaran DATE NOT NULL,
  bukti_bayar VARCHAR(255) NOT NULL
);

CREATE TABLE rekening (
  id_rekening INT(11) NOT NULL AUTO_INCREMENT,
  jenis_pembayaran VARCHAR(255) NOT NULL,
  nomor_rekening VARCHAR(255) NOT NULL,
  pemilik_rekening VARCHAR(255) NOT NULL
);

CREATE TABLE pindah_kamar (
  id_pindah_kamar INT PRIMARY KEY AUTO_INCREMENT,
  id_penghuni INT,
  kamar_sebelum INT,
  kamar_sesudah INT,
  waktu_pindah DATETIME,
  alasan_pindah TEXT,
  FOREIGN KEY (id_penghuni) REFERENCES penghuni (id_penghuni),
  FOREIGN KEY (kamar_sebelum) REFERENCES kamar (id_kamar),
  FOREIGN KEY (kamar_sesudah) REFERENCES kamar (id_kamar)
);

CREATE TABLE check_out (
  id_checkout INT PRIMARY KEY AUTO_INCREMENT,
  id_penghuni INT NOT NULL,
  id_kamar INT NOT NULL,
  tanggal_checkout DATE,
  keterangan_checkout VARCHAR(255),
  FOREIGN KEY (id_penghuni) REFERENCES penghuni(id_penghuni),
  FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar)
);
