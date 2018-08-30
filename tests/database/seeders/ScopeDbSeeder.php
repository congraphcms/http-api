<?php
/*
 * This file is part of the congraph/api package.
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
 * ScopeDbSeeder
 *
 * Seeds Database with needed entries before tests
 *
 * @uses   		Illuminate\Database\Schema\Blueprint
 * @uses   		Illuminate\Database\Seeder
 *
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/api
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class ScopeDbSeeder extends Seeder {

	public function run()
	{
		DB::table('oauth_scopes')->delete();
		DB::table('oauth_scopes')->insert([
			[
				'id' => 'manage_users',
				'label' => 'Manage Users',
				'description' => 'Allows user to manage other user accounts.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'read_users',
				'label' => 'Read Users',
				'description' => 'Allows user to read other user accounts.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'manage_roles',
				'label' => 'Manage Roles',
				'description' => 'Allows user to manage user rols.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'read_roles',
				'label' => 'Read Roles',
				'description' => 'Allows user to read user rols.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'manage_clients',
				'label' => 'Manage Clients',
				'description' => 'Allows user to manage OAuth clients.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'read_clients',
				'label' => 'Read Clients',
				'description' => 'Allows user to read OAuth clients.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'manage_content_model',
				'label' => 'Manage Content Model',
				'description' => 'Allows user to manage entity types and attributes.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'read_content_model',
				'label' => 'Read Content Model',
				'description' => 'Allows user to read entity types and attributes.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'manage_content',
				'label' => 'Manage Content',
				'description' => 'Allows user to manage all content.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'id' => 'read_content',
				'label' => 'Read Content',
				'description' => 'Allows user to read all content.',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			// [
			// 	'id' => 'create_entities',
			// 	'label' => 'Create Content',
			// 	'description' => 'Allows user to create and read own content.',
			// 	'created_at' => date("Y-m-d H:i:s"),
			// 	'updated_at' => date("Y-m-d H:i:s")
			// ],
			// [
			// 	'id' => 'update_entities',
			// 	'label' => 'Update Content',
			// 	'description' => 'Allows user to change and read someone else\'s content.',
			// 	'created_at' => date("Y-m-d H:i:s"),
			// 	'updated_at' => date("Y-m-d H:i:s")
			// ],
		]);

	}

}
