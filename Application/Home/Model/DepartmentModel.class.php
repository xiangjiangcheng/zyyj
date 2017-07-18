<?php
/**
 *部门与学院和本身关联模型
 */
namespace Home\Model;
use Think\Model\RelationModel;
Class DepartmentModel extends RelationModel{
	protected $tableName = 'department';
	protected $_link = array(
		'college'=>array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'college',
			'forgin_key' => 'college_id',
			'mapping_name' => 'college',
			'mapping_fields' => 'name',
			'as_fields'=>'name:cname',
			),
		'department'=>array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'department',
			'forgin_key' => 'department_id',
			'mapping_name' => 'college',
			'mapping_fields' => 'name',
			'as_fields'=>'name:fname',
		),
	);
}