-- DelphianLogic in Action — schema
-- Auto-loaded by the mysql container on first boot (00 prefix = runs before seed).

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS tabs (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name        VARCHAR(100) NOT NULL,
  slug        VARCHAR(120) NOT NULL,
  icon        VARCHAR(150) NOT NULL DEFAULT '',     -- SVG filename in public/assets/icons
  sort_order  INT NOT NULL DEFAULT 0,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_tabs_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS slides (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tab_id          INT UNSIGNED NOT NULL,
  category_label  VARCHAR(150) NOT NULL DEFAULT '',
  title           VARCHAR(255) NOT NULL,
  link_text       VARCHAR(100) NOT NULL DEFAULT 'Learn More',
  link_url        VARCHAR(255) NOT NULL DEFAULT '#',
  image_path      VARCHAR(255) NOT NULL DEFAULT '',   -- relative to public/, e.g. uploads/foo.jpg
  sort_order      INT NOT NULL DEFAULT 0,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_slides_tab (tab_id),
  CONSTRAINT fk_slides_tab FOREIGN KEY (tab_id) REFERENCES tabs (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
