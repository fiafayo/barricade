CREATE TABLE tambah_sks(
  id INTEGER NOT NULL AUTO_INCREMENT,
  tahun_semester INTEGER NOT NULL default 20121,
  nrp INTEGER NOT NULL ,
  sks INTEGER NOT NULL default 2,
  alasan VARCHAR(16) NOT NULL default 'prestasi',
  npk INTEGER NOT NULL,
  created_at TIMESTAMP ,
  updated_at TIMESTAMP,
  primary key (id)
);

CREATE TABLE semester(
  id INTEGER NOT NULL  default 20121,  
  status_aktif SMALLINT NOT NULL default 0,
  primary key (id)
);

CREATE TABLE buka_lock_ip2(
  id INTEGER NOT NULL AUTO_INCREMENT,
  tahun_semester INTEGER NOT NULL default 20121,
  nrp INTEGER NOT NULL ,
  npk INTEGER NOT NULL,
  updated_at TIMESTAMP,
  primary key (id)
);
