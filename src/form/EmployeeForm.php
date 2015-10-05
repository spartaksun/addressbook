<?php

namespace spartaksun\addresses\form;

use spartaksun\addresses\components\Db;

/**
 * Form for creating employee
 * @package spartaksun\addresses\form
 */
class EmployeeForm extends Form
{
    /**
     * @inheritdoc
     */
    protected function validators()
    {
        $obj = $this;

        return array(
            'name' => function ($attribute) use ($obj) {
                $obj->filterTrim($attribute);
                $obj->validateLength($attribute, array(
                    'min' => 5,
                    'max' => 255,
                ));
            },
            'title' => function ($attribute) use ($obj) {
                $obj->filterTrim($attribute);
                $obj->validateLength($attribute, array(
                    'min' => 2,
                    'max' => 255,
                ));
            },
            'email' => function ($attribute) use ($obj) {
                $obj->filterTrim($attribute);
                $obj->filterLowerCase($attribute);
                $obj->validateLength($attribute, array(
                    'min' => 6,
                    'max' => 60,
                ));
                $obj->validateEmail($attribute);
                $obj->validateUnique($attribute, Db::TABLE_EMPLOYEE);
            },
            'supervisor_id' => function ($attribute) use ($obj) {
                $supervisor = $obj->loadSupervisor(
                    $obj->getAttribute($attribute)
                );
                if (empty($supervisor)) {
                    $obj->addError($attribute, 'Supervisor not found.');
                }
            }
        );
    }

    /**
     * Create new employee record
     * @throws \spartaksun\addresses\AddressBookException
     */
    public function save()
    {
        $errors = $this->getErrors();

        if (empty($errors)) {
            return $this->db
                ->insert(Db::TABLE_EMPLOYEE, $this->attributesToSave);
        }

        return false;
    }

    /**
     * @return array of supervisor data
     * @throws \spartaksun\addresses\AddressBookException
     */
    public function getSupervisor()
    {
        $id = $this->getAttribute('supervisor_id');

        $supervisor = $this->loadSupervisor($id);
        if (empty($supervisor)) {
            throw new \spartaksun\addresses\AddressBookException('Supervisor not found2.');
        }

        return $supervisor;
    }

    /**
     * @param $id
     * @return array of supervisor data
     */
    public function loadSupervisor($id)
    {
        if($id == 0) {
            return array(
                'id' => 0,
                'name' => 'Main level',
            );
        }
        $supervisor = $this->db->select(\spartaksun\addresses\components\Db::TABLE_EMPLOYEE,
            array('id' => $id), array('name')
        );

        return $supervisor;
    }

}