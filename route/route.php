<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
Route::get('setting', 'index/index/testSetting');

/**
 * Index
 */
Route::post('login', 'all/index/login');

/**
 * Employee
 */
Route::get('employee', 'all/employee/getAllEmployee');
Route::get('employee/search', 'all/employee/search');
Route::get('employee/info', 'all/employee/getMyInfo');
Route::get('employee/:id', 'all/employee/getDetailByNum');
Route::post('employee/add', 'all/employee/add');
Route::get('employee/delete/all', 'all/employee/deleteAll');
Route::get('employee/delete/:id', 'all/employee/deleteByNum', [], ['id'=>'\d+']);
Route::post('employee/update', 'all/employee/update');
Route::post('class/add', 'all/employee/addClass');
Route::post('class/change', 'all/employee/changeClass');
Route::get('class/get/:id', 'all/employee/getClass');
Route::post('course/add', 'all/employee/addCourse');
Route::post('course/change', 'all/employee/changeCourse');
Route::get('course/get/:id', 'all/employee/getCourse', [], ['id'=>'\d+']);


/**
 * Student
 */
Route::get('student', 'all/student/getAllStudent');
Route::get('student/by_employee', 'all/student/getStudentByEmployee');
Route::get('student/search', 'all/student/search');
Route::get('student/info', 'all/student/getMyInfo');
Route::get('student/:id', 'all/student/getDetailByNum');
Route::post('student/add', 'all/student/add');
Route::get('student/delete/all', 'all/student/deleteAll');
Route::get('student/delete/:id', 'all/student/deleteByNum', [], ['id'=>'\d+']);
Route::post('student/update', 'all/student/update');
Route::post('student/room/change', 'all/student/changeRoom');
Route::post('student/class/change', 'all/student/changeClass');
Route::get('student/course/by_num', 'all/student/getCourse');
Route::get('student/course/my', 'all/student/getMyCourse');
Route::post('student/course/add', 'all/student/addCourse');
Route::post('student/course/change', 'all/student/changeCourse');

/**
 * Department
 */
Route::get('department', 'all/department/getAllDepartment');
Route::get('department/search', 'all/department/search');
Route::post('department/add', 'all/department/add');
Route::get('department/delete/all', 'all/department/deleteAll');
Route::get('department/delete/:id', 'all/department/deleteByNum', [], ['id'=>'\d+']);
Route::post('department/update', 'all/department/update');

/**
 * Clazz
 */
Route::get('class', 'all/clazz/getAllClass');
Route::get('class/by_employee', 'all/clazz/getClassByEmployee');
Route::get('class/by_department', 'all/clazz/getClassByDepartment');
Route::get('class/search', 'all/clazz/search');
Route::post('class/add/by_employee', 'all/clazz/add');
Route::post('class/update', 'all/clazz/update');
Route::get('class/delete/all', 'all/clazz/deleteAll');
Route::get('class/delete/:id', 'all/clazz/deleteById', [], ['id'=>'\d+']);

/**
 * Classroom
 */
Route::get('classroom', 'all/classroom/getAllClassroom');
Route::get('classroom/search', 'all/classroom/search');
Route::post('classroom/add', 'all/classroom/add');
Route::get('classroom/delete/all', 'all/classroom/deleteAll');
Route::get('classroom/delete/:id', 'all/classroom/deleteByID', [], ['id'=>'\d+']);
Route::post('classroom/update', 'all/classroom/updateByID');

/**
 * Course
 */
Route::get('course', 'all/course/getAllCourse');
Route::get('course/by_employee', 'all/course/getCourseByEmployee');
Route::post('course/add/by_employee', 'all/course/addCourseByEmployee');
Route::get('course/all', 'all/course/getCourse');
Route::get('course/search', 'all/course/search');
Route::get('course/:id', 'all/course/getCourseByID');
Route::post('course/add/room', 'all/course/addTimeAndRoom');
Route::get('course/delete/room', 'all/course/deleteTimeAndRoom');
Route::get('course/delete/all', 'all/course/deleteAll');
Route::get('course/delete/:id', 'all/course/deleteByID', [], ['id'=>'\d+']);

/**
 * Room
 */
Route::get('room', 'all/room/getAllRoom');
Route::get('room/by_gender', 'all/room/getRoomByGender');
Route::get('room/search', 'all/room/search');
Route::post('room/add', 'all/room/add');
Route::get('room/delete/all', 'all/room/deleteAll');
Route::get('room/delete/:id', 'all/room/deleteByID', [], ['id'=>'\d+']);
Route::post('room/update', 'all/room/update');


return [

];
