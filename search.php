<?php
require_once("Models/SearchDataset.php");
require_once("Models/UsersDataset.php");
require_once("Models/ProjectDataset.php");
require_once("Models/User.php");
require_once ("Models/WorkItemDataset.php");
$view = new stdClass();
session_start();
$view->pageTitle="Results";

$searchDataset = new SearchDataset();
$usersDataset = new UsersDataset();
$projectDataset = new ProjectDataset();

$user = $usersDataset->fetchUserDetails($_SESSION['email']);

// get all tasks for a project
if(isset($_GET['projectID'])){
    if(!isset($_GET['teamID'])){
        $view->projectTasks = $searchDataset->fetchWorkItems($_GET['projectID'], "userID", $_SESSION['userID']);
//        echo "show for user";
    }else{
        $view->projectTasks = $searchDataset->fetchTeamWorkItems($_GET['projectID'], "teamID", $_GET['teamID']);
//        echo "show for teams";
    }
    $project = $projectDataset->getProject($_GET['projectID']);
    $view->noOfTasks = $searchDataset->getNoOfSearchResults() . " task(s) found in " . $project->getProjectName();

    // get the date difference in days

}
// view all task for a team member
if(isset($_GET['userID'])){
    $view->projectTasks = $searchDataset->getUserTeamTasks($_GET['userID']);
}

require_once ("Views/search.phtml");