<?php
namespace MVC\Controllers;

use MVC\Core\Application;
use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Helpers\Common;
use MVC\Forms\EditUserModelForm;
use MVC\Forms\AdminChangeUserPasswordForm;
use MVC\Middlewares\AuthMiddleware;
use MVC\Middlewares\AuthorizeMiddleware;
use MVC\Models\Question;
use MVC\Models\Module;
use MVC\Models\User;
use MVC\Models\Contact;
use MVC\Models\Role;
use MVC\Models\Permission;
use MVC\Models\PrivilegedUser;
use MVC\Models\Answer;
use MVC\Exceptions\BadRequestException;


class AdminController extends Controller
{
    public function __construct()
    {;
        $this->registerMiddleware(new AuthMiddleware([
            'index',
            'addModule',
            'editModule',
            'deleteModule',
            'addQuestion',
            'editQuestion',
            'deleteQuestion',
            'addUser',
            'editUser',
            'deleteUser',
            'addContact',
            'editContact',
            'deleteContact',
            'changePassword',
            'addRole',
            'editRole',
            'deleteRole',
            'addPermission',
            'editPermission',
            'deletePermission',
        ]));
        $this->registerMiddleware(new AuthorizeMiddleware([
            'index',
            'addModule',
            'editModule',
            'deleteModule',
            'addQuestion',
            'editQuestion',
            'deleteQuestion',
            'addUser',
            'editUser',
            'deleteUser',
            'addContact',
            'editContact',
            'deleteContact',
            'changePassword',
            'addRole',
            'editRole',
            'deleteRole',
            'addPermission',
            'editPermission',
            'deletePermission',
        ]));
        $this->limit = 10;
        $this->layout = "admin-base";
    }

    public function index()
    {
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
            try {
                $page = (int) $page;
                $this->currentPage = $page;
                if ($page < 0) throw new \Exception($message="Invalid page query param.");
            } catch (\Exception $e) {
                throw new BadRequestException($e->getMessage());
            }
        }
        $tab = isset($_GET["tab"]) ? $_GET["tab"] : "questions";
        
        if (!in_array($tab, ['questions', 'modules', 'users', 'contacts', 'roles', 'permissions', 'answers'])) throw new BadRequestException("Invalid tab query param!");

        $questions = Question::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalQuestions = Question::countAll([]);
        $totalPageQuestions = ceil($totalQuestions / $this->getLimit());

        $modules = Module::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalModules = Module::countAll();
        $totalPageModules = ceil($totalModules / $this->getLimit());

        $users = User::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalUsers = User::countAll([]);
        $totalPageUsers = ceil($totalUsers / $this->getLimit());

        $contacts = Contact::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalContacts = Contact::countAll();
        $totalPageContacts = ceil($totalContacts / $this->getLimit());

        $roles = Role::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalRoles = Role::countAll();
        $totalPageRoles = ceil($totalRoles / $this->getLimit());

        $permissions = Permission::findAll([], $this->getLimit(), $this->getPageOffset(), "perm ASC");
        $totalPermissions = Permission::countAll();
        $totalPagePermissions = ceil($totalPermissions / $this->getLimit());

        $answers = Answer::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalAnswers = Answer::countAll();
        $totalPageAnswers = ceil($totaltotalAnswersPermissions / $this->getLimit());

