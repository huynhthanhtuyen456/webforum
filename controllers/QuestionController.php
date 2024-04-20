<?php
namespace MVC\Controllers;

use MVC\Core\Application;
use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Exceptions\BadRequestException;
use MVC\Helpers\Common;
use MVC\Models\Question;
use MVC\Models\User;
use MVC\Models\Module;
use MVC\Middlewares\AuthMiddleware;


class QuestionController extends Controller
{
    public function __construct()
    {
        $this->model = new Question();
        $this->registerMiddleware(new AuthMiddleware([
            'get',
            'post',
            'update',
            'delete',
        ]));
    }

    public function list()
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
        $questions = Question::findAll(["isActive" => true], $this->getLimit(), $this->getPageOffset());
        $totalQuestions = Question::countAll(["isActive" => true]);
        $totalPage = ceil($totalQuestions / $this->getLimit());
        return $this->render($view='questions', $params=[
            "questions" => $questions,
            "totalQuestions" => $totalQuestions,
            "totalPage" => $totalPage,
            "currentPage" => $this->currentPage,
        ], $title="Questions");
    }

    public function get(Request $request)
    {
        $modules = Module::findAll();
        return $this->render('askquestions', [
            'model' => $this->model,
            'modules' => $modules
        ], "Ask A Question");
    }

    public function post(Request $request)
    {
        $questionModel = $this->model;
        $questionModel->loadData($request->getBody());
    
        if ($questionModel->validate()) {
            $image_path = Common::upload_file();
            if ($image_path) {
                $questionModel->image = $image_path;
            }
            $questionModel->save();
            $id = $questionModel->id;
            Application::$app->response->redirect('/question/'.$id);
        }
        
        $modules = Module::findAll([], 1000, 0);
        return $this->render('askquestions', [
            'model' => $questionModel,
            'modules' => $modules
        ], "Ask Question");
    }

    public function detail(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $question = Question::findOne(["id" => $id]);
        $user = User::findOne(["id" => $question->authorID]);
        $authorName = $user->getDisplayName();

        $date1 = new \DateTime($question->createdAt);
        $date2 = new \DateTime('now');
        $intervalCreatedDay = $date2->diff($date1);
        $intervalCreatedDay = $intervalCreatedDay->days;
        if ($intervalCreatedDay < 1) {
            $intervalCreatedDay = $question->createdAt;
        } else {
            $intervalCreatedDay = $intervalCreatedDay == 1 ? $intervalCreatedDay." day" : $intervalCreatedDay." days";
        }


        return $this->render($view='question', $params=[
            'model' => $question,
            'authorName' => $authorName,
            'intervalCreatedDay' => $intervalCreatedDay,
        ], $title="Question");
    }
    
    public function update(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $question = Question::findOne(["id" => $id]);

        if (!$question) throw new \MVC\Exceptions\BadRequestException("Not Found Your Post!");

        if (Application::$app->user->id != $question->authorID) throw new \MVC\Exceptions\ForbiddenException();

        if ($request->isPost()) {
            $image_path = Common::upload_file();
            if ($image_path) {
                $question->image = $image_path;
            }
            $question->loadData($request->getBody());
            $updateData = $question->getUpdateData();

            if ($question->validate()) {
                Question::update($updateData);
                $id = $question->id;
                Application::$app->response->redirect('/question/'.$id);
            }
        }

        return $this->render($view='editquestion', $params=[
            'model' => $question,
        ], $title="Edit Question");
    }

    public function delete(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $question = Question::findOne(["id" => $id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$question) throw new \MVC\Exceptions\BadRequestException("Not Found Your Post!");

        if (Application::$app->user->id != $question->authorID) throw new \MVC\Exceptions\ForbiddenException();

        $question->isActive = Question::BOOL_FALSE;
        $updateData = $question->getUpdateData();
        Question::update($updateData);
        $id = $question->id;
        Application::$app->response->redirect('/questions');
    }
}
?>