<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login/auth', 'Login::auth');
$routes->get('login/home', 'Login::home');
$routes->get('login/logout', 'Login::logout');

// Manage
$routes->group('manage', function ($routes) {
    $routes->get('employees', 'Manage::employees');
    $routes->post('employees/add', 'Manage::addEmployee');
    $routes->post('employees/edit/(:num)', 'Manage::editEmployee/$1');
    $routes->post('employees/delete/(:num)', 'Manage::deleteEmployee/$1');


    $routes->get('permissions', 'Manage::permissions');
    $routes->post('permissions/add', 'Manage::addPermission');
    $routes->post('permissions/update/(:num)', 'Manage::updatePermission/$1');
    $routes->post('permissions/delete/(:num)', 'Manage::deletePermission/$1');

    $routes->get('permission_groups', 'Manage::permissionGroups');
    $routes->post('permission_groups/add', 'Manage::addPermissionGroup');
    $routes->post('permission_groups/edit/(:num)', 'Manage::editPermissionGroup/$1');
    $routes->post('permission_groups/delete/(:num)', 'Manage::deletePermissionGroup/$1');
});

// Animals
$routes->group('animals', function ($routes) {
    $routes->get('animalsList', 'AnimalsController::animalsList');
    $routes->post('animalsList/add', 'AnimalsController::addAnimal');
    $routes->post('animalsList/edit/(:num)', 'AnimalsController::editAnimal/$1');
    $routes->post('animalsList/delete/(:num)', 'AnimalsController::deleteAnimal/$1');

    $routes->get('get-breeds/(:num)', 'AnimalsController::getBreeds/$1');
});

// Pen/Semen/Technician
$routes->group('pen-semen-tech', function ($routes) {
    $routes->get('pen', 'PenController::penList');
    $routes->post('pen/add', 'PenController::addPen');
    $routes->post('pen/edit/(:num)', 'PenController::editPen/$1');
    $routes->post('pen/delete/(:num)', 'PenController::deletePen/$1');

    $routes->get('semen', 'SemenController::semenList');
    $routes->post('semen/add', 'SemenController::addSemen');
    $routes->post('semen/edit/(:num)', 'SemenController::editSemen/$1');
    $routes->post('semen/delete/(:num)', 'SemenController::deleteSemen/$1');

    $routes->get('technician', 'TechnicianController::technicianList');
    $routes->post('technician/add', 'TechnicianController::addTechnician');
    $routes->post('technician/edit/(:num)', 'TechnicianController::editTechnician/$1');
    $routes->post('technician/delete/(:num)', 'TechnicianController::deleteTechnician/$1');
});

// Schedule Events
$routes->group('schedule-events', function ($routes) {
    $routes->get('schedule', 'ScheduleController::scheduleList');
    $routes->post('schedule/add', 'ScheduleController::addSchedule');
    $routes->post('schedule/edit/(:num)', 'ScheduleController::editSchedule/$1');
    $routes->post('schedule/delete/(:num)', 'ScheduleController::deleteSchedule/$1');


});


// Utilities
$routes->get('utilities/colors', 'Pages::colors');
$routes->get('utilities/borders', 'Pages::borders');
$routes->get('utilities/animations', 'Pages::animations');
$routes->get('utilities/others', 'Pages::others');

// Pages
$routes->get('pages/login', 'Pages::login');
$routes->get('pages/register', 'Pages::register');
$routes->post('login/store', 'Login::store');
$routes->get('pages/forgot-password', 'Pages::forgotPassword');
$routes->get('pages/404', 'Pages::errorPage');
$routes->get('pages/blank', 'Pages::blank');


$routes->get('login/charts', 'Login::charts');
$routes->get('login/tables', 'Login::tables');
