<?php

class TaskController extends BaseController {

    public function __construct() {
        $this->beforeFilter(function() {
            if (!Auth::check()) {
                return Redirect::to('login')->with('error_message', "You are either not logged in or you dont have permissions to access this page");
            }
        });



        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['task'] = BookingTask::with('user_name', 'taskComment')->orderBy('booking_task_id','desc')->paginate(10);


        $basicPageVariables = ZnUtilities::basicPageVariables("Task", " All Task", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['Task'] = array("link" => '/tasks', "active" => '0');

        ZnUtilities::push_js_files('components/task.js');

        $data['active_nav'] = 'task';
        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['assign_to'] = User::where('user_group_id', '=', 2)->get();

        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("components/task.js");

        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("task_description");
                       });';
        ZnUtilities::push_js($editor_js);




        $basicPageVariables = ZnUtilities::basicPageVariables("Task", " Add new Task", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Task'] = array("link" => '/tasks', "active" => '0');
        $data['breadcrumbs']['New Task'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }





        $validator = Validator::make(
                        array(
                    'task_title' => Input::get('task_title'),
                    'task_description' => Input::get('task_description'),
                    'assign_to' => Input::get('assign_to'),
                    'due_date' => Input::get('due_date'),
                        ), array(
                    'task_title' => 'required',
                    'task_description' => 'required',
                    //'assign_to' => 'required',
                    'due_date' => 'required',
                        )
        );

        if ($validator->passes()) {



            $task = new BookingTask();
            $task->task_title = Input::get('task_title');
            $task->task_description = Input::get('task_description');
            $task->assign_to = Input::get('assign_to');
            $task->due_date = Input::get('due_date');
            $task->assign_date = date('Y/m/d');
            $task->task_status = 'Open';

            //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();

            $task->save();


            return Redirect::to('tasks')->with('success_message', 'Task created successfully!');
        } else {

            return Redirect::to('tasks/create')->withErrors($validator)->withInput();
            ;
        }
    }

    public function edit($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        //Throw exception if project id does not exists
        $data['task'] = BookingTask::findOrFail($id);
        $data['assign_to'] = User::where('user_group_id', '=', 2)->get();


        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("components/task.js");

        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("task_description");
                       });';
        ZnUtilities::push_js($editor_js);



        $basicPageVariables = ZnUtilities::basicPageVariables("Task", "Edit Task", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Task'] = array("link" => '/tasks', "active" => '0');
        $data['breadcrumbs']['Edit Task'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }
//            ZnUtilities::pa($id);  die();
        //skip email's unique validation if email is not changed
        $task = BookingTask::find($id);

        $validator = Validator::make(
                        array(
                    'task_title' => Input::get('task_title'),
                    'task_description' => Input::get('task_description'),
                    'assign_to' => Input::get('assign_to'),
                    'due_date' => Input::get('due_date'),
                        ), array(
                    'task_title' => 'required',
                    'task_description' => 'required',
                    //'assign_to' => 'required',
                    'due_date' => 'required',
                        )
        );

        if ($validator->passes()) {




            $task->task_title = Input::get('task_title');
            $task->task_description = Input::get('task_description');
            $task->assign_to = Input::get('assign_to');
            $task->due_date = Input::get('due_date');
            $task->assign_date = date('Y/m/d');
            $task->task_status = 'Open';

            //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();

            $task->save();



            return Redirect::to('tasks')->with('success_message', 'Task Updated Successfully');
        } else {

            return Redirect::to('tasks/' . $id . '/edit/')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }


        $task = BookingTask::find($id);

        $task->delete();

        // redirect
        return Redirect::to('tasks')->with('success_message', 'Task deleted successfully!');
    }

    public function taskSearch($search) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $keyword = $search;
        $data['keyword'] = $keyword;


        $package = DB::table('booking_tasks as t');
        $package->leftJoin('users as u', 'u.id', '=', 't.assign_to');
