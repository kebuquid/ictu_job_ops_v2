<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('dev-login/', 'DevAuth::loginPage');
$routes->get('dev-auth/login/(:segment)', 'DevAuth::login/$1');
$routes->post('employee-verification', 'EmployeeVerificationController::verify');

$routes->post('employee/verify', 'EmployeeAuthenticationController::verify');
$routes->post('employee/verify-data', 'EmployeeAuthenticationController::verifyUserData');
$routes->get('employee/email-suggestions', 'EmployeeAuthenticationController::getEmailSuggestion');
$routes->get('employee/check-email/(:any)', 'EmployeeAuthenticationController::checkEmailIfExists/$1');
$routes->post('employee/create-email', 'EmployeeAuthenticationController::createEmail');

$routes->get('login', 'Auth::index');
$routes->get('auth/google', 'Auth::google');
$routes->get('auth/google/callback', 'Auth::googleCallback');
$routes->get('logout', 'Auth::logout');

$routes->group('super-admin', static function ($routes) {
    $routes->get('dashboard', 'SuperAdminController::dashboard');
    $routes->get('tickets', 'SuperAdminController::tickets');
    $routes->get('ticket/(:num)', 'SuperAdminController::viewTicket/$1');
    $routes->get('employees', 'SuperAdminController::employees');
    $routes->get('employees/add', 'SuperAdminController::addEmployeePage');
    $routes->post('employees/add', 'SuperAdminController::addEmployee');
    $routes->get('employees/search-users', 'SuperAdminController::searchUsers');
    $routes->get('employees/check-head', 'SuperAdminController::checkHeadOfSection');
    $routes->get('employees/edit/(:num)', 'SuperAdminController::editEmployeePage/$1');
    $routes->put('employees/edit/(:num)', 'SuperAdminController::updateEmployee/$1');
    $routes->delete('employees/(:num)/expertise/(:num)', 'SuperAdminController::removeEmployeeExpertise/$1/$2');
    $routes->post('employees/(:num)/expertise', 'SuperAdminController::addEmployeeExpertise/$1');

    $routes->get('expertise/search', 'SuperAdminController::searchExpertise');

    $routes->get('buildings', 'SuperAdminController::buildings');
    $routes->get('buildings/add', 'SuperAdminController::addBuildingPage');
    $routes->post('buildings/add', 'SuperAdminController::addBuilding');
    $routes->get('buildings/edit/(:num)', 'SuperAdminController::editBuildingPage/$1');
    $routes->put('buildings/edit/(:num)', 'SuperAdminController::updateBuilding/$1');
    $routes->delete('buildings/delete/(:num)', 'SuperAdminController::deleteBuilding/$1');

    $routes->get('expertise', 'SuperAdminController::expertise');
    $routes->get('expertise/add', 'SuperAdminController::addExpertisePage');
    $routes->post('expertise/add', 'SuperAdminController::addExpertise');
    $routes->get('expertise/edit/(:num)', 'SuperAdminController::editExpertisePage/$1');
    $routes->put('expertise/edit/(:num)', 'SuperAdminController::updateExpertise/$1');
    $routes->delete('expertise/delete/(:num)', 'SuperAdminController::deleteExpertise/$1');

    // Issue Types
    $routes->get('issue-types', 'SuperAdminController::issueTypes');
    $routes->get('issue-types/add', 'SuperAdminController::addIssueTypePage');
    $routes->post('issue-types/add', 'SuperAdminController::addIssueType');
    $routes->get('issue-types/edit/(:num)', 'SuperAdminController::editIssueTypePage/$1');
    $routes->put('issue-types/edit/(:num)', 'SuperAdminController::updateIssueType/$1');
    $routes->delete('issue-types/delete/(:num)', 'SuperAdminController::deleteIssueType/$1');

    // Organizational Units
    $routes->get('organizational-units', 'SuperAdminController::organizationalUnits');
    $routes->get('organizational-units/add', 'SuperAdminController::addOrgUnitPage');
    $routes->post('organizational-units/add', 'SuperAdminController::addOrgUnit');
    $routes->get('organizational-units/edit/(:num)', 'SuperAdminController::editOrgUnitPage/$1');
    $routes->put('organizational-units/edit/(:num)', 'SuperAdminController::updateOrgUnit/$1');
    $routes->delete('organizational-units/delete/(:num)', 'SuperAdminController::deleteOrgUnit/$1');

    // Priority Levels
    $routes->get('priority-levels', 'SuperAdminController::priorityLevels');
    $routes->get('priority-levels/add', 'SuperAdminController::addPriorityLevelPage');
    $routes->post('priority-levels/add', 'SuperAdminController::addPriorityLevel');
    $routes->get('priority-levels/edit/(:num)', 'SuperAdminController::editPriorityLevelPage/$1');
    $routes->put('priority-levels/edit/(:num)', 'SuperAdminController::updatePriorityLevel/$1');
    $routes->delete('priority-levels/delete/(:num)', 'SuperAdminController::deletePriorityLevel/$1');

    // Request Actions
    $routes->get('request-actions', 'SuperAdminController::requestActions');
    $routes->get('request-actions/add', 'SuperAdminController::addRequestActionPage');
    $routes->post('request-actions/add', 'SuperAdminController::addRequestAction');
    $routes->get('request-actions/edit/(:num)', 'SuperAdminController::editRequestActionPage/$1');
    $routes->put('request-actions/edit/(:num)', 'SuperAdminController::updateRequestAction/$1');
    $routes->delete('request-actions/delete/(:num)', 'SuperAdminController::deleteRequestAction/$1');

    // Request Platforms
    $routes->get('request-platforms', 'SuperAdminController::requestPlatforms');
    $routes->get('request-platforms/add', 'SuperAdminController::addRequestPlatformPage');
    $routes->post('request-platforms/add', 'SuperAdminController::addRequestPlatform');
    $routes->get('request-platforms/edit/(:num)', 'SuperAdminController::editRequestPlatformPage/$1');
    $routes->put('request-platforms/edit/(:num)', 'SuperAdminController::updateRequestPlatform/$1');
    $routes->delete('request-platforms/delete/(:num)', 'SuperAdminController::deleteRequestPlatform/$1');

    // Request Types
    $routes->get('request-types', 'SuperAdminController::requestTypes');
    $routes->get('request-types/add', 'SuperAdminController::addRequestTypePage');
    $routes->post('request-types/add', 'SuperAdminController::addRequestType');
    $routes->get('request-types/edit/(:num)', 'SuperAdminController::editRequestTypePage/$1');
    $routes->put('request-types/edit/(:num)', 'SuperAdminController::updateRequestType/$1');
    $routes->delete('request-types/delete/(:num)', 'SuperAdminController::deleteRequestType/$1');

    // Ticket Equipment
    $routes->get('ticket-equipment', 'SuperAdminController::ticketEquipment');
    $routes->get('ticket-equipment/add', 'SuperAdminController::addTicketEquipmentPage');
    $routes->post('ticket-equipment/add', 'SuperAdminController::addTicketEquipment');
    $routes->get('ticket-equipment/edit/(:num)', 'SuperAdminController::editTicketEquipmentPage/$1');
    $routes->put('ticket-equipment/edit/(:num)', 'SuperAdminController::updateTicketEquipment/$1');
    $routes->delete('ticket-equipment/delete/(:num)', 'SuperAdminController::deleteTicketEquipment/$1');

    // Section Access Control
    $routes->get('section-access', 'SuperAdminController::sectionAccess');
    $routes->post('section-access', 'SuperAdminController::updateSectionAccess');

    // Form Option Access Control
    $routes->get('form-option-access', 'SuperAdminController::formOptionAccess');
    $routes->post('form-option-access', 'SuperAdminController::updateFormOptionAccess');

    // Keyword Rules CRUD
    $routes->get('keyword-rules', 'SuperAdminController::keywordRules');
    $routes->get('keyword-rules/add', 'SuperAdminController::addKeywordRulePage');
    $routes->post('keyword-rules/add', 'SuperAdminController::addKeywordRule');
    $routes->get('keyword-rules/edit/(:num)', 'SuperAdminController::editKeywordRulePage/$1');
    $routes->put('keyword-rules/edit/(:num)', 'SuperAdminController::updateKeywordRule/$1');
    $routes->delete('keyword-rules/delete/(:num)', 'SuperAdminController::deleteKeywordRule/$1');
});

