<?php
namespace Veneridze\LaravelForms\Controllers;

use Veneridze\LaravelForms\Models\Draft;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DraftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!$request->query('form'), 400);
        return Draft::
            where('form', $request->query('form'))
            ->where(function (Builder $query) {
                $query->where('created_by', Auth::id())
                    ->orWhere('public', 1);
            })
            ->ordered()
            ->get()
            ->map(fn(Draft $draft) => [
                'id' => $draft->id,
                'name' => $draft->name
            ]);
    }

    public function reorder(Request $request)
    {
        abort_if(!$request->query('form'), 400);
        $categories = $request->input('order');
        Draft::setNewOrder($categories);
    }

    public function show(Draft $draft)
    {
        abort_if($draft->createdBy->id != Auth::id() && !$draft->public, 404);
        return $draft->data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'data' => ['required', 'array'],
            // 'form' => ['required', 'string'],
        ]);

        return Draft::updateOrCreate([
            'created_by' => Auth::id(),
            'name' => $request->input('name'),
            'form' => $request->query('form')
        ], [
            'public' => $request->input('public', false),
            'data' => $request->input('data')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Draft $draft)
    {
        abort_if($draft->createdBy()->id != Auth::id() && !$draft->public, 404);
        $draft->deleteOrFail();
        return response(null, 201);
    }
}
