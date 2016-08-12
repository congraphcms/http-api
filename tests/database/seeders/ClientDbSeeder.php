<?php
/*
 * This file is part of the cookbook/api package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
/**
 * ClientDbSeeder
 * 
 * Seeds Database with needed entries before tests
 * 
 * @uses   		Illuminate\Database\Schema\Blueprint
 * @uses   		Illuminate\Database\Seeder
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ClientDbSeeder extends Seeder {

	public function run()
	{
		DB::table('oauth_clients')->delete();
		DB::table('oauth_clients')->insert([
			[
				'id' => 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR',
				'secret' => '3wMlLnCBONHSlrxUJESPm1VwF9kBnHEGcCFt8iVR',
				'name' => 'Test Client',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]
		]);

		DB::table('oauth_sessions')->delete();
		$session_id = DB::table('oauth_sessions')->insertGetId(
			[
				'client_id' => 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR',
				'owner_id' => 'iuqp7E9myPGkoKuyvI9Jo06gIor2WsiivuUbuobR',
				'owner_type' => 'client',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]
		);

		DB::table('oauth_access_tokens')->delete();
		DB::table('oauth_access_tokens')->insert([
			[
				'id' => 'e4qrk81UaGtbrJKNY3X5qe2vIn4A1cC3jzDeL9zz',
				'session_id' => $session_id,
				'expire_time' => 147092935900,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]
		]);
	}

}