// ─── Section Head (Admin) Routes ─────────────────────────
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'SectionHeadController::dashboard');
    $routes->get('tickets', 'SectionHeadController::tickets');
    $routes->get('ticket/(:num)', 'SectionHeadController::viewTicket/$1');
    $routes->get('my-tickets', 'SectionHeadController::myTickets');
    $routes->get('employees', 'SectionHeadController::employees');
    $routes->get('verify', 'SectionHeadController::verify');
    $routes->post('verify/(:num)', 'SectionHeadController::verifyTicket/$1');
    $routes->get('respond/(:num)', 'SectionHeadController::respondForm/$1');
    $routes->post('respond/(:num)', 'SectionHeadController::submitResponse/$1');
    $routes->get('transfer/(:num)', 'SectionHeadController::transferForm/$1');
    $routes->post('transfer/(:num)', 'SectionHeadController::transferTicket/$1');

    // Keyword Rules CRUD (section-scoped)
    $routes->get('keyword-rules', 'SectionHeadController::keywordRules');
    $routes->get('keyword-rules/add', 'SectionHeadController::addKeywordRulePage');
    $routes->post('keyword-rules/add', 'SectionHeadController::addKeywordRule');
    $routes->get('keyword-rules/edit/(:num)', 'SectionHeadController::editKeywordRulePage/$1');
    $routes->put('keyword-rules/edit/(:num)', 'SectionHeadController::updateKeywordRule/$1');
    $routes->delete('keyword-rules/delete/(:num)', 'SectionHeadController::deleteKeywordRule/$1');
});

