create database donasikuy;
use donasikuy;

create table users (
id_user int primary key not null,
nama varchar(128),
email varchar(255),
password_user varchar(255),
nomor_telp varchar(16),
alamat text,
tanggal_regis date,
foto_profil longblob,
jumlah_donasi double(15,2),
jumlah_kampanye int
);

create table admins (
id_admin int primary key not null,
nama_admin varchar(128) not null,
email_admin varchar(255) not null,
password_admin varchar(255) not null
);

create table kategori_kampanye (
id_kategori int primary key not null,
nama_kategori varchar(255) not null
);

create table badge (
id_badge int primary key not null,
nama_badge varchar(64),
deskripsi text,
jumlah_minimal double(15,2),
foto_icon longblob
);

create table kampanye (
id_kampanye int primary key not null,
id_user int,
id_kategori int,
id_admin int,
judul_kampanye varchar(255),
deskripsi text,
jumlah_target int,
jumlah_terkumpul double(15,2),
tanggal_mulai date,
tanggal_selesai date,
status_kampanye enum('open', 'closed'),
gambar_kampanye longblob,
foreign key (id_user) references users (id_user),
foreign key (id_kategori) references kategori_kampanye (id_kategori),
foreign key (id_admin) references admins (id_admin)
);

create table donasi (
id_donasi int primary key not null,
id_user int not null,
id_kampanye int not null,
jumlah_donasi double(15,2),
metode_pembayaran varchar(255),
tanggal_donasi date,
status_donasi enum ('terkirim', 'pending'),
foreign key (id_user) references users(id_user),
foreign key (id_kampanye) references kampanye(id_kampanye)
);

create table donasi_bulanan (
id_bulanan int primary key not null,
id_user int not null,
id_kampanye int not null,
jumlah double(15,2),
tanggal_mulai date,
pembayaran_selanjutnya varchar(255),
status_bulanan varchar(255),
foreign key (id_user) references users(id_user),
foreign key (id_kampanye) references kampanye (id_kampanye)
);

create table users_badge (
id_user int not null,
id_badge int not null,
tanggal_diraih date,
foreign key (id_user) references users(id_user),
foreign key (id_badge) references badge(id_badge)
);