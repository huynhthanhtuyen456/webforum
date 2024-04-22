<?php
namespace MVC\Controllers;

use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Core\Response;
use MVC\Core\Application;
use MVC\Models\Question;
use MVC\Models\User;


class SearchQuestionsController extends Controller
{
    public function search(Request $request)
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
        $query = isset($_GET["query"]) ? $_GET["query"] : "";
        $questions = Question::search($query=$query, ["isActive" => Question::BOOL_TRUE], $this->getLimit(), $this->getPageOffset());
        return $this->render('search', [
            "questions" => $questions
        ], "Search");
    }
}
?>