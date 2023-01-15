<?php
require_once ("Models/TeamDataSet.php");
require_once ("Models/TeamMemberDataSet.php");
require_once ("Models/ProjectDataSet.php");
require_once ("Models/UsersDataset.php");
require_once ("Models/OrganisationDataset.php");
$view = new stdClass();
$view->pageTitle = "Team";

session_start();

$teamDataset = new TeamDataSet();
$projectDataset = new ProjectDataset();
$teamMemberDataset = new TeamMemberDataSet();
$userDataSet = new UsersDataset();
$organisationDataSet = new OrganisationDataset();

$view->userDataSet = $userDataSet->fetchUserDetails($_SESSION['email']);
$orgID = $view->userDataSet->getOrganizationID();
$view->organisationDataSet = $organisationDataSet->getOrganisationMembers($orgID);
if(isset($_GET['teamID'])){
    $teamID = $_GET['teamID'];
    $view->team = $teamDataset->getTeam($teamID);
    $view->members = $teamMemberDataset->fetchTeamMembers($teamID);
    $view->projectDataset = $projectDataset->getTeamProjects($teamID);
    $view->isTeamLeader = $teamMemberDataset->checkTeamLeader($_SESSION['userID'], $teamID);
//    echo "Team Leader: " . $view->isTeamLeader;
//    add team project
    if(isset($_POST['projectSubmitBtn'])){
        $projectName = $_POST['projectName'];
        $projectDataset->createTeamProject($teamID, $projectName);
        header("Location: team.php?teamID=" . $teamID);
    }

//    $teamID = $_GET['teamID'];
//    $view->team = $teamDataset->getTeam($teamID);
//    $view->members = $teamMemberDataset->fetchTeamMembers($teamID);
//    add team project
    if(isset($_POST['addMemberBtn'])){

        $userNames = $_POST['teamMembers'];
        $name = explode(" ", $userNames);
        $email = $name[3];
//        echo $email;
        $userID = $userDataSet->fetchUserDetails($email)->getUserID();
//        echo "<br>" . $userID;
        $teamMemberDataset->addMember( $userID , $teamID, 0);

        header("Location: team.php?teamID=" . $teamID);
    }
}
//if(isset($_GET['teamID'])){
//
//
//
//}
if(isset($_GET['teamID']) && isset($_GET['showMembers'])){
    $view->teamMembers = $teamMemberDataset->getAllTeamMembers($teamID);
}


require_once ("Views/team.phtml");