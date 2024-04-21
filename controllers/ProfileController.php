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


class ProfileController extends Controller
{
    public function __construct()
    {;
        $this->registerMiddleware(new AuthMiddleware([
            'index',
        ]));
        $this->limit = 10;
        $this->me = Application::$app->user;
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
        $tab = isset($_GET["tab"]) ? $_GET["tab"] : "profile";
        
        if (!in_array($tab, ['profile', 'aboutMe', 'questions', 'contacts'])) throw new BadRequestException("Invalid tab query param!");

        $questions = Question::findAll(['authorID' => $this->me->id, 'isActive' => Question::BOOL_TRUE], $this->getLimit(), $this->getPageOffset());
        $totalQuestions = Question::countAll(['isActive' => Question::BOOL_TRUE, 'authorID' => $this->me->id]);
        $totalPageQuestions = ceil($totalQuestions / $this->getLimit());

        $contacts = Contact::findAll(["emailAddress" => $this->emailAddress], $this->getLimit(), $this->getPageOffset());
        $totalContacts = Contact::countAll(["emailAddress" => $this->emailAddress]);
        $totalPageContacts = ceil($totalContacts / $this->getLimit());

        return $this->render($view='profile', $params=[
            "questions" => $questions,
            "totalQuestions" => $totalQuestions,
            "totalPageQuestions" => $totalPageQuestions,

            "contacts" => $contacts,
            "totalContacts" => $totalContacts,
            "totalPageContacts" => $totalPageContacts,

            "currentPage" => $this->currentPage,
            "tab" => $tab,
        ], $title="Profile");
    }
}
?>