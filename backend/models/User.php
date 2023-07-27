<?php
namespace backend\models;

/** @todo Remove unnecessary interface */
use developeruz\db_rbac\interfaces\UserRbacInterface;

class User extends \common\models\User implements UserRbacInterface {}