<?php
namespace common\models;

use Yii;
use yii\base\Model;

class Role extends Model
{
	const LEADER = 'leader';
	const FOLLOWER = 'follower';

	/**
	 * Returns the other role
	 * @param  string $role The role to invert
	 * @return string
	 */
	public static function invertRole($role){
		if($role == self::LEADER){
			return self::FOLLOWER;
		}else if($role == self::FOLLOWER){
			return self::LEADER;
		}
		return null;
	}
}