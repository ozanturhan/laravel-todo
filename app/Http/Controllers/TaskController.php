<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
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

        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message'=> 'Not Found'], 404);
        }

        $task
            ->fill($request->all())
            ->save();

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message'=> 'Not Found'], 404);
        }

        $task->delete();

        return response()->json(['message'=> 'Success']);
    }
}