        return $this->render($view='admin', $params=[
            "questions" => $questions,
            "totalQuestions" => $totalQuestions,
            "totalPageQuestions" => $totalPageQuestions,

            "modules" => $modules,
            "totalModules" => $totalModules,
            "totalPageModules" => $totalPageModules,

            "users" => $users,
            "totalUsers" => $totalUsers,
            "totalPageUsers" => $totalPageUsers,

            "contacts" => $contacts,
            "totalContacts" => $totalContacts,
            "totalPageContacts" => $totalPageContacts,

            "roles" => $roles,
            "totalRoles" => $totalRoles,
            "totalPageRoles" => $totalPageRoles,

            "permissions" => $permissions,
            "totalPermissions" => $totalPermissions,
            "totalPagePermissions" => $totalPagePermissions,

            "answers" => $answers,
            "totalAnswers" => $totalAnswers,
            "totalPageAnswers" => $totalPageAnswers,

            "currentPage" => $this->currentPage,
            "tab" => $tab,
        ], $title="Admin");
    }

    public function addModule(Request $request)
    {
        $module = new Module();
        
        if ($request->isPost()) {
            $module->loadData($request->getBody());
            if ($module->validate() && $module->save()) {
                Application::$app->session->setFlash('success', 'A new module was added successfully!');
                Application::$app->response->redirect('/admin?tab=modules');
            }
        }

        return $this->render('adminAddModule', [
            'model' => $module
        ], "Add Module");
    }

    public function editModule(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $module = Module::findOne(["id" => $id]);
        
        if (!$module) throw new \MVC\Exceptions\BadRequestException("Not Found Module!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $data["isActive"] = $data["isActive"] ? Module::BOOL_TRUE : Module::BOOL_FALSE;
            $module->loadData($data);
            $updateData = $module->getUpdateData();
            if ($module->validate()) {
                Module::update($updateData);
                Application::$app->session->setFlash('success', 'The '.$module->name.' module was updated successfully!');
                Application::$app->response->redirect('/admin?tab=modules');
            }
        }

        return $this->render('adminEditModule', [
            'model' => $module
        ], "Edit Module");
    }

    public function deleteModule(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $module = Module::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$module) throw new \MVC\Exceptions\BadRequestException("Not Found Your Module!");

        $module->delete();
        Application::$app->session->setFlash('success', 'The module ID='.$module->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=modules');
    }

    public function addQuestion(Request $request)
    {
        $questionModel = new Question();
        $modules = Module::findAll(["isActive" => Module::BOOL_TRUE]);
        $users = User::findAll(["isActive" => Module::BOOL_TRUE]);

        if ($request->isPost()) {
            $data = $request->getBody();
            if (isset($data["authorID"])) {
                $questionModel->authorID = (int) $data["authorID"];
            }
            $questionModel->loadData($request->getBody());
            if ($questionModel->validate()) {
                $image_path = Common::upload_file();
                if ($image_path) {
                    $questionModel->image = $image_path;
                }
                $questionModel->save();
                Application::$app->session->setFlash('success', 'A new question was added successfully!');
                Application::$app->response->redirect('/admin?tab=questions');
            }
        }

        return $this->render('adminAddQuestion', [
            'model' => $questionModel,
            'modules' => $modules,
            'users' => $users,
        ], "Add Question");
    }

    public function editQuestion(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $question = Question::findOne(["id" => $id]);
        $modules = Module::findAll(["isActive" => Module::BOOL_TRUE]);
        $preselectedModule = Module::findOne(["id" => $question->moduleID]);

        if (!$question) throw new \MVC\Exceptions\BadRequestException("Not Found Your Post!");

        if ($request->isPost()) {
            $image_path = Common::upload_file();
            if ($image_path) {
                $question->image = $image_path;
            }
            $data = $request->getBody();
            $data["isActive"] = $data["isActive"] ? Module::BOOL_TRUE : Module::BOOL_FALSE;
            var_dump($data);
            $question->loadData($data);
            $question->setUpdatedAt("now");
            $updateData = $question->getUpdateData();

            if ($question->validate()) {
                Question::update($updateData);
                Application::$app->session->setFlash('success', 'The question ID='.$question->id.' was updated successfully!');
                Application::$app->response->redirect('/admin?tab=questions');
            }
        }

        return $this->render($view='adminEditQuestion', $params=[
            'model' => $question,
            'modules' => $modules,
            'preselectedModule' => $preselectedModule,
        ], $title="Edit Question");
    }

    public function deleteQuestion(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $question = Question::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$question) throw new \MVC\Exceptions\BadRequestException("Not Found Your Question!");

        $question->delete();
        Application::$app->session->setFlash('success', 'The question ID='.$question->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=questions');
    }

    public function addUser(Request $request)
    {
        $user = new PrivilegedUser();
        $roles = Role::findAll(["isActive" => Role::BOOL_TRUE], 1000, 0, "name ASC");

        if ($request->isPost()) {
            $data = $request->getBody();
            $data["birthday"] = $data["birthday"] ? $data["birthday"] : null;
            $data["aboutMe"] = $data["aboutMe"] ? $data["aboutMe"] : null;
            $data["isActive"] = $data["isActive"] ? PrivilegedUser::BOOL_TRUE : PrivilegedUser::BOOL_FALSE;
            $data["isSuperAdmin"] = $data["isSuperAdmin"] ? PrivilegedUser::BOOL_TRUE : PrivilegedUser::BOOL_FALSE;
            $user->loadData($data);
            if ($user->validate() && $user->save()) {
                if (!$data["role"]) {
                    Application::$app->session->setFlash('success', 'The user ID='.$user->id.' was added successfully!');
                    Application::$app->response->redirect('/admin?tab=users');
                }
                if(PrivilegedUser::insertUserRole($user->id, $data["role"])){
                    Application::$app->session->setFlash('success', 'The new user was added successfully!');
                    Application::$app->response->redirect('/admin?tab=users');
                }
            }
        }

        return $this->render('adminAddUser', [
            'model' => $user,
            'roles' => $roles,
        ], "Add User");
    }

    public function editUser(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $user = EditUserModelForm::getByID($id);
        $roles = Role::findAll(["isActive" => Role::BOOL_TRUE], 1000, 0, "name ASC");
        
        if (!$user) throw new \MVC\Exceptions\BadRequestException("Not Found Module!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $data["isActive"] = $data["isActive"] ? User::BOOL_TRUE : User::BOOL_FALSE;
            $data["isSuperAdmin"] = $data["isSuperAdmin"] ? User::BOOL_TRUE : User::BOOL_FALSE;
            $data["birthday"] = $data["birthday"] ? $data["birthday"] : null;
            $data["aboutMe"] = $data["aboutMe"] ? $data["aboutMe"] : null;
            $user->loadData($data);
            $updateData = $user->getUpdateData();
            if ($user->validate()) {
                if (!$data["role"]) {
                    EditUserModelForm::update($updateData);
                    Application::$app->session->setFlash('success', 'The user ID='.$user->id.' was added successfully!');
                    Application::$app->response->redirect('/admin?tab=users');
                }
                if(!$user->roles[$data["role"]]) {
                    Role::deleteUserRoles($user->id);
                    Role::insertUserRole($user->id, $data["role"]);
                }
                EditUserModelForm::update($updateData);
                Application::$app->session->setFlash('success', 'The user ID='.$user->id.' was added successfully!');
                Application::$app->response->redirect('/admin?tab=users');
            }
        }

        return $this->render('adminEditUser', [
            'model' => $user,
            'roles' => $roles,
        ], "Edit USer");
    }

    public function deleteUser(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $user = User::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$user) throw new \MVC\Exceptions\BadRequestException("Not Found This User!");

        Role::deleteUserRoles($user->id);
        $user->delete();
        Application::$app->session->setFlash('success', 'The user ID='.$user->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=users');
    }

    public function addContact(Request $request)
    {
        $contact = new Contact();

        if ($request->isPost()) {
            $data = $request->getBody();
            $contact->loadData($data);
            if ($contact->validate() && $contact->save()) {
                Application::$app->session->setFlash('success', 'A new contact was added successfully!');
                Application::$app->response->redirect('/admin?tab=contacts');
            }
        }

        return $this->render('adminAddContact', [
            'model' => $contact,
        ], "Add Contact");
    }

    public function editContact(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $contact = Contact::findOne(["id" => $id]);
        
        if (!$contact) throw new \MVC\Exceptions\BadRequestException("Not Found Contact!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $contact->loadData($data);
            if ($contact->validate()) {
                $contact->setUpdatedAt("now");
                $updateData = $contact->getUpdateData();
                Contact::update($updateData);
                Application::$app->session->setFlash('success', 'The contact ID='.$contact->id.' was updated successfully!');
                Application::$app->response->redirect('/admin?tab=contacts');
            }
        }

        return $this->render('adminEditContact', [
            'model' => $contact
        ], "Edit Contact");
    }

    public function deleteContact(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $contact = Contact::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$contact) throw new \MVC\Exceptions\BadRequestException("Not Found This Contact!");

        $contact->delete();
        Application::$app->session->setFlash('success', 'The contact ID='.$contact->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=contacts');
    }

    public function changePassword(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $user = AdminChangeUserPasswordForm::findOne(["id" => $id]);
        
        if (!$user) throw new \MVC\Exceptions\BadRequestException("Not Found User!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $user->loadData($data);
            if ($user->validate()) {
                $user->password = password_hash($user->newPassword, PASSWORD_DEFAULT);
                $updateData = $user->getUpdateData();
                AdminChangeUserPasswordForm::update($updateData);
                Application::$app->session->setFlash('success', 'Password of user ID='.$user->id.' was changed successfully!');
                Application::$app->response->redirect('/admin?tab=users');
            }
        }

        return $this->render('adminChangeUserPassword', [
            'model' => $user
        ], "Change Password");
    }

    public function addRole(Request $request)
    {
        $role = new Role();
        $permissions = Permission::findAll([], 1000, 0, "perm ASC");

        if ($request->isPost()) {
            $perms = isset($_POST["perms"]) && count($_POST["perms"]) ? $_POST["perms"] : [];
            $data = $request->getBody();
            $role->loadData($data);
            if ($role->validate() && $role->save()) {
                foreach($perms as $perm) {
                    Role::insertRolePermission($role->id, $perm);
                }
                Application::$app->session->setFlash('success', 'A new role was added successfully!');
                Application::$app->response->redirect('/admin?tab=roles');
            }
        }

        return $this->render('adminAddRole', [
            'model' => $role,
            'permissions' => $permissions,
        ], "Add Role");
    }

    public function editRole(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $role = Role::findOne(["id" => $id]);
        $role = Role::getRolePerms($role);
        $permissions = Permission::findAll(["isActive" => Permission::BOOL_TRUE], 1000, 0, "perm ASC");
        
        if (!$role) throw new \MVC\Exceptions\BadRequestException("Not Found Role!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $perms = isset($_POST["perms"]) && count($_POST["perms"]) ? $_POST["perms"] : [];
            $data["isActive"] = $data["isActive"] ? Role::BOOL_TRUE : Role::BOOL_FALSE;
            $role->loadData($data);
            $updateData = $role->getUpdateData();
            if ($role->validate()) {
                Role::update($updateData);
                foreach($role->permissionIDs as $id) {
                    Role::deleteRolePermissions($role->id, $id);
                }
                foreach($perms as $perm) {
                    Role::insertRolePermission($role->id, $perm);
                }
                Application::$app->session->setFlash('success', 'The '.$role->name.' role was updated successfully!');
                Application::$app->response->redirect('/admin?tab=roles');
            }
        }

        return $this->render('adminEditRole', [
            'model' => $role,
            'permissions' => $permissions,
        ], "Edit Role");
    }

    public function deleteRole(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $role = Role::findOne(["id" => $id]);
        $role = Role::getRolePerms($role);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$role) throw new \MVC\Exceptions\BadRequestException("Not Found This Role ID=$role->id!");

        foreach($role->users as $id) {
            Role::deleteRoleUsers($role->id);
        }
        foreach($role->permissionIDs as $id) {
            Role::deleteRolePermissions($role->id, $id);
        }
        $role->delete();
        Application::$app->session->setFlash('success', 'The contact ID='.$role->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=roles');
    }

    public function addPermission(Request $request)
    {
        $permission = new Permission();
        
        if ($request->isPost()) {
            $permission->loadData($request->getBody());
            if ($permission->validate() && $permission->save()) {
                Application::$app->session->setFlash('success', 'A new permission was added successfully!');
                Application::$app->response->redirect('/admin?tab=permissions');
            }
        }

        return $this->render('adminAddPermission', [
            'model' => $permission
        ], "Add Permission");
    }

    public function editPermission(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $permission = Permission::findOne(["id" => $id]);
        
        if (!$permission) throw new \MVC\Exceptions\BadRequestException("Not Found Permission!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $data["isActive"] = $data["isActive"] ? Permission::BOOL_TRUE : Permission::BOOL_FALSE;
            $permission->loadData($data);
            $updateData = $permission->getUpdateData();
            if ($permission->validate()) {
                Permission::update($updateData);
                Application::$app->session->setFlash('success', 'The '.$permission->perm.' permission was updated successfully!');
                Application::$app->response->redirect('/admin?tab=modules');
            }
        }

        return $this->render('adminEditPermission', [
            'model' => $permission
        ], "Edit Permission");
    }

    public function deletePermission(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $permission = Permission::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$permission) throw new \MVC\Exceptions\BadRequestException("Not Found Your Permission!");

        $permission->delete();
        Application::$app->session->setFlash('success', 'The permission ID='.$permission->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=permissions');
    }

    public function editAnswer(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $answer = Answer::findOne(["id" => $id]);
        
        if (!$answer) throw new \MVC\Exceptions\BadRequestException("Not Found Answer!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $answer->loadData($data);
            if ($answer->validate()) {
                $answer->setUpdatedAt("now");
                $updateData = $answer->getUpdateData();
                Answer::update($updateData);
                Application::$app->session->setFlash('success', 'The answer ID='.$answer->id.' was updated successfully!');
                Application::$app->response->redirect('/admin?tab=answers');
            }
        }

        return $this->render('adminEditAnswer', [
            'model' => $answer
        ], "Edit Answer");
    }

    public function deleteAnswer(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $answer = Answer::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$answer) throw new \MVC\Exceptions\BadRequestException("Not Found This Answer!");

        $answer->delete();
        Application::$app->session->setFlash('success', 'The answer ID='.$answer->id.' was deleted successfully!');
        Application::$app->response->redirect('/admin?tab=answers');
    }
}
?>