//                $package->leftJoin('locations as l','l.location_id','=','e.location_id');



        if ($keyword != '') {
            $keyword = trim($keyword);
            $package->orWhere(function ($package) use ($keyword) {


                $package->where("t.task_title", "like", "%" . $keyword . "%")
                        ->orwhere("t.task_description", "like", "%" . $keyword . "%")
                        ->orwhere("t.task_status", "like", "%" . $keyword . "%")
                        ->orwhere("u.name", "like", "%" . $keyword . "%");
//                                   ->orwhere("e.hotel_estimate_cost_child","like","%".$keyword."%")
            });
        }
        $data['task'] = $package->paginate(10);
        //Basic Page Settings

        $basicPageVariables = ZnUtilities::basicPageVariables("Task", "Search results", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Task'] = array("link" => '/tasks', "active" => '0');
        $data['breadcrumbs']['Search'] = array("link" => "", "active" => '1');

        $data['search'] = $search;

        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.searchResult', $data);
    }

    public function taskActions() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $search = Input::get('search');
        if ($search != '') {
            return Redirect::to('/taskSearch/' . $search);
        } else {


            //die(Input::get('bulk_action')   );

            $cid = Input::get('cid');
//            ZnUtilities::pa($cid);die();
            $bulk_action = Input::get('bulk_action');
            if ($bulk_action != '') {
                switch ($bulk_action) {


                    case 'delete': {


                            foreach ($cid as $id) {
                                DB::table('booking_tasks')
                                        ->where('booking_task_id', $id)
                                        ->delete();
                            }

                            return Redirect::to('/tasks/')->with('success_message', 'Rows Delete!');
                            break;
                        }
                } //end switch
            } // end if task_descriptionment
            return Redirect::to('/tasks');
        }
    }

    //-----------Comment Function Of Admin-----//

    public function newComment($task_id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }



        $data = array();
        $data['task'] = BookingTask::where('booking_task_id', $task_id)->pluck('task_status');
        $taskss = BookingTask::where('assign_to', Auth::user()->id)->pluck('booking_task_id');


        $data['commentCount'] = TaskComment::where('booking_task_id', $task_id)->get()->count();


        $taskComment = TaskComment::where('booking_task_id', $task_id)->orderBy('comment_id', 'desc')->get()->toArray();





        $taskscomm = array();
        $taskdate = array();
        $username = array();
        foreach ($taskComment as $t) {
            $taskscomm[] = $t['comments'];
            $taskdate[] = $t['comment_date'];
            $username[] = $t['user_id'];
        }


        $data['taskComment'] = $taskscomm;

        $data['commentdate'] = $taskdate;

        $data['username'] = $username;

        $data['uname'] = TaskComment::with('username')->get();

        //  ZnUtilities::pa($data['taskComment']); die();

        $data['booking_task_id'] = $task_id;
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("components/task.js");

        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("task_description");
                       });';
        ZnUtilities::push_js($editor_js);




        $basicPageVariables = ZnUtilities::basicPageVariables("Task", "Add New Comment", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Task'] = array("link" => '/tasks', "active" => '0');
        $data['breadcrumbs']['Add New Comment'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.newComment', $data);
    }

    public function storeComment($task_id) {


        if (!User::checkPermission(Auth::user()->user_group_id, 'task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }


//           $task = TaskComment::find($task_id);
        $tasks = BookingTask::find($task_id);
        $validator = Validator::make(
                        array(
                    'comment' => Input::get('comments'),
                    'task_status' => Input::get('task_status'),
                        ), array(
                    'comment' => 'required',
                    'task_status' => 'required',
                        )
        );

        if ($validator->passes()) {
            //ZnUtilities::pa(Input::get('comments')); die();

            $name = Input::get('comments');

            $task_comment = new TaskComment();
            $task_comment->comments = $name;
            $task_comment->booking_task_id = $task_id;
            $task_comment->comment_date = date('Y/m/d h:i:s a', time());
            $task_comment->user_id = Auth::user()->id;

            //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();

            $task_comment->save();

            $tasks->task_status = Input::get('task_status');

            $tasks->save();


            return Redirect::to('tasks')->with('success_message', 'Task Comment created successfully!');
        } else {

            return Redirect::to('newTaskComment/' . $task_id)->withErrors($validator)->withInput();
            ;
        }
    }

    //------ Task comment functio of Staff--//

    public function staffNewComment($task_id) {
        if (!User::checkPermission(Auth::user()->user_group_id, 'staff_task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        //ZnUtilities::pa($task_id); die();


        $data = array();

        $data['taskss'] = BookingTask::where('booking_task_id', $task_id)->first();

        $data['assign_to'] = User::where('user_group_id', '=', 2)->get();
        $taskss = BookingTask::where('assign_to', Auth::user()->id)->pluck('booking_task_id');

        $data['task'] = BookingTask::where('booking_task_id', $task_id)->pluck('task_status');


        $data['commentCount'] = TaskComment::where('booking_task_id', $task_id)->get()->count();


        $taskComment = TaskComment::where('booking_task_id', $task_id)->orderBy('comment_id', 'desc')->get()->toArray();


        $taskscomm = array();
        $taskdate = array();
        $username = array();
        foreach ($taskComment as $t) {
            $taskscomm[] = $t['comments'];
            $taskdate[] = $t['comment_date'];
            $username[] = $t['user_id'];
        }


        $data['taskComment'] = $taskscomm;

        $data['commentdate'] = $taskdate;

        $data['username'] = $username;

        //ZnUtilities::pa($data['taskComment']); die();

        $data['booking_task_id'] = $task_id;
        ZnUtilities::push_js_files("jquery.validate.min.js");
        ZnUtilities::push_js_files("components/task.js");

        ZnUtilities::push_js_files('plugins/ckeditor/ckeditor.js');
        $editor_js = '$(function() {
                       // $("#content").wysihtml5();
                       CKEDITOR.replace("task_description");
                       });';
        ZnUtilities::push_js($editor_js);




        $basicPageVariables = ZnUtilities::basicPageVariables("Task", " Add New Comment", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);

        $data['breadcrumbs']['All Task'] = array("link" => '/tasks', "active" => '0');
        $data['breadcrumbs']['Add New Comment'] = array("link" => "", "active" => '1');

        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.staffnewComment', $data);
    }

    public function staffStoreComment($task_id) {


        if (!User::checkPermission(Auth::user()->user_group_id, 'staff_task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }


//           $task = TaskComment::find($task_id);
        $tasks = BookingTask::find($task_id);
        $validator = Validator::make(
                        array(
                    'comment' => Input::get('comments'),
                    'task_status' => Input::get('task_status'),
                        ), array(
                    'comment' => 'required',
                    'task_status' => 'required',
                        )
        );

        if ($validator->passes()) {
            //ZnUtilities::pa(Input::get('comments')); die();


            $name = Input::get('comments');

            $task_comment = new TaskComment();
            $task_comment->comments = $name;
            $task_comment->booking_task_id = $task_id;
            $task_comment->comment_date = date('Y/m/d h:i:s a', time());
            $task_comment->user_id = Auth::user()->id;

            //ZnUtilities::pa(date('Y/m/d h:i:s a')); die();

            $task_comment->save();

            $tasks->task_status = Input::get('task_status');

            $tasks->save();


            return Redirect::to('staffnewTaskComment/' . $task_id)->with('success_message', 'Comment created successfully!');
        } else {

            return Redirect::to('staffnewTaskComment/' . $task_id)->withErrors($validator)->withInput();
            ;
        }
    }

    public function showUserTask() {
        if (!User::checkPermission(Auth::user()->user_group_id, 'staff_task', 'manage')) {
            return Redirect::to('/permissionDenied');
        }

        $data = array();

        $data['task'] = BookingTask::with('user_name')->where('assign_to', Auth::user()->id)->paginate();

        $taskss = BookingTask::where('assign_to', Auth::user()->id)->pluck('booking_task_id');


        $data['commentCount'] = TaskComment::where('booking_task_id', $taskss)->get()->count();

        // ZnUtilities::pa($data['commentCount']); die();

        $name = User::where('id', Auth::user()->id)->pluck('name');

        $basicPageVariables = ZnUtilities::basicPageVariables("Task", "$name Task", "tasks", "1");
        $data = array_merge($data, $basicPageVariables);


        $data['breadcrumbs']['Task'] = array("link" => '/tasks', "active" => '0');

        ZnUtilities::push_js_files('components/task.js');

        $data['active_nav'] = 'task';
        $data['submenus'] = $this->_submenus('tasks');
        return View::make('admin.tasks.showTaskUser', $data);
    }

    private function _submenus($active) {
        $data = array();
        $data['Back'] = array("link" => '/faq', "active" => $active == 'faqs' ? '1' : '0', "icon" => 'fa-angle-left',);
        $data['FAQ'] = array("link" => '/faq', "active" => $active == 'faqs' ? '1' : '0', "icon" => 'fa-question');
        $data['Email Template'] = array("link" => '/emailTemplates', "active" => $active == 'email_templates' ? '1' : '0', "icon" => 'fa-file-o');
        $data['Mailing Lists'] = array("link" => '/mailinglists', "active" => $active == 'mailinglists' ? '1' : '0', "icon" => 'fa-list');
        $data['Mailing List Subscribers'] = array("link" => '/mailinglistsubscribers', "active" => $active == 'mailinglistsubscribers' ? '1' : '0', "icon" => 'fa-user-plus');
        $data['Messages'] = array("link" => '/messages', "active" => $active == 'messages' ? '1' : '0', "icon" => 'fa-envelope');
        $data['Newsletters'] = array("link" => '/newsletters', "active" => $active == 'newsletter' ? '1' : '0', "icon" => 'fa-list');
        $data['Tasks'] = array("link" => '/tasks', "active" => $active == 'tasks' ? '1' : '0', "icon" => 'fa-list');
        $data['Currency Convert '] = array("link" => '/currency/create', "active" => $active == 'convert_currency' ? '1' : '0', "icon" => 'fa-gear');
        return $data;
    }

}

?>