// ─── Technician Routes ───────────────────────────────────
$routes->group('technician', static function ($routes) {
    $routes->get('dashboard', 'TechnicianController::dashboard');
    $routes->get('my-tickets', 'TechnicianController::myTickets');
    $routes->get('ticket/(:num)', 'TechnicianController::viewTicket/$1');
    $routes->get('respond/(:num)', 'TechnicianController::respondForm/$1');
    $routes->post('respond/(:num)', 'TechnicianController::submitResponse/$1');
    $routes->get('transfer/(:num)', 'TechnicianController::transferForm/$1');
    $routes->post('transfer/(:num)', 'TechnicianController::transferTicket/$1');
});

// ─── Staff Routes ────────────────────────────────────────
$routes->group('staff', static function ($routes) {
    $routes->get('dashboard', 'TechnicianController::dashboard');
    $routes->get('my-tickets', 'TechnicianController::myTickets');
    $routes->get('ticket/(:num)', 'TechnicianController::viewTicket/$1');
    $routes->get('respond/(:num)', 'TechnicianController::respondForm/$1');
    $routes->post('respond/(:num)', 'TechnicianController::submitResponse/$1');
    $routes->get('transfer/(:num)', 'TechnicianController::transferForm/$1');
    $routes->post('transfer/(:num)', 'TechnicianController::transferTicket/$1');
});

// ─── Employee Routes (ICTU Employees) ────────────────────
$routes->group('employee', static function ($routes) {
    $routes->get('dashboard', 'EmployeeDashboardController::dashboard');
    $routes->get('my-tickets', 'EmployeeDashboardController::myTickets');
    $routes->get('ticket/(:num)', 'EmployeeDashboardController::viewTicket/$1');

    // Ticket creation
    $routes->get('create-ticket', 'TicketController::create');
    $routes->post('create-ticket', 'TicketController::store');
    $routes->get('create-ticket/section-data/(:num)', 'TicketController::getSectionData/$1');
    $routes->get('create-ticket/request-type-data/(:num)', 'TicketController::getRequestTypeData/$1');
});

// ─── Student Routes ──────────────────────────────────────
$routes->group('student', static function ($routes) {
    $routes->get('dashboard', 'StudentDashboardController::dashboard');
    $routes->get('my-tickets', 'StudentDashboardController::myTickets');
    $routes->get('ticket/(:num)', 'StudentDashboardController::viewTicket/$1');

    // Ticket creation
    $routes->get('create-ticket', 'TicketController::create');
    $routes->post('create-ticket', 'TicketController::store');
    $routes->get('create-ticket/section-data/(:num)', 'TicketController::getSectionData/$1');
    $routes->get('create-ticket/request-type-data/(:num)', 'TicketController::getRequestTypeData/$1');
});
