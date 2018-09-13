<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;
use TodoApp\Form\TodoForm;
use Zero\Storage\StorageInterface;

/**
 * Class AddAction
 * @package TodoApp\Action
 */
class AddAction
{
    use FormActionTrait;

    /**
     * @var TodosDao
     */
    private $dao;

    /**
     * @var TodoForm
     */
    private $form;

    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(TodosDao $dao, TodoForm $form, StorageInterface $storage)
    {
        $this->dao = $dao;
        $this->form = $form;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        return $this->handle($this->form, $this->storage, $request, $response, $args);
    }

    protected function handleValidData(array $data, array $args)
    {
        $data['status'] = Todo::STATUS_INCOMPLETE;
        $todo = $this->dao->createTodoFromArray($data);
        $this->dao->saveTodo($todo);
    }
}