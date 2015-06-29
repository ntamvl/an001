-- --------------------------------------------------------
-- WPXtreme Countries Table SQL Dump
--
-- Manage countries and currency
--
-- @table        {wpprefix}_wpx_countries
-- @author       =undo= <info@wpxtre.me>
-- @copyright    Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
-- @version      1.0.0
-- @since        1.4.0
--
-- --------------------------------------------------------

CREATE TABLE %s (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID Zone-Country',
  zone varchar(255) NOT NULL DEFAULT '' COMMENT 'Zone',
  country varchar(255) NOT NULL DEFAULT '' COMMENT 'Country name',
  isocode char(2) DEFAULT '' COMMENT 'ISO CODE',
  currency varchar(255) NOT NULL DEFAULT '' COMMENT 'Currency',
  symbol varchar(10) NOT NULL DEFAULT '' COMMENT 'Currency symbol',
  symbol_html varchar(10) NOT NULL DEFAULT '' COMMENT 'HTML currency symbol',
  code char(3) NOT NULL DEFAULT '' COMMENT 'Code',
  tax decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Tax',
  continent varchar(20) NOT NULL DEFAULT '' COMMENT 'Continent',
  status enum('publish','trash') NOT NULL DEFAULT 'publish' COMMENT 'Status of record',
  PRIMARY KEY (id),
  KEY status (status)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Manage the country currency and tax';