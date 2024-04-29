<?php
namespace MVC\Controllers;

use MVC\Core\Controller;
use MVC\Models\Module;
use MVC\Exceptions\BadRequestException;


class ModuleController extends Controller
{
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
        $modules = Module::findAll(["isActive" => Module::BOOL_TRUE], $this->getLimit(), $this->getPageOffset(), "creaetdAt DESC");
        $totalModules = Module::countAll(["isActive" => Module::BOOL_TRUE]);
        $totalPage = ceil($totalModules / $this->getLimit());
        return $this->render($view='modules', $params=[
            "modules" => $modules,
            "totalModules" => $totalModules,
            "totalPage" => $totalPage,
            "currentPage" => $this->currentPage,
        ], $title="Modules");
    }
}
?>