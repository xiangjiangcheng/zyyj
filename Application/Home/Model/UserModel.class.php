<?php
/**
 * User和端口ID以及部门ID关联模型
 */
namespace Home\Model;
use Think\Model\RelationModel;
Class UserModel extends RelationModel{
	protected $tableName = 'user';
	protected $_link = array(
		'department'=>array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'department',
			'forgin_key' => 'department_id',
			'mapping_fields'=>'name',
			'mapping_fields'=>'tree_code',
			'as_fields'=>'name:dptname,tree_code:rank',
			),
	);
}