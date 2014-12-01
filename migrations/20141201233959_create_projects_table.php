<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectsTable extends AbstractMigration
{
    public function change()
    {
        $projects = $this->table('projects');
        $projects->addColumn('title', 'string')
          ->addColumn('description', 'string')
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', array('null' => true))
          ->addColumn('deleted_at', 'datetime', array('null' => true))
          ->save();
    }
}