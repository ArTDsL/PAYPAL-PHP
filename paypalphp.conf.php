<?php
/**
 *
 * PayPal PHP
 * 
 * @author: "Arthur 'ArTDsL' Dias dos Santos Lasso";
 * @version: "1.0.0.0";
 * 
 * Dev At: "2022-01-25";
 * Last Update: "2022-01-26";
 * 
 * file: 'paypalphp.conf.php';
 *
 * Copyright (c) 2022. Arthur 'ArTDsL' Dias dos Santos Lasso.
 * This  program  is  free  software:  you can  redistribute it  and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the  License, or (at your 
 * option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Github Repo: "https://github.com/ArTDsL/paypal-php/"
 *
 */
/*
 * SandBox Mode
 *  --Recommend for setup and test.
 */
define("SANDBOX_MODE", TRUE);
define("DEBUG_MODE", TRUE);
/*
 *	Access Configuration
 *   -- Tokens and connection parameters...
 */
define("__CLIENT_ID_TOKEN_SANDBOX__", "__YOUR_SANDBOX_CLIENT_ID_HERE__");
define("__SECRET_TOKEN_SANDBOX__", "__YOUR_SANDBOX_SECRET_TOKEN_HERE__");
//
define("__CLIENT_ID_TOKEN_LIVE__", "__YOUR_LIVE_CLIENT_ID_HERE__");
define("__SECRET_TOKEN_LIVE__", "__YOUR_LIVE_CLIENT_ID_HERE__");
/*
 * Payment Options
 *
 */
define("_PAYMENT_PREFIX_", "PPPHP");
define("_PAYMENT_CURRENCY_CODE_", "BRL");
/*
 * IPS Configuration
 *  -- IPS Callback configuration (come with PAYPAL-PHP IPS default check Wiki for more information)
 */
define("__URL_IPS__", "https://localhost/paypal-php/ips.php");
/*
 * Other
 *  -- Don't need to change...
 */
define('_PLUGIN_ERROR_ID_', 'PAYPAL-PHP :: ');
