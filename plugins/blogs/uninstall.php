<?php

use Chum\ChumDb;

ChumDb::getInstance()->run("DROP TABLE IF EXISTS " . CHUM_DB_PREFIX . "blogs_post;");