-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--

CREATE TABLE IF NOT EXISTS 'users' (
  'id' int(10) unsigned NOT NULL AUTO_INCREMENT,
  'client_id' int(10) unsigned NOT NULL,
  'first_name' varchar(20) NOT NULL,
  'last_name' varchar(20) NOT NULL,
  'passwd' varchar(100) NOT NULL,
  'email' varchar(100) NOT NULL,
  'droppage' varchar(100) DEFAULT NULL,
  'skin' varchar(45) DEFAULT NULL,
  'advertiser_id' int(10) unsigned NOT NULL,
  PRIMARY KEY ('id')
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

INSERT INTO users
(id, client_id, first_name, last_name, passwd, email, droppage, skin, advertiser_id) VALUES
(0,  1, 'Vern', 'Six', 'Y29tcGFx', 'vern@vernsix.com', '', 'cupertino',  0);