<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $todos = DB::table('todos')
            ->select(DB::raw('todos.id, todos.name, todos.created_at as date, count(tasks.id) as tasks'))
            ->leftJoin('tasks', 'todos.id', '=', 'tasks.todo_id')
            ->groupBy('todos.id')
            ->get();

        return response()->json($todos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'name'=> 'required',
                'tasks'=> 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $todoRequest = $request->toArray();

        $todo = new Todo(['name'=> $todoRequest['name']]);

        $todo->save();

        $todo->tasks()->createMany($todoRequest['tasks']);

        $todoData = $todo->toArray();
        $todoData['tasks'] = $todo->tasks()->get()->toArray();

        return response()->json($todoData);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): JsonResponse
    {
        $todo = Todo::with('tasks')->find($id);

        if (!$todo) {
            return response()->json(['message'=> 'Not Found'], 404);
        }

        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $this->validate($request, [
                'name'=> 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $todo = Todo::with('tasks')->find($id);

        if (!$todo) {
            return response()->json(['message'=> 'Not Found'], 404);
        }

        $todo
            ->fill($request->all())
            ->save();

        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id): JsonResponse
    {
        $todo = Todo::with('tasks')->find($id);

        if (!$todo) {
            return response()->json(['message'=> 'Not Found'], 404);
        }

        $todo->delete();

        return response()->json(['message'=> 'Success']);
    }
}
