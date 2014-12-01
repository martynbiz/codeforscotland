<?php

namespace Projects\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Projects\Model\Projects;
use Projects\Form\ProjectForm;


class ProjectsController extends AbstractActionController
{
    
    protected $projectTable;
    
    public function indexAction()
    {
        return new ViewModel(array(
            'projects' => $this->getProjectsTable()->fetchAll(),
        ));
    }

    // Add content to this method:
    public function addAction()
    {
        $form = new ProjectForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $project = new Project();
            $form->setInputFilter($project->getInputFilter()); // dependency injection
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $project->exchangeArray($form->getData());
                $this->getProjectsTable()->saveProject($project);

                // Redirect to list of projects
                return $this->redirect()->toRoute('projects');
            }
        }
        return array(
            'form' => $form
        );
    }


    // Add content to this method:
    public function editAction()
    {
        // get the id from the url
        $id = (int) $this->params()->fromRoute('id', 0);
        
        // if not id, redirect to the projects page
        if (!$id) {
            return $this->redirect()->toRoute('projects', array(
                'action' => 'add'
            ));
        }
        
        // get the projects for this id using the projects table object
        $project = $this->getProjectsTable()->getProject($id);
        
        
        // get the form, bind the projects to the form (so we can display those values before submission)
        $form  = new ProjectForm();
        $form->bind($project);
        $form->get('submit')->setAttribute('value', 'Edit'); // set button
        
        // get the form request
        $request = $this->getRequest();
        
        // if request is post, proceed with the form values
        if ($request->isPost()) {
            
            // set the input filter to that of the model
            $form->setInputFilter($project->getInputFilter());
            
            // set the form data to that of the request
            $form->setData($request->getPost());
            
            // check if valid
            if ($form->isValid()) {
                
                // save the values using the projects table 
                $this->getProjectsTable()->saveProject($form->getData());

                // Redirect to list of projects
                return $this->redirect()->toRoute('projects');
            }
        }

        // return array(
        //     'id' => $id,
        //     'form' => $form,
        // );
    }

    // Add content to the following method:
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('projects');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProjectsTable()->deleteProject($id);
            }

            // Redirect to list of projects
            return $this->redirect()->toRoute('projects');
        }

        return array(
            'id'    => $id,
            'projects' => $this->getProjectsTable()->getProject($id)
        );
    }
    
    public function getProjectsTable()
    {
        if (!$this->projectsTable) {
            $sm = $this->getServiceLocator();
            $this->projectsTable = $sm->get('Projects\Model\ProjectsTable');
        }
        return $this->projectsTable;
    }
}