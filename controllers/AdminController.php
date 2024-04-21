<?php
namespace MVC\Controllers;

use MVC\Core\Application;
use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Helpers\Common;
use MVC\Forms\EditUserModelForm;
use MVC\Middlewares\AuthMiddleware;
use MVC\Middlewares\AuthorizeMiddleware;
use MVC\Models\Question;
use MVC\Models\Module;
use MVC\Models\User;
use MVC\Models\Contact;
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
            'editQuestion',
        ]));
        $this->registerMiddleware(new AuthorizeMiddleware([
            'index',
            'addModule',
            'editModule',
            'deleteModule',
            'editQuestion',
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
        
        if (!in_array($tab, ['questions', 'modules', 'users', 'contacts'])) throw new BadRequestException("Invalid tab query param!");

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
            $question->loadData($data);
            $question->setUpdatedAt("now");
            $updateData = $question->getUpdateData();

            if ($question->validate()) {
                Question::update($updateData);
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
        Application::$app->response->redirect('/admin?tab=questions');
    }

    public function addUser(Request $request)
    {
        $user = new User();

        if ($request->isPost()) {
            $data = $request->getBody();
            $data["birthday"] = $data["birthday"] ? $data["birthday"] : null;
            $data["aboutMe"] = $data["aboutMe"] ? $data["aboutMe"] : null;
            $user->loadData($data);
            if ($user->validate() && $user->save()) {
                Application::$app->response->redirect('/admin?tab=users');
            }
        }

        return $this->render('adminAddUser', [
            'model' => $user,
        ], "Add User");
    }

    public function editUser(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $user = EditUserModelForm::findOne(["id" => $id]);
        
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
                EditUserModelForm::update($updateData);
                Application::$app->response->redirect('/admin?tab=users');
            }
        }

        return $this->render('adminEditUser', [
            'model' => $user
        ], "Edit USer");
    }

    public function deleteUser(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $user = User::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$user) throw new \MVC\Exceptions\BadRequestException("Not Found This User!");

        $user->delete();
        Application::$app->response->redirect('/admin?tab=users');
    }
}
?>