import psycopg2

# Koneksi ke database
conn = psycopg2.connect("postgresql://neondb_owner:WDB1yOzHSk5R@ep-frosty-haze-a1kda0nu.ap-southeast-1.aws.neon.tech/neondb?sslmode=require")
cur = conn.cursor()

# Menjalankan skrip SQL
sql_script = """
-- Menghapus tabel jika ada dan membuat tabel produk
DROP TABLE IF EXISTS produk CASCADE;
CREATE TABLE produk (
  id CHAR(13) NOT NULL PRIMARY KEY,
  name VARCHAR(50) NOT NULL
);

-- Menghapus data untuk tabel produk dan menyisipkan data baru
DELETE FROM produk;
INSERT INTO produk (id, name) VALUES
	('2234567890120', 'Aqua 100ml'),
	('2234567890121', 'Sari Roti'),
	('1234567890123', 'New Orleans ml'),
	('1234567890124', 'Paris ml'),
	('2234567890129', 'Le Mineral');

-- Menghapus tabel jika ada dan membuat tabel toko
DROP TABLE IF EXISTS toko CASCADE;
CREATE TABLE toko (
  id SERIAL PRIMARY KEY,
  name VARCHAR(30),
  alamat VARCHAR(50),
  lat DOUBLE PRECISION,
  lng DOUBLE PRECISION
);

-- Menghapus data untuk tabel toko dan menyisipkan data baru
DELETE FROM toko;
INSERT INTO toko (id, name, alamat, lat, lng) VALUES
	(1, 'toko1', 'Jl.Soekarno', -0.04183445144272559, 109.32028965224141),
	(2, 'toko2', 'Jl.Soetoyo', -0.04520722723041784, 109.36358881291396),
	(3, 'toko3', 'Jl.Hatta', -0.05623711446160067, 109.3372446919995),
	(4, 'Toko Budi', 'Jl. Keramat Jati', -0.05623711446160067, 109.3372446919995),
	(5, 'Toko Budi', 'Jl. Keramat Jati', -0.05623711446160067, 109.3372446919995),
	(6, 'Toko Budi', 'Jl. Keramat Jati', -0.05623711446160067, 109.3372446919995);

-- Menghapus tabel jika ada dan membuat tabel pengguna
DROP TABLE IF EXISTS pengguna CASCADE;
CREATE TABLE pengguna (
  id SERIAL PRIMARY KEY,
  name VARCHAR(32) NOT NULL,
  email VARCHAR(32) NOT NULL,
  telp VARCHAR(13) NOT NULL,
  pin CHAR(6) NOT NULL,
  UNIQUE (email, telp)
);

-- Menghapus data untuk tabel pengguna dan menyisipkan data baru
DELETE FROM pengguna;
INSERT INTO pengguna (id, name, email, telp, pin) VALUES
	(1, 'Budi Nugraha', 'a@a.a', '081234567891', '123456'),
	(2, 'Andi Sebastian', 'c@c.c', '082148213646', '123458');

-- Menghapus tabel jika ada dan membuat tabel transaksi
DROP TABLE IF EXISTS transaksi CASCADE;
CREATE TABLE transaksi (
  id SERIAL PRIMARY KEY,
  prod_id CHAR(13) NOT NULL,
  toko_id INT NOT NULL,
  harga INT,
  pengguna_id INT NOT NULL,
  FOREIGN KEY (prod_id) REFERENCES produk (id),
  FOREIGN KEY (toko_id) REFERENCES toko (id),
  FOREIGN KEY (pengguna_id) REFERENCES pengguna (id)
);

-- Menghapus data untuk tabel transaksi dan menyisipkan data baru
DELETE FROM transaksi;
INSERT INTO transaksi (id, prod_id, toko_id, harga, pengguna_id) VALUES
	(5, '1234567890123', 1, 3000, 1),
	(7, '2234567890121', 2, 3000, 1);
"""

# Menjalankan skrip SQL
cur.execute(sql_script)
conn.commit()

# Menutup koneksi
cur.close()
conn.close()
