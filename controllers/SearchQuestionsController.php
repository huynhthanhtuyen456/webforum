<?php
namespace MVC\Controllers;

use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Models\Question;
use MVC\Models\Module;
use MVC\Exceptions\BadRequestException;


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
        $moduleID = isset($_GET["moduleID"]) ? $_GET["moduleID"] : null;

        $filters = ["isActive" => Question::BOOL_TRUE];

        if ($moduleID) $filters = array_merge(["moduleID" => $moduleID], $filters);
        $modules = Module::findAll(["isActive" => Module::BOOL_TRUE]);
        $questions = Question::search(
            $query=$query,
            $filters,
            $this->getLimit(),
            $this->getPageOffset()
        );
        return $this->render('search', [
            "questions" => $questions,
            "modules" => $modules,
            "query" => $query,
            "moduleID" => $moduleID,
        ], "Search");
    }
}
?>