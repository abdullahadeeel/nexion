<?php

namespace App\Controllers;

use Nexion\Controller\BaseController;
use Nexion\Http\Request;
use Nexion\Http\Response;
use App\Models\Todo;

class TodoController extends BaseController
{
    public function index(Request $request)
    {
        $todos = Todo::orderBy('created_at', 'DESC')->get();

        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? [];
        $success = $_SESSION['success'] ?? null;

        unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);

        return $this->render('todos.index', [
            'title' => 'My Todo List',
            'todos' => $todos,
            'errors' => $errors,
            'old' => $old,
            'success' => $success
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|min:3',
            ]);
        } catch (\Nexion\Http\ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old'] = $request->all();
            return Response::redirect('/');
        }

        $todo = new Todo([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'completed' => 0
        ]);
        
        $todo->save();

        $_SESSION['success'] = "Todo created successfully!";
        return Response::redirect('/');
    }

    public function toggle(Request $request, $id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->completed = $todo->completed ? 0 : 1;
            $todo->save();
            $_SESSION['success'] = "Todo status updated!";
        }

        return Response::redirect('/');
    }

    public function edit(Request $request, $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return Response::redirect('/');
        }

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        return $this->render('todos.edit', [
            'title' => 'Edit Todo',
            'todo' => $todo,
            'errors' => $errors
        ]);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return Response::redirect('/');
        }

        try {
            $request->validate([
                'title' => 'required|min:3',
            ]);
        } catch (\Nexion\Http\ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            return Response::redirect("/todos/{$id}/edit");
        }

        $todo->title = $request->input('title');
        $todo->description = $request->input('description');
        $todo->save();

        $_SESSION['success'] = "Todo updated successfully!";
        return Response::redirect('/');
    }

    public function destroy(Request $request, $id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->delete();
            $_SESSION['success'] = "Todo deleted successfully!";
        }

        return Response::redirect('/');
    }
}
