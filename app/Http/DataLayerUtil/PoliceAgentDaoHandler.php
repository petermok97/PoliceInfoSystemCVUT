<?php
/**
 * Created by PhpStorm.
 * User: mok00
 * Date: 12/15/2017
 * Time: 11:27 AM
 */
namespace App\Http;
use App\Model\PoliceAgentModel;
use App\Model\RolePoliceModel;
use App\Model\OfficerModel;
use App\Model\CrimeSceneInvestigatorModel;
use App\Model\DetectiveModel;
use App\Model\HeadOfDepartmentModel;
use App\Model\ChiefOfficerModel;
use App\Model\PersonModel;
use App\Model\DepartmentModel;
use App\Model\PoliceAgentToDepartmentModel;
use App\Model\PoliceStationModel;

/**
 * Implements the data layer for the management of the Police Agents
 *
 */
class PoliceAgentDaoHandler implements IPoliceAgentDaoHandler
{
    private $policeAgentModel;
    private $rolePoliceModel;
    private $officerModel;
    private $investigatorModel;
    private $detectiveModel;
    private $headOfDepartmentModel;
    private $chiefOfficerModel;
    private $departmentModel;
    private $personModel;
    private $policeStationModel;

    /**
     * Returns a list of arrays containing all departments names and id and matching police station name and id
     *
     * Returns NULL if no departments are found
     */
    public function getDepartmentStationList(){
        $places = [];
        $i = 0;
        $departments = $this->departmentModel->all();
        if($departments == NULL){
            return NULL;
        } else {
            foreach ($departments as $dep) {
                $policeStation = $this->policeStationModel->find($dep['policeStation_id']);
                $depName = $policeStation['name']." -- ".$dep['name'];
                $depID = $dep['department_id'];

                $place = ["name" => $depName, "id" => $depID];
                $places[$i] = $place;
                $i++;
            }
        }
        return $places;
    }


    /**
     * PoliceAgentDAOHandler constructor.
     * @param $policeAgentModel;
     * @param $rolePoliceModel;
     * @param $officerModel;
     * @param $investigatorModel;
     * @param $detectiveModel
     * @param $headOfDepartmentModel;
     * @param $chiefOfficerModel;
     * @param $personModel;
     */
    public function __construct(PoliceAgentModel $policeAgentModel, RolePoliceModel $rolePoliceModel,OfficerModel $officerModel,CrimeSceneInvestigatorModel $investigatorModel,DetectiveModel $detectiveModel,HeadOfDepartmentModel $headOfDepartmentModel,ChiefOfficerModel $chiefOfficerModel, DepartmentModel $departmentModel, PersonModel $personModel, PoliceStationModel $policeStationModel)
    {
        $this->policeAgentModel = $policeAgentModel;
        $this->rolePoliceModel = $rolePoliceModel;
        $this->officerModel = $officerModel;
        $this->investigatorModel = $investigatorModel;
        $this->detectiveModel = $detectiveModel;
        $this->headOfDepartmentModel = $headOfDepartmentModel;
        $this->chiefOfficerModel = $chiefOfficerModel;
        $this->departmentModel = $departmentModel;
        $this->personModel = $personModel;
        $this->policeStationModel = $policeStationModel;
    }

    /**
     * Returns a list of all policeAgents in the system
     */
    public function all()
    {
        return $this->policeAgentModel->all();
    }

    /**
     * Returns the detailed information of a policeAgent
     * @param $poloceAgent_id
     */
    public function getPoliceAgentDetail($policeAgent_id)
    {
        $policeAgent = $this->policeAgentModel->find($policeAgent_id);
        if($policeAgent == null)
            return null;
          // echo $policeAgent;
          // return null;
        $roleLink = $this->rolePoliceModel->where('policeAgent_id', $policeAgent['policeAgent_id'])->get();
        $role = $this->getPoliceAgentRole($roleLink[0]['rolePolice_id']);
        $person = $this->personModel->find($policeAgent['policeAgent_id']);
        $department = $this->departmentModel->find($policeAgent['department_id']);
        $dptName = $department['name'];
        $station = $this->policeStationModel->find($department['policeStation_id']);
        $detail = [
            "surname" => $person["surname"],
            "name" => $person["name"],
            "address" => $person["address"],
            "username" => $policeAgent["username"],
            "department" => $department["name"],
            "policeStation" => $station["name"],
            "role" => $role
        ];
        return $detail;
    }

    /**
     * Adds a police Agent to the information system
     * @param $name
     * @param $surname
     * @param $address
     * @param $dob : date of birth
     * @param $username
     * @param $password
     * @param $department : integer
     * @param $type : integer
     *
     */
    public function addPoliceAgent($name, $surname, $address, $dob, $username, $password, $department, $type){
        if($department == NULL)
            return NULL;
        $policeStation = $this->departmentModel->find($department);
        $policeStationId = $policeStation['policeStation_id'];
        $person = new PersonModel([
            PersonModel::COL_SURNAME => $surname,
            PersonModel::COL_NAME => $name,
            PersonModel::COL_ADD => $address,
            PersonModel::COL_DOB => $dob
        ]);
        $person->save();
        $policeAgent = new PoliceAgentModel([
            "policeAgent_id" => $person[PersonModel::COL_ID],
            "username" => $username,
            "password" => $password,
            "department_id" => $department,
            "policeStation_id" => $policeStationId,
            "rolePolice_id" => NULL
        ]);
        $policeAgent->save();
        $rolePolice = new RolePoliceModel([
            RolePoliceModel::COL_POLID => $policeAgent[PoliceAgentModel::COL_ID]
        ]);
        $rolePolice->save();
        switch ($type) {
            case 0:
                $roleAssign = new OfficerModel([
                    'officer_id' =>$rolePolice[RolePoliceModel::COL_ID]
                ]);
                break;

            case 1:
                $roleAssign = new CrimeSceneInvestigatorModel([
                    'crimeSceneInvestigator_id' =>$rolePolice[RolePoliceModel::COL_ID]
                ]);
                break;
            case 2:
                $roleAssign = new DetectiveModel([
                    'detective_id' =>$rolePolice[RolePoliceModel::COL_ID]
                ]);
                break;
            case 3:
                $roleAssign = new HeadOfDepartmentModel([
                    'headOfDepartment_id' =>$rolePolice[RolePoliceModel::COL_ID]
                ]);
                break;
            case 4:
                $roleAssign = new ChiefOfficerModel([
                    'chiefOfficer_id' =>$rolePolice[RolePoliceModel::COL_ID]
                ]);
                break;
        }
        $roleAssign->save();
        return $policeAgent;
    }


    /**
     * sets the role of a policeAgent to Officer
     * @param $policeAgent_id
     */
    public function setOfficer($policeAgent_id){
        $policeAgent = $this->policeAgentModel->find($policeAgent_id);
        $role = $policeAgent->Role();
        if($role->officer() == NULL){
            $role_id = $role['rolePolice_id'];
            $officer = new OfficerModel([
                "officer_id" => $role_id
            ]);
            $officer->save();
            $officer->associate($role);
            return $officer;
        }
        return $role->officer();
    }

    /**
     * sets the role of a policeAgent to Crime Scene Investigator
     * @param $policeAgent_id
     */
    public function setInvestigator($policeAgent_id){
        $policeAgent = $this->policeAgentModel->find($policeAgent_id);
        $role = $policeAgent->Role();
        if($role->CrimeSceneInvestigator() == NULL){
            $role_id = $role['rolePolice_id'];
            $officer = new CrimeSceneInvestigatorModel([
                "crimeSceneInvestigator_id" => $role_id
            ]);
            $officer->save();
            $officer->associate($role);
            return $officer;
        }
        return $role->CrimeSceneInvestigator();
    }

    /**
     * sets the role of a policeAgent to Detective
     * @param $policeAgent_id
     */
    public function setDetective($policeAgent_id){
        $policeAgent = $this->policeAgentModel->find($policeAgent_id);
        $role = $policeAgent->Role();
        if($role->Detective() == NULL){
            $role_id = $role['rolePolice_id'];
            $detective = new DetectiveModel([
                "detective_id" => $role_id
            ]);
            $detective->save();
            $detective->associate($role);
            return $detective;
        }
        return $role->Detective();
    }

    /**
     * sets the role of a policeAgent to Head of department
     * @param $policeAgent_id
     */
    public function setHeaddpt($policeAgent_id){
        $policeAgent = $this->policeAgentModel->find($policeAgent_id);
        $role = $policeAgent->Role();
        if($role->HeadOfTheDepartment() == NULL){
            $role_id = $role['rolePolice_id'];
            $headdpt = new  HeadOfDepartmentModel([
                "headOfDepartment_id" => $role_id
            ]);
            $headdpt->save();
            $headdpt->associate($role);
            return $headdpt;
        }
        return $role->HeadOfTheDepartment();
    }

    /**
     * sets the role of a policeAgent to Chief of Police
     * @param $policeAgent_id
     */
    public function setChief($policeAgent_id){
        $policeAgent = $this->policeAgentModel->find($policeAgent_id);
        $role = $policeAgent->Role();
        if($role->ChiefOfficer() == NULL){
            $role_id = $role['rolePolice_id'];
            $chief = new  ChiefOfficerModel([
                "chiefOfficer_id" => $role_id
            ]);
            $chief->save();
            $chief->associate($role);
            return $chief;
        }
        return $role->HeadOfTheDepartment();
    }

    /**
     * Returns the role of a police Agent
     * @param $id
     */
    public function getPoliceAgentRole($id){
        $role = NULL;
        if($role == NULL){
            if($this->chiefOfficerModel->find($id) != NULL){
                $role = "Chief Officer";
            }
        }
        if($role == NULL){
            if($this->investigatorModel->find($id) != NULL){
                $role = "Crime Scene Investigator";
            }
        }
        if($role == NULL){
            if($this->detectiveModel->find($id) != NULL){
                $role = "Detective";
            }
        }
        if($role == NULL){
            if($this->headOfDepartmentModel->find($id) != NULL){
                $role = "Head of Department";
            }
        }
        if($role == NULL){
            if($this->officerModel->find($id) != NULL){
                $role = "Officer";
            }
        }
        if($role == NULL){
            $role = "No current assigned role";
        }

        return $role;
    }


    /**
     * Returns all police Agents
     */
    public function getPoliceRow(){
        return $this->policeAgentModel::orderBy('policeAgent_id')->get();
    }

    /**
     * returns the column names of the police Agent table
     */
    public function getRowTitle()
    {
        // TODO: Implement getRowTitle() method.
        if($this->policeAgentModel->first()!= null)
            $title  =array_keys($this->policeAgentModel->first()->toArray());
        else
            $title = Schema::getColumnListing(PoliceAgentModel::TABLE_NAME);
        return $title;
    }
}
