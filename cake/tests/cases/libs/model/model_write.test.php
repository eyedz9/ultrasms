<?php
/* SVN FILE: $Id: model.test.php 8225 2009-07-08 03:25:30Z mark_story $ */

/**
 * ModelWriteTest file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/view/1196/Testing>
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/view/1196/Testing CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model
 * @since         CakePHP(tm) v 1.2.0.4206
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
require_once dirname(__FILE__) . DS . 'model.test.php';
/**
 * ModelWriteTest
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.operations
 */
class ModelWriteTest extends BaseModelTest {

/**
 * testInsertAnotherHabtmRecordWithSameForeignKey method
 *
 * @access public
 * @return void
 */
	function testInsertAnotherHabtmRecordWithSameForeignKey() {
		$this->loadFixtures('JoinA', 'JoinB', 'JoinAB');
		$TestModel = new JoinA();

		$result = $TestModel->JoinAsJoinB->findById(1);
		$expected = array(
			'JoinAsJoinB' => array(
				'id' => 1,
				'join_a_id' => 1,
				'join_b_id' => 2,
				'other' => 'Data for Join A 1 Join B 2',
				'created' => '2008-01-03 10:56:33',
				'updated' => '2008-01-03 10:56:33'
		));
		$this->assertEqual($result, $expected);

		$TestModel->JoinAsJoinB->create();
		$result = $TestModel->JoinAsJoinB->save(array(
			'join_a_id' => 1,
			'join_b_id' => 1,
			'other' => 'Data for Join A 1 Join B 1',
			'created' => '2008-01-03 10:56:44',
			'updated' => '2008-01-03 10:56:44'
		));
		$this->assertTrue($result);
		$lastInsertId = $TestModel->JoinAsJoinB->getLastInsertID();
		$this->assertTrue($lastInsertId != null);

		$result = $TestModel->JoinAsJoinB->findById(1);
		$expected = array(
			'JoinAsJoinB' => array(
				'id' => 1,
				'join_a_id' => 1,
				'join_b_id' => 2,
				'other' => 'Data for Join A 1 Join B 2',
				'created' => '2008-01-03 10:56:33',
				'updated' => '2008-01-03 10:56:33'
		));
		$this->assertEqual($result, $expected);

		$updatedValue = 'UPDATED Data for Join A 1 Join B 2';
		$TestModel->JoinAsJoinB->id = 1;
		$result = $TestModel->JoinAsJoinB->saveField('other', $updatedValue, false);
		$this->assertTrue($result);

		$result = $TestModel->JoinAsJoinB->findById(1);
		$this->assertEqual($result['JoinAsJoinB']['other'], $updatedValue);
	}

/**
 * testSaveDateAsFirstEntry method
 *
 * @access public
 * @return void
 */
	function testSaveDateAsFirstEntry() {
		$this->loadFixtures('Article');

		$Article =& new Article();

		$data = array(
			'Article' => array(
				'created' => array(
					'day' => '1',
					'month' => '1',
					'year' => '2008'
				),
				'title' => 'Test Title',
				'user_id' => 1
		));
		$Article->create();
		$this->assertTrue($Article->save($data));

		$testResult = $Article->find(array('Article.title' => 'Test Title'));

		$this->assertEqual($testResult['Article']['title'], $data['Article']['title']);
		$this->assertEqual($testResult['Article']['created'], '2008-01-01 00:00:00');

	}

/**
 * testUnderscoreFieldSave method
 *
 * @access public
 * @return void
 */
	function testUnderscoreFieldSave() {
		$this->loadFixtures('UnderscoreField');
		$UnderscoreField =& new UnderscoreField();

		$currentCount = $UnderscoreField->find('count');
		$this->assertEqual($currentCount, 3);
		$data = array('UnderscoreField' => array(
			'user_id' => '1',
			'my_model_has_a_field' => 'Content here',
			'body' => 'Body',
			'published' => 'Y',
			'another_field' => 4
		));
		$ret = $UnderscoreField->save($data);
		$this->assertTrue($ret);

		$currentCount = $UnderscoreField->find('count');
		$this->assertEqual($currentCount, 4);
	}

/**
 * testAutoSaveUuid method
 *
 * @access public
 * @return void
 */
	function testAutoSaveUuid() {
		// SQLite does not support non-integer primary keys
		$this->skipIf($this->db->config['driver'] == 'sqlite');

		$this->loadFixtures('Uuid');
		$TestModel =& new Uuid();

		$TestModel->save(array('title' => 'Test record'));
		$result = $TestModel->findByTitle('Test record');
		$this->assertEqual(
			array_keys($result['Uuid']),
			array('id', 'title', 'count', 'created', 'updated')
		);
		$this->assertEqual(strlen($result['Uuid']['id']), 36);
	}

/**
 * Ensure that if the id key is null but present the save doesn't fail (with an
 * x sql error: "Column id specified twice")
 *
 * @return void
 * @access public
 */
	function testSaveUuidNull() {
		// SQLite does not support non-integer primary keys
		$this->skipIf($this->db->config['driver'] == 'sqlite');

		$this->loadFixtures('Uuid');
		$TestModel =& new Uuid();

		$TestModel->save(array('title' => 'Test record', 'id' => null));
		$result = $TestModel->findByTitle('Test record');
		$this->assertEqual(
			array_keys($result['Uuid']),
			array('id', 'title', 'count', 'created', 'updated')
		);
		$this->assertEqual(strlen($result['Uuid']['id']), 36);
	}

/**
 * testZeroDefaultFieldValue method
 *
 * @access public
 * @return void
 */
	function testZeroDefaultFieldValue() {
		$this->skipIf(
			$this->db->config['driver'] == 'sqlite',
			'%s SQLite uses loose typing, this operation is unsupported'
		);
		$this->loadFixtures('DataTest');
		$TestModel =& new DataTest();

		$TestModel->create(array());
		$TestModel->save();
		$result = $TestModel->findById($TestModel->id);
		$this->assertIdentical($result['DataTest']['count'], '0');
		$this->assertIdentical($result['DataTest']['float'], '0');
	}

/**
 * testNonNumericHabtmJoinKey method
 *
 * @access public
 * @return void
 */
	function testNonNumericHabtmJoinKey() {
		$this->loadFixtures('Post', 'Tag', 'PostsTag');
		$Post =& new Post();
		$Post->bindModel(array(
			'hasAndBelongsToMany' => array('Tag')
		));
		$Post->Tag->primaryKey = 'tag';

		$result = $Post->find('all');
		$expected = array(
			array(
				'Post' => array(
					'id' => '1',
					'author_id' => '1',
					'title' => 'First Post',
					'body' => 'First Post Body',
					'published' => 'Y',
					'created' => '2007-03-18 10:39:23',
					'updated' => '2007-03-18 10:41:31'
				),
				'Author' => array(
					'id' => null,
					'user' => null,
					'password' => null,
					'created' => null,
					'updated' => null,
					'test' => 'working'
				),
				'Tag' => array(
					array(
						'id' => '1',
						'tag' => 'tag1',
						'created' => '2007-03-18 12:22:23',
						'updated' => '2007-03-18 12:24:31'
					),
					array(
						'id' => '2',
						'tag' => 'tag2',
						'created' => '2007-03-18 12:24:23',
						'updated' => '2007-03-18 12:26:31'
			))),
			array(
				'Post' => array(
					'id' => '2',
					'author_id' => '3',
					'title' => 'Second Post',
					'body' => 'Second Post