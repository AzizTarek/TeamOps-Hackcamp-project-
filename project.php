<?php
require_once ("Models/ProjectDataset.php");
require_once ("Models/WorkItemDataset.php");
require_once ("Models/TeamMemberDataSet.php");
require_once ("Models/TaskPerformerDataset.php");
require_once ("Models/UsersDataset.php");
$view = new stdClass();
$view->pageTitle  = "Project";

session_start();

$taskDataset = new WorkItemDataset();
$projectDataset = new ProjectDataset();
$teamMemberDataset = new TeamMemberDataSet();
$taskPerformerDataset = new TaskPerformerDataset();
$usersDataset = new UsersDataset();

// if the user click to view a project from the user portal view
if(isset($_GET['projectID'])){
    $projectID =  $_GET['projectID'];
    $view->projectID = $projectID;
    $view->projectName = $projectDataset->getProject($projectID)->getProjectName();

//    check if the project belongs to a team
    $view->teamID = $projectDataset->getProject($projectID)->getTeamID();
    $view->teamMembers = $teamMemberDataset->getAllTeamMembers($view->teamID);

    // if the user submitted the form to add task
    if(isset($_POST['taskSubmitBtn'])){
        $name = trim($_POST['taskName']);
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $comments = $_POST['comments'];
        $effort = $_POST['effort'];
        $projectID = $_GET['projectID'];
        $taskDataset->addWorkItem($name, $startDate, $endDate, $comments, $effort, $projectID);

        if(isset($_POST['assignMember'])){
            $member = explode(" ", $_POST['assignMember']);
            $email = $member[0];
            $teamMember = $usersDataset->fetchUserDetails($email);

            $taskID = $taskDataset->getTask($name)->getWorkItemId();
//            var_dump($task);
            $userID = $teamMember->getUserID();
            $taskPerformerDataset->assignTask($userID, $taskID);
//            echo $teamMember->getUserID() . " tid: " . $taskID . " " . $name;
        }
//        echo $projectID;
//        $project = $projectDataset->getProject($projectID);


    }

    //if the user submitted the form to delete a project
    if(isset($_POST['delProjectSubmitBtn'])){
        $projectDataset->deleteProject($projectID);
        //        redirect to the view all userportal page
        header('Location: userportal.php');
    }
}


require_once ("Views/project.phtml");
?>
