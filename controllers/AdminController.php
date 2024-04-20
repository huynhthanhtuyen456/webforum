<?php
namespace MVC\Controllers;

use MVC\Core\Application;
use MVC\Core\Controller;
use MVC\Core\Request;
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
        ]));
        $this->registerMiddleware(new AuthorizeMiddleware([
            'index',
        ]));
        $this->limit = 4;
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
        $totalQuestions = Question::countAll();
        $totalPageQuestions = ceil($totalQuestions / $this->getLimit());

        $modules = Module::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalModules = Module::countAll();
        $totalPageModules = ceil($totalModules / $this->getLimit());

        $users = User::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalUsers = User::countAll();
        $totalPageUsers = ceil($totalUsers / $this->getLimit());

        $contacts = Contact::findAll([], $this->getLimit(), $this->getPageOffset());
        $totalContacts = Contact::countAll();
        $totalPageContacts = ceil($totalContacts / $this->getLimit());

        return $this->render($view='admin', $params=[
            "questions" => $questions,
            "totalPageQuestions" => $totalPageQuestions,

            "modules" => $modules,
            "totalPageModules" => $totalPageModules,

            "users" => $users,
            "totalPageUsers" => $totalPageUsers,

            "contacts" => $contacts,
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
            $module->loadData($request->getBody());
            $updateData = $module->getUpdateData();
            if ($module->validate()) {
                Module::update($updateData);
                Application::$app->response->redirect('/admin?tab=modules');
            }
        }

        return $this->render('adminEditModule', [
            'model' => $module
        ], "Add Module");
    }
}
?>