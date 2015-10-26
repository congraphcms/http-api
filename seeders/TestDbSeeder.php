<?php
/*
 * This file is part of the cookbook/eav package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Api\Seeders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
/**
 * TestDbSeeder
 * 
 * Populates DB with data for testing
 * 
 * @uses   		Illuminate\Database\Schema\Blueprint
 * @uses   		Illuminate\Database\Seeder
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/eav
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class TestDbSeeder extends Seeder {

	public function run()
	{
		DB::table('entity_types')->truncate();

		DB::table('entity_types')->insert([
			[
				'code' => 'tests',
				'name' => 'Test',
				'plural_name' => 'Tests',
				'multiple_sets' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'tests2',
				'name' => 'Test2',
				'plural_name' => 'Tests2',
				'multiple_sets' => 0,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'tests3',
				'name' => 'Test3',
				'plural_name' => 'Tests3',
				'multiple_sets' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]
		]);

		DB::table('attributes')->truncate();

		DB::table('attributes')->insert([
			[
				'code' => 'attribute1',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '',
				'unique' => false,
				'required' => true,
				'filterable' => false,
				'status' => 'system_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute2',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '',
				'unique' => false,
				'required' => false,
				'filterable' => false,
				'status' => 'user_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute3',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '',
				'unique' => true,
				'required' => false,
				'filterable' => false,
				'status' => 'user_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute4',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '',
				'unique' => false,
				'required' => false,
				'filterable' => true,
				'status' => 'user_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute5',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '123',
				'unique' => false,
				'required' => true,
				'filterable' => false,
				'status' => 'system_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute6',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '',
				'unique' => false,
				'required' => false,
				'filterable' => false,
				'status' => 'user_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute7',
				'field_type' => 'text',
				'table' => 'attribute_values_varchar',
				'localized' => false,
				'default_value' => '',
				'unique' => false,
				'required' => false,
				'filterable' => true,
				'status' => 'system_defined',
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
		]);

		DB::table('attribute_sets')->truncate();
		DB::table('set_attributes')->truncate();

		DB::table('attribute_sets')->insert([
			[
				'code' => 'attribute_set1',
				'name' => 'Attribute Set 1',
				'entity_type_id' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute_set2',
				'name' => 'Attribute Set 2',
				'entity_type_id' => 2,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'code' => 'attribute_set3',
				'name' => 'Attribute Set 3',
				'entity_type_id' => 3,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]
		]);

		DB::table('set_attributes')->insert([
			[
				'attribute_set_id' => 1,
				'attribute_id' => 1,
				'sort_order' => 1
			],
			[
				'attribute_set_id' => 1,
				'attribute_id' => 2,
				'sort_order' => 0
			],
			[
				'attribute_set_id' => 1,
				'attribute_id' => 3,
				'sort_order' => 2
			],
			[
				'attribute_set_id' => 2,
				'attribute_id' => 3,
				'sort_order' => 0
			],
			[
				'attribute_set_id' => 2,
				'attribute_id' => 4,
				'sort_order' => 1
			],
			[
				'attribute_set_id' => 3,
				'attribute_id' => 5,
				'sort_order' => 3
			],
			[
				'attribute_set_id' => 3,
				'attribute_id' => 6,
				'sort_order' => 2
			],
			[
				'attribute_set_id' => 3,
				'attribute_id' => 7,
				'sort_order' => 1
			]
		]);

		DB::table('entities')->truncate();
		DB::table('entities')->insert([
			[
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			],
			[
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s")
			]
		]);

		DB::table('attribute_values_varchar')->truncate();
		DB::table('attribute_values_varchar')->insert([
			[
				'entity_id' => 1,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 1,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value1',
			],
			[
				'entity_id' => 1,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 2,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value2',
			],
			[
				'entity_id' => 1,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 3,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value3',
			],
			[
				'entity_id' => 2,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 1,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value21',
			],
			[
				'entity_id' => 2,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 2,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value22',
			],
			[
				'entity_id' => 2,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 3,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value23',
			],
			[
				'entity_id' => 3,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 1,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value31',
			],
			[
				'entity_id' => 3,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 2,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value32',
			],
			[
				'entity_id' => 3,
				'entity_type_id' => 1,
				'attribute_set_id' => 1,
				'attribute_id' => 3,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value33',
			],


			[
				'entity_id' => 4,
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'attribute_id' => 3,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value43',
			],
			[
				'entity_id' => 4,
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'attribute_id' => 4,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value44',
			],
			[
				'entity_id' => 5,
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'attribute_id' => 3,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value53',
			],
			[
				'entity_id' => 5,
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'attribute_id' => 4,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value54',
			],
			[
				'entity_id' => 6,
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'attribute_id' => 3,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value63',
			],
			[
				'entity_id' => 6,
				'entity_type_id' => 2,
				'attribute_set_id' => 2,
				'attribute_id' => 4,
				'locale_id' => 0,
				'sort_order' => 0,
				'value' => 'value64',
			]
		]);
	}

}