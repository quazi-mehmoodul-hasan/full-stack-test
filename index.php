<?php
/**
 * Root entry point — redirect only.
 *
 * The whole project folder is the Apache web root (DocumentRoot), so the public
 * site lives under /public/ and the admin under /admin/. Visiting the bare site
 * root lands here; we just forward to the frontend section.
 *
 * Not consolidated on purpose: we could instead point DocumentRoot at /public
 * and alias /admin, but keeping this thin redirect lets the repo run unchanged
 * on any plain LAMP/MAMP/XAMPP host, not only the bundled Docker setup.
 */
header('Location: /public/');
exit;
