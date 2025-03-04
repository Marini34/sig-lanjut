import psycopg2 # type: ignore

# Koneksi ke database
# postgres://default:ZtnC4Dg5lLzI@ep-gentle-smoke-a40kybs6.us-east-1.aws.neon.tech:5432/verceldb?sslmode=require
# conn = psycopg2.connect("postgresql://neondb_owner:WDB1yOzHSk5R@ep-frosty-haze-a1kda0nu.ap-southeast-1.aws.neon.tech/neondb?sslmode=require")
conn = psycopg2.connect("postgres://default:ZtnC4Dg5lLzI@ep-gentle-smoke-a40kybs6.us-east-1.aws.neon.tech:5432/verceldb?sslmode=require")
cur = conn.cursor()

# Menjalankan skrip SQL
sql_script = """
-- Drop tables if they exist
DROP TABLE IF EXISTS produk CASCADE;
DROP TABLE IF EXISTS toko CASCADE;
DROP TABLE IF EXISTS transaksi CASCADE;

-- Create the produk table
CREATE TABLE IF NOT EXISTS produk (
  bar CHAR(13) PRIMARY KEY,
  nama VARCHAR(50) NOT NULL,
  kategori VARCHAR(20) NOT NULL DEFAULT 'lainnya'
);

-- Delete existing data and insert new data into produk table
TRUNCATE TABLE produk RESTART IDENTITY;
INSERT INTO produk (bar, nama, kategori) VALUES
	('1234567890123', 'New Orleans ml', 'Minuman'),
	('1234567890124', 'Paris ml', 'Parfum'),
	('2234567890120', 'Le Mineral 200ml', 'lainnya'),
	('2234567890129', 'Aqua 100ml', 'lainnya');

-- Create the toko table
CREATE TABLE IF NOT EXISTS toko (
  id SERIAL PRIMARY KEY,
  nama VARCHAR(50),
  alamat VARCHAR(50),
  lat DOUBLE PRECISION,
  lng DOUBLE PRECISION
);

-- Delete existing data and insert new data into toko table
TRUNCATE TABLE toko RESTART IDENTITY;
INSERT INTO toko (nama, alamat, lat, lng) VALUES
    ('toko1', 'Jl.Soekarno', -0.04183445144272559, 109.32028965224141),
    ('toko2', 'Jl.Soetoyo', -0.04520722723041784, 109.36358881291396),
    ('toko3', 'Jl.Hatta', -0.05623711446160067, 109.3372446919995);

-- Create the transaksi table with foreign key constraints
CREATE TABLE IF NOT EXISTS transaksi (
  id SERIAL PRIMARY KEY,
  prod_id CHAR(13) NOT NULL,
  toko_id INT NOT NULL,
  harga INT,
  tgl TIMESTAMP NOT NULL,
  jumlah INT,
  FOREIGN KEY (prod_id) REFERENCES produk (bar) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (toko_id) REFERENCES toko (id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Delete existing data and insert new data into transaksi table
TRUNCATE TABLE transaksi RESTART IDENTITY;
INSERT INTO transaksi (prod_id, toko_id, harga, tgl, jumlah) VALUES
    ('1234567890123', 1, 3000, '2024-09-22 02:46:34', 1),
    ('1234567890123', 2, 3000, '2024-09-22 02:46:34', 1),
    ('1234567890123', 3, 3000, '2024-09-22 02:46:34', 1),
    ('1234567890124', 2, 3000, '2024-09-22 02:46:34', 1);

"""

# Menjalankan skrip SQL
cur.execute(sql_script)
conn.commit()

# Menutup koneksi
cur.close()
conn.close()
