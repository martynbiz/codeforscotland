<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $users = $this->table('users');
        $users->addColumn('username', 'string')
              ->addColumn('password', 'string')
              ->addColumn('password_salt', 'string')
              ->addColumn('email', 'string')
              ->addColumn('first_name', 'string')
              ->addColumn('last_name', 'string')
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime', array('null' => true))
              ->addColumn('deleted_at', 'datetime', array('null' => true))
              ->addIndex(array('username', 'email'), array('unique' => true))
              ->save();
    }
}