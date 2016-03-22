<?php
/*
 * This file is part of the Phinx package.
 *
 * (c) Rob Morgan <robbym@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Use the notation:
 *
 * defined(...) || define(...);
 *
 * This ensures that, when a test is marked to run in a separate process,
 * PHP will not complain of a constant already being defined.
 */
/**
 * Phinx_Db_Adapter_MysqlAdapter
 */
defined('TESTS_ENV') || define('TESTS_ENV', getenv('TESTS_ENV'));
defined('COOKIE_FILE') || define('COOKIE_FILE', getenv('/tmp/ptphp.cookie